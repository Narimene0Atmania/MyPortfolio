{{-- shared by skills.dll (desktop) and the mobile about panel.
     the lead phrase before the em dash is bolded; strings without one render plain --}}
<div class="skills__head">
    <span class="skills__label">{{ __('site.skills.label') }}</span>
    <span class="skills__rule" aria-hidden="true"></span>
</div>
<ul class="skills__list">
    @foreach (__('site.skills.items') as $point)
        @php [$lead, $rest] = array_pad(explode('—', $point, 2), 2, null); @endphp
        <li class="diff-card diff-card--{{ $loop->iteration }}">
            <span class="diff-card__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            <p class="diff-card__text">
                @if ($rest)
                    <strong>{{ trim($lead) }}</strong> — {{ trim($rest) }}
                @else
                    {{ $point }}
                @endif
            </p>
        </li>
    @endforeach
</ul>
