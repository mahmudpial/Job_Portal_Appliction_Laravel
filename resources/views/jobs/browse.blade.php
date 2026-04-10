<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Career Discovery</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Browse Jobs</h2>
                <p class="mt-1 text-sm text-slate-600">Find roles that match your goals, skills, and preferred work style.</p>
            </div>
            @auth
                @if(Auth::user()->isEmployer())
                    <a href="{{ route('jobs.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-800">
                        Post a Job
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="mb-8 rounded-3xl border border-blue-200/80 bg-white/75 p-6 shadow-[0_20px_40px_rgba(30,88,188,0.15)] backdrop-blur sm:p-8">
                <div class="mb-6 flex flex-col gap-4 border-b border-blue-100 pb-5 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Opportunity Board</p>
                        <h3 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Find your next role</h3>
                        <p class="mt-1 text-sm text-slate-600">Search by skill, location, and compensation to get targeted matches.</p>
                    </div>
                    <div class="inline-flex rounded-xl border border-blue-200 bg-blue-50/80 px-3 py-2 text-sm font-semibold text-blue-900">
                        {{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }} opportunities
                    </div>
                </div>
                <form method="GET" action="{{ route('jobs.browse') }}" class="space-y-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="search" class="mb-1.5 block text-sm font-medium text-slate-700">Search Jobs</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Job title or company"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="location" class="mb-1.5 block text-sm font-medium text-slate-700">Location</label>
                            <input type="text" name="location" id="location" value="{{ request('location') }}" placeholder="City or Remote"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="job_type" class="mb-1.5 block text-sm font-medium text-slate-700">Job Type</label>
                            <select name="job_type" id="job_type"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Types</option>
                                <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="freelance" {{ request('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                        </div>

                        <div>
                            <label for="salary_min" class="mb-1.5 block text-sm font-medium text-slate-700">Min Salary</label>
                            <input type="number" name="salary_min" id="salary_min" value="{{ request('salary_min') }}" placeholder="0"
                                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-blue-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-600">
                            {{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }} jobs found
                        </p>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                                Search
                            </button>
                            <a href="{{ route('jobs.browse') }}" class="inline-flex items-center rounded-xl border border-blue-200 bg-white/90 px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-blue-900 transition hover:bg-blue-50">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            @if($jobs->count() > 0)
                <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($jobs as $job)
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-slate-900">
                                        <a href="{{ route('jobs.show', $job) }}" class="transition hover:text-indigo-600">{{ $job->title }}</a>
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $job->company->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $job->location ?? 'Remote' }}</p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m0 0V4"></path>
                                        </svg>
                                        {{ ucfirst($job->job_type) }}
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

                                    @if($job->deadline)
                                        <div class="flex items-center text-sm text-amber-600">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Deadline: {{ $job->deadline->format('M j, Y') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                    <span class="text-xs text-slate-500">Posted {{ $job->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-indigo-700">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                <div class="mt-8">
                    @if(method_exists($jobs, 'links'))
                        {{ $jobs->appends(request()->query())->links() }}
                    @endif
                </div>
            @else
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                    <svg class="mx-auto mb-4 h-24 w-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m0 0V4"></path>
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">No jobs found</h3>
                    <p class="mb-6 text-slate-500">Try adjusting your filters or check back soon for fresh opportunities.</p>
                    <a href="{{ route('jobs.browse') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
