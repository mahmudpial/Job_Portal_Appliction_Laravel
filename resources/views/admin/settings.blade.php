<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">{{ __('Admin Console') }}</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('System Settings') }}</h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-6 text-lg font-semibold text-slate-900">Application Settings</h3>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="font-semibold text-slate-800">System Info</h4>
                        <p class="mt-2 text-sm text-slate-600">Laravel Version: {{ app()->version() }}</p>
                        <p class="text-sm text-slate-600">PHP Version: {{ phpversion() }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="font-semibold text-slate-800">Database</h4>
                        <p class="mt-2 text-sm text-slate-600">Connection: {{ config('database.default') }}</p>
                        <p class="text-sm text-slate-600">Database: {{ config('database.connections.mysql.database') }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="font-semibold text-slate-800">Statistics</h4>
                        <p class="mt-2 text-sm text-slate-600">Total Users: {{ \App\Models\User::count() }}</p>
                        <p class="text-sm text-slate-600">Total Companies: {{ \App\Models\Company::count() }}</p>
                        <p class="text-sm text-slate-600">Total Jobs: {{ \App\Models\Job::count() }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="font-semibold text-slate-800">Quick Actions</h4>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800">Clear Cache</button>
                            <button class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-700 transition hover:bg-slate-100">Clear Logs</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
