@php
use Illuminate\Support\Facades\Auth;

$activeJobs = $jobs->where('status', 'active')->count();
$closedJobs = $jobs->where('status', 'closed')->count();
$totalVacancies = $jobs->sum('vacancy');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">{{ __('Employer Space') }}</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ __('My Jobs') }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ __('Manage openings, track status, and keep your listings current.') }}</p>
            </div>
            @if(Auth::check() && Auth::user()->isEmployer())
                <a href="{{ route('jobs.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Post New Job') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-6 flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                <svg class="mr-3 h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                <svg class="mr-3 h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <article class="rounded-2xl border border-slate-200 bg-white/95 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ __('Total Jobs') }}</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $jobs->count() }}</p>
                </article>
                <article class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-600">{{ __('Active') }}</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-700">{{ $activeJobs }}</p>
                </article>
                <article class="rounded-2xl border border-rose-200 bg-rose-50/80 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-rose-600">{{ __('Closed') }}</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-rose-700">{{ $closedJobs }}</p>
                </article>
                <article class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">{{ __('Open Positions') }}</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-indigo-700">{{ $totalVacancies }}</p>
                </article>
            </section>

            @if($jobs->count() == 0)
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="p-12 text-center">
                        <svg class="mx-auto mb-4 h-24 w-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m0 0V4"></path>
                        </svg>
                        <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ __('No jobs posted yet') }}</h3>
                        <p class="mb-6 text-slate-500">{{ __('Get started by creating your first job posting') }}</p>
                        @if(Auth::check() && Auth::user()->isEmployer())
                            <a href="{{ route('jobs.create') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Create Your First Job') }}
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($jobs as $job)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                        <div class="p-6">
                            <div class="mb-5 flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $job->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $job->location ?? __('Remote') }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-slate-600">
                                    <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m0 0V4"></path>
                                    </svg>
                                    {{ $job->job_type }}
                                </div>

                                @if($job->salary_min || $job->salary_max)
                                <div class="flex items-center text-sm font-medium text-emerald-600">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    @if($job->salary_min && $job->salary_max)
                                        ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                    @elseif($job->salary_min)
                                        From ${{ number_format($job->salary_min) }}
                                    @else
                                        Up to ${{ number_format($job->salary_max) }}
                                    @endif
                                </div>
                                @endif

                                <div class="flex items-center text-sm text-slate-600">
                                    <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $job->vacancy }} position{{ $job->vacancy > 1 ? 's' : '' }} available
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
                                <a href="{{ route('jobs.edit', $job) }}"
                                   class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this job?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-rose-300 bg-white px-3 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
