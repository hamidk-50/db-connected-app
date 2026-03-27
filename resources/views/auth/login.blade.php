<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|space-grotesk:500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-900 font-sans antialiased text-slate-100">
        <main class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/auth-background.svg') }}');"></div>
            <div class="absolute inset-0 bg-slate-950/65"></div>
            <div class="pointer-events-none absolute right-4 top-4 z-10 sm:right-8 sm:top-8">
                <img src="{{ asset('images/bitcore-logo.svg') }}?v=20" alt="Bitcore" class="w-auto drop-shadow-2xl" style="height: 8.8rem;" />
            </div>

            <div class="relative mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-5xl space-y-8">
                    <header class="mx-auto max-w-3xl space-y-3 text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-300">Bitcore Access</p>
                        <h1 class="font-['Space_Grotesk'] text-3xl font-semibold tracking-tight text-white sm:text-4xl">Welcome back</h1>
                        <p class="text-sm text-slate-200 sm:text-base">
                            Sign in with your role-specific credentials to access dashboards and operations.
                        </p>
                    </header>

                    <x-auth-session-status class="rounded-lg border border-emerald-300/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100" :status="session('status')" />

                    @if ($errors->any())
                        <div class="rounded-lg border border-rose-300/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                            {{ __('Login failed. Please check your email and password and try again.') }}
                        </div>
                    @endif

                    <div class="mx-auto flex w-full flex-wrap items-start justify-center gap-10">
                        <section class="w-full max-w-sm overflow-hidden rounded-[28px] border border-white/20 bg-slate-900/70 p-6 shadow-2xl shadow-black/35 backdrop-blur-md sm:p-7">
                            <div class="mb-5 space-y-1">
                                <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white">{{ __('Admin Login') }}</h2>
                                <p class="text-sm text-slate-100">{{ __('For platform administration and privileged actions.') }}</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label for="admin_email" :value="__('Admin Email')" class="!text-slate-100" />
                                    <x-text-input
                                        id="admin_email"
                                        class="mt-1 block w-full !border-slate-400/80 !bg-slate-950/90 !text-slate-50 !placeholder:text-slate-400"
                                        type="email"
                                        name="email"
                                        :value="old('email')"
                                        placeholder="admin@bitcore.ca"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                </div>

                                <div>
                                    <x-input-label for="admin_password" :value="__('Password')" class="!text-slate-100" />
                                    <x-text-input
                                        id="admin_password"
                                        class="mt-1 block w-full !border-slate-400/80 !bg-slate-950/90 !text-slate-50"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                    />
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <label for="remember_admin" class="inline-flex items-center text-sm text-slate-100">
                                        <input id="remember_admin" type="checkbox" class="rounded border-slate-300/80 bg-slate-800 text-cyan-500 shadow-sm focus:ring-cyan-400" name="remember">
                                        <span class="ms-2">{{ __('Remember me') }}</span>
                                    </label>

                                    <x-primary-button class="!bg-cyan-600 !text-white hover:!bg-cyan-500 focus:!bg-cyan-500 active:!bg-cyan-700">
                                        {{ __('Admin Sign In') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </section>

                        <section class="w-full max-w-sm overflow-hidden rounded-[28px] border border-white/20 bg-slate-900/70 p-6 shadow-2xl shadow-black/35 backdrop-blur-md sm:p-7">
                            <div class="mb-5 space-y-1">
                                <h2 class="font-['Space_Grotesk'] text-2xl font-semibold text-white">{{ __('User Login') }}</h2>
                                <p class="text-sm text-slate-100">{{ __('For sales and operations day-to-day workflows.') }}</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label for="user_email" :value="__('User Email')" class="!text-slate-100" />
                                    <x-text-input
                                        id="user_email"
                                        class="mt-1 block w-full !border-slate-400/80 !bg-slate-950/90 !text-slate-50 !placeholder:text-slate-400"
                                        type="email"
                                        name="email"
                                        :value="old('email')"
                                        placeholder="user@bitcore.ca"
                                        required
                                        autocomplete="username"
                                    />
                                </div>

                                <div>
                                    <x-input-label for="user_password" :value="__('Password')" class="!text-slate-100" />
                                    <x-text-input
                                        id="user_password"
                                        class="mt-1 block w-full !border-slate-400/80 !bg-slate-950/90 !text-slate-50"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                    />
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <label for="remember_user" class="inline-flex items-center text-sm text-slate-100">
                                        <input id="remember_user" type="checkbox" class="rounded border-slate-300/80 bg-slate-800 text-cyan-500 shadow-sm focus:ring-cyan-400" name="remember">
                                        <span class="ms-2">{{ __('Remember me') }}</span>
                                    </label>

                                    <x-primary-button class="!bg-cyan-600 !text-white hover:!bg-cyan-500 focus:!bg-cyan-500 active:!bg-cyan-700">
                                        {{ __('User Sign In') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </section>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="text-sm text-cyan-200 underline decoration-cyan-300/50 underline-offset-4 transition hover:text-white" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </body>
</html>
