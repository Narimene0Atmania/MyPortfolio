<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('portfolio.name') }} — {{ __('site.meta.title') }}</title>
    <meta name="description" content="{{ __('site.meta.description') }}">

    <link rel="preload" as="image" href="/images/wallpaper-city.webp" type="image/webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&family=Karla:wght@400;600;700&family=Caveat:wght@600&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif

    @php
        $portfolioI18n = [
            'invalid' => __('site.contact.status.invalid'),
            'error' => __('site.contact.status.error'),
            'network' => __('site.contact.status.network'),
        ];
    @endphp
    <script>
        window.PORTFOLIO_I18N = @json($portfolioI18n);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="desktop"
     x-data="desktop('{{ request()->routeIs('projects.*') ? 'projects' : '' }}')"
     @keydown.escape.window="startOpen = false"
     @click.away="startOpen = false"
     data-screen-label="desktop v2">

    {{-- ============ boot splash ============ --}}
    <div class="boot"
         x-show="!booted"
         :class="{ 'boot--fading': bootFading }"
         x-ref="boot"
         data-screen-label="boot splash">
        <div class="boot__title">narimene os</div>
        <div class="boot__track">
            <div class="boot__fill" x-ref="bootFill"></div>
        </div>
        <div class="boot__label">loading desktop<span class="boot__caret">_</span></div>
    </div>

    {{-- ============ cursor trail ============ --}}
    <div class="cursor-trail" x-ref="trail" aria-hidden="true">
        @foreach ([['11px', '#FFD9EA'], ['10px', '#E2D5F6'], ['9px', '#CFEEE6'], ['8px', '#FFF3CF'], ['7px', '#F7C7DE'], ['6px', '#D8E6F9']] as $i => [$size, $color])
            <span class="cursor-trail__dot"
                  style="width: {{ $size }}; height: {{ $size }}; background: {{ $color }};"></span>
        @endforeach
    </div>

    {{-- ============ desktop icons ============ --}}
    <div class="desktop-icons">
        <button type="button" class="desktop-icon" @click="focus('about')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <rect x="6" y="6" width="30" height="22" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1.5"/>
                <rect x="10" y="10" width="22" height="14" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M17 28v4h8v-4M13 34h16" stroke="#4A3D73" stroke-width="1.5" fill="none"/>
                <path d="M14 14h8M14 18h11" stroke="#9D8FD6" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label desktop-icon__label--selected">about.txt</span>
        </button>

        <button type="button" class="desktop-icon" @click="focus('projects')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <path d="M6 9h12l3 4h15v20H6z" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M6 15h30" stroke="#4A3D73" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label">projects/</span>
        </button>

        <button type="button" class="desktop-icon" @click="focus('skills')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <path d="M16 7v4M21 7v4M26 7v4M16 31v4M21 31v4M26 31v4M7 16h4M7 21h4M7 26h4M31 16h4M31 21h4M31 26h4" stroke="#4A3D73" stroke-width="1.5"/>
                <rect x="11" y="11" width="20" height="20" fill="#A9E2DA" stroke="#4A3D73" stroke-width="1.5"/>
                <rect x="16" y="16" width="10" height="10" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label">skills.dll</span>
        </button>

        <button type="button" class="desktop-icon" @click="focus('resume')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <path d="M10 5h16l6 6v26H10z" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M26 5v6h6z" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1.5"/>
                <rect x="7" y="23" width="20" height="11" fill="#9D8FD6" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M11 27h12M11 30h8" stroke="#FBF9FE" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label">resume.pdf</span>
        </button>

        <button type="button" class="desktop-icon" @click="focus('contact')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <rect x="5" y="11" width="32" height="22" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M5 11l16 13 16-13" fill="none" stroke="#4A3D73" stroke-width="1.5"/>
                <circle cx="33" cy="12" r="6" fill="#A9E2DA" stroke="#4A3D73" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label">contact.exe</span>
        </button>

        <button type="button" class="desktop-icon" @click="focus('changelog')">
            <svg width="42" height="42" viewBox="0 0 42 42" aria-hidden="true">
                <rect x="9" y="7" width="24" height="30" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                <rect x="15" y="4" width="12" height="6" rx="1.5" fill="#A9E2DA" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M13 17l2 2 4-4" stroke="#4DBB98" stroke-width="1.5" fill="none"/>
                <path d="M22 18h8" stroke="#9D8FD6" stroke-width="1.5"/>
                <path d="M13 25l2 2 4-4" stroke="#4DBB98" stroke-width="1.5" fill="none"/>
                <path d="M22 26h8" stroke="#9D8FD6" stroke-width="1.5"/>
                <path d="M13 33h6" stroke="#C9BAEC" stroke-width="1.5"/>
            </svg>
            <span class="desktop-icon__label">changelog.txt</span>
        </button>
    </div>

    {{-- ============ window: projects/ ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('projects') }"
         :style="winStyle('projects', '48%', '52px', '720px', '60ms')"
         x-show="isOpenVisible('projects')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('projects')"
         data-screen-label="projects window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('projects') }"
             @pointerdown="startDrag('projects', $event)">
            <span>projects/{{ $activeProject['slug'] ?? '' }}</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('projects')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn" aria-label="close" @click.stop="close('projects')">x</button>
            </div>
        </div>
        <div class="projects__pathbar">
            @if ($activeProject)
                <a href="{{ route('projects.index') }}" class="projects__back"><span class="i-flip">&larr;</span> {{ __('projects.back') }}</a>
            @endif
            <span>c:/narimene/projects/{{ $activeProject ? $activeProject['slug'].'/' : '' }}</span>
            <span>{{ $activeProject ? $activeProject['year'] : trans_choice('site.projects.item_count', count($projects)) }}</span>
        </div>
        @if (count($projects) === 0)
            <div class="well projects__empty">
                <div class="projects__empty-folder" aria-hidden="true"></div>
                <div class="projects__empty-title">{{ __('site.projects.empty_title') }}</div>
                <div class="projects__empty-subtitle">{{ __('site.projects.empty_subtitle') }}</div>
                <div class="projects__empty-bar" aria-hidden="true"></div>
            </div>
        @elseif ($activeProject)
            <div class="well projects__well">
                @include('partials.project-detail', ['project' => $activeProject, 'projects' => $projects])
            </div>
        @else
            <div class="well projects__well">
                @include('partials.projects-index', ['projects' => $projects])
            </div>
        @endif
    </div>

    {{-- ============ window: contact.exe ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('contact') }"
         :style="winStyle('contact', '11%', '428px', '410px', '120ms')"
         x-show="isOpenVisible('contact')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('contact')"
         data-screen-label="contact window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('contact') }"
             @pointerdown="startDrag('contact', $event)">
            <span>contact.exe</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('contact')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn" aria-label="close" @click.stop="close('contact')">x</button>
            </div>
        </div>
        <form class="well contact__body" @submit.prevent="submitContact" @click.stop novalidate>
            <div class="form-field">
                <label class="form-field__label" for="name">{{ __('site.contact.label_name') }}</label>
                <input id="name" name="name" type="text" class="form-field__input" x-model="form.name" autocomplete="name">
                <span class="form-field__error" x-show="formErrors.name" x-text="formErrors.name"></span>
            </div>
            <div class="form-field">
                <label class="form-field__label" for="email">{{ __('site.contact.label_email') }}</label>
                <input id="email" name="email" type="email" class="form-field__input" x-model="form.email" autocomplete="email">
                <span class="form-field__error" x-show="formErrors.email" x-text="formErrors.email"></span>
            </div>
            <div class="form-field">
                <label class="form-field__label" for="message">{{ __('site.contact.label_message') }}</label>
                <textarea id="message" name="message" class="form-field__textarea" x-model="form.message"></textarea>
                <span class="form-field__error" x-show="formErrors.message" x-text="formErrors.message"></span>
            </div>
            <div class="contact__status"
                 :class="formStatus?.ok ? 'contact__status--ok' : 'contact__status--err'"
                 x-show="formStatus"
                 x-text="formStatus?.message"></div>
            <div class="contact__footer">
                <a class="link-btn" href="mailto:{{ config('portfolio.contact_email') }}">{{ __('site.contact.footer.email') }}</a>
                <a class="link-btn" href="{{ config('portfolio.github_url') }}" target="_blank" rel="noopener">{{ __('site.contact.footer.github') }}</a>
                <button type="submit" class="btn-pink" :disabled="formSubmitting">
                    <span x-text="formSubmitting ? '{{ __('site.contact.status.sending') }}' : '{{ __('site.contact.status.send') }}'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- ============ window: changelog.txt ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('changelog') }"
         :style="winStyle('changelog', '38%', '110px', '700px', '150ms')"
         x-show="isOpenVisible('changelog')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('changelog')"
         data-screen-label="changelog window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('changelog') }"
             @pointerdown="startDrag('changelog', $event)">
            <span>changelog.txt</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('changelog')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn" aria-label="close" @click.stop="close('changelog')">x</button>
            </div>
        </div>
        <div class="well changelog__well" dir="ltr">
            <div class="changelog__head">
                <h2 class="changelog__heading">{{ __('changelog.heading') }}</h2>
                <p class="changelog__subheading">{{ __('changelog.subheading') }}</p>
            </div>
            <div class="changelog__table-wrap">
                <table class="changelog-table">
                    <thead>
                        <tr>
                            <th>{{ __('changelog.col_issue') }}</th>
                            <th>{{ __('changelog.col_solution') }}</th>
                            <th>{{ __('changelog.col_details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($changelogItems as $item)
                            <tr>
                                <td>{{ $item['issue'] }}</td>
                                <td>{{ $item['solution'] }}</td>
                                <td>{{ $item['details'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="changelog-table__empty">{{ __('changelog.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ window: about.txt ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('about') }"
         :style="winStyle('about', '22%', '136px', '{{ app()->getLocale() === 'ar' ? '610px' : '580px' }}')"
         x-show="isOpenVisible('about')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('about')"
         data-screen-label="about window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('about') }"
             @pointerdown="startDrag('about', $event)">
            <span>about.txt — narimene atmania</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('about')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn window__btn--close-pink" aria-label="close" @click.stop="close('about')">x</button>
            </div>
        </div>
        <div class="window__menu">
            <span><span class="mnemonic">f</span>ile</span>
            <span><span class="mnemonic">e</span>dit</span>
            <span><span class="mnemonic">v</span>iew</span>
            <span><span class="mnemonic">h</span>elp</span>
        </div>
        <div class="well">
            <div class="about__banner" role="img" aria-label="{{ __('site.about.banner_alt') }}"></div>
            <div class="about__header">
                <div class="about__name">{{ config('portfolio.name') }}</div>
                <div class="about__role">
                    <span>{{ __('site.about.role') }}</span>
                    <span class="about__status">{{ __('site.about.status') }}</span>
                </div>
                <div class="about__bio-wrap">
                    <span class="about__bio-rule" aria-hidden="true"></span>
                    <p class="about__bio">{{ __('site.about.bio') }}</p>
                </div>
            </div>
            @include('partials.chips')
        </div>
    </div>

    {{-- ============ window: skills.dll ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('skills') }"
         :style="winStyle('skills', '53%', '360px', '{{ app()->getLocale() === 'fr' ? '600px' : '500px' }}', '90ms')"
         x-show="isOpenVisible('skills')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('skills')"
         data-screen-label="skills window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('skills') }"
             @pointerdown="startDrag('skills', $event)">
            <span>skills.dll</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('skills')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn" aria-label="close" @click.stop="close('skills')">x</button>
            </div>
        </div>
        <div class="well skills__well">
            @include('partials.skill-cards')
        </div>
    </div>

    {{-- ============ window: resume.pdf ============ --}}
    <div class="window"
         :class="{ 'window--focused': isFocused('resume') }"
         :style="winStyle('resume', '33%', '96px', '470px')"
         x-show="isOpenVisible('resume')"
         x-transition:leave.opacity.scale.96.duration.180ms
         @click="focus('resume')"
         data-screen-label="resume window">
        <div class="window__titlebar" :class="{ 'window__titlebar--focused': isFocused('resume') }"
             @pointerdown="startDrag('resume', $event)">
            <span>resume.pdf</span>
            <div class="window__btns">
                <button type="button" class="window__btn" aria-label="minimize" @click.stop="minimize('resume')">_</button>
                <button type="button" class="window__btn window__btn--max" aria-label="maximize" tabindex="-1">&#9633;</button>
                <button type="button" class="window__btn" aria-label="close" @click.stop="close('resume')">x</button>
            </div>
        </div>
        <div class="well resume__well">
            <div class="resume__card">
                <div class="resume__name">{{ config('portfolio.name') }}</div>
                <div class="resume__role">{{ __('site.resume.role_summary') }}</div>
                <div class="resume__divider"></div>
                <div class="resume__label">{{ __('site.resume.roles_label') }}</div>
                <div class="resume__text">{{ __('site.resume.roles_text') }}</div>
                <div class="resume__label">{{ __('site.resume.stack_label') }}</div>
                <div class="resume__text">laravel · php · react · js · rest api · flutter</div>
            </div>
            <div class="resume__footer">
                <a class="btn-pink" href="{{ config('portfolio.resume_urls.'.app()->getLocale(), config('portfolio.resume_urls.en')) }}" target="_blank" rel="noopener">{{ __('site.resume.download_label') }}</a>
            </div>
        </div>
    </div>

    {{-- ============ dialog ============ --}}
    <div class="dialog"
         x-show="dialogVisible"
         x-transition:leave.opacity.scale.96.duration.180ms
         :style="dialogStyle()"
         data-screen-label="dialog">
        <div class="dialog__titlebar" @pointerdown="startDrag('dialog', $event)">
            <span>system</span>
            <button type="button" class="dialog__close" aria-label="close" @click="closeDialog">x</button>
        </div>
        <div class="dialog__body">
            <svg class="dialog__heart" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" style="flex:none">
                <path d="M12 21c-5-3.6-9-6.8-9-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.2-4 7.4-9 11z" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1.5"/>
            </svg>
            <span class="dialog__text">{{ __('site.dialog.text') }}</span>
        </div>
        <div class="dialog__actions">
            <button type="button" class="dialog__btn dialog__btn--yes" @click="closeDialog">{{ __('site.dialog.yes') }}</button>
            <button type="button" class="dialog__btn dialog__btn--no" @click="closeDialog">{{ __('site.dialog.no') }}</button>
        </div>
    </div>

    {{-- ============ resume notification ============ --}}
    @php
        $resumeUrl = config('portfolio.resume_urls.'.app()->getLocale(), config('portfolio.resume_urls.en'));
        $resumePath = public_path(ltrim($resumeUrl, '/'));
        $resumeDate = is_file($resumePath)
            ? \Illuminate\Support\Carbon::createFromTimestamp(filemtime($resumePath))
                ->locale(app()->getLocale())->isoFormat('MMMM YYYY')
            : null;
    @endphp
    <div class="notif"
         x-show="showNotif"
         data-screen-label="resume notification">
        <div class="notif__titlebar">
            <span class="notif__titlebar-label">&#9744; {{ __('site.notif.titlebar') }}</span>
            <button type="button" class="notif__close" aria-label="close" @click="dismissNotif">x</button>
        </div>
        <div class="notif__body">
            <svg class="notif__icon" width="34" height="40" viewBox="0 0 34 40" aria-hidden="true">
                <path d="M2 2h20l10 10v26H2z" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M22 2v10h10z" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1.5"/>
                <path d="M8 20h18M8 26h18M8 32h11" stroke="#9D8FD6" stroke-width="1.5"/>
            </svg>
            <div class="notif__text">
                <div class="notif__title">{{ __('site.notif.title') }}</div>
                @if ($resumeDate)
                    <div class="notif__meta">{{ __('site.notif.meta', ['date' => $resumeDate]) }}</div>
                @endif
            </div>
        </div>
        <div class="notif__actions">
            <button type="button" class="notif__btn" @click="dismissNotif">{{ __('site.notif.later') }}</button>
            <a class="notif__btn notif__btn--download"
               href="{{ $resumeUrl }}"
               target="_blank"
               rel="noopener"
               @click="dismissNotif">{{ __('site.notif.download') }}</a>
        </div>
    </div>

    {{-- ============ sticky note ============ --}}
    {{-- steps aside while the toast occupies the same corner --}}
    <div class="sticky-note"
         x-show="showSticky && !showNotif"
         data-screen-label="sticky note">
        <div class="sticky-note__tape"></div>
        <div class="sticky-note__text">{{ __('site.sticky_note.todo') }}<br>&#10003; {{ __('site.sticky_note.item_1') }}<br>&#9633; {{ __('site.sticky_note.item_2') }}<br>&#9633; {{ __('site.sticky_note.item_3') }}</div>
    </div>

    {{-- ============ taskbar ============ --}}
    <div class="taskbar" data-screen-label="taskbar">
        <button type="button" class="taskbar__start" @click.stop="toggleStart">
            <svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">
                <rect x="1" y="1" width="5.4" height="5.4" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1"/>
                <rect x="7.6" y="1" width="5.4" height="5.4" fill="#A9E2DA" stroke="#4A3D73" stroke-width="1"/>
                <rect x="1" y="7.6" width="5.4" height="5.4" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1"/>
                <rect x="7.6" y="7.6" width="5.4" height="5.4" fill="#9D8FD6" stroke="#4A3D73" stroke-width="1"/>
            </svg>
            <span>start</span>
        </button>

        <div class="taskbar__divider"></div>

        <div class="taskbar__sections">
            <button type="button" class="taskbar__section-btn" @click="focus('about')">{{ __('site.taskbar.sections.about') }}</button>
            <button type="button" class="taskbar__section-btn" @click="focus('skills')">{{ __('site.taskbar.sections.skills') }}</button>
            <button type="button" class="taskbar__section-btn" @click="focus('projects')">{{ __('site.taskbar.sections.projects') }}</button>
            <button type="button" class="taskbar__section-btn" @click="focus('resume')">{{ __('site.taskbar.sections.resume') }}</button>
            <button type="button" class="taskbar__section-btn" @click="focus('contact')">{{ __('site.taskbar.sections.contact') }}</button>
            <button type="button" class="taskbar__section-btn" @click="focus('changelog')">{{ __('site.taskbar.sections.changelog') }}</button>
        </div>

        <div class="taskbar__divider"></div>

        <div class="taskbar__windows">
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('about') }" x-show="win.about.open" @click="taskbarClick('about')">about.txt</button>
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('skills') }" x-show="win.skills.open" @click="taskbarClick('skills')">skills.dll</button>
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('projects') }" x-show="win.projects.open" @click="taskbarClick('projects')">projects/</button>
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('resume') }" x-show="win.resume.open" @click="taskbarClick('resume')">resume.pdf</button>
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('contact') }" x-show="win.contact.open" @click="taskbarClick('contact')">contact.exe</button>
            <button type="button" class="taskbar__win-btn" :class="{ 'taskbar__win-btn--active': isFocused('changelog') }" x-show="win.changelog.open" @click="taskbarClick('changelog')">changelog.txt</button>
        </div>

        <div class="taskbar__lang" role="group" aria-label="{{ __('site.lang_switcher.label') }}">
            @foreach (config('portfolio.locales') as $code => $label)
                <a href="{{ route('locale.switch', $code) }}"
                   class="taskbar__lang-btn {{ app()->getLocale() === $code ? 'taskbar__lang-btn--active' : '' }}">{{ strtoupper($code) }}</a>
            @endforeach
        </div>

        <div class="taskbar__clock">
            <span x-text="clock"></span><span class="taskbar__clock-date" x-text="dateStr"></span><span class="taskbar__moon" aria-hidden="true"></span>
        </div>
    </div>

    {{-- ============ start menu ============ --}}
    <div class="start-menu" x-show="startOpen" @click.stop data-screen-label="start menu">
        <div class="start-menu__inner">
            <div class="start-menu__rail">
                <span class="start-menu__rail-label">{{ __('site.start_menu.rail_label') }}</span>
            </div>
            <div class="start-menu__list">
                <button type="button" class="start-menu__item" @click="focus('about')">about.txt</button>
                <button type="button" class="start-menu__item" @click="focus('skills')">skills.dll</button>
                <button type="button" class="start-menu__item" @click="focus('projects')">projects/</button>
                <button type="button" class="start-menu__item" @click="focus('resume')">resume.pdf</button>
                <button type="button" class="start-menu__item" @click="focus('contact')">contact.exe</button>
                <button type="button" class="start-menu__item" @click="focus('changelog')">changelog.txt</button>
                <div class="start-menu__divider"></div>
                <a class="start-menu__item" href="mailto:{{ config('portfolio.contact_email') }}">{{ __('site.start_menu.email') }}</a>
                <a class="start-menu__item" href="{{ config('portfolio.github_url') }}" target="_blank" rel="noopener">{{ __('site.start_menu.github') }}</a>
            </div>
        </div>
    </div>
</div>

{{-- ============ mobile layout (responsive, auto-shown at <768px) — per design_handoff_mobile_nav ============ --}}
@php
    [$mobileFirstName, $mobileLastName] = array_pad(explode(' ', config('portfolio.name'), 2), 2, null);
@endphp
<div class="mobile" x-data="mobile('{{ request()->routeIs('projects.*') ? 'projects' : 'about' }}')">

    <div class="mobile-scroll" x-ref="scroller" data-screen-label="mobile scroll container">

        {{-- ============ panel: about ============ --}}
        <div class="mobile-panel" x-show="tab === 'about'" style="padding-bottom: 132px">
            <div class="mobile-banner" role="img" aria-label="{{ __('site.about.banner_alt') }}"></div>

            <div class="mobile-pad">
                <div class="mobile-eyebrow">about.txt</div>
                <h1 class="mobile-name">{{ $mobileFirstName }}<br>{{ $mobileLastName }}</h1>
                <div class="mobile-role-row">
                    <span>{{ __('site.about.role') }}</span>
                    <span class="mobile-status">
                        <span class="mobile-status__dot" aria-hidden="true"></span>{{ __('site.about.status') }}
                    </span>
                </div>
            </div>

            <div class="mobile-bio">
                <span class="mobile-bio__rule" aria-hidden="true"></span>
                <p class="mobile-bio__text">{{ __('site.about.bio') }}</p>
            </div>

            @include('partials.chips')

            @include('partials.skill-cards')
        </div>

        {{-- ============ panel: projects ============ --}}
        <div class="mobile-panel" x-show="tab === 'projects'" style="padding: 26px 22px 132px">
            @if ($activeProject)
                <a href="{{ route('projects.index') }}" class="mobile-back"><span class="i-flip">&larr;</span> {{ __('projects.back') }}</a>
                <div class="mobile-eyebrow">projects/{{ $activeProject['slug'] }}</div>
                @include('partials.project-detail', ['project' => $activeProject, 'projects' => $projects])
            @else
                <div class="mobile-eyebrow">projects/</div>
                <h1 class="mobile-heading">Projects</h1>
                <div class="mobile-meta">{{ trans_choice('site.projects.item_count', count($projects)) }}</div>

                @if (count($projects) === 0)
                    <div class="mobile-empty">
                        <div class="mobile-empty__icon" aria-hidden="true"></div>
                        <div class="mobile-empty__title">{{ __('site.projects.empty_title') }}</div>
                        <div class="mobile-empty__subtitle">{{ __('site.projects.empty_subtitle') }}</div>
                        <div class="mobile-empty__bar" aria-hidden="true"><span></span></div>
                    </div>
                @else
                    @include('partials.projects-index', ['projects' => $projects])
                @endif
            @endif
        </div>

        {{-- ============ panel: resume ============ --}}
        <div class="mobile-panel" x-show="tab === 'resume'" style="padding: 26px 22px 132px">
            <div class="mobile-eyebrow">resume.pdf</div>
            <h1 class="mobile-heading">Resume</h1>

            <div class="mobile-resume-card">
                <div class="mobile-resume-card__name">{{ config('portfolio.name') }}</div>
                <div class="mobile-resume-card__role">{{ __('site.resume.role_summary') }}</div>
                <div class="mobile-resume-card__divider"></div>
                <div class="mobile-resume-card__label">{{ __('site.resume.roles_label') }}</div>
                <div class="mobile-resume-card__text">{{ __('site.resume.roles_text') }}</div>
                <div class="mobile-resume-card__label">{{ __('site.resume.stack_label') }}</div>
                <div class="mobile-resume-card__text">laravel · php · react · js · rest api · flutter</div>
            </div>

            <a class="mobile-btn-primary"
               href="{{ config('portfolio.resume_urls.'.app()->getLocale(), config('portfolio.resume_urls.en')) }}"
               target="_blank" rel="noopener">{{ __('site.resume.download_label') }}</a>
        </div>

        {{-- ============ panel: contact ============ --}}
        <div class="mobile-panel" x-show="tab === 'contact'" style="padding: 26px 22px 132px">
            <div class="mobile-eyebrow">contact.exe</div>
            <h1 class="mobile-heading">Get in touch</h1>

            <form class="mobile-form" @submit.prevent="submitContact" novalidate>
                <div class="mobile-field">
                    <label for="mobile-name">{{ __('site.contact.label_name') }}</label>
                    <input id="mobile-name" name="name" type="text" x-model="form.name" autocomplete="name">
                    <span class="mobile-field__error" x-show="formErrors.name" x-text="formErrors.name"></span>
                </div>
                <div class="mobile-field">
                    <label for="mobile-email">{{ __('site.contact.label_email') }}</label>
                    <input id="mobile-email" name="email" type="email" x-model="form.email" autocomplete="email">
                    <span class="mobile-field__error" x-show="formErrors.email" x-text="formErrors.email"></span>
                </div>
                <div class="mobile-field">
                    <label for="mobile-message">{{ __('site.contact.label_message') }}</label>
                    <textarea id="mobile-message" name="message" rows="4" x-model="form.message"></textarea>
                    <span class="mobile-field__error" x-show="formErrors.message" x-text="formErrors.message"></span>
                </div>
                <div class="mobile-form__status"
                     :class="formStatus?.ok ? 'mobile-form__status--ok' : 'mobile-form__status--err'"
                     x-show="formStatus"
                     x-text="formStatus?.message"></div>
                <button type="submit" class="mobile-btn-primary" :disabled="formSubmitting">
                    <span x-text="formSubmitting ? '{{ __('site.contact.status.sending') }}' : '{{ __('site.contact.status.send') }}'"></span>
                </button>
            </form>

            <div class="mobile-social">
                <a href="{{ config('portfolio.github_url') }}" target="_blank" rel="noopener" class="mobile-social__tile">{{ __('site.contact.footer.github') }}</a>
                <a href="mailto:{{ config('portfolio.contact_email') }}" class="mobile-social__tile">{{ __('site.contact.footer.email') }}</a>
            </div>
        </div>

    </div>

    {{-- ============ bottom tab bar ============ --}}
    <nav class="mobile-nav" aria-label="{{ __('site.nav.label') }}">
        <div class="mobile-nav__bar">
            <button type="button" class="mobile-nav__tab" :class="{ 'mobile-nav__tab--active': tab === 'about' }"
                    :aria-current="tab === 'about' ? 'page' : null" @click="go('about')">
                <svg class="mobile-nav__icon" width="24" height="20" viewBox="0 0 42 42" aria-hidden="true">
                    <rect x="6" y="6" width="30" height="22" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1.5"/>
                    <rect x="10" y="10" width="22" height="14" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                    <path d="M17 28v4h8v-4M13 34h16" stroke="#4A3D73" stroke-width="1.5" fill="none"/>
                    <path d="M14 14h8M14 18h11" stroke="#9D8FD6" stroke-width="1.5"/>
                </svg>
                <span class="mobile-nav__label">{{ __('site.taskbar.sections.about') }}</span>
            </button>
            <button type="button" class="mobile-nav__tab" :class="{ 'mobile-nav__tab--active': tab === 'projects' }"
                    :aria-current="tab === 'projects' ? 'page' : null" @click="go('projects')">
                <svg class="mobile-nav__icon" width="24" height="20" viewBox="0 0 42 42" aria-hidden="true">
                    <path d="M6 9h12l3 4h15v20H6z" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1.5"/>
                    <path d="M6 15h30" stroke="#4A3D73" stroke-width="1.5"/>
                </svg>
                <span class="mobile-nav__label">{{ __('site.taskbar.sections.projects') }}</span>
            </button>
            <button type="button" class="mobile-nav__tab" :class="{ 'mobile-nav__tab--active': tab === 'resume' }"
                    :aria-current="tab === 'resume' ? 'page' : null" @click="go('resume')">
                <svg class="mobile-nav__icon" width="24" height="20" viewBox="0 0 42 42" aria-hidden="true">
                    <path d="M10 5h16l6 6v26H10z" fill="#FBF9FE" stroke="#4A3D73" stroke-width="1.5"/>
                    <path d="M26 5v6h6z" fill="#C9BAEC" stroke="#4A3D73" stroke-width="1.5"/>
                    <rect x="7" y="23" width="20" height="11" fill="#9D8FD6" stroke="#4A3D73" stroke-width="1.5"/>
                    <path d="M11 27h12M11 30h8" stroke="#FBF9FE" stroke-width="1.5"/>
                </svg>
                <span class="mobile-nav__label">{{ __('site.taskbar.sections.resume') }}</span>
            </button>
            <button type="button" class="mobile-nav__tab" :class="{ 'mobile-nav__tab--active': tab === 'contact' }"
                    :aria-current="tab === 'contact' ? 'page' : null" @click="go('contact')">
                <svg class="mobile-nav__icon" width="24" height="20" viewBox="0 0 42 42" aria-hidden="true">
                    <rect x="5" y="11" width="32" height="22" fill="#F2B8DC" stroke="#4A3D73" stroke-width="1.5"/>
                    <path d="M5 11l16 13 16-13" fill="none" stroke="#4A3D73" stroke-width="1.5"/>
                    <circle cx="33" cy="12" r="6" fill="#A9E2DA" stroke="#4A3D73" stroke-width="1.5"/>
                </svg>
                <span class="mobile-nav__label">{{ __('site.taskbar.sections.contact') }}</span>
            </button>
        </div>
    </nav>

</div>
</body>
</html>
