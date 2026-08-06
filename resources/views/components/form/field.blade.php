@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2">
    @if ($label)
        <label
            for="{{ $name }}"
            class="label">{{ $label }}
        </label>
    @endif

    {{-- if type is text area, render this as proper text area --}}
    @if ($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            class="textarea"
            {{ $attributes  }}
        >{{ old($name) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            class="input"
            value="{{ old($name) }}"
            {{ $attributes  }}>
    @endif

    <x-form.error name="{{ $name }}" />

</div>
