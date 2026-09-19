<?php

// English-only by design — a dev changelog, like the site's other literal
// filename-style chrome (about.txt, resume.pdf). FR/AR visitors see this
// automatically fall back to English rather than being duplicated 3x.
//
// Row content is NOT here — it's read live from docs/Portfolio_Issues_and_Fixes.docx
// (see App\Support\ChangelogReader + the `desktop` view composer in
// AppServiceProvider), so the Word document stays the single source of
// truth instead of a copy that can drift out of sync with it.

return [

    'heading' => 'issues & fixes log',
    'subheading' => 'a running log of issues found and fixed during development.',
    'col_issue' => 'issue',
    'col_solution' => 'solution',
    'col_details' => 'details',
    'empty' => 'no entries yet — check back after the next update.',

];
