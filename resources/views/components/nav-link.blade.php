@props(['active' => false])

@php
$classes = $active
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-indigo-600 text-indigo-700 font-medium'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
