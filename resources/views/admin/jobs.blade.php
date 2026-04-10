<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">{{ __('Admin Console') }}</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Manage Jobs') }}</h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-6">
                    @if($jobs->isEmpty())
                        <p class="text-slate-500">No jobs found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Title</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Company</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Location</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                        <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($jobs as $job)
                                        <tr class="hover:bg-slate-50/80">
                                            <td class="px-6 py-4 text-slate-800">{{ $job->title }}</td>
                                            <td class="px-6 py-4 text-slate-600">{{ $job->company->company_name }}</td>
                                            <td class="px-6 py-4 text-slate-600">{{ $job->location ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-slate-600">{{ $job->job_type }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                    {{ $job->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="text-sm font-semibold text-rose-600 transition hover:text-rose-800" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-slate-100 p-4">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
