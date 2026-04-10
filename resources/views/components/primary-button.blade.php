<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-[0_10px_20px_rgba(30,102,230,0.32)] transition hover:-translate-y-0.5 hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
