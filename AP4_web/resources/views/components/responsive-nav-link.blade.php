@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-festival-primary text-start text-base font-medium text-festival-dark bg-festival-primary/10 focus:outline-none focus:text-festival-dark focus:bg-festival-primary/20 focus:border-festival-secondary transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-festival-dark/60 hover:text-festival-primary hover:bg-festival-light hover:border-festival-primary/50 focus:outline-none focus:text-festival-primary focus:bg-festival-light focus:border-festival-primary/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
