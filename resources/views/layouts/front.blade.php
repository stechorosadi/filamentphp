<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
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
    class="bg-[#FFF8D4] dark:bg-[#4B2E2B] text-[#2C1A0E] dark:text-[#FFF8D4] transition-colors duration-300 antialiased">

{{-- ── NAVBAR ── --}}
<header
    :class="scrolled ? 'shadow-md bg-[#FFF8D4]/90 dark:bg-[#3D2220]/90 backdrop-blur' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-amber-600 dark:text-amber-500 tracking-tight">
                {{ config('app.name') }}
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('home', 'content.show') ? 'text-amber-700 dark:text-amber-400 bg-[#EDE5A8] dark:bg-[#6B4540]' : 'text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540]' }}">
                    Blog
                </a>

                {{-- Divider --}}
                <div class="w-px h-5 bg-[#C8B870] dark:bg-[#8C5A3C] mx-2"></div>

                {{-- Dark toggle --}}
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle dark mode">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>
            </nav>

            {{-- Mobile: dark toggle + burger --}}
            <div class="flex md:hidden items-center gap-2">
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle dark mode">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>

                {{-- Burger / X --}}
                <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle menu">
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
            class="md:hidden border-t border-[#DDD090] dark:border-[#6B4540] pb-4 pt-2">
            <nav class="flex flex-col gap-1">
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}"
                   class="px-4 py-3 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('home', 'content.show') ? 'text-amber-700 dark:text-amber-400 bg-[#EDE5A8] dark:bg-[#6B4540]' : 'text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540]' }}">
                    Blog
                </a>
            </nav>
        </div>
    </div>
</header>

{{-- ── PAGE CONTENT ── --}}
@yield('content')

{{-- ── FOOTER ── --}}
<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>
<footer class="bg-[#4B2E2B] dark:bg-[#2E1A18] py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <span class="text-lg font-bold text-amber-400">{{ config('app.name') }}</span>
        <p class="text-sm text-[#C4A080]">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
