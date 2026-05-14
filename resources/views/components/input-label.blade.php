@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-accent mb-1']) }}>
    {{ $value ?? $slot }}
</label>
