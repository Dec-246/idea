@props(['label' => false, 'name', 'type' => 'text', 'value' => ''])

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

            {{-- old gets the  old value from previous form submission.
             if no previous form submission, default to empty string--}}
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            class="input"
            value="{{ old($name, $value) }}"
            {{ $attributes  }}>
    @endif

    <x-form.error name="{{ $name }}" />

</div>
