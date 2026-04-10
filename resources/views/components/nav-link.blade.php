@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-xl bg-blue-100/85 px-3.5 py-2 text-sm font-semibold text-blue-900 shadow-sm ring-1 ring-blue-200/80 transition'
            : 'inline-flex items-center rounded-xl px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-blue-50/85 hover:text-blue-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
