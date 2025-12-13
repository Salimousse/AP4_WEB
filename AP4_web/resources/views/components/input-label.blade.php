@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-festival-dark']) }}>
    {{ $value ?? $slot }}
</label>
