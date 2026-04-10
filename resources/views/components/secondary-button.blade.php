<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-xl border border-blue-200 bg-white/85 px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-blue-900 shadow-[0_8px_18px_rgba(28,85,179,0.12)] transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
