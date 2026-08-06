{{-- @if ($href = $attributes->get('href')) --}}

    @props(['is' => 'a'])

    <{{ $is }} {{ $attributes(['class'=> 'border border-border rounded-lg bg-card p-4 md:text-sm block']) }}>
    {{-- ->except('href')->merge(['href' => $href]) --}}

        {{ $slot }}
    </{{ $is }}>
{{-- @else
    <div {{ $attributes(['class'=> 'border border-border rounded-lg bg-card p-4 md:text-sm block']) }}>
        {{ $slot }}
    </div>
@endif --}}
