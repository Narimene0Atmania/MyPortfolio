{{-- Expects $projects in scope. Shared by the desktop projects/ window and the mobile projects panel. --}}
<div class="projects__grid-new">
    @foreach ($projects as $project)
        @include('partials.project-card', ['project' => $project])
    @endforeach
</div>
