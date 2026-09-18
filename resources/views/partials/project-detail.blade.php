{{--
    Expects $project (merged config+lang array) and $projects (full ordered
    list, for the next-project wraparound) in scope. Shared by the desktop
    projects/ window's detail view and the mobile projects panel.
--}}
@php
    $statusTone = $project['status_tone'];
    $slugs = array_column($projects, 'slug');
    $currentIndex = array_search($project['slug'], $slugs, true);
    $nextProject = $projects[($currentIndex + 1) % count($projects)];
@endphp
<div class="project-detail">
    <div class="project-detail__hero">
        @if ($project['hero'])
            <img src="{{ asset($project['hero']) }}" alt="{{ $project['hero_alt'] ?? $project['title'] }}" loading="lazy">
        @else
            <span class="project-detail__placeholder-tag">{{ __('site.projects.no_screenshot') }}</span>
        @endif
    </div>

    <div class="project-detail__header">
        <div>
            <h1 class="project-detail__title">{{ $project['title'] }}</h1>
            <div class="project-detail__meta">{{ $project['year'] }} · {{ $project['scope'] }} · {{ $project['stack'] }}</div>
        </div>
        <span class="status-pill status-pill--{{ $statusTone }}">
            <span class="status-pill__dot" aria-hidden="true"></span>{{ __('projects.status.' . $project['status_key']) }}
        </span>
    </div>

    <div class="project-detail__body">
        <span class="project-detail__body-rule" aria-hidden="true"></span>
        <p>{{ $project['body'] }}</p>
    </div>

    <div class="project-detail__facts">
        <div class="fact-card">
            <div class="fact-card__label">{{ __('site.resume.roles_label') }}</div>
            <div class="fact-card__value">{{ $project['role'] }}</div>
        </div>
        <div class="fact-card">
            <div class="fact-card__label">timeline</div>
            <div class="fact-card__value">{{ $project['timeline'] }}</div>
        </div>
        <div class="fact-card">
            <div class="fact-card__label">{{ __('site.resume.stack_label') }}</div>
            <div class="fact-card__value">{{ $project['stack'] }}</div>
        </div>
    </div>

    <div class="project-detail__features">
        <div class="skills__head">
            <span class="skills__label">what it does</span>
            <span class="skills__rule" aria-hidden="true"></span>
        </div>
        <ul class="skills__list project-detail__feature-list">
            @foreach ($project['features'] as $i => $feature)
                @php [$lead, $rest] = $feature; @endphp
                <li class="diff-card diff-card--{{ ($i % 3) + 1 }}">
                    <span class="diff-card__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <p class="diff-card__text"><strong>{{ $lead }}</strong> — {{ $rest }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    @if (! empty($project['screens']))
        <div class="project-detail__screens">
            <div class="skills__head">
                <span class="skills__label">screens</span>
                <span class="skills__rule" aria-hidden="true"></span>
            </div>
            <div class="project-detail__screens-grid">
                @foreach ($project['screens'] as $screen)
                    <div class="project-detail__screen">
                        <img src="{{ asset($screen) }}" alt="" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="project-detail__footer">
        @if (! empty($project['links']['live']))
            <a href="{{ $project['links']['live'] }}" target="_blank" rel="noopener" class="btn-project btn-project--live">{{ __('projects.live') }}</a>
        @endif
        @if (! empty($project['links']['source']))
            <a href="{{ $project['links']['source'] }}" target="_blank" rel="noopener" class="btn-project btn-project--source">{{ __('projects.source') }}</a>
        @elseif ($project['private_source'])
            <span class="project-detail__private-note">{{ __('projects.private_note') }}</span>
        @endif
        <a href="{{ route('projects.show', $nextProject['slug']) }}" class="btn-project btn-project--next">{{ __('projects.next') }} <span class="i-flip">&rarr;</span></a>
    </div>
</div>
