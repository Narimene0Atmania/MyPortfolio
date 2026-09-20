<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Portfolio owner links
    |--------------------------------------------------------------------------
    |
    | Placeholder values — update these in your .env file once the real
    | links are ready (see README handoff notes: email/github/resume are
    | placeholders until supplied by the owner).
    |
    */

    'name' => env('PORTFOLIO_NAME', 'Narimene Atmania'),

    'contact_email' => env('PORTFOLIO_CONTACT_EMAIL', 'hello@example.com'),

    'github_url' => env('PORTFOLIO_GITHUB_URL', 'https://github.com/'),

    'resume_urls' => [
        'en' => env('PORTFOLIO_RESUME_URL_EN', '/resume/Atmania_Narimene_CV_EN.pdf'),
        'fr' => env('PORTFOLIO_RESUME_URL_FR', '/resume/Atmania_Narimene_CV_FR.pdf'),
        'ar' => env('PORTFOLIO_RESUME_URL_AR', '/resume/Atmania_Narimene_CV_AR.pdf'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported locales
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the /{locale} route prefix, the SetLocale
    | middleware, and the taskbar language switcher UI.
    |
    */

    'locales' => [
        'en' => 'English',
        'fr' => 'Français',
        'ar' => 'العربية',
    ],

];
