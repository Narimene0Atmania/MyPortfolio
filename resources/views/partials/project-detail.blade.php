{{--
    Expects $project (merged config+lang array) and $projects (full ordered
    list, for the next-project wraparound) in scope. Shared by the desktop
    projects/ window's detail view and the mobile projects panel.
--}}
@php
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
        @if (! empty($project['links']['live']))
            <a href="{{ $project['links']['live'] }}" target="_blank" rel="noopener nofollow" class="status-pill status-pill--live"
               data-umami-event="demo-open" data-umami-event-project="{{ $project['slug'] }}" data-umami-event-from="detail">
                <span class="status-pill__dot" aria-hidden="true"></span>{{ __('projects.live') }}
            </a>
        @endif
    </div>

    <div class="project-detail__body">
        <span class="project-detail__body-rule" aria-hidden="true"></span>
        <p>{{ $project['body'] }}</p>
    </div>

    @if (! empty($project['sketch']))
        @php
            $sketchUrl = asset($project['sketch']);
            $finalUrl = asset($project['hero']);
            $sketchAlt = $project['sketch_alt'] ?? '';
            $finalAlt = $project['hero_alt'] ?? '';
        @endphp
        <figure class="project-detail__sketch" x-data="sketchCompare({{ \Illuminate\Support\Js::from([$sketchUrl, $finalUrl]) }})" @keydown.window="onKey($event)">
            <div class="project-detail__sketch-pair">
                <button type="button" class="project-detail__sketch-thumb" @click="open(0)">
                    <img src="{{ $sketchUrl }}" alt="{{ $sketchAlt }}" loading="lazy">
                </button>
                <button type="button" class="project-detail__sketch-thumb" @click="open(1)">
                    <img src="{{ $finalUrl }}" alt="{{ $finalAlt }}" loading="lazy">
                </button>
            </div>
            <figcaption>{{ $project['sketch_caption'] ?? '' }}</figcaption>

            {{-- desktop: both images enlarged together, side by side --}}
            <template x-teleport="body">
                <div class="lightbox" x-show="pairOpen" x-cloak x-transition.opacity.duration.150ms
                     @click.self="closePair()" data-screen-label="sketch compare lightbox (pair)">
                    <button type="button" class="lightbox__close" @click="closePair()" aria-label="close">&times;</button>
                    <div class="lightbox__pair">
                        <img :src="images[0]" alt="{{ $sketchAlt }}">
                        <img :src="images[1]" alt="{{ $finalAlt }}">
                    </div>
                </div>
            </template>

            {{-- mobile: one at a time, with prev/next between the two --}}
            <template x-teleport="body">
                <div class="lightbox" x-show="single !== null" x-cloak x-transition.opacity.duration.150ms
                     @click.self="closeSingle()" data-screen-label="sketch compare lightbox (single)">
                    <button type="button" class="lightbox__close" @click="closeSingle()" aria-label="close">&times;</button>
                    <button type="button" class="lightbox__nav lightbox__nav--prev" @click="prev()" aria-label="previous">&larr;</button>
                    <img class="lightbox__img" :src="single !== null ? images[single] : ''" alt="">
                    <button type="button" class="lightbox__nav lightbox__nav--next" @click="next()" aria-label="next">&rarr;</button>
                </div>
            </template>
        </figure>
    @endif

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
        @php $screenUrls = array_map(fn ($s) => asset($s), $project['screens']); @endphp
        <div class="project-detail__screens" x-data="gallery({{ \Illuminate\Support\Js::from($screenUrls) }})" @keydown.window="onKey($event)">
            <div class="skills__head">
                <span class="skills__label">screens</span>
                <span class="skills__rule" aria-hidden="true"></span>
            </div>
            <div class="project-detail__screens-grid">
                @foreach ($screenUrls as $i => $url)
                    <button type="button" class="project-detail__screen" @click="open({{ $i }})">
                        <img src="{{ $url }}" alt="" loading="lazy">
                    </button>
                @endforeach
            </div>

            <template x-teleport="body">
                <div class="lightbox" x-show="lightbox !== null" x-cloak x-transition.opacity.duration.150ms
                     @click.self="close()" data-screen-label="screenshot lightbox">
                    <button type="button" class="lightbox__close" @click="close()" aria-label="close">&times;</button>
                    <button type="button" class="lightbox__nav lightbox__nav--prev" x-show="screens.length > 1" @click="prev()" aria-label="previous">&larr;</button>
                    <img class="lightbox__img" :src="lightbox !== null ? screens[lightbox] : ''" alt="">
                    <button type="button" class="lightbox__nav lightbox__nav--next" x-show="screens.length > 1" @click="next()" aria-label="next">&rarr;</button>
                </div>
            </template>
        </div>
    @endif

    <div class="project-detail__footer">
        @if (! empty($project['links']['source']))
            <a href="{{ $project['links']['source'] }}" target="_blank" rel="noopener" class="btn-project btn-project--source">{{ __('projects.source') }}</a>
        @elseif ($project['private_source'])
            <span class="project-detail__private-note">{{ __('projects.private_note') }}</span>
        @endif
        <a href="{{ route('projects.show', $nextProject['slug']) }}" class="btn-project btn-project--next">{{ __('projects.next') }} <span class="i-flip">&rarr;</span></a>
    </div>
</div>
