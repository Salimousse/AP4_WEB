@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-festival-primary text-sm font-medium leading-5 text-festival-dark focus:outline-none focus:border-festival-secondary transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-festival-dark/60 hover:text-festival-primary hover:border-festival-primary/50 focus:outline-none focus:text-festival-primary focus:border-festival-primary/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
