<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
    {{-- Favicon --}}
    @if($siteSetting->favicon_path)
        <link rel="icon" href="{{ Storage::disk('public')->url($siteSetting->favicon_path) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="{{ $siteSetting->color_accent ?? '#4F772D' }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $siteSetting->site_title }}">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    {{-- Prevent dark mode flash --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    {{-- Dynamic theme: derive full 10-token palette from Site Settings --}}
    @php
        $thMix = function (string $h1, string $h2, float $r): string {
            $h1 = ltrim($h1, '#'); $h2 = ltrim($h2, '#');
            return sprintf('#%02x%02x%02x',
                (int) round(hexdec(substr($h1,0,2)) * (1-$r) + hexdec(substr($h2,0,2)) * $r),
                (int) round(hexdec(substr($h1,2,2)) * (1-$r) + hexdec(substr($h2,2,2)) * $r),
                (int) round(hexdec(substr($h1,4,2)) * (1-$r) + hexdec(substr($h2,4,2)) * $r),
            );
        };
        $lbg  = $siteSetting->color_light_bg    ?? '#ECF39E';
        $dbg  = $siteSetting->color_dark_bg     ?? '#132A13';
        $ltxt = $siteSetting->color_light_text  ?? '#132A13';
        $dtxt = $siteSetting->color_dark_text   ?? '#ECF39E';
        $acc  = $siteSetting->color_accent      ?? '#4F772D';
        $dacc = $siteSetting->color_accent_dark ?? '#90A955';
    @endphp
    <style>
        :root {
            --bg-primary:   {{ $lbg }};
            --bg-card:      {{ $thMix($lbg, '#ffffff', 0.55) }};
            --bg-alt:       {{ $thMix($lbg, $acc, 0.12) }};
            --text-primary: {{ $ltxt }};
            --text-muted:   {{ $thMix($ltxt, '#ffffff', 0.35) }};
            --accent:       {{ $acc }};
            --accent-dim:   {{ $thMix($acc, '#ffffff', 0.35) }};
            --border:       {{ $thMix($acc, '#ffffff', 0.45) }};
            --dark-section: {{ $thMix($ltxt, $dbg, 0.6) }};
            --dark-bg:      {{ $dbg }};
            --on-dark:      {{ $lbg }};
            --accent-on-dark: {{ $dacc }};
        }
        :where(.dark, .dark *) {
            --bg-primary:   {{ $dbg }};
            --bg-card:      {{ $thMix($dbg, $dacc, 0.18) }};
            --bg-alt:       {{ $thMix($dbg, $dacc, 0.25) }};
            --text-primary: {{ $dtxt }};
            --text-muted:   {{ $thMix($dtxt, '#000000', 0.30) }};
            --accent:       {{ $dacc }};
            --accent-dim:   {{ $thMix($dacc, '#000000', 0.20) }};
            --border:       {{ $thMix($dbg, $dacc, 0.40) }};
            --dark-section: {{ $thMix($dbg, '#000000', 0.25) }};
            --dark-bg:      {{ $thMix($dbg, '#000000', 0.12) }};
        }
    [x-cloak] { display: none !important; }
    </style>
</head>
<body
    x-data="{
        darkMode: document.documentElement.classList.contains('dark'),
        scrolled: false,
        mobileMenu: false,
        toggleDark() {
            this.darkMode = !this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        }
    }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    class="site-body transition-colors duration-300 antialiased">

{{-- Pre-compute locale toggle URLs once, used in both top bar and navbar --}}
@php
    $currentLocale = app()->getLocale();
    $currentRoute  = request()->route();
    $routeName     = $currentRoute?->getName();
    $routeParams = collect($currentRoute?->parameters() ?? [])
        ->map(fn ($value) => $value instanceof \App\Models\TeamMember ? $value->nickname : $value)
        ->all();
    $routeHasLocale = $currentRoute && in_array('locale', $currentRoute->parameterNames());
    $urlId = $routeHasLocale
        ? route($routeName, array_merge($routeParams, ['locale' => 'id']))
        : route('home', ['locale' => 'id']);
    $urlEn = $routeHasLocale
        ? route($routeName, array_merge($routeParams, ['locale' => 'en']))
        : route('home', ['locale' => 'en']);
@endphp

{{-- ── TOP BAR ── --}}
@if($siteSetting->contact_email || $siteSetting->contact_address)
<div
    x-show="!scrolled"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    class="fixed inset-x-0 top-0 z-50 py-2 flex items-center bg-(--accent) text-white dark:text-(--bg-primary)">
    <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-8">
        {{-- Left: top bar menu (desktop only) --}}
        @if($topbarMenuItems->isNotEmpty())
        <nav class="hidden md:flex items-center gap-1">
            @foreach($topbarMenuItems as $item)
            @php $href = str_starts_with($item->url, 'http') ? $item->url : '/' . ltrim($item->url, '/'); @endphp
            <a href="{{ $href }}"
               target="{{ $item->target === '_blank' ? '_blank' : '_self' }}"
               class="px-3 py-1 rounded text-sm font-semibold hover:opacity-80 transition-opacity">
                {{ $item->title }}
            </a>
            @endforeach
        </nav>
        @else
        <div class="hidden md:block"></div>
        @endif

        {{-- Right: contact info + mobile lang toggle --}}
        <div class="flex items-center gap-4 md:gap-8 w-full md:w-auto">
        @if($siteSetting->contact_email)
        <a href="mailto:{{ $siteSetting->contact_email }}" class="flex items-start gap-2.5 hover:opacity-80 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
            </svg>
            <div class="flex flex-col leading-tight">
                <span class="text-xs font-semibold uppercase tracking-wide opacity-75">{{ __('ui.contact_us') }}</span>
                <span class="text-sm">{{ $siteSetting->contact_email }}</span>
            </div>
        </a>
        @endif
        {{-- Language toggle: mobile only, pushed to the right --}}
        <div class="md:hidden ml-auto flex items-center gap-0 rounded-lg border border-white/40 overflow-hidden shrink-0">
            <a href="{{ $urlId }}" class="px-2.5 py-1 text-xs font-semibold transition-colors {{ $currentLocale === 'id' ? 'bg-white/25 text-white' : 'text-white/70 hover:bg-white/15' }}">ID</a>
            <a href="{{ $urlEn }}" class="px-2.5 py-1 text-xs font-semibold transition-colors {{ $currentLocale === 'en' ? 'bg-white/25 text-white' : 'text-white/70 hover:bg-white/15' }}">EN</a>
        </div>
        @if($siteSetting->contact_address)
        @php $addrParts = explode(',', $siteSetting->contact_address, 2); @endphp
        <span class="hidden md:flex items-start gap-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
            </svg>
            <div class="flex flex-col leading-tight">
                <span class="text-sm">{{ trim($addrParts[0]) }}</span>
                @isset($addrParts[1])
                <span class="text-sm">{{ trim($addrParts[1]) }}</span>
                @endisset
            </div>
        </span>
        @endif
        </div>{{-- end right contact info --}}
    </div>
</div>
@endif

{{-- ── NAVBAR ── --}}
<header
    :class="scrolled ? 'top-0' : '{{ ($siteSetting->contact_email || $siteSetting->contact_address) ? 'top-14' : 'top-0' }}'"
    class="fixed inset-x-0 z-40 shadow-md backdrop-blur transition-all duration-300"
    style="background-color: color-mix(in srgb, var(--bg-primary) 80%, transparent)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Logo --}}
            <a href="{{ lroute('home') }}" class="flex items-center gap-2 text-xl font-bold text-[var(--accent)] dark:text-[var(--accent)] tracking-tight">
                @if($siteSetting->logo_path)
                    <img src="{{ Storage::disk('public')->url($siteSetting->logo_path) }}" alt="{{ $siteSetting->site_title }}" class="h-8 w-auto object-contain">
                @endif
                {{ $siteSetting->site_title }}
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                @foreach($navMenuItems as $item)
                @php
                    $href   = str_starts_with($item->url, 'http') ? $item->url : '/' . ltrim($item->url, '/');
                    $active = request()->is(ltrim($item->url, '/')) || request()->is(ltrim($item->url, '/') . '/*');
                @endphp
                <a href="{{ $href }}"
                   target="{{ $item->target === '_blank' ? '_blank' : '_self' }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ $active ? 'text-[var(--text-primary)] dark:text-[var(--accent)] bg-[var(--accent-dim)]' : 'text-[var(--text-primary)] hover:text-[var(--text-primary)] dark:hover:text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-(--bg-alt)' }}">
                    {{ $item->title }}
                </a>
                @endforeach

                {{-- Divider --}}
                <div class="w-px h-5 bg-[var(--accent-dim)] dark:bg-[var(--accent)] mx-2"></div>

                {{-- Language switcher --}}
                <div class="flex items-center gap-0 rounded-lg border border-[var(--accent-dim)] overflow-hidden">
                    <a href="{{ $urlId }}"
                       class="px-2.5 py-1 text-xs font-semibold transition-colors {{ $currentLocale === 'id' ? 'bg-[var(--accent)] text-white' : 'text-[var(--text-muted)] hover:bg-[var(--accent-dim)]' }}">ID</a>
                    <a href="{{ $urlEn }}"
                       class="px-2.5 py-1 text-xs font-semibold transition-colors {{ $currentLocale === 'en' ? 'bg-[var(--accent)] text-white' : 'text-[var(--text-muted)] hover:bg-[var(--accent-dim)]' }}">EN</a>
                </div>

                {{-- Dark toggle --}}
                <button @click="toggleDark()" class="rounded-lg p-2 text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-(--bg-alt) transition-colors" aria-label="{{ __('ui.dark_mode') }}">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--text-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--text-muted)]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>
            </nav>

            {{-- Mobile: dark toggle + burger --}}
            <div class="flex md:hidden items-center gap-2">
                <button @click="toggleDark()" class="rounded-lg p-2 text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-(--bg-alt) transition-colors" aria-label="{{ __('ui.dark_mode') }}">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--text-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--text-muted)]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>

                {{-- Burger / X --}}
                <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-[var(--text-muted)] hover:bg-[var(--accent-dim)] dark:hover:bg-(--bg-alt) transition-colors" aria-label="Toggle menu">
                    <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile dropdown --}}
        <div
            x-show="mobileMenu"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-[var(--border)] pb-4 pt-2">
            <nav class="flex flex-col gap-1">
                @foreach($navMenuItems as $item)
                @php
                    $href   = str_starts_with($item->url, 'http') ? $item->url : '/' . ltrim($item->url, '/');
                    $active = request()->is(ltrim($item->url, '/')) || request()->is(ltrim($item->url, '/') . '/*');
                @endphp
                <a @click="mobileMenu = false"
                   href="{{ $href }}"
                   target="{{ $item->target === '_blank' ? '_blank' : '_self' }}"
                   class="px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          {{ $active ? 'text-[var(--text-primary)] dark:text-[var(--accent)] bg-[var(--accent-dim)]' : 'text-[var(--text-primary)] hover:text-[var(--text-primary)] dark:hover:text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-(--bg-alt)' }}">
                    {{ $item->title }}
                </a>
                @endforeach
            </nav>
        </div>
    </div>
</header>

{{-- ── PAGE CONTENT ── --}}
@yield('content')

{{-- ── FOOTER ── --}}
<footer class="relative bg-(--dark-bg) overflow-hidden">
    {{-- Top accent line --}}
    <div class="h-px bg-linear-to-r from-transparent via-(--accent-on-dark)/40 to-transparent"></div>

    {{-- Background decorations --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    <div class="absolute -top-16 right-0 h-56 w-56 rounded-full bg-(--accent-on-dark)/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -left-16 h-48 w-48 rounded-full bg-(--accent-on-dark)/15 blur-3xl pointer-events-none"></div>

    {{-- Main content --}}
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-10 pb-6">

        {{-- Top row: logo + links --}}
        <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-8 mb-8">

            {{-- Brand --}}
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2.5 mb-1">
                    @if($siteSetting->logo_path)
                    <img src="{{ Storage::disk('public')->url($siteSetting->logo_path) }}"
                         alt="{{ $siteSetting->site_title }}"
                         class="h-8 w-auto object-contain opacity-90">
                    @endif
                    <span class="text-2xl font-bold text-white tracking-tight">{{ $siteSetting->site_title }}</span>
                </div>
                <p class="mt-2 text-sm text-(--accent-on-dark)/80 max-w-xs leading-relaxed">
                    {{ $siteSetting->site_description ?: 'Discover articles, research, and resources curated by our team.' }}
                </p>

                {{-- Social media icons --}}
                @php
                    $socials = array_filter([
                        'facebook'  => $siteSetting->facebook_url  ?? null,
                        'instagram' => $siteSetting->instagram_url ?? null,
                        'x'         => $siteSetting->x_url         ?? null,
                        'youtube'   => $siteSetting->youtube_url   ?? null,
                    ]);
                @endphp
                @if($socials)
                <div class="mt-4 flex items-center justify-center md:justify-start gap-3">
                    @foreach($socials as $platform => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/8 text-(--accent-on-dark) hover:bg-(--accent-on-dark)/20 hover:text-white transition-all duration-200"
                       aria-label="{{ ucfirst($platform) }}">
                        @if($platform === 'facebook')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        @elseif($platform === 'instagram')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        @elseif($platform === 'x')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        @elseif($platform === 'youtube')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        @endif
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Footer 3-column link lists --}}
            @if($footerList1 || $footerList2 || $footerList3)
            <div class="grid grid-cols-3 gap-12 text-sm w-full md:w-auto">
                @foreach([$footerList1, $footerList2, $footerList3] as $list)
                @if($list && $list->menuItems->isNotEmpty())
                <div>
                    <h4 class="mb-4 font-semibold text-white">
                        {{ preg_replace('/ - [A-Z]{2}$/', '', str_replace('Footer Menu - ', '', $list->name)) }}
                    </h4>
                    <ul class="space-y-2.5">
                        @foreach($list->menuItems as $item)
                        @php $href = str_starts_with($item->url, 'http') ? $item->url : '/' . ltrim($item->url, '/'); @endphp
                        <li>
                            <a href="{{ $href }}"
                               target="{{ $item->target === '_blank' ? '_blank' : '_self' }}"
                               class="text-(--accent-on-dark) hover:text-white transition-colors">
                                {{ $item->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>

        {{-- Divider --}}
        <div class="h-px bg-linear-to-r from-transparent via-white/15 to-transparent mb-6"></div>

        {{-- Bottom bar --}}
        <p class="text-center text-xs text-white/40">
            &copy; {{ date('Y') }} <span class="text-(--accent-on-dark) font-medium">{{ $siteSetting->site_title }}</span>. {{ __('ui.all_rights') }}
        </p>

    </div>
</footer>

@stack('scripts')
</body>
</html>
