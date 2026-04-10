<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl bg-gradient-to-r from-rose-600 to-red-500 px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-[0_10px_20px_rgba(225,66,66,0.28)] transition hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
