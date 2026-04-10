<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Applications</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $pageTitle ?? 'My Job Applications' }}</h2>
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

            @if($applications->count() == 0)
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                    <svg class="mx-auto mb-4 h-24 w-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">No applications yet</h3>
                    <p class="mb-6 text-slate-500">Start browsing jobs and submit your first application.</p>
                    <a href="{{ route('jobs.browse') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Browse Jobs
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($applications as $application)
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                            <div class="mb-4 flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">
                                        <a href="{{ route('jobs.show', $application->job) }}" class="transition hover:text-indigo-600">
                                            {{ $application->job->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-slate-600">{{ $application->job->company->name }}</p>
                                    @if($canManageStatus ?? false)
                                        <p class="text-sm text-slate-500">Applicant: {{ $application->user->name }} ({{ $application->user->email }})</p>
                                    @endif
                                    <p class="mt-1 text-sm text-slate-500">Applied on {{ $application->applied_at->format('M j, Y') }}</p>
                                    @if(!($canManageStatus ?? false) && $application->status_reason)
                                        <p class="mt-2 rounded-lg border border-blue-100 bg-blue-50/70 px-3 py-2 text-sm text-slate-700">
                                            <span class="font-semibold text-blue-700">Feedback:</span>
                                            {{ \Illuminate\Support\Str::limit($application->status_reason, 180) }}
                                        </p>
                                    @endif
                                </div>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                       ($application->status === 'reviewed' ? 'bg-indigo-100 text-indigo-700' :
                                       ($application->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' :
                                       'bg-rose-100 text-rose-700')) }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="text-sm">
                                    @if($application->resume_path)
                                        <a href="{{ Storage::disk('public')->url($application->resume_path) }}" target="_blank" class="font-medium text-indigo-600 transition hover:text-indigo-800">
                                            View Resume
                                        </a>
                                    @endif
                                </div>
                                <a href="{{ route('job-applications.show', $application) }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
                                    {{ ($canManageStatus ?? false) ? 'Review & Update' : 'View Application' }}
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
