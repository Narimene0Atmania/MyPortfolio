# Narimene Atmania — Portfolio

Retro "desktop OS" portfolio, built from the `design_handoff_retro_desktop_portfolio` mockup
(v2 — Y2K pastel purple, interactive). Laravel serves a single Blade view; [Alpine.js](https://alpinejs.dev)
drives all the window/taskbar/start-menu state, mirroring the mockup's `Component` class 1:1.

## Stack

- **Laravel 12** — routing, the `contact.exe` form endpoint, config.
- **Alpine.js** — window focus/minimize/close/z-order, taskbar, start menu, live clock, dialog.
- **Vite** — asset bundling.
- **Plain CSS** (`resources/css/app.css`) — hand-written to match the handoff's exact tokens
  (colors, bevels, gradients, spacing). No Tailwind — the design is too bespoke for utility
  classes to pay off.
- **SQLite** — stores contact form submissions (`contact_messages` table).

## Running locally

```bash
composer install
npm install
php artisan migrate
npm run dev      # or: npm run build
php artisan serve
```

Visit `http://localhost:8000`.

## What's built

- Full desktop: wallpaper + grid, 5 desktop icons, 5 windows (about.txt, skills.dll, projects/,
  resume.pdf, contact.exe), taskbar with start menu, live clock, sticky note, "take a break?"
  dialog.
- Window manager: focus/z-order, minimize, close, taskbar toggling, start-menu integration —
  all in `resources/js/app.js` (`Alpine.data('desktop', ...)`).
- Mobile (<768px): a separate layout, not a responsive fold of the desktop chrome — four
  scrollable panels (about, projects, resume, contact — skills folds into the about panel)
  behind a persistent bottom tab bar with 4 tabs.
- Working contact form: validates, saves to `contact_messages`, and emails
  `config('portfolio.contact_email')` (via `MAIL_MAILER`, currently `log` — see below).

## Still placeholders (per the design handoff)

Update these in `.env` (see `PORTFOLIO_*` keys) and swap in real assets when ready:

- `PORTFOLIO_CONTACT_EMAIL`
- Portrait photo (`.about__portrait` in `resources/views/desktop.blade.php`) — currently a
  gradient circle placeholder.
- Real projects — the projects/ window currently shows an empty "coming soon" state.
- Mail sending — `MAIL_MAILER=log` writes to `storage/logs/laravel.log` instead of sending.
  Set real SMTP credentials in `.env` to actually deliver contact form emails.

Resume PDFs are no longer a placeholder — one per locale lives in `public/resume/` and is
served via `config('portfolio.resume_urls')`, overridable per locale with
`PORTFOLIO_RESUME_URL_EN` / `_FR` / `_AR` in `.env` if you ever host them elsewhere.

## Structure

- `resources/views/desktop.blade.php` — the whole page.
- `resources/css/app.css` — all component styles, design tokens as CSS custom properties.
- `resources/js/app.js` — Alpine `desktop()` component (window state, clock, contact form fetch).
- `app/Http/Controllers/ContactController.php` + `app/Models/ContactMessage.php` — contact form.
- `config/portfolio.php` — owner-facing placeholders (name/email/github/resume).
