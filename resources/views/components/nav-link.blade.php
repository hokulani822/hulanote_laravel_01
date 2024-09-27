@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 text-2xl font-bold leading-5 text-[#8B7355] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 text-2xl font-medium leading-5 text-gray-500 hover:text-[#8B7355] focus:outline-none focus:text-[#8B7355] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>