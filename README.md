# Narimene Atmania — Portfolio

Retro "desktop OS" portfolio, built from the `design_handoff_retro_desktop_portfolio` mockup
(v2 — Y2K pastel purple, interactive). Laravel renders a single Blade view; [Alpine.js](https://alpinejs.dev)
drives all the window/taskbar/start-menu state, mirroring the mockup's `Component` class 1:1.

The site is developed as a Laravel app but **ships as static HTML** — `php artisan export`
crawls it and writes a complete static bundle, which is what gets deployed. There is no
database and no backend in production.

## Stack

- **Laravel 12** — routing, config, Blade rendering, and the static export.
- **Alpine.js** — window focus/minimize/close/z-order, taskbar, start menu, live clock, dialog.
- **Vite** — asset bundling.
- **Plain CSS** (`resources/css/app.css`) — hand-written to match the handoff's exact tokens
  (colors, bevels, gradients, spacing). No Tailwind — the design is too bespoke for utility
  classes to pay off.
- **spatie/laravel-export** — turns the rendered app into deployable static files.

## Running locally

```bash
composer install
npm install
npm run dev      # or: npm run build
php artisan serve
```

Visit `http://localhost:8000` — it redirects to `/en`.

No `php artisan migrate` step: nothing in the app touches a database.

## Building and deploying

```bash
php artisan export
```

This rebuilds the assets, crawls every page in every language, and writes the result to
`dist/` — 19 HTML pages plus `public/` (demos, images, résumés, favicons). An `after` hook
rewrites the absolute URLs Laravel generates into root-relative ones, so the same bundle
works on any domain, including Netlify's per-commit preview URLs.

Deployment runs through `.github/workflows/deploy.yml`: every push to `main` exports the site
in CI and publishes `dist/` to Netlify. Netlify's build image has no PHP, which is why the
export happens in the workflow rather than on Netlify itself.

Two repository secrets are required: `NETLIFY_AUTH_TOKEN` and `NETLIFY_SITE_ID`.

## Languages

Three locales — English, French, Arabic (RTL) — living in `lang/{en,fr,ar}/`.

Language is part of the URL (`/en/projects`, `/fr/projects/plannari`, `/ar/…`) rather than a
server session, which is what allows each language to export as its own set of static files.
The `SetLocale` middleware registers the active locale as a default route parameter, so
`route()` calls elsewhere stay in the current language without passing it explicitly.

## What's built

- Full desktop: wallpaper + grid, 6 desktop icons, 6 windows (about.txt, skills.dll,
  projects/, resume.pdf, contact.exe, changelog.txt), taskbar with start menu and language
  switcher, live clock, sticky note, "take a break?" dialog.
- Window manager: focus/z-order, minimize, close, taskbar toggling, start-menu integration —
  all in `resources/js/app.js` (`Alpine.data('desktop', ...)`). Window state persists across
  navigation via session storage.
- Mobile (<768px): a separate layout, not a responsive fold of the desktop chrome — a header
  with the logo, four scrollable panels (about, projects, resume, contact — skills folds into
  the about panel), and a persistent bottom tab bar.
- Four project pages, each with a **live demo**: static, backend-free builds of the real
  applications under `public/demos/`. BusCatcher's includes a mocked live-tracking map driven
  by a service worker.
- `changelog.txt` reads its table straight out of `docs/Portfolio_Issues_and_Fixes.docx`, so
  editing the Word document updates the site — no duplicated copy to keep in sync.
- Contact form posts to [Netlify Forms](https://docs.netlify.com/manage/forms/setup/); there's
  no backend. Validation runs in the browser, in all three languages, with a honeypot field
  for spam.

## Configuration

`config/portfolio.php`, overridable via `.env`:

- `PORTFOLIO_NAME`, `PORTFOLIO_CONTACT_EMAIL`, `PORTFOLIO_GITHUB_URL`
- `PORTFOLIO_RESUME_URL_EN` / `_FR` / `_AR` — one résumé per locale, served from
  `public/resume/` by default.

## Structure

- `resources/views/desktop.blade.php` — the whole page (desktop + mobile layouts).
- `resources/views/partials/` — project card/detail, logo, chips, skill cards.
- `resources/css/app.css` — all component styles, design tokens as CSS custom properties.
- `resources/js/app.js` — Alpine components: `desktop()`, `mobile()`, `gallery()`,
  `sketchCompare()`, plus the shared contact-form submit and validation.
- `config/projects.php` + `lang/{locale}/projects.php` — project data, split into
  locale-independent fields and translated copy, keyed by slug.
- `app/Support/ChangelogReader.php` — reads the changelog table out of the .docx.
- `app/Console/Commands/RelativizeExport.php` — post-export URL rewriting.
- `public/demos/` — the static live-demo builds.
