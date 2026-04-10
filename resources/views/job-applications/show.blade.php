<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Application Details</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                {{ $application->job->title }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div
                    id="status-success-alert"
                    class="mb-6 flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 transition-opacity duration-500"
                >
                    <svg class="mr-3 h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function () {
                        var alertEl = document.getElementById('status-success-alert');
                        if (!alertEl) {
                            return;
                        }

                        alertEl.classList.add('opacity-0');
                        setTimeout(function () {
                            alertEl.remove();
                        }, 500);
                    }, 5000);
                </script>
            @endif

            <div class="mb-6 flex flex-wrap items-center gap-2">
                <a href="{{ route('job-applications.index') }}" class="inline-flex items-center rounded-xl border border-blue-200 bg-white/90 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
                    Back to Applications
                </a>
                <a href="{{ route('jobs.show', $application->job) }}" class="inline-flex items-center rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                    View Job Post
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <section class="rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur lg:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900">Cover Letter</h3>
                    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                        {{ $application->cover_letter }}
                    </div>
                </section>

                <aside class="space-y-6">
                    <section class="rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur">
                        <h3 class="text-lg font-semibold text-slate-900">Summary</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-700">
                            <p><span class="font-semibold text-slate-900">Company:</span> {{ $application->job->company->name }}</p>
                            <p><span class="font-semibold text-slate-900">Applied:</span> {{ $application->applied_at->format('M j, Y g:i A') }}</p>
                            <p><span class="font-semibold text-slate-900">Status:</span>
                                <span class="ml-1 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                       ($application->status === 'reviewed' ? 'bg-indigo-100 text-indigo-700' :
                                       ($application->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' :
                                       'bg-rose-100 text-rose-700')) }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </p>
                            @if($application->status_reason)
                                <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-blue-700">Status Feedback</p>
                                    <p class="mt-1 whitespace-pre-line break-words text-sm text-slate-700">{{ $application->status_reason }}</p>
                                </div>
                            @endif
                            @if($application->resume_path)
                                <p>
                                    <a href="{{ Storage::disk('public')->url($application->resume_path) }}" target="_blank" class="inline-flex items-center font-semibold text-indigo-600 transition hover:text-indigo-800">
                                        View Resume
                                    </a>
                                </p>
                            @endif
                        </div>
                    </section>

                    @if($canManageStatus)
                        <section class="rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur">
                            <h3 class="text-lg font-semibold text-slate-900">Update Status</h3>
                            <form method="POST" action="{{ route('job-applications.update-status', $application) }}" class="mt-4 space-y-3">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="block w-full rounded-xl border border-blue-200 bg-white/90 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <option value="pending" {{ old('status', $application->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="reviewed" {{ old('status', $application->status) === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <option value="accepted" {{ old('status', $application->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="rejected" {{ old('status', $application->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror

                                <div>
                                    <label for="status_reason" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-slate-600">
                                        Reason / Feedback
                                    </label>
                                    <textarea
                                        id="status_reason"
                                        name="status_reason"
                                        rows="4"
                                        placeholder="Add a clear reason for your decision (required when status is Rejected)."
                                        class="block w-full rounded-xl border border-blue-200 bg-white/90 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    >{{ old('status_reason') }}</textarea>
                                    <p class="mt-2 text-xs text-slate-500">When rejecting an application, this reason is shown to the employee.</p>
                                    @error('status_reason')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="inline-flex items-center rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                                    Save Status
                                </button>
                            </form>
                        </section>
                    @else
                        <section class="rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur">
                            <h3 class="text-lg font-semibold text-slate-900">Status</h3>
                            <p class="mt-3 text-sm text-slate-600">
                                Only the job owner (employer) or admin can update this application status.
                            </p>
                        </section>
                    @endif

                    <section class="rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur">
                        <h3 class="text-lg font-semibold text-slate-900">Applicant</h3>
                        <div class="mt-4 space-y-2 text-sm text-slate-700">
                            <p><span class="font-semibold text-slate-900">Name:</span> {{ $application->user->name }}</p>
                            <p><span class="font-semibold text-slate-900">Email:</span> {{ $application->user->email }}</p>
                            @if($application->user->employeeProfile?->phone)
                                <p><span class="font-semibold text-slate-900">Phone:</span> {{ $application->user->employeeProfile->phone }}</p>
                            @endif
                            @if($application->user->employeeProfile?->address)
                                <p><span class="font-semibold text-slate-900">Address:</span> {{ $application->user->employeeProfile->address }}</p>
                            @endif
                            @if($application->user->employeeProfile?->skills)
                                <p class="break-words"><span class="font-semibold text-slate-900">Skills:</span> {{ $application->user->employeeProfile->skills }}</p>
                            @endif
                            @if($application->user->employeeProfile?->bio)
                                <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-blue-700">Professional Summary</p>
                                    <p class="mt-1 whitespace-pre-line break-words text-sm text-slate-700">{{ $application->user->employeeProfile->bio }}</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </aside>
            </div>

            @if(
                $application->user->employeeProfile
                && (
                    $application->user->employeeProfile->educations->isNotEmpty()
                    || $application->user->employeeProfile->workExperiences->isNotEmpty()
                    || $application->user->employeeProfile->education
                    || $application->user->employeeProfile->experience
                )
            )
                <section class="mt-6 rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_14px_30px_rgba(24,72,153,0.14)] backdrop-blur">
                    <h3 class="text-lg font-semibold text-slate-900">Applicant Professional Details</h3>
                    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-[0.12em] text-blue-700">Education</h4>
                            @if($application->user->employeeProfile->educations->isEmpty())
                                @if($application->user->employeeProfile->education)
                                    <article class="mt-3 rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                                        <p class="whitespace-pre-line break-words text-sm text-slate-700">{{ $application->user->employeeProfile->education }}</p>
                                    </article>
                                @else
                                    <p class="mt-2 text-sm text-slate-500">No education entries added.</p>
                                @endif
                            @else
                                <div class="mt-3 space-y-3">
                                    @foreach($application->user->employeeProfile->educations as $education)
                                        <article class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                                            <p class="text-sm font-semibold text-slate-900">{{ $education->program ?? 'Program not specified' }}</p>
                                            <p class="text-sm text-slate-700">{{ $education->school_name ?? 'School not specified' }}</p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Passing Year: {{ $education->passing_year ?? 'N/A' }}
                                                @if($education->grade_system || $education->grade_value)
                                                    | {{ strtoupper($education->grade_system ?? 'GRADE') }}: {{ $education->grade_value ?? 'N/A' }}
                                                @endif
                                            </p>
                                            @if($education->description)
                                                <p class="mt-2 whitespace-pre-line break-words text-sm text-slate-700">{{ $education->description }}</p>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-[0.12em] text-blue-700">Work Experience</h4>
                            @if($application->user->employeeProfile->workExperiences->isEmpty())
                                @if($application->user->employeeProfile->experience)
                                    <article class="mt-3 rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                                        <p class="whitespace-pre-line break-words text-sm text-slate-700">{{ $application->user->employeeProfile->experience }}</p>
                                    </article>
                                @else
                                    <p class="mt-2 text-sm text-slate-500">No work experience entries added.</p>
                                @endif
                            @else
                                <div class="mt-3 space-y-3">
                                    @foreach($application->user->employeeProfile->workExperiences as $workExperience)
                                        <article class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                                            <p class="text-sm font-semibold text-slate-900">{{ $workExperience->job_title ?? 'Job title not specified' }}</p>
                                            <p class="text-sm text-slate-700">{{ $workExperience->company_name ?? 'Company not specified' }}</p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $workExperience->start_year ?? 'N/A' }} - {{ $workExperience->end_year ?? 'Present' }}
                                                @if($workExperience->employment_type)
                                                    | {{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $workExperience->employment_type)) }}
                                                @endif
                                            </p>
                                            @if($workExperience->description)
                                                <p class="mt-2 whitespace-pre-line break-words text-sm text-slate-700">{{ $workExperience->description }}</p>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
