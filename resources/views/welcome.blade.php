<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-canvas min-h-screen font-sans text-slate-900 antialiased">
    <div class="page-shell mx-auto flex min-h-screen max-w-7xl flex-col px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-6 flex items-center justify-between rounded-2xl border border-blue-200/80 bg-white/70 px-4 py-3 backdrop-blur-md sm:px-6">
            <a href="/" class="inline-flex items-center gap-3">
                <span class="rounded-xl border border-blue-200 bg-white p-2 shadow-sm">
                    <x-application-logo class="h-8 w-auto fill-current text-blue-700" />
                </span>
                <span class="text-lg font-semibold tracking-tight text-slate-900">Job Portal</span>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl border border-blue-200 bg-white/85 px-4 py-2 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(30,102,230,0.3)] transition hover:brightness-105">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="grid flex-1 items-center gap-10 py-6 lg:grid-cols-[1.05fr_0.95fr] lg:py-14">
            <section>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-700">Hiring Platform</p>
                <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Build Teams Faster With a
                    <span class="bg-gradient-to-r from-blue-700 to-sky-500 bg-clip-text text-transparent">Beautiful Hiring Workflow</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg text-slate-600">
                    Post jobs, discover top talent, and manage applications with a role-based portal designed for employers, job seekers, and admins.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('jobs.browse') }}" class="rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_22px_rgba(30,102,230,0.32)] transition hover:brightness-105">
                        Browse Jobs
                    </a>
                    @guest
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-xl border border-blue-200 bg-white/85 px-5 py-3 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
                                Create Account
                            </a>
                        @endif
                    @endguest
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3 sm:max-w-lg">
                    <article class="rounded-xl border border-blue-200/80 bg-white/75 px-4 py-3 backdrop-blur">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Flow</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">Role Based</p>
                    </article>
                    <article class="rounded-xl border border-blue-200/80 bg-white/75 px-4 py-3 backdrop-blur">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Portal</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">Responsive UI</p>
                    </article>
                    <article class="rounded-xl border border-blue-200/80 bg-white/75 px-4 py-3 backdrop-blur">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Hiring</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">Application Tracking</p>
                    </article>
                </div>
            </section>

            <section class="rounded-3xl border border-blue-200/80 bg-white/75 p-7 shadow-[0_22px_44px_rgba(28,77,162,0.16)] backdrop-blur sm:p-8">
                <h2 class="text-xl font-semibold text-slate-900">Everything In One Place</h2>
                <p class="mt-2 text-sm text-slate-600">A clean workflow from job posting to final application review.</p>
                <div class="mt-6 space-y-4">
                    <article class="rounded-2xl border border-blue-200/70 bg-blue-50/70 p-4">
                        <h3 class="font-semibold text-slate-900">Employers</h3>
                        <p class="mt-1 text-sm text-slate-600">Create company profile, publish roles, and manage active/closed listings.</p>
                    </article>
                    <article class="rounded-2xl border border-blue-200/70 bg-blue-50/70 p-4">
                        <h3 class="font-semibold text-slate-900">Candidates</h3>
                        <p class="mt-1 text-sm text-slate-600">Build profiles, upload resumes, filter jobs, and apply with confidence.</p>
                    </article>
                    <article class="rounded-2xl border border-blue-200/70 bg-blue-50/70 p-4">
                        <h3 class="font-semibold text-slate-900">Admins</h3>
                        <p class="mt-1 text-sm text-slate-600">Monitor users, companies, jobs, and keep the platform healthy.</p>
                    </article>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
