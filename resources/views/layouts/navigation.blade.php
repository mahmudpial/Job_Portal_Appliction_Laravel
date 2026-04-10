<nav x-data="{ open: false }" class="page-shell z-50 overflow-visible border-b border-blue-200/80 bg-white/70 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="shrink-0 rounded-xl border border-blue-200/80 bg-white/80 p-2 shadow-sm">
                    <x-application-logo class="block h-8 w-auto fill-current text-blue-700" />
                </a>

                <div class="hidden items-center gap-1 sm:flex">
                    <x-nav-link :href="route('jobs.browse')" :active="request()->routeIs('jobs.browse')">
                        {{ __('Browse Jobs') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        @if(Auth::user()->isEmployer())
                            <x-nav-link :href="route('company.index')" :active="request()->routeIs('company.*')">
                                {{ __('Company') }}
                            </x-nav-link>
                            <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                                {{ __('Jobs') }}
                            </x-nav-link>
                            <x-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                                {{ __('Applications') }}
                            </x-nav-link>
                        @elseif(Auth::user()->isEmployee())
                            <x-nav-link :href="route('profile.employee')" :active="request()->routeIs('profile.employee*')">
                                {{ __('My Profile') }}
                            </x-nav-link>
                            <x-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                                {{ __('My Applications') }}
                            </x-nav-link>
                        @elseif(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.companies')" :active="request()->routeIs('admin.companies')">
                                {{ __('Companies') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.jobs')" :active="request()->routeIs('admin.jobs')">
                                {{ __('Jobs') }}
                            </x-nav-link>
                            <x-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                                {{ __('Applications') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                                {{ __('Settings') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden items-center sm:flex sm:ms-6">
                @auth
                    <details class="group relative">
                        <summary class="inline-flex list-none cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-white/90 px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="text-xs text-slate-400">({{ Auth::user()->user_type }})</span>
                            <svg class="h-4 w-4 fill-current text-slate-400 transition group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 z-50 mt-2 w-52 rounded-2xl border border-blue-200 bg-white/95 p-1 shadow-xl backdrop-blur">
                            <a href="{{ route('profile.edit') }}" class="block w-full rounded-xl px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-blue-50 hover:text-blue-900">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full rounded-xl px-4 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-blue-50 hover:text-blue-900">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </details>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="rounded-xl border border-blue-200 bg-white/90 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-blue-200 bg-white/90 p-2 text-blue-700 transition hover:bg-blue-50 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-blue-200 bg-white/85 backdrop-blur sm:hidden">
        <div class="space-y-1 px-4 pb-3 pt-3">
            <x-responsive-nav-link :href="route('jobs.browse')" :active="request()->routeIs('jobs.browse')">
                {{ __('Browse Jobs') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                @if(Auth::user()->isEmployer())
                    <x-responsive-nav-link :href="route('company.index')" :active="request()->routeIs('company.*')">
                        {{ __('Company') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                        {{ __('Jobs') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                        {{ __('Applications') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->isEmployee())
                    <x-responsive-nav-link :href="route('profile.employee')" :active="request()->routeIs('profile.employee*')">
                        {{ __('My Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                        {{ __('My Applications') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.companies')" :active="request()->routeIs('admin.companies')">
                        {{ __('Companies') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.jobs')" :active="request()->routeIs('admin.jobs')">
                        {{ __('Jobs') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('job-applications.index')" :active="request()->routeIs('job-applications.*')">
                        {{ __('Applications') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <div class="border-t border-blue-200 px-4 py-3">
                <div class="text-base font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
                <div class="mt-1 text-xs text-slate-500">Role: {{ Auth::user()->user_type }}</div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="border-t border-blue-200 px-4 py-3">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            </div>
        @endauth
    </div>
</nav>
