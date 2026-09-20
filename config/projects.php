<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    |
    | Locale-independent per-project data (translatable copy lives in
    | lang/{en,fr,ar}/projects.php, keyed by the same slug). Order here is
    | display order — buscatcher leads as the featured project.
    |
    | PLACEHOLDER DATA — copy and links are draft, written to exercise the
    | new projects/ index + detail views (design_handoff_projects). Review
    | before treating any of this as final: status tones, timeline, and the
    | live/source links in particular are best guesses pending real details.
    |
    */

    'items' => [
        [
            'slug' => 'buscatcher',
            'year' => '2026',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            'stack' => 'laravel · flutter · mysql',
            'chips' => ['laravel', 'flutter'],
            'hero' => 'images/projects/buscatcher-hero.webp',
            // screens gallery emptied in favor of the live demo — images stay
            // on disk under images/projects/buscatcher/ in case they're ever
            // needed again, just not referenced here
            'screens' => [],
            'links' => [
                // static snapshot of the real admin dashboard, seeded demo data,
                // live map mocked via a service worker interpolating buses along
                // their real routes — see public/demos/buscatcher/
                'live' => '/demos/buscatcher/dashboard.html',
                'source' => null, // not public yet
            ],
            'private_source' => false,
        ],
        [
            'slug' => 'plannari',
            'year' => '2026',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            'stack' => 'flutter · laravel · mysql',
            'chips' => ['flutter', 'laravel'],
            'hero' => 'images/projects/plannari-hero.webp',
            'sketch' => 'images/projects/plannari/design-sketch.jpg',
            'screens' => [],
            'links' => [
                // Flutter web build + a service-worker-mocked API — see
                // public/demos/plannari-app/mock-sw.js. Framed at phone size
                // on desktop by public/demos/plannari/index.html.
                'live' => '/demos/plannari/',
                'source' => null, // repo not yet public
            ],
            'private_source' => false,
        ],
        [
            'slug' => 'mealplannari',
            'year' => '2026',
            'status_key' => 'in_use',
            'status_tone' => 'blue',
            'stack' => 'laravel · react · typescript',
            'chips' => ['laravel', 'react', 'ts'],
            'hero' => 'images/projects/mealplannari-hero.webp',
            'screens' => [],
            'links' => [
                // static snapshot of the real app, logged in as a seeded demo
                // user — see public/demos/mealplannari/ (captured, not live)
                'live' => '/demos/mealplannari/home.html',
                'source' => null,
            ],
            'private_source' => false,
        ],
        [
            'slug' => 'loai-atmania',
            'year' => '2025',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            'stack' => 'react · typescript · vite',
            'chips' => ['react', 'ts'],
            'hero' => 'images/projects/loai-atmania-hero.webp',
            'screens' => [],
            'links' => [
                // static-data build of the real frontend, not a live backend —
                // see public/demos/loai-atmania/ (built from LouisPortfolio.zip)
                'live' => '/demos/loai-atmania/',
                'source' => null,
            ],
            'private_source' => false,
        ],
    ],

];
