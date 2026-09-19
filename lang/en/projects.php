<?php

// PLACEHOLDER COPY — draft, written to exercise the new projects/ index and
// detail views. Review before shipping: summaries, body paragraphs, and
// feature text are reasonable drafts based on what's known about each
// project, not final copy.

return [

    'back' => 'back',
    'next' => 'next project',
    'live' => 'live site',
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
            'summary' => 'Meal planning, calorie tracking, and the grocery list it generates.',
            'scope' => 'personal project',
            'body' => 'Meal-planning apps usually stop at the plan, leaving you to build the grocery list yourself and track calories somewhere else entirely. Meal Plannari connects the three — plan a week of meals, watch the calorie count as you go, and get a grocery list generated straight from what you planned.',
            'role' => 'concept, design, build',
            'timeline' => 'ongoing personal project',
            'features' => [
                ['Weekly meal planner', 'lay out meals for the week in one view.'],
                ['Auto-generated grocery list', 'built directly from your planned meals, not a separate step.'],
                ['Calorie tracking', 'see where you stand as you plan, not after the fact.'],
            ],
        ],

        'loai-atmania' => [
            'title' => 'AI Consultant Portfolio',
            'hero_alt' => 'The portfolio\'s dark dashboard-styled homepage, with an operator profile card, a live-looking system stats panel, and three automation result cards.',
            'summary' => 'A dark, data-driven portfolio for an AI infrastructure consultant.',
            'scope' => 'client project',
            'body' => 'A consultant needed a portfolio that reads as evidence, not a résumé — proof the automation work actually holds up in production. The result is a dark, dashboard-styled landing page: live-looking system stats, before/after automation metrics, and a capability breakdown sit above the fold instead of a bio.',
            'role' => 'design, build',
            'timeline' => 'client project',
            'features' => [
                ['Live system panel', 'animated uptime, workflow, and hours-saved counters that make the automation claims feel measurable.'],
                ['Before/after result cards', 'three automation case cards — invoice triage, lead routing, support copilot — each showing a concrete metric shift.'],
                ['Persistent bottom nav', 'a fixed action bar keeping capabilities, work, and a "book a call" CTA always reachable.'],
            ],
        ],

    ],

];
