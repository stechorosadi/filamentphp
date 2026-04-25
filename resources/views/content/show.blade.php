<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content->title }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ $content->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($content->content), 160) }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $content->title }}">
    <meta property="og:description" content="{{ $content->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($content->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($content->featured_image)
    <meta property="og:image" content="{{ asset('storage/' . $content->featured_image) }}">
    @elseif($content->header_image)
    <meta property="og:image" content="{{ asset('storage/' . $content->header_image) }}">
    @endif
    <meta property="article:published_time" content="{{ $content->created_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $content->user->name }}">
    @foreach($content->tags as $tag)
    <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $content->title }}">
    <meta name="twitter:description" content="{{ $content->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($content->content), 160) }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
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

{{-- ── NAVBAR ── --}}
<header
    :class="scrolled ? 'shadow-md bg-[#FFF8D4]/90 dark:bg-[#3D2220]/90 backdrop-blur' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-amber-600 dark:text-amber-500 tracking-tight">
                {{ config('app.name') }}
            </a>
            <nav class="hidden md:flex items-center gap-1">
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a href="#" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Blog</a>
                <div class="w-px h-5 bg-[#C8B870] dark:bg-[#8C5A3C] mx-2"></div>
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors" aria-label="Toggle dark mode">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>
            </nav>
            <div class="flex md:hidden items-center gap-2">
                <button @click="toggleDark()" class="rounded-lg p-2 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#5C3A1E]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>
                <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">
                    <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="mobileMenu"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-[#DDD090] dark:border-[#6B4540] pb-4 pt-2">
            <nav class="flex flex-col gap-1">
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Profile</a>
                <a @click="mobileMenu = false" href="#" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Portfolio</a>
                <a @click="mobileMenu = false" href="{{ route('home') }}" class="px-4 py-3 rounded-lg text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">Blog</a>
            </nav>
        </div>
    </div>
</header>

{{-- ── HERO IMAGE ── --}}
@if($content->header_image)
<div class="relative h-72 md:h-96 overflow-hidden">
    <img src="{{ asset('storage/' . $content->header_image) }}"
         alt="{{ $content->title }}"
         class="h-full w-full object-cover">
    <div class="absolute inset-0 bg-linear-to-t from-[#2C1A0E]/70 via-[#2C1A0E]/20 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 px-4 sm:px-6 lg:px-8 pb-8 mx-auto max-w-4xl">
        <div class="flex flex-wrap gap-2 mb-3">
            @if($content->classification)
            <span class="rounded-full bg-amber-600/90 px-3 py-1 text-xs font-semibold text-white">{{ $content->classification->name }}</span>
            @endif
            @if($content->category)
            <span class="rounded-full bg-[#8C5A3C]/90 px-3 py-1 text-xs font-semibold text-white">{{ $content->category->name }}</span>
            @endif
        </div>
        <h1 class="text-2xl md:text-4xl font-bold text-white leading-tight">{{ $content->title }}</h1>
    </div>
</div>
@endif

{{-- ── ARTICLE BODY ── --}}
<main class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="mb-8 flex items-center gap-2 text-sm text-[#8C6040] dark:text-[#C4A080]">
        <a href="{{ route('home') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Home</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @if($content->category)
        <a href="{{ route('home', ['category' => $content->content_category_id]) }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $content->category->name }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @endif
        <span class="truncate text-[#5C3A1E] dark:text-[#E8C9A8]">{{ \Illuminate\Support\Str::limit($content->title, 50) }}</span>
    </nav>

    {{-- Title (shown when no header image) --}}
    @if(!$content->header_image)
    <h1 class="mb-6 text-3xl md:text-5xl font-bold tracking-tight text-[#2C1A0E] dark:text-[#FFF8D4] leading-tight">
        {{ $content->title }}
    </h1>
    @endif

    {{-- Meta row --}}
    <div class="mb-8 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-[#8C6040] dark:text-[#C4A080] border-b border-[#DDD090] dark:border-[#6B4540] pb-6">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
            <span class="font-medium text-[#5C3A1E] dark:text-[#E8C9A8]">{{ $content->user->name }}</span>
        </div>
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
            </svg>
            <span>{{ $content->created_at->format('M d, Y') }}</span>
        </div>
        @if($content->tags->isNotEmpty())
        <div class="flex flex-wrap gap-1.5">
            @foreach($content->tags as $tag)
            <span class="rounded-full bg-[#EDE5A8] dark:bg-[#5C3835] px-2.5 py-0.5 text-xs font-medium text-[#5C3A1E] dark:text-[#E8C9A8]">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Excerpt --}}
    @if($content->excerpt)
    <p class="mb-8 text-lg leading-relaxed text-[#5C3A1E] dark:text-[#E8C9A8] font-medium border-l-4 border-amber-500 pl-5">
        {{ $content->excerpt }}
    </p>
    @endif

    {{-- Article content --}}
    <div class="prose-content">
        {!! $content->content !!}
    </div>

    {{-- YouTube embed --}}
    @if($content->youtube_url)
    @php
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^&\s?]+)/', $content->youtube_url, $m);
        $embedUrl = isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] : null;
    @endphp
    @if($embedUrl)
    <div class="mt-10">
        <h3 class="mb-4 text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Video</h3>
        <div class="aspect-video overflow-hidden rounded-2xl shadow-lg">
            <iframe src="{{ $embedUrl }}" class="h-full w-full" allowfullscreen></iframe>
        </div>
    </div>
    @endif
    @endif

    {{-- Image attachments --}}
    @if($content->imageAttachments->isNotEmpty())
    <div class="mt-12">
        <h3 class="mb-5 text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Gallery</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($content->imageAttachments as $img)
            <div class="overflow-hidden rounded-xl border border-[#DDD090] dark:border-[#6B4540]">
                <img src="{{ asset('storage/' . $img->path) }}"
                     alt="{{ $img->caption ?? 'Image' }}"
                     class="w-full object-cover aspect-video">
                @if($img->caption)
                <p class="px-3 py-2 text-xs text-[#8C6040] dark:text-[#C4A080]">{{ $img->caption }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- File attachments --}}
    @if($content->fileAttachments->isNotEmpty())
    <div class="mt-12">
        <h3 class="mb-5 text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Downloads</h3>
        <div class="flex flex-col gap-3">
            @foreach($content->fileAttachments as $file)
            <a href="{{ asset('storage/' . $file->path) }}" target="_blank" download
               class="flex items-center gap-4 rounded-xl border border-[#DDD090] dark:border-[#6B4540] bg-[#FFFEF0] dark:bg-[#5C3835] px-5 py-4 hover:border-amber-500 dark:hover:border-[#8C5A3C] transition-colors group">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EDE5A8] dark:bg-[#6B4540] text-xs font-bold text-[#5C3A1E] dark:text-[#E8C9A8] uppercase">
                    {{ strtoupper(pathinfo($file->path, PATHINFO_EXTENSION)) }}
                </span>
                <span class="flex-1 font-medium text-[#2C1A0E] dark:text-[#FFF8D4] group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                    {{ $file->original_name }}
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#8C6040] dark:text-[#C4A080] group-hover:text-amber-600 transition-colors">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Link attachments --}}
    @if($content->linkAttachments->isNotEmpty())
    <div class="mt-12">
        <h3 class="mb-5 text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Related Links</h3>
        <div class="flex flex-col gap-3">
            @foreach($content->linkAttachments as $link)
            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-4 rounded-xl border border-[#DDD090] dark:border-[#6B4540] bg-[#FFFEF0] dark:bg-[#5C3835] px-5 py-4 hover:border-amber-500 dark:hover:border-[#8C5A3C] transition-colors group">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-[#6B4540]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-600 dark:text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                    </svg>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-[#2C1A0E] dark:text-[#FFF8D4] group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                        {{ $link->label ?: $link->url }}
                    </p>
                    @if($link->label)
                    <p class="text-xs text-[#8C6040] dark:text-[#C4A080] truncate">{{ $link->url }}</p>
                    @endif
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0 text-[#8C6040] dark:text-[#C4A080]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Back link --}}
    <div class="mt-14 pt-8 border-t border-[#DDD090] dark:border-[#6B4540]">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to all articles
        </a>
    </div>
</main>

{{-- ── FOOTER ── --}}
<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>
<footer class="bg-[#E5DC98] dark:bg-[#2E1A18] py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <span class="text-lg font-bold text-amber-700 dark:text-amber-500">{{ config('app.name') }}</span>
        <p class="text-sm text-[#8C6040] dark:text-[#C4A080]">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
