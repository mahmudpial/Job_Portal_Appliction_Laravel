@php
use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Dashboard</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Dashboard - ' . ucfirst($role)) }}</h2>
            <span class="inline-flex items-center rounded-xl border border-blue-200 bg-blue-50/80 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-blue-900">
                {{ ucfirst($role) }} mode
            </span>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if($role === 'admin')
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Admin Panel</h3>
                        <p class="mt-2 text-slate-600">Welcome to the control center.</p>
                    </div>
                    <div class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm lg:col-span-2">
                        <h3 class="text-lg font-semibold text-slate-900">Admin Features</h3>
                        <ul class="mt-3 space-y-2 text-slate-600">
                            <li>Manage users and roles</li>
                            <li>Monitor companies and job postings</li>
                            <li>Access system-wide settings</li>
                        </ul>
                    </div>
                </div>
            @elseif($role === 'employer')
                @if($company)
                    <div class="space-y-6">
                        <section class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">My Company</h3>
                            <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                <p><span class="font-semibold text-slate-800">Name:</span> {{ $company->company_name }}</p>
                                <p><span class="font-semibold text-slate-800">Industry:</span> {{ $company->industry ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-800">Location:</span> {{ $company->location ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-800">Website:</span> {{ $company->website ?? 'N/A' }}</p>
                            </div>
                            <a href="{{ route('company.index') }}" class="mt-4 inline-flex text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">Edit Company</a>
                        </section>

                        <section class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">My Jobs</h3>
                                <a href="{{ route('jobs.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800">Post New Job</a>
                            </div>
                            @if($jobs->isEmpty())
                                <p class="text-slate-500">No jobs posted yet.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Title</th>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Location</th>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Type</th>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Status</th>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Vacancy</th>
                                                <th class="px-4 py-2 text-left font-semibold text-slate-500">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($jobs as $job)
                                                <tr>
                                                    <td class="px-4 py-3 text-slate-800">{{ $job->title }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $job->location ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $job->job_type }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                            {{ $job->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-600">{{ $job->vacancy }}</td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <a href="{{ route('jobs.edit', $job) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Edit</a>
                                                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="ml-3 inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="font-semibold text-rose-600 hover:text-rose-800" onclick="return confirm('Are you sure?')">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </section>
                    </div>
                @else
                    <div class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Create Your Company</h3>
                        <p class="mt-2 text-slate-600">You need a company profile before posting jobs.</p>
                        <a href="{{ route('company.index') }}" class="mt-4 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800">Create Company</a>
                    </div>
                @endif
            @else
                <div class="space-y-6">
                    <section class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                        @if($profile)
                            <h3 class="text-lg font-semibold text-slate-900">My Profile</h3>
                            <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                @php
                                    $educationCount = $profile->educations->count() > 0 ? $profile->educations->count() : ($profile->education ? 1 : 0);
                                    $workExperienceCount = $profile->workExperiences->count() > 0 ? $profile->workExperiences->count() : ($profile->experience ? 1 : 0);
                                @endphp
                                <p><span class="font-semibold text-slate-800">Phone:</span> {{ $profile->phone ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-800">Address:</span> {{ $profile->address ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-800">Skills:</span> {{ $profile->skills ?? 'N/A' }}</p>
                                <p><span class="font-semibold text-slate-800">Education Entries:</span> {{ $educationCount }}</p>
                                <p><span class="font-semibold text-slate-800">Work Experience Entries:</span> {{ $workExperienceCount }}</p>
                                <p>
                                    <span class="font-semibold text-slate-800">Latest Education:</span>
                                    {{ optional($profile->educations->first())->program ?? ($profile->education ? 'Added (Legacy)' : 'N/A') }}
                                </p>
                            </div>
                            <a href="{{ route('profile.employee') }}" class="mt-4 inline-flex text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">Edit Profile</a>
                        @else
                            <h3 class="text-lg font-semibold text-slate-900">Create Your Profile</h3>
                            <p class="mt-2 text-slate-600">Complete your profile to apply for jobs.</p>
                            <a href="{{ route('profile.employee') }}" class="mt-4 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800">Create Profile</a>
                        @endif
                    </section>

                    <section class="rounded-2xl border border-blue-200/80 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-slate-900">Available Jobs</h3>
                        @if($availableJobs->count() > 0)
                            <div class="space-y-4">
                                @foreach($availableJobs as $job)
                                    <article class="rounded-xl border border-slate-200 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h4 class="text-lg font-semibold text-slate-900">{{ $job->title }}</h4>
                                                <p class="font-medium text-indigo-600">{{ $job->company->company_name }}</p>
                                                <p class="mt-1 text-sm text-slate-600">{{ $job->location ?? 'Location not specified' }}</p>
                                                <p class="text-sm text-slate-500">{{ $job->job_type }}</p>
                                            </div>
                                            <div class="text-left sm:text-right">
                                                @if($job->salary_min && $job->salary_max)
                                                    <p class="font-semibold text-emerald-600">${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
                                                @elseif($job->salary_min)
                                                    <p class="font-semibold text-emerald-600">From ${{ number_format($job->salary_min) }}</p>
                                                @else
                                                    <p class="text-slate-500">Salary not disclosed</p>
                                                @endif
                                                <p class="text-sm text-slate-500">{{ $job->vacancy }} positions</p>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <p class="text-sm text-slate-600">{{ Str::limit($job->description, 100) }}</p>
                                            <a href="{{ route('jobs.show', $job) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-indigo-700">
                                                View
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('jobs.browse') }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">View All Jobs</a>
                            </div>
                        @else
                            <p class="text-slate-600">No jobs available at the moment.</p>
                        @endif
                    </section>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
