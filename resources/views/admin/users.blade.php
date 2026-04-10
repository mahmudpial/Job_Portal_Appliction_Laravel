<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">{{ __('Admin Console') }}</p>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Manage Users') }}</h2>
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Email</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Role</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Created</th>
                                <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-6 py-4 text-slate-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                            {{ $user->user_type === 'admin' ? 'bg-rose-100 text-rose-700' :
                                               ($user->user_type === 'employer' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ $user->user_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->user_type !== 'admin')
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-sm font-semibold text-rose-600 transition hover:text-rose-800" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
