@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl bg-blue-100/85 px-3 py-2 text-start text-base font-semibold text-blue-900 ring-1 ring-blue-200/80 transition'
            : 'block w-full rounded-xl px-3 py-2 text-start text-base font-medium text-slate-700 transition hover:bg-blue-50/85 hover:text-blue-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
