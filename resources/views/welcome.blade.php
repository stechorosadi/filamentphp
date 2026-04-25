<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    {{-- Set dark class before render to prevent flash --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

{{-- ─────────────────────────────────────────── --}}
{{-- NAVBAR --}}
{{-- ─────────────────────────────────────────── --}}
<header
    :class="scrolled ? 'shadow-md bg-[#FFF8D4]/90 dark:bg-[#3D2220]/90 backdrop-blur' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Logo --}}
            <a href="/" class="text-xl font-bold text-amber-600 dark:text-amber-500 tracking-tight">
                {{ config('app.name') }}
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Blog</a>

                {{-- Divider --}}
                <div class="w-px h-5 bg-[#C8B870] dark:bg-[#8C5A3C] mx-2"></div>

                {{-- Dark toggle --}}
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle dark mode">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>
            </nav>

            {{-- Mobile: dark toggle + burger --}}
            <div class="flex md:hidden items-center gap-2">
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle dark mode">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                {{-- Burger / X --}}
                <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle menu">
                    {{-- Burger --}}
                    <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    {{-- X --}}
                    <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile dropdown --}}
        <div
            x-show="mobileMenu"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-[#DDD090] dark:border-[#6B4540] pb-4 pt-2">
            <nav class="flex flex-col gap-1">
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Blog</a>
            </nav>
        </div>
    </div>
</header>

{{-- ─────────────────────────────────────────── --}}
{{-- HERO --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative flex min-h-screen items-center bg-[#FFF8D4] dark:bg-[#4B2E2B] overflow-hidden">
    {{-- Background grid pattern --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000010_1px,transparent_1px),linear-gradient(to_bottom,#00000010_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-size-[48px_48px]"></div>
    {{-- Glow --}}
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-amber-500/20 dark:bg-[#8C5A3C]/30 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-32 text-center">
        <p class="animate-fade-up mb-4 inline-block rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#EDE5A8] dark:bg-[#8C5A3C]/20 px-4 py-1 text-sm font-medium text-amber-700 dark:text-amber-400">
            Welcome
        </p>
        <h1 class="animate-fade-up-delay-1 text-4xl font-bold tracking-tight text-[#2C1A0E] dark:text-[#FFF8D4] sm:text-6xl lg:text-7xl">
            {{ config('app.name') }}
        </h1>
        <p class="animate-fade-up-delay-2 mt-6 text-lg leading-8 text-[#5C3A1E] dark:text-[#E8C9A8] max-w-2xl mx-auto">
            Discover articles, research, and resources curated by our team. Stay informed with the latest content.
        </p>
        <div class="animate-fade-up-delay-2 mt-10">
            <a href="#content"
               class="inline-flex items-center gap-2 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-7 py-3.5 text-sm font-semibold text-white dark:text-[#FFF8D4] shadow-lg hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors duration-200">
                Browse Content
                {{-- Arrow down --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                </svg>
            </a>
        </div>
    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- CATEGORIES --}}
{{-- ─────────────────────────────────────────── --}}
@if($categories->isNotEmpty())
<section class="bg-[#F5EDBA] dark:bg-[#3D2220] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            {{-- Tag icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
            </svg>
            <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Browse by Category</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            @foreach($categories as $category)
            <a href="#"
               class="inline-flex items-center gap-1.5 rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#FFFEF0] dark:bg-[#5C3835] px-5 py-2.5 text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-amber-600 dark:hover:bg-[#8C5A3C] hover:border-amber-600 hover:text-white dark:hover:text-[#FFF8D4] transition-all duration-200">
                {{ $category->name }}
                <span class="rounded-full bg-[#EDE5A8] dark:bg-[#6B4540] px-2 py-0.5 text-xs text-[#5C3A1E] dark:text-[#E8C9A8]">
                    {{ $category->contents_count }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- LATEST CONTENT --}}
{{-- ─────────────────────────────────────────── --}}
<section id="content" class="bg-[#FFF8D4] dark:bg-[#4B2E2B] py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-10">
            {{-- Document-text icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Latest Content</h2>
        </div>

        @if($latestContents->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-[#C8A878] dark:text-[#6B4540] mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <p class="text-[#A87850] dark:text-[#C4A080] text-lg">No content published yet.</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestContents as $content)
                <article class="group flex flex-col rounded-2xl overflow-hidden bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                    {{-- Image --}}
                    <div class="relative aspect-video overflow-hidden bg-[#EDE5A8] dark:bg-[#6B4540]">
                        <img src="{{ asset("storage/{$content->header_image}") }}"
                             alt="{{ $content->title }}"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($content->category)
                        <span class="absolute top-3 left-3 rounded-full bg-amber-600 dark:bg-amber-500 px-3 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="mb-2 text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4] line-clamp-2 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors duration-200">
                            {{ $content->title }}
                        </h3>
                        @if($content->excerpt)
                        <p class="mb-4 text-sm leading-relaxed text-[#8C6040] dark:text-[#C4A080] line-clamp-3 flex-1">
                            {{ $content->excerpt }}
                        </p>
                        @endif
                        <a href="#"
                           class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-amber-700 dark:text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 transition-colors">
                            Read more
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- CLASSIFICATIONS --}}
{{-- ─────────────────────────────────────────── --}}
@if($classifications->isNotEmpty())
<section class="bg-[#F5EDBA] dark:bg-[#3D2220] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            {{-- Squares-2x2 icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Classifications</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            @foreach($classifications as $classification)
            <a href="#"
               class="inline-flex items-center gap-1.5 rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#FFFEF0] dark:bg-[#5C3835] px-5 py-2.5 text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-amber-600 dark:hover:bg-[#8C5A3C] hover:border-amber-600 hover:text-white dark:hover:text-[#FFF8D4] transition-all duration-200">
                {{ $classification->name }}
                <span class="rounded-full bg-[#EDE5A8] dark:bg-[#6B4540] px-2 py-0.5 text-xs text-[#5C3A1E] dark:text-[#E8C9A8]">
                    {{ $classification->contents_count }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- FOOTER --}}
{{-- ─────────────────────────────────────────── --}}
<footer class="bg-[#E5DC98] dark:bg-[#2E1A18] py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <span class="text-lg font-bold text-amber-700 dark:text-amber-500">{{ config('app.name') }}</span>
        <p class="text-sm text-[#8C6040] dark:text-[#C4A080]">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
