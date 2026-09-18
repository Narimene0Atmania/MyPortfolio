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
            'year' => '2025',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            'stack' => 'laravel · flutter · mysql',
            'chips' => ['laravel', 'flutter'],
            'hero' => 'images/projects/buscatcher-hero.webp',
            'screens' => [],
            'links' => [
                'live' => null,
                // graduation project — university-owned code, source stays private
                'source' => null,
            ],
            'private_source' => true,
        ],
        [
            'slug' => 'plannari',
            'year' => '2024',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            'stack' => 'flutter · dart',
            'chips' => ['flutter'],
            'hero' => 'images/projects/plannari-hero.webp',
            'screens' => [],
            'links' => [
                'live' => null,
                'source' => null, // repo not yet public
            ],
            'private_source' => false,
        ],
        [
            'slug' => 'mealplannari',
            'year' => '2024',
            'status_key' => 'in_use',
            'status_tone' => 'blue',
            // stack says flutter/dart per earlier conversation, but this
            // screenshot is clearly a browser web app (nav bar, hover cards)
            // — flagged for you, see chat
            'stack' => 'flutter · dart',
            'chips' => ['flutter'],
            'hero' => 'images/projects/mealplannari-hero.webp',
            'screens' => [],
            'links' => [
                'live' => null,
                'source' => null,
            ],
            'private_source' => false,
        ],
        [
            'slug' => 'loai-atmania',
            'year' => '2025',
            'status_key' => 'shipped',
            'status_tone' => 'green',
            // stack unconfirmed — placeholder pending real details
            'stack' => 'html · css · javascript',
            'chips' => ['js'],
            'hero' => 'images/projects/loai-atmania-hero.webp',
            'screens' => [],
            'links' => [
                'live' => null, // unconfirmed
                'source' => null,
            ],
            'private_source' => false,
        ],
    ],

];
