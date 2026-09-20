<?php

namespace App\Providers;

use App\Support\ChangelogReader;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // the changelog.txt window's table reads straight from the Word doc
        // rather than a duplicated copy — a composer (not each controller)
        // so it's available no matter which route rendered `desktop`
        View::composer('desktop', function ($view) {
            $view->with(
                'changelogItems',
                ChangelogReader::read(base_path('docs/Portfolio_Issues_and_Fixes.docx'))
            );

            // the taskbar language switcher points at the current page in
            // each other language, so switching keeps you where you are
            $view->with('localeUrls', $this->localeUrls());
        });
    }

    /**
     * The current route's URL in every supported locale.
     *
     * @return array<string, string>
     */
    private function localeUrls(): array
    {
        $route = request()->route();
        $urls = [];

        foreach (array_keys(config('portfolio.locales')) as $code) {
            $urls[$code] = $route
                ? route($route->getName(), [...$route->parameters(), 'locale' => $code])
                : url('/'.$code);
        }

        return $urls;
    }
}
