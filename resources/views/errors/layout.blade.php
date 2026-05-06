@php
    $errorLocale    = app()->getLocale();
    $errorHomeUrl   = route('home',   ['locale' => $errorLocale]);
    $errorSearchUrl = route('search', ['locale' => $errorLocale]);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $errorLocale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('error_code') @yield('error_title') — {{ $siteSetting->site_title }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    {{-- Prevent dark mode flash --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css'])
</head>
<body
    x-data="{
        darkMode: document.documentElement.classList.contains('dark'),
        countdown: 15,
        progress: 100,
        init() {
            const tick = setInterval(() => {
                this.countdown--;
                this.progress = (this.countdown / 15) * 100;
                if (this.countdown <= 0) {
                    clearInterval(tick);
                    window.location.href = '{{ $errorHomeUrl }}';
                }
            }, 1000);
        },
        toggleDark() {
            this.darkMode = !this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        }
    }"
    x-init="init()"
    class="min-h-screen bg-[var(--bg-primary)] text-[var(--text-primary)] font-[Instrument_Sans,ui-sans-serif,system-ui,sans-serif] antialiased transition-colors duration-300">

    {{-- Background grid --}}
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#00000008_1px,transparent_1px),linear-gradient(to_bottom,#00000008_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    {{-- Ambient glow --}}
    <div class="fixed top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-[var(--accent)]/15 dark:bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>

    {{-- Dark mode toggle --}}
    <div class="fixed top-4 right-4 z-10">
        <button @click="toggleDark()"
                class="rounded-lg p-2 text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-[#2a5c2a] transition-colors"
                aria-label="Toggle dark mode">
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#90A955]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--text-muted)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
        </button>
    </div>

    {{-- Main content --}}
    <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-16 text-center">

        {{-- Watermark error code --}}
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden select-none" aria-hidden="true">
            <span class="text-[20rem] font-black leading-none text-[var(--text-primary)]/4 dark:text-[var(--text-primary)]/4">
                @yield('error_code')
            </span>
        </div>

        {{-- Icon --}}
        <div class="relative mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-[#4F772D]/30 bg-[var(--accent)]/10 dark:bg-[var(--accent)]/15">
            @yield('error_icon')
        </div>

        {{-- Error code badge --}}
        <div class="mb-4 inline-flex items-center rounded-full border border-[#4F772D]/30 bg-[var(--accent)]/10 dark:bg-[var(--accent)]/20 px-4 py-1.5">
            <span class="text-sm font-bold uppercase tracking-widest text-[var(--text-muted)] dark:text-[var(--accent)]">
                Error @yield('error_code')
            </span>
        </div>

        {{-- Title --}}
        <h1 class="mb-4 text-3xl sm:text-4xl lg:text-5xl font-bold text-[var(--text-primary)] leading-tight">
            @yield('error_title')
        </h1>

        {{-- Description --}}
        <p class="mb-10 max-w-lg text-base leading-relaxed text-[var(--text-muted)]">
            @yield('error_description')
        </p>

        {{-- Action buttons --}}
        <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
            <a href="{{ $errorHomeUrl }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-[var(--accent)] dark:hover:bg-[#6B9A38] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                {{ __('ui.go_to_homepage') }}
            </a>
            @if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== url()->current())
            <button onclick="history.back()"
                    class="inline-flex items-center gap-2 rounded-xl border-2 border-[var(--accent-dim)] dark:border-[var(--accent)] px-6 py-3 text-sm font-semibold text-[var(--text-muted)] hover:bg-[var(--accent-dim)] dark:hover:bg-[#2a5c2a] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                {{ __('ui.go_back') }}
            </button>
            @endif
            <a href="{{ $errorSearchUrl }}"
               class="inline-flex items-center gap-2 rounded-xl border-2 border-[var(--accent-dim)] dark:border-[var(--accent)] px-6 py-3 text-sm font-semibold text-[var(--text-muted)] hover:bg-[var(--accent-dim)] dark:hover:bg-[#2a5c2a] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                {{ __('ui.search') }}
            </a>
        </div>

        {{-- Countdown ring --}}
        <div class="flex flex-col items-center gap-2">
            <div class="relative h-16 w-16">
                <svg class="h-16 w-16 -rotate-90" viewBox="0 0 64 64">
                    {{-- Track --}}
                    <circle cx="32" cy="32" r="28"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="4"
                            class="text-[#a0c84a] dark:text-[#2a5c2a]"/>
                    {{-- Progress --}}
                    <circle cx="32" cy="32" r="28"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="4"
                            stroke-linecap="round"
                            stroke-dasharray="175.9"
                            :stroke-dashoffset="175.9 - (175.9 * progress / 100)"
                            class="text-[var(--accent)] transition-all duration-1000 ease-linear"/>
                </svg>
                {{-- Countdown number --}}
                <span class="absolute inset-0 flex items-center justify-center text-lg font-bold text-[var(--text-primary)]"
                      x-text="countdown"></span>
            </div>
            <p class="text-xs text-[var(--accent)]">
                {{ __('ui.redirecting_in') }} <span class="font-semibold" x-text="countdown"></span>{{ __('ui.seconds_abbr') }}
            </p>
        </div>

        {{-- Brand --}}
        <a href="{{ $errorHomeUrl }}" class="mt-12 text-sm font-bold text-[var(--accent)] dark:text-[var(--accent)] hover:text-[var(--text-muted)] dark:hover:text-[#90A955] transition-colors">
            {{ $siteSetting->site_title }}
        </a>
    </div>

</body>
</html>
