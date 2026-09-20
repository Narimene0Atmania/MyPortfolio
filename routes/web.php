<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Locale-prefixed routes
|--------------------------------------------------------------------------
|
| Every page lives under /{locale} (/en/projects, /fr/projects/plannari, …)
| rather than switching language via a session. That's what makes the site
| exportable to static hosting: each language is its own set of real HTML
| files, with no server needed to remember who picked what.
|
| SetLocale registers the matched locale as a default route parameter, so
| `route('projects.show', $slug)` still works everywhere without passing a
| locale by hand — it resolves to whatever language the current page is in.
|
*/

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('portfolio.locales')))
    ->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('home');

        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
    });

// bare "/" has no language of its own — send it to the default. In the
// static build this is handled by public/_redirects instead, since there's
// no PHP to run the redirect.
Route::get('/', fn () => redirect('/'.config('app.locale')));
