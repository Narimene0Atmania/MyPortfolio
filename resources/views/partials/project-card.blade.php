{{--
    Expects $project (a merged config+lang project array) in scope.
    Shared by the desktop projects/ window's index grid and the mobile
    projects panel — same markup, CSS collapses it to one column at
    narrow widths either way.
--}}
@php
    $chipVariants = ['laravel' => 'pink', 'php' => 'lavender', 'react' => 'blue', 'js' => 'yellow', 'ts' => 'mint', 'rest api' => 'mint', 'flutter' => 'blue', 'vue' => 'mint'];
@endphp
<div class="project-card">
    <a href="{{ route('projects.show', $project['slug']) }}" class="project-card__link" aria-label="{{ $project['title'] }}"></a>
    <div class="project-card__thumb">
        @if ($project['hero'])
            <img src="{{ asset($project['hero']) }}" alt="{{ $project['hero_alt'] ?? $project['title'] }}" loading="lazy">
        @else
            <span class="project-card__placeholder-tag">{{ __('site.projects.no_screenshot') }}</span>
        @endif
        @if (! empty($project['links']['live']))
            <a href="{{ $project['links']['live'] }}" target="_blank" rel="noopener" class="status-pill status-pill--live status-pill--card">
                <span class="status-pill__dot" aria-hidden="true"></span>{{ __('projects.live') }}
            </a>
        @endif
    </div>
    <div class="project-card__body">
        <div class="project-card__title-row">
            <span class="project-card__title">{{ $project['title'] }}</span>
            <span class="project-card__year">{{ $project['year'] }}</span>
        </div>
        <p class="project-card__summary">{{ $project['summary'] }}</p>
        <div class="project-card__chips">
            @foreach ($project['chips'] as $chip)
                <span class="chip chip--sm chip--{{ $chipVariants[$chip] ?? 'lavender' }}">{{ $chip }}</span>
            @endforeach
        </div>
    </div>
</div>
