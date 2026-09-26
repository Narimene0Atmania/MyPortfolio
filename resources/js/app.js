import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const WINDOW_KEYS = ['about', 'skills', 'projects', 'contact', 'resume', 'changelog'];

// small breathing room kept between a window and the left/right/top edges —
// windows are clamped to stop here rather than going flush to the edge
const EDGE_MARGIN = 12;

// Every /projects/{slug} click is a full server-rendered page load, not a
// client-side route change — so Alpine re-inits desktop() from scratch each
// time. Without this, that reset closed windows, undid drag positions, and
// dropped focus back to whatever the hardcoded defaults are, every single
// navigation. sessionStorage survives across those reloads (same mechanism
// already used for the boot splash and resume-notification dismissal) but
// clears when the tab actually closes, so a fresh session still starts clean.
const WINDOW_STATE_KEY = 'desktopWindowState';

function loadWindowState(defaults) {
    try {
        const saved = JSON.parse(sessionStorage.getItem(WINDOW_STATE_KEY));
        if (!saved || typeof saved !== 'object') return defaults;
        return {
            win: { ...defaults.win, ...(saved.win && typeof saved.win === 'object' ? saved.win : {}) },
            order: Array.isArray(saved.order) && saved.order.length
                ? saved.order.filter((k) => WINDOW_KEYS.includes(k))
                : defaults.order,
            positions: saved.positions && typeof saved.positions === 'object' ? saved.positions : {},
        };
    } catch (e) {
        return defaults;
    }
}

const I18N = window.PORTFOLIO_I18N ?? {
    invalid: 'please check the form.',
    error: 'something went wrong. try again?',
    network: 'network error. try again?',
    success: 'thanks — message sent!',
    errors: {
        name: 'your name, please.',
        email: 'an email address, please.',
        emailInvalid: "that doesn't look like an email address.",
        message: 'a message would help.',
    },
};

// The site is served as static files, so there's no backend to validate
// against — the browser checks the fields and Netlify's form endpoint
// takes the submission. Shared by the desktop window and the mobile panel,
// which run the same form in two different shells.
const CONTACT_FORM_NAME = 'contact';

function validateContact(form) {
    const errors = {};

    if (!form.name.trim()) errors.name = I18N.errors.name;

    if (!form.email.trim()) {
        errors.email = I18N.errors.email;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) {
        errors.email = I18N.errors.emailInvalid;
    }

    if (!form.message.trim()) errors.message = I18N.errors.message;

    return errors;
}

// Netlify expects a urlencoded POST carrying the form's name, sent to any
// path on the site — the page's own URL is the convention.
async function submitContactForm(form) {
    const body = new URLSearchParams({
        'form-name': CONTACT_FORM_NAME,
        name: form.name,
        email: form.email,
        message: form.message,
    });

    const res = await fetch(window.location.pathname, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
    });

    if (!res.ok) throw new Error(`form endpoint returned ${res.status}`);

    // the message itself arrives by email; this only puts it on the
    // dashboard next to the other engagement signals. Optional chaining
    // because the tracking script isn't loaded on local builds.
    window.umami?.track('contact-submit');
}

Alpine.data('desktop', (focusedWindow = '') => {
    const defaults = {
        win: {
            about: { open: true, min: false },
            skills: { open: true, min: false },
            projects: { open: true, min: false },
            contact: { open: true, min: false },
            resume: { open: false, min: false },
            changelog: { open: false, min: false },
        },
        order: ['contact', 'projects', 'skills', 'about'],
        positions: {},
    };
    const restored = loadWindowState(defaults);

    return {
    // --- state -------------------------------------------------------
    win: restored.win,
    // last entry in `order` is the focused/topmost window (see isFocused()
    // and zIndex() below)
    order: restored.order,
    startOpen: false,
    dialogClosed: false,
    showSticky: true,
    // was `true` — popped up instantly on every load, before any scrolling
    // could plausibly have happened, contradicting its own "you have been
    // scrolling for a while" copy. Now an idle timer (see initDialog()).
    showDialog: false,
    dialogActivityEvents: ['mousemove', 'mousedown', 'keydown', 'touchstart', 'wheel'],
    now: new Date(),
    clock: '',
    dateStr: '',

    // drag + boot
    positions: restored.positions,
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

        // persist window state across the full page reloads every
        // /projects/{slug} navigation causes (see loadWindowState() above).
        // Registered BEFORE the forced-focus mutation below, or that specific
        // mutation would happen before anything is watching it and never
        // actually get saved.
        this.$watch('win', () => this.persistWindowState());
        this.$watch('order', () => this.persistWindowState());
        this.$watch('positions', () => this.persistWindowState());

        // only force a window forward when the route itself implies one
        // (a /projects/{slug} visit) — restored/default state stands as-is
        // otherwise, rather than always snapping back to "about"
        if (focusedWindow) {
            this.win[focusedWindow] = { ...this.win[focusedWindow], open: true, min: false };
            this.order = [...this.order.filter((w) => w !== focusedWindow), focusedWindow];
        }

        this.tick();
        this.timer = setInterval(() => this.tick(), 1000);
        this.initBoot();
        this.initCursorTrail();
        this.initNotif();
        this.initDialog();
    },

    persistWindowState() {
        try {
            sessionStorage.setItem(WINDOW_STATE_KEY, JSON.stringify({
                win: this.win,
                order: this.order,
                positions: this.positions,
            }));
        } catch (e) {
            // sessionStorage unavailable (private mode, quota, etc.) — the
            // desktop still works, it just won't remember state across a
            // full page navigation
        }
    },

    destroy() {
        clearInterval(this.timer);
        clearTimeout(this.bootFadeTimer);
        clearTimeout(this.bootDoneTimer);
        clearTimeout(this.notifTimer);
        clearTimeout(this.dialogTimer);
        if (this.resetDialogIdle) {
            this.dialogActivityEvents.forEach((evt) => window.removeEventListener(evt, this.resetDialogIdle));
            window.removeEventListener('scroll', this.resetDialogIdle, true);
        }
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
        // deliberately NOT removed from `order` — the window plays a 180ms
        // fade/scale leave transition (x-transition:leave), and dropping it
        // out of `order` here immediately zeroed its zIndex(), so it visibly
        // sank behind every other window right as the animation started
        // instead of just fading in place. isFocused() already excludes
        // closed windows on its own (`&& i.open`), and focus() re-dedupes
        // this array whenever the window is reopened, so nothing relies on
        // a closed window actually being absent from `order`.
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

    // --- "take a break?" dialog ---------------------------------------------
    initDialog() {
        // idle timer, not a flat delay — fires 50s after the *last* activity,
        // so it only ever shows up once the page has genuinely gone still,
        // rather than ambushing someone mid-scroll 50s after they landed
        const reset = () => {
            clearTimeout(this.dialogTimer);
            this.dialogTimer = setTimeout(() => { this.showDialog = true; }, 50000);
        };
        this.resetDialogIdle = reset;

        this.dialogActivityEvents.forEach((evt) => window.addEventListener(evt, reset));
        // scroll doesn't bubble to window by default — capture it instead so
        // scrolling inside a window's .well still counts as activity
        window.addEventListener('scroll', reset, true);

        reset();
    },

    closeDialog() {
        this.dialogClosed = true;
    },

    get dialogVisible() {
        // shares the resume notification's bottom-right slot, so it waits
        // for that to be gone (dismissed or never shown) rather than
        // stacking on top of it
        return this.showDialog && !this.dialogClosed && !this.showNotif;
    },

    // --- contact form ---------------------------------------------------
    async submitContact() {
        this.formErrors = validateContact(this.form);
        this.formStatus = null;

        if (Object.keys(this.formErrors).length) {
            this.formStatus = { ok: false, message: I18N.invalid };
            return;
        }

        this.formSubmitting = true;

        try {
            await submitContactForm(this.form);
            this.formStatus = { ok: true, message: I18N.success };
            this.form = { name: '', email: '', message: '' };
        } catch (e) {
            this.formStatus = { ok: false, message: I18N.network };
        } finally {
            this.formSubmitting = false;
        }
    },
    };
});

// project-detail screenshot gallery — shared by the desktop window and the
// mobile panel (project-detail.blade.php includes it in both). Lightbox
// markup is teleported to <body> (see x-teleport in the blade), so it isn't
// affected by this component's own position: fixed escaping the window's
// clipped/animated ancestor is otherwise unreliable.
Alpine.data('gallery', (screens = []) => ({
    screens,
    lightbox: null,

    open(i) {
        this.lightbox = i;
    },

    close() {
        this.lightbox = null;
    },

    next() {
        this.lightbox = (this.lightbox + 1) % this.screens.length;
    },

    prev() {
        this.lightbox = (this.lightbox + this.screens.length - 1) % this.screens.length;
    },

    onKey(event) {
        if (this.lightbox === null) return;
        if (event.key === 'Escape') this.close();
        if (event.key === 'ArrowRight') this.next();
        if (event.key === 'ArrowLeft') this.prev();
    },
}));

// sketch-vs-final comparison (e.g. Plannari's concept sketch next to the
// finished illustration) — desktop has room to enlarge both together, but
// at the same <768px width where the layout below already stacks them
// (see .project-detail__sketch-pair in app.css), enlarging both at once
// would just repeat the stacked thumbnails at a bigger size, so mobile gets
// the one-at-a-time gallery lightbox instead.
Alpine.data('sketchCompare', (images = []) => ({
    images,
    pairOpen: false,
    single: null,

    open(i) {
        if (window.innerWidth > 767) {
            this.pairOpen = true;
        } else {
            this.single = i;
        }
    },

    closePair() {
        this.pairOpen = false;
    },

    closeSingle() {
        this.single = null;
    },

    next() {
        this.single = (this.single + 1) % this.images.length;
    },

    prev() {
        this.single = (this.single + this.images.length - 1) % this.images.length;
    },

    onKey(event) {
        if (event.key === 'Escape') {
            this.closePair();
            this.closeSingle();
            return;
        }
        if (this.single === null) return;
        if (event.key === 'ArrowRight') this.next();
        if (event.key === 'ArrowLeft') this.prev();
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
        this.formErrors = validateContact(this.form);
        this.formStatus = null;

        if (Object.keys(this.formErrors).length) {
            this.formStatus = { ok: false, message: I18N.invalid };
            return;
        }

        this.formSubmitting = true;

        try {
            await submitContactForm(this.form);
            this.formStatus = { ok: true, message: I18N.success };
            this.form = { name: '', email: '', message: '' };
        } catch (e) {
            this.formStatus = { ok: false, message: I18N.network };
        } finally {
            this.formSubmitting = false;
        }
    },
}));

Alpine.start();
