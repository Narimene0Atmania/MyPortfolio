{{--
    The "Nari/" logo — see docs/design_handoff_logo/README.md for the full
    spec. $variant: a space-separated string combining one size ('large' or
    'small') with an optional 'reversed' for dark grounds, e.g. 'small',
    'large reversed'. Defaults to 'large'.
--}}
@php
    $variant = $variant ?? 'large';
    $logoClasses = collect(explode(' ', $variant))->map(fn ($v) => "logo--{$v}")->implode(' ');
    $isSmall = str_contains($variant, 'small');
@endphp
<span class="logo {{ $logoClasses }}" role="img" aria-label="Nari">
    <span class="logo__tab" aria-hidden="true">
        @unless ($isSmall)
            <span class="logo__pixel">
                <span></span><span></span><span></span><span></span>
            </span>
        @endunless
    </span>
    <span class="logo__body">
        <span class="logo__wordmark">Nari<span class="logo__slash">/</span></span>
    </span>
</span>
