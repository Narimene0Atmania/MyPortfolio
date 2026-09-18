<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Index and home share this — the desktop shell always has the
     * projects/ window available, just closed/behind others by default
     * when reached via `/`. See design_handoff_projects: no separate page
     * chrome, the depth (index vs detail) is expressed in the same window.
     */
    public function index(): View
    {
        return view('desktop', [
            'projects' => $this->allProjects(),
            'activeProject' => null,
        ]);
    }

    public function show(string $slug): View
    {
        $projects = $this->allProjects();
        $project = collect($projects)->firstWhere('slug', $slug);

        abort_unless($project, 404);

        return view('desktop', [
            'projects' => $projects,
            'activeProject' => $project,
        ]);
    }

    /**
     * Merge config/projects.php's locale-independent fields with this
     * locale's translatable copy from lang/{locale}/projects.php, keyed by slug.
     */
    private function allProjects(): array
    {
        $copy = trans('projects.items');

        return collect(config('projects.items'))
            ->map(fn (array $item) => array_merge($item, $copy[$item['slug']] ?? []))
            ->all();
    }
}
