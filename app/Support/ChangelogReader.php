<?php

namespace App\Support;

/**
 * Reads the issue/solution/details table straight out of docs/Portfolio_Issues_and_Fixes.docx
 * so the changelog.txt window always mirrors whatever the Word document
 * currently says — no separate copy of the content to keep in sync by hand.
 *
 * A .docx is a zip archive; the document body lives in word/document.xml as
 * WordprocessingML. No Composer package needed — PHP's built-in ZipArchive
 * and DOM extensions are enough to pull text out of the first table's cells.
 */
class ChangelogReader
{
    private const WORD_NS = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    /**
     * @return array<int, array{issue: string, solution: string, details: string}>
     */
    public static function read(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        // cache keyed by the file's own mtime — edit-and-save in Word
        // changes the mtime, which changes the key, which busts the cache
        // automatically. No manual "clear cache" step ever needed.
        $cacheKey = 'changelog_docx_' . md5($path) . '_' . filemtime($path);

        return cache()->remember($cacheKey, now()->addDay(), fn () => self::parse($path));
    }

    /**
     * @return array<int, array{issue: string, solution: string, details: string}>
     */
    private static function parse(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            return [];
        }

        $dom = new \DOMDocument();
        // docx XML can be large-ish and has no external entities we need;
        // suppress warnings from any minor well-formedness quirks Word emits
        libxml_use_internal_errors(true);
        $loaded = $dom->loadXML($xml);
        libxml_clear_errors();

        if (! $loaded) {
            return [];
        }

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::WORD_NS);

        $tables = $xpath->query('//w:tbl');
        if ($tables === false || $tables->length === 0) {
            return [];
        }

        // first table in the document — that's the issues/solution/details
        // table build.js generates; skip its header row
        $rows = $xpath->query('.//w:tr', $tables->item(0));
        if ($rows === false) {
            return [];
        }

        $items = [];
        foreach ($rows as $i => $row) {
            if ($i === 0) {
                continue;
            }

            $cells = $xpath->query('.//w:tc', $row);
            if ($cells === false || $cells->length < 3) {
                continue;
            }

            $cellText = fn (\DOMNode $cell) => self::cellText($xpath, $cell);

            $items[] = [
                'issue' => $cellText($cells->item(0)),
                'solution' => $cellText($cells->item(1)),
                'details' => $cellText($cells->item(2)),
            ];
        }

        return $items;
    }

    /**
     * Word splits text across multiple <w:t> runs within a cell (revision
     * markers, spell-check boundaries, etc.) — concatenate them all rather
     * than assuming one run per cell.
     */
    private static function cellText(\DOMXPath $xpath, \DOMNode $cell): string
    {
        $textNodes = $xpath->query('.//w:t', $cell);
        if ($textNodes === false) {
            return '';
        }

        $text = '';
        foreach ($textNodes as $node) {
            $text .= $node->textContent;
        }

        return trim($text);
    }
}
