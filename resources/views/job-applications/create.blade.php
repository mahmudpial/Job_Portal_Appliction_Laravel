<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Application</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Apply for {{ $job->title }}</h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm font-medium text-slate-600 transition hover:text-slate-900">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Job Details
                </a>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 border-b border-slate-100 pb-5">
                    <h3 class="text-xl font-semibold text-slate-900">{{ $job->title }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $job->company->name }} • {{ $job->location ?? 'Remote' }}</p>
                </div>

                <form method="POST" action="{{ route('job-applications.store', $job) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="cover_letter" :value="__('Cover Letter')" />
                        <textarea id="cover_letter" name="cover_letter" rows="8" class="mt-1 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tell the employer why you are the right fit for this role..." required>{{ old('cover_letter') }}</textarea>
                        <x-input-error :messages="$errors->get('cover_letter')" class="mt-2" />
                        <p class="mt-2 text-xs text-slate-500">Minimum 50 characters.</p>
                    </div>

                    <div>
                        <x-input-label for="resume" :value="__('Resume (PDF, DOC, DOCX, max 5MB)')" />
                        <input id="resume" name="resume" type="file" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-xs file:font-semibold file:uppercase file:tracking-wide file:text-white hover:file:bg-slate-800" required>
                        <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                        <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-slate-700 transition hover:bg-slate-50">
                            Cancel
                        </a>
                        <x-primary-button>
                            {{ __('Submit Application') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
