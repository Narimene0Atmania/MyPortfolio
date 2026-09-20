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
            'summary' => 'Live bus tracking and role-based management across multiple university fleets.',
            'scope' => 'graduation project',
            'body' => 'BusCatcher gives every role — from a platform admin overseeing several universities down to individual drivers and students — a live view of the fleet: GPS tracking with automatic deviation and dwell alerts, route and stop management, and notifications that keep everyone on schedule. Built with my graduation-project partner, Madjida Benmammar.',
            'role' => 'requirements analysis, systems design, build',
            'timeline' => 'final-year graduation project',
            'features' => [
                ['Live GPS tracking with automatic alerts', 'real-time bus positions on an interactive campus map — the system flags it on its own when a bus strays off-route or lingers too long at a stop.'],
                ['A real multi-university hierarchy', 'platform admin, university admin, operator, driver, and student each get their own scoped dashboard, built to run several universities on one platform rather than a single campus.'],
                ['Route & stop management', 'admins configure routes, stops, and schedules without touching code, down to picking between alternative road paths for each segment of a route.'],
            ],
        ],

        'plannari' => [
            'title' => 'Plannari',
            'hero_alt' => 'An illustrated desk scene — a wall calendar, a to-do list, and a laptop showing a planning dashboard — evoking Plannari\'s day-planning focus.',
            'sketch_alt' => 'A hand-drawn pen sketch of the same desk scene — pinboard, shelf, wall calendar, to-do list, and laptop — the original concept drawing for the app\'s navigation.',
            'sketch_caption' => 'From sketch to final illustration — every object in the drawing made it in.',
            'summary' => 'A day planner where time is optional, and nothing unfinished just vanishes.',
            'scope' => 'personal project',
            'body' => 'Most to-do apps force a time onto every task, then quietly bury whatever didn\'t get done. Plannari makes time optional — a task can carry one, a duration, or neither — and anything left unchecked past its day becomes a debt you consciously reschedule, return, or drop, not something that disappears. Navigation is the illustrated desk scene above: tap the calendar, the to-do sheet, or the laptop to open the screen behind it.',
            'role' => 'concept, design, build',
            'timeline' => 'ongoing personal project',
            'features' => [
                ['Time is optional, not required', 'a task can carry a time, a duration, or neither — timed tasks stay locked in order, untimed ones drag anywhere between them.'],
                ['Debts instead of disappearing tasks', 'anything left unchecked past its day becomes a reviewable debt — reschedule it, send it back to its category, or drop it, from the debts view or the calendar.'],
                ['Routines that cascade and catch clashes', 'adding a routine copies its steps into the day; give it a start time and the steps cascade through their durations, auto-shifting, overlapping, or cancelling on collision.'],
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
