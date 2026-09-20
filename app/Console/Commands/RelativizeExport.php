<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RelativizeExport extends Command
{
    protected $signature = 'export:relativize {--dist=dist : the exported site directory}';

    protected $description = 'Rewrite absolute APP_URL links in the exported site to root-relative ones';

    /**
     * route() and asset() always produce absolute URLs built from APP_URL,
     * so a fresh export has http://localhost baked into every link and asset
     * path. Stripping the origin leaves root-relative URLs, which work on
     * the production domain, on Netlify deploy previews (whose subdomains
     * are generated per-build), and when opening dist/ through any local
     * server — without the export needing to know where it will be hosted.
     */
    public function handle(): int
    {
        $dist = base_path($this->option('dist'));

        if (! is_dir($dist)) {
            $this->error("No exported site at [{$dist}]. Run `php artisan export` first.");

            return self::FAILURE;
        }

        $origin = rtrim(config('app.url'), '/');
        $rewritten = 0;

        foreach ($this->htmlFiles($dist) as $file) {
            $html = file_get_contents($file);

            if (! str_contains($html, $origin)) {
                continue;
            }

            // trailing slash first, so "{origin}/" doesn't become "" and
            // collapse a root-relative path into a protocol-less fragment
            file_put_contents($file, str_replace([$origin.'/', $origin], ['/', ''], $html));
            $rewritten++;
        }

        $this->info("Rewrote {$origin} → / in {$rewritten} file(s).");

        return self::SUCCESS;
    }

    /**
     * @return iterable<string>
     */
    private function htmlFiles(string $dist): iterable
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dist, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'html') {
                yield $file->getPathname();
            }
        }
    }
}
