import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const WINDOW_KEYS = ['about', 'skills', 'projects', 'contact', 'resume'];

// small breathing room kept between a window and the left/right/top edges —
// windows are clamped to stop here rather than going flush to the edge
const EDGE_MARGIN = 12;

const I18N = window.PORTFOLIO_I18N ?? {
    invalid: 'please check the form.',
    error: 'something went wrong. try again?',
    network: 'network error. try again?',
};

Alpine.data('desktop', () => ({
    // --- state -------------------------------------------------------
    win: {
        about: { open: true, min: false },
        skills: { open: true, min: false },
        projects: { open: true, min: false },
        contact: { open: true, min: false },
        resume: { open: false, min: false },
    },
    order: ['contact', 'projects', 'skills', 'about'],
    startOpen: false,
    dialogClosed: false,
    showSticky: true,
    showDialog: true,
    now: new Date(),
    clock: '',
    dateStr: '',

    // drag + boot
    positions: {},
    dragging: null,
    booted: false,
    bootFading: false,

    // resume notification
    showNotif: false,

    // contact form
    form: { name: '', email: '', message: '' },
    formErrors: {},
    formStatus: null, // { ok: true|false, message: string }
    formSubmitting: false,

    init() {
        // bound once so add/removeEventListener see the same reference and
        // `this` stays the component rather than the event target
        this.onDragMove = this.onDragMove.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);

        this.tick();
        this.timer = setInterval(() => this.tick(), 1000);
        this.initBoot();
        this.initCursorTrail();
        this.initNotif();
    },

    destroy() {
        clearInterval(this.timer);
        clearTimeout(this.bootFadeTimer);
        clearTimeout(this.bootDoneTimer);
        clearTimeout(this.notifTimer);
        cancelAnimationFrame(this.trailFrame);
        this.stopDrag();
    },

    tick() {
        const n = new Date();
        const p = (x) => String(x).padStart(2, '0');
        this.clock = `${p(n.getHours())}:${p(n.getMinutes())}`;
        this.dateStr = `${p(n.getDate())}/${p(n.getMonth() + 1)}`;
    },

    // --- window management --------------------------------------------
    focus(w) {
        this.win[w] = { open: true, min: false };
        this.order = [...this.order.filter((x) => x !== w), w];
        this.startOpen = false;
    },

    minimize(w) {
        this.win[w] = { ...this.win[w], min: true };
    },

    close(w) {
        this.win[w] = { open: false, min: false };
        this.order = this.order.filter((x) => x !== w);
    },

    taskbarClick(w) {
        const i = this.win[w];
        const top = this.order[this.order.length - 1];
        if (!i.open || i.min || top !== w) {
            this.focus(w);
        } else {
            this.minimize(w);
        }
    },

    isOpenVisible(w) {
        const i = this.win[w];
        return i.open && !i.min;
    },

    isFocused(w) {
        const i = this.win[w];
        const top = this.order[this.order.length - 1];
        return top === w && i.open && !i.min;
    },

    zIndex(w) {
        return 2 + this.order.indexOf(w);
    },

    toggleStart() {
        this.startOpen = !this.startOpen;
    },

    // --- window positioning + dragging ------------------------------------
    winStyle(key, startInline, startTop, width, delay) {
        const pos = this.positions[key];
        const topPx = pos ? pos.y : parseFloat(startTop);
        return {
            // default (never-dragged) position is clamped so the window's
            // right edge can't pass the viewport's right edge (minus a small
            // margin) at narrow widths — width stays fixed (never shrinks),
            // so instead the window just can't sit as far over as its
            // designed %, down to EDGE_MARGIN from the edge if it's wider
            // than the viewport. A dragged position is already clamped by
            // onDragMove.
            insetInlineStart: pos
                ? pos.x + 'px'
                : `max(${EDGE_MARGIN}px, min(${startInline}, calc(100vw - ${width} - ${EDGE_MARGIN}px)))`,
            top: pos ? pos.y + 'px' : startTop,
            width,
            // cap height relative to THIS window's own top offset (not a flat
            // viewport number) — a window starting further down the page has
            // less room before the taskbar, and this budget has to reflect
            // that, or it silently overflows past the visible area regardless
            // of viewport height. 60px clears the 44px taskbar plus a margin.
            maxHeight: `calc(100vh - ${topPx}px - 60px)`,
            zIndex: this.zIndex(key),
            ...(delay ? { animationDelay: delay } : {}),
        };
    },

    dialogStyle() {
        const pos = this.positions.dialog;
        // clear the CSS `bottom: 100px` default too — leaving it set while `top`
        // is also set stretches the box between them instead of moving it
        return pos ? { insetInlineStart: pos.x + 'px', top: pos.y + 'px', bottom: 'auto' } : {};
    },

    startDrag(key, event) {
        if (event.button != null && event.button !== 0) return;

        if (key !== 'dialog') this.focus(key);

        const el = event.currentTarget.closest('.window, .dialog');
        if (!el) return;

        const rect = el.getBoundingClientRect();
        // $root, not $el — inside a handler $el is the bound titlebar
        const surface = this.$root.getBoundingClientRect();
        const rtl = document.documentElement.dir === 'rtl';

        this.dragging = {
            key,
            pointerX: event.clientX,
            pointerY: event.clientY,
            // inset-inline-start measures from the right edge in RTL
            originX: rtl ? surface.right - rect.right : rect.left - surface.left,
            originY: rect.top - surface.top,
            width: rect.width,
            rtl,
            surfaceWidth: surface.width,
        };

        event.preventDefault();
        window.addEventListener('pointermove', this.onDragMove);
        window.addEventListener('pointerup', this.onDragEnd);
        window.addEventListener('pointercancel', this.onDragEnd);
    },

    onDragMove(event) {
        const d = this.dragging;
        if (!d) return;

        const dx = event.clientX - d.pointerX;
        const dy = event.clientY - d.pointerY;
        // dragging right moves a window toward the inline-end in RTL
        const nextX = d.originX + (d.rtl ? -dx : dx);
        const nextY = d.originY + dy;

        // full containment, minus a small breathing-room margin — a window
        // can't be dragged past the left or right edge at all (not even
        // leaving a sliver hanging off, like before). If the window is wider
        // than the surface, it just can't go further in that direction:
        // stops at EDGE_MARGIN from the left, same idea as the vertical
        // clamp below.
        const maxX = Math.max(EDGE_MARGIN, d.surfaceWidth - d.width - EDGE_MARGIN);
        const minX = EDGE_MARGIN;

        this.positions[d.key] = {
            x: Math.min(maxX, Math.max(minX, nextX)),
            y: Math.max(EDGE_MARGIN, nextY),
        };
    },

    onDragEnd() {
        this.stopDrag();
    },

    stopDrag() {
        this.dragging = null;
        window.removeEventListener('pointermove', this.onDragMove);
        window.removeEventListener('pointerup', this.onDragEnd);
        window.removeEventListener('pointercancel', this.onDragEnd);
    },

    // --- boot splash --------------------------------------------------------
    initBoot() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reduced || sessionStorage.getItem('booted')) {
            this.booted = true;
            return;
        }

        requestAnimationFrame(() => {
            if (this.$refs.bootFill) this.$refs.bootFill.style.width = '100%';
        });

        this.bootFadeTimer = setTimeout(() => { this.bootFading = true; }, 1550);
        this.bootDoneTimer = setTimeout(() => {
            this.booted = true;
            sessionStorage.setItem('booted', '1');
        }, 2350);
    },

    // --- resume notification ------------------------------------------------
    initNotif() {
        if (sessionStorage.getItem('resumeNotif')) return;

        // land a few seconds after the desktop settles; shorter when the boot
        // splash was skipped, since there's nothing to wait out
        this.notifTimer = setTimeout(() => { this.showNotif = true; }, this.booted ? 5000 : 7000);
    },

    dismissNotif() {
        this.showNotif = false;
        sessionStorage.setItem('resumeNotif', '1');
    },

    // --- cursor trail -------------------------------------------------------
    initCursorTrail() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced || window.matchMedia('(hover: none)').matches) return;

        const dots = Array.from(this.$refs.trail?.children ?? []);
        if (!dots.length) return;

        const points = dots.map(() => ({ x: 0, y: 0 }));
        let cursor = null;

        this.$root.addEventListener('pointermove', (event) => {
            if (!cursor) {
                points.forEach((p) => { p.x = event.clientX; p.y = event.clientY; });
                dots.forEach((dot, i) => { dot.style.opacity = String(0.6 - i * 0.07); });
            }
            cursor = { x: event.clientX, y: event.clientY };
        });

        const step = () => {
            if (cursor) {
                points.forEach((point, i) => {
                    const target = i === 0 ? cursor : points[i - 1];
                    const ease = 0.3 - i * 0.025;
                    point.x += (target.x - point.x) * ease;
                    point.y += (target.y - point.y) * ease;

                    const size = dots[i].offsetWidth / 2;
                    dots[i].style.transform = `translate(${point.x - size}px, ${point.y - size}px)`;
                });
            }
            this.trailFrame = requestAnimationFrame(step);
        };

        this.trailFrame = requestAnimationFrame(step);
    },

    closeDialog() {
        this.dialogClosed = true;
    },

    get dialogVisible() {
        return this.showDialog && !this.dialogClosed;
    },

    // --- contact form ---------------------------------------------------
    async submitContact() {
        this.formErrors = {};
        this.formStatus = null;
        this.formSubmitting = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token ?? '',
                },
                body: JSON.stringify(this.form),
            });

            const data = await res.json();

            if (res.status === 422) {
                this.formErrors = Object.fromEntries(
                    Object.entries(data.errors ?? {}).map(([k, v]) => [k, v[0]])
                );
                this.formStatus = { ok: false, message: I18N.invalid };
                return;
            }

            if (!res.ok) {
                this.formStatus = { ok: false, message: I18N.error };
                return;
            }

            this.formStatus = { ok: true, message: data.message ?? 'message sent!' };
            this.form = { name: '', email: '', message: '' };
        } catch (e) {
            this.formStatus = { ok: false, message: I18N.network };
        } finally {
            this.formSubmitting = false;
        }
    },
}));

Alpine.data('mobile', (initialTab = 'about') => ({
    // --- state -------------------------------------------------------
    // Per design_handoff_mobile_nav: the whole nav store is just `tab`.
    // No drag state, no z-order, no window map, no boot flag — those are
    // desktop-only. Contact-form state is kept because the panel is functional.
    // initialTab lets a direct visit to /projects/{slug} land on the
    // projects panel instead of always defaulting to about.
    tab: initialTab,
    form: { name: '', email: '', message: '' },
    formErrors: {},
    formStatus: null,
    formSubmitting: false,

    // --- tab switching -------------------------------------------------
    go(key) {
        this.tab = key;
        if (this.$refs.scroller) {
            this.$refs.scroller.scrollTop = 0;
        }
    },

    // --- contact form (same endpoint/flow as the desktop component) ----
    async submitContact() {
        this.formErrors = {};
        this.formStatus = null;
        this.formSubmitting = true;

        try {
            const res = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify(this.form),
            });

            const data = await res.json();

            if (data.errors) {
                this.formErrors = data.errors;
                this.formStatus = { ok: false, message: I18N.invalid };
                return;
            }

            if (!res.ok) {
                this.formStatus = { ok: false, message: I18N.error };
                return;
            }

            this.formStatus = { ok: true, message: data.message ?? 'message sent!' };
            this.form = { name: '', email: '', message: '' };
        } catch (e) {
            this.formStatus = { ok: false, message: I18N.network };
        } finally {
            this.formSubmitting = false;
        }
    },
}));

Alpine.start();
