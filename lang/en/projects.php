<?php

// PLACEHOLDER COPY — draft, written to exercise the new projects/ index and
// detail views. Review before shipping: summaries, body paragraphs, and
// feature text are reasonable drafts based on what's known about each
// project, not final copy.

return [

    'back' => 'back',
    'next' => 'next project',
    'live' => 'live demo',
    'source' => 'source',
    'private_note' => 'source is private — university-owned code from my graduation project.',

    'status' => [
        'shipped' => 'shipped',
        'in_use' => 'in use',
        'archived' => 'archived',
    ],

    'items' => [

        'buscatcher' => [
            'title' => 'BusCatcher',
            'hero_alt' => 'BusCatcher\'s road route confirmation screen, showing a three-segment bus route on a map of Tlemcen with alternative road options for each segment.',
            'summary' => 'Live bus tracking and role-based management for a university campus.',
            'scope' => 'graduation project',
            'body' => 'Students and staff had no way to know when their bus would actually arrive, and the transport office ran schedules and drivers by hand. BusCatcher gives every role — admin, driver, and rider — a live view of the fleet: real-time GPS tracking, route and stop management, and notifications that keep everyone on schedule.',
            'role' => 'requirements analysis, systems design, build',
            'timeline' => 'final-year graduation project',
            'features' => [
                ['Live GPS tracking', 'real-time bus positions on an interactive campus map, updated continuously.'],
                ['Role-based dashboards', 'separate views for admins, drivers, and students, each scoped to what they need.'],
                ['Route & stop management', 'admins configure routes, stops, and schedules without touching code.'],
            ],
        ],

        'plannari' => [
            'title' => 'Plannari',
            'hero_alt' => 'An illustrated desk scene — a wall calendar, a to-do list, and a laptop showing a planning dashboard — evoking Plannari\'s day-planning focus.',
            'summary' => 'A day planner that keeps today\'s to-dos, and only today\'s, in view.',
            'scope' => 'personal project',
            'body' => 'Most to-do apps bury today\'s tasks under weeks of backlog, or demand a rigid project structure before you can add a single item. Plannari strips that away — a clean day view for planning what\'s actually in front of you, with just enough structure to stay organized without becoming its own chore.',
            'role' => 'concept, design, build',
            'timeline' => 'ongoing personal project',
            'features' => [
                ['Day-first view', 'today\'s list front and center, not buried in a project tree.'],
                ['Quick capture', 'add a task in one tap, no required fields to fight through first.'],
            ],
        ],

        'mealplannari' => [
            'title' => 'Meal Plannari',
            'hero_alt' => 'The meal browsing page, showing a grid of meal cards with photos, calories, macros, price, prep time, and shopping status for each.',
            'summary' => 'Meal planning, calorie tracking, and a pantry-aware shopping badge for every recipe.',
            'scope' => 'personal project',
            'body' => 'Paid calorie-tracking apps rarely let you build your own recipes, and there\'s no way to tell if tonight\'s plan needs a shopping trip or not. Meal Plannari fixes both: a week of meals with nutrition and price computed live from a shared ingredient database, your own recipes kept private or submitted to a moderated catalog, and a per-meal badge for whether it\'s pantry-ready or needs a store run.',
            'role' => 'concept, design, build',
            'timeline' => 'ongoing personal project',
            'features' => [
                ['Nutrition computed from ingredients', 'calories, macros, and price for every meal are derived live from a shared per-ingredient database, not entered by hand — change one ingredient and every meal using it updates.'],
                ['Build & submit your own recipes', 'bilingual recipes with steps and ingredients, kept private or sent to an admin moderation queue to join the public catalog.'],
                ['Pantry-ready shopping badge', 'a per-meal indicator based on what share of the recipe is pantry staples versus what you\'d actually need to buy.'],
            ],
        ],

        'loai-atmania' => [
            'title' => 'AI Consultant Portfolio',
            'hero_alt' => 'The portfolio\'s dark dashboard-styled homepage, with an operator profile card, a live-looking system stats panel, and three automation result cards.',
            'summary' => 'A dark, data-driven portfolio for an AI infrastructure consultant.',
            'scope' => 'freelance project',
            'body' => 'This portfolio needed to read as evidence, not a résumé — proof the automation work actually holds up in production. The result is a fixed, single-viewport HUD-style console: a boot sequence on load, then six tabs — home, capabilities, work, process, stack, contact — switched via a bottom tile nav, with live-looking system stats and before/after automation metrics standing in for a bio.',
            'role' => 'design, build',
            'timeline' => 'freelance project',
            'features' => [
                ['Styled to match the work', 'boot sequence, terminal output, operator/agent language — it mirrors the actual product: AI agents running infrastructure.'],
                ['Two purpose-built layouts', 'under 1024px the site swaps to an entirely different scrolling layout, not a shrunk-down desktop — built from the same underlying data.'],
                ['Six-tab HUD nav', 'a fixed bottom tile nav — home, capabilities, work, process, stack, contact — plus a persistent "book a call" CTA that\'s always one click away.'],
            ],
        ],

    ],

];
