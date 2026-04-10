<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Job Details</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $job->title }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ $job->company->name }}</p>
            </div>
            @auth
                @if(Auth::user()->isEmployer() && $job->company->user_id === Auth::id())
                    <a href="{{ route('jobs.edit', $job) }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800">
                        Edit Job
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('jobs.browse') }}" class="inline-flex items-center text-sm font-medium text-slate-600 transition hover:text-slate-900">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Jobs
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $job->title }}</h1>
                                <p class="mt-2 text-lg font-medium text-slate-700">{{ $job->company->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $job->location ?? 'Remote' }}</p>
                            </div>
                            @if($job->salary_min || $job->salary_max)
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-right">
                                    <div class="text-xl font-bold text-emerald-700">
                                        @if($job->salary_min && $job->salary_max)
                                            ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                        @elseif($job->salary_min)
                                            From ${{ number_format($job->salary_min) }}
                                        @else
                                            Up to ${{ number_format($job->salary_max) }}
                                        @endif
                                    </div>
                                    <div class="text-xs font-medium uppercase tracking-wide text-emerald-600">Per Year</div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                <span class="font-semibold text-slate-900">Type:</span> {{ ucfirst($job->job_type) }}
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                <span class="font-semibold text-slate-900">Vacancy:</span> {{ $job->vacancy }} position{{ $job->vacancy > 1 ? 's' : '' }}
                            </div>
                            @if($job->deadline)
                                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                    <span class="font-semibold">Deadline:</span> {{ $job->deadline->format('M j, Y') }}
                                </div>
                            @endif
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                <span class="font-semibold text-slate-900">Posted:</span> {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-sm text-slate-500">Job ID: {{ $job->id }}</div>
                            @auth
                                @if(Auth::user()->isEmployee())
                                    @php
                                        $hasApplied = Auth::user()->jobApplications()->where('job_id', $job->id)->exists();
                                    @endphp
                                    @if($hasApplied)
                                        <div class="inline-flex items-center rounded-xl border border-emerald-300 bg-emerald-100 px-5 py-2.5 text-sm font-semibold text-emerald-800">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Already Applied
                                        </div>
                                    @else
                                        <a href="{{ route('job-applications.create', $job) }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                            Apply for this Job
                                        </a>
                                    @endif
                                @else
                                    <p class="text-sm text-slate-500">Sign in as an employee to apply.</p>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                    Sign In to Apply
                                </a>
                            @endauth
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h3 class="mb-4 text-xl font-semibold text-slate-900">Job Description</h3>
                        <div class="prose prose-sm max-w-none text-slate-700">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </section>

                    @if($job->requirements)
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                            <h3 class="mb-4 text-xl font-semibold text-slate-900">Requirements</h3>
                            <div class="prose prose-sm max-w-none text-slate-700">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="space-y-6 lg:col-span-1">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">About the Company</h3>
                        <div class="space-y-3 text-sm text-slate-600">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $job->company->name }}</p>
                                @if($job->company->website)
                                    <a href="{{ $job->company->website }}" target="_blank" class="text-indigo-600 transition hover:text-indigo-800">
                                        {{ $job->company->website }}
                                    </a>
                                @endif
                            </div>
                            @if($job->company->description)
                                <p>{{ Str::limit($job->company->description, 150) }}</p>
                            @endif
                            @if($job->company->industry)
                                <p><span class="font-semibold text-slate-800">Industry:</span> {{ $job->company->industry }}</p>
                            @endif
                            @if($job->company->size)
                                <p><span class="font-semibold text-slate-800">Company Size:</span> {{ $job->company->size }}</p>
                            @endif
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-3 text-lg font-semibold text-slate-900">Similar Jobs</h3>
                        <p class="text-sm text-slate-500">More roles from {{ $job->company->name }} and related positions will appear here.</p>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
