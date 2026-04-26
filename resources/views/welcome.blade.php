@extends('layouts.front')

@section('seo')
<title>{{ config('app.name') }} — Stay Informed</title>
<meta name="description" content="Discover articles, research, and resources curated by our team. Stay informed with the latest content.">
<meta property="og:title" content="{{ config('app.name') }}">
<meta property="og:description" content="Discover articles, research, and resources curated by our team.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
@if($featuredContents->isNotEmpty() && $featuredContents->first()->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $featuredContents->first()->featured_image) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url('/') }}">
@endsection

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{-- HERO --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative flex min-h-screen items-center bg-[#FFF8D4] dark:bg-[#4B2E2B] overflow-hidden">
    {{-- Background grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000010_1px,transparent_1px),linear-gradient(to_bottom,#00000010_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-size-[48px_48px]"></div>
    {{-- Glow --}}
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-128 w-lg rounded-full bg-amber-500/20 dark:bg-[#8C5A3C]/30 blur-3xl pointer-events-none"></div>

    @if($featuredContents->isNotEmpty())
    {{-- ── FEATURED SLIDER ── --}}
    <div class="relative w-full"
         x-data="{
             current: 0,
             slides: {{ $featuredContents->count() }},
             preview: false,
             slideData: @js($featuredContents->map(fn ($s) => [
                 'title'    => $s->title,
                 'excerpt'  => \Illuminate\Support\Str::limit($s->excerpt ?? '', 250),
                 'url'      => route('content.show', $s->slug),
                 'category' => $s->category?->name,
                 'date'     => $s->created_at->format('M d, Y'),
             ])->values()),
             timer: null,
             init() { if (this.slides > 1) this.timer = setInterval(() => this.next(), 6000); },
             next() { this.current = (this.current + 1) % this.slides; },
             prev() { this.current = (this.current - 1 + this.slides) % this.slides; },
             go(i) { this.current = i; clearInterval(this.timer); if (this.slides > 1) this.timer = setInterval(() => this.next(), 6000); }
         }">

        {{-- Slides --}}
        <div class="relative min-h-240 lg:min-h-screen">
            @foreach($featuredContents as $index => $slide)
            <div x-show="current === {{ $index }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 flex items-center">

                <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 pt-20 pb-12 lg:py-32">
                    <div class="grid grid-cols-1 lg:grid-cols-[5fr_7fr] gap-10 lg:gap-16 items-center">

                        {{-- LEFT: Text --}}
                        <div class="space-y-7">
                            {{-- Classification · Category · Date pills --}}
                            <div class="flex flex-wrap gap-2">
                                @if($slide->classification?->name)
                                <span class="inline-flex items-center rounded-full bg-[#5C3A1E] dark:bg-[#5C3835] px-3 py-1 text-xs font-semibold text-white dark:text-[#E8C9A8]">
                                    {{ $slide->classification->name }}
                                </span>
                                @endif
                                @if($slide->category?->name)
                                <span class="inline-flex items-center rounded-full bg-amber-600 dark:bg-[#8C5A3C] px-3 py-1 text-xs font-semibold text-white dark:text-[#FFF8D4]">
                                    {{ $slide->category->name }}
                                </span>
                                @endif
                                <span class="inline-flex items-center rounded-full border border-[#C8B870] dark:border-[#6B4540] bg-[#EDE5A8] dark:bg-[#6B4540] px-3 py-1 text-xs font-semibold text-[#2C1A0E] dark:text-[#E8C9A8]">
                                    {{ $slide->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[#2C1A0E] dark:text-[#FFF8D4] leading-tight">
                                {{ $slide->title }}
                            </h1>

                            {{-- Excerpt --}}
                            @if($slide->excerpt)
                            <p class="text-lg leading-relaxed text-[#5C3A1E] dark:text-[#E8C9A8] max-w-lg">
                                {{ \Illuminate\Support\Str::limit($slide->excerpt, 160) }}
                            </p>
                            @endif

                            {{-- CTA buttons --}}
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="{{ route('content.show', $slide->slug) }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-[#2C1A0E] dark:bg-[#FFF8D4] px-6 py-3 text-sm font-semibold text-white dark:text-[#2C1A0E] shadow-lg hover:opacity-90 transition-opacity duration-200">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                                <button @click="preview = true"
                                        class="inline-flex items-center gap-2.5 rounded-xl border-2 border-[#C8B870] dark:border-[#8C5A3C] px-6 py-3 text-sm font-semibold text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors duration-200">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#2C1A0E] dark:bg-[#FFF8D4]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 text-white dark:text-[#2C1A0E] translate-x-0.5">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                    Preview
                                </button>
                            </div>

                        </div>

                        {{-- RIGHT: Browser mockup --}}
                        <div class="relative order-first lg:order-0">
                            {{-- Blob glow --}}
                            <div class="absolute inset-0 -z-10 scale-110 rounded-3xl bg-amber-400/20 dark:bg-[#8C5A3C]/25 blur-2xl"></div>

                            {{-- Browser card --}}
                            <div class="relative overflow-hidden rounded-2xl border border-[#DDD090] dark:border-[#6B4540] bg-[#FFFEF0] dark:bg-[#5C3835] shadow-2xl dark:shadow-[0_25px_60px_rgba(0,0,0,0.5)]">
                                {{-- Browser chrome --}}
                                <div class="flex items-center gap-1.5 border-b border-[#DDD090] dark:border-[#6B4540] bg-[#F5EDBA] dark:bg-[#3D2220] px-4 py-3">
                                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                    <span class="h-3 w-3 rounded-full bg-green-400"></span>
                                    <div class="ml-3 h-4 max-w-48 flex-1 rounded-md bg-white/60 dark:bg-[#4B2E2B]/60"></div>
                                </div>
                                {{-- Featured image --}}
                                <img src="{{ asset("storage/{$slide->featured_image}") }}"
                                     alt="{{ $slide->title }}"
                                     class="aspect-video lg:aspect-4/3 w-full object-cover"
                                     @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                            </div>

                            {{-- Author badge (bottom-left) --}}
                            <div class="absolute -bottom-3 -left-3 hidden sm:flex items-center gap-2 rounded-xl bg-[#2C1A0E] dark:bg-[#FFF8D4] px-4 py-2 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-white dark:text-[#2C1A0E] shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                <span class="text-xs font-semibold text-white dark:text-[#2C1A0E]">{{ $slide->user?->name ?? config('app.name') }}</span>
                            </div>

                            {{-- Featured badge (top-right) --}}
                            <div class="absolute -top-3 -right-3 hidden sm:flex items-center gap-1.5 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-4 py-2 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-white shrink-0">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-semibold text-white">Featured</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Navigation dots --}}
        @if($featuredContents->count() > 1)
        <div class="absolute bottom-8 left-1/2 z-10 flex -translate-x-1/2 items-center gap-2">
            @foreach($featuredContents as $i => $_)
            <button @click="go({{ $i }})"
                    :class="current === {{ $i }} ? 'w-6 bg-amber-600 dark:bg-amber-400' : 'w-2 bg-[#C8B870] dark:bg-[#8C5A3C]'"
                    class="h-2 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>

        {{-- Prev arrow --}}
        <button @click="prev()" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full border border-[#DDD090] dark:border-[#6B4540] bg-white/70 dark:bg-[#5C3835]/70 p-2.5 text-[#5C3A1E] dark:text-[#E8C9A8] shadow-md backdrop-blur hover:bg-amber-600 hover:text-white dark:hover:bg-[#8C5A3C] transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </button>

        {{-- Next arrow --}}
        <button @click="next()" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full border border-[#DDD090] dark:border-[#6B4540] bg-white/70 dark:bg-[#5C3835]/70 p-2.5 text-[#5C3A1E] dark:text-[#E8C9A8] shadow-md backdrop-blur hover:bg-amber-600 hover:text-white dark:hover:bg-[#8C5A3C] transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
        @endif

        {{-- ── PREVIEW MODAL ── --}}
        <div x-show="preview"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="preview = false"
             @click.self="preview = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#2C1A0E]/60 backdrop-blur-sm">

            <div x-show="preview"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg rounded-2xl bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] shadow-2xl p-8">

                {{-- Close --}}
                <button @click="preview = false"
                        class="absolute top-4 right-4 rounded-lg p-1.5 text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Category badge --}}
                <div class="mb-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EDE5A8] dark:bg-[#6B4540] px-3 py-1 text-xs font-semibold text-amber-700 dark:text-amber-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                        <span x-text="slideData[current]?.category ?? 'Featured'"></span>
                    </span>
                </div>

                {{-- Title --}}
                <h2 class="mb-3 text-xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4] leading-snug"
                    x-text="slideData[current]?.title"></h2>

                {{-- Excerpt --}}
                <p class="mb-5 text-sm leading-relaxed text-[#5C3A1E] dark:text-[#E8C9A8]"
                   x-text="slideData[current]?.excerpt || 'No preview available.'"></p>

                {{-- Date --}}
                <p class="mb-6 text-xs text-[#8C6040] dark:text-[#C4A080]" x-text="slideData[current]?.date"></p>

                {{-- CTA --}}
                <a :href="slideData[current]?.url"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-5 py-3 text-sm font-semibold text-white dark:text-[#FFF8D4] hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors">
                    Read Full Article
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    @else
    {{-- ── FALLBACK ── --}}
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-32 text-center">
        <p class="animate-fade-up mb-4 inline-block rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#EDE5A8] dark:bg-[#8C5A3C]/20 px-4 py-1 text-sm font-medium text-amber-700 dark:text-amber-400">
            Welcome
        </p>
        <h1 class="animate-fade-up-delay-1 text-4xl font-bold tracking-tight text-[#2C1A0E] dark:text-[#FFF8D4] sm:text-6xl lg:text-7xl">
            {{ config('app.name') }}
        </h1>
        <p class="animate-fade-up-delay-2 mt-6 max-w-2xl mx-auto text-lg leading-8 text-[#5C3A1E] dark:text-[#E8C9A8]">
            Discover articles, research, and resources curated by our team. Stay informed with the latest content.
        </p>
        <div class="animate-fade-up-delay-2 mt-10">
            <a href="#content" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-7 py-3.5 text-sm font-semibold text-white dark:text-[#FFF8D4] shadow-lg hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors duration-200">
                Browse Content
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                </svg>
            </a>
        </div>
    </div>
    @endif
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- SEARCH --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-[#2C1A0E] dark:bg-[#1E1008] py-20 sm:py-24">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-amber-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 right-1/4 h-72 w-72 rounded-full bg-[#8C5A3C]/25 blur-3xl pointer-events-none"></div>
    {{-- Subtle grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px]"></div>

    <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Icon --}}
        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-amber-500/30 bg-amber-500/15">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-amber-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
        </div>

        {{-- Heading --}}
        <h2 class="mb-4 text-3xl sm:text-4xl lg:text-5xl font-bold text-[#FFF8D4] leading-tight">
            Discover Knowledge<br>Without Limits
        </h2>

        {{-- Subtitle --}}
        <p class="mb-10 text-base sm:text-lg leading-relaxed text-[#C4A080] max-w-xl mx-auto">
            Search across our entire collection of articles, research, and resources — all curated and organised in one place, ready for you to explore.
        </p>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('search') }}" class="mb-10">
            <div class="flex overflow-hidden rounded-2xl border-2 border-amber-600/30 bg-white/8 backdrop-blur-sm focus-within:border-amber-500/60 transition-all duration-200 shadow-[0_8px_32px_rgba(0,0,0,0.4)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="ml-5 h-5 w-5 shrink-0 self-center text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Search articles, topics, or keywords…"
                       class="flex-1 bg-transparent px-4 py-4 text-base text-[#FFF8D4] placeholder-[#8C6040] focus:outline-none">
                <button type="submit"
                        class="m-2 rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-amber-500 transition-colors duration-200 shrink-0">
                    Search
                </button>
            </div>
        </form>

        {{-- Stats pills --}}
        <div class="flex flex-wrap justify-center gap-3">
            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-400 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#FFF8D4]">{{ $totalArticles }}</span>
                <span class="text-sm text-[#C4A080]">{{ Str::plural('Article', $totalArticles) }}</span>
            </div>

            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-400 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#FFF8D4]">{{ $categories->count() }}</span>
                <span class="text-sm text-[#C4A080]">{{ Str::plural('Category', $categories->count()) }}</span>
            </div>

            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-400 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#FFF8D4]">{{ $classifications->count() }}</span>
                <span class="text-sm text-[#C4A080]">{{ Str::plural('Classification', $classifications->count()) }}</span>
            </div>
        </div>

    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- CATEGORIES --}}
{{-- ─────────────────────────────────────────── --}}
@if($categories->isNotEmpty())
@php $bgCategory = $categories->firstWhere('image', '!=', null); @endphp
<section id="cat-section" class="relative py-16 overflow-hidden">
    {{-- Parallax background image --}}
    @if($bgCategory)
    <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
        <img id="cat-parallax-bg"
             src="{{ asset('storage/' . $bgCategory->image) }}"
             alt=""
             class="absolute inset-x-0 w-full object-cover will-change-transform"
             style="height:140%; top:-20%; filter:blur(5px); opacity:0.22;">
    </div>
    @endif
    {{-- Warm colour overlay --}}
    <div class="absolute inset-0 bg-[#F5EDBA]/85 dark:bg-[#3D2220]/90 pointer-events-none"></div>
    {{-- Dot grid --}}
    <div class="absolute inset-0 bg-[radial-gradient(#C8B87028_1px,transparent_1px)] dark:bg-[radial-gradient(#6B454028_1px,transparent_1px)] bg-size-[28px_28px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Browse by Category</h2>
            </div>
            <p class="ml-9 text-sm text-[#8C6040] dark:text-[#C4A080]">Explore our content organised by topic</p>
        </div>

        @php $catColors = ['#FFF8D4','#FFE8CC','#FFDAC4','#FFECD8','#F8E8C0','#FFD8C0']; @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
            @php $bg = $catColors[$loop->index % count($catColors)]; @endphp

            @if($loop->first)
            {{-- ── FEATURED card (spans 2 cols) ── --}}
            <a href="{{ route('home', ['category' => $category->id]) }}"
               class="card-animate group relative overflow-hidden rounded-2xl col-span-2
                      flex flex-col sm:flex-row
                      dark:bg-[#5C3835] border border-transparent dark:border-[#6B4540]
                      shadow-sm transition-all duration-300
                      hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(202,138,4,0.25)]
                      dark:hover:shadow-[0_8px_30px_rgba(140,90,60,0.4)]
                      hover:border-[#C8B870] dark:hover:border-[#8C5A3C]"
               :style="darkMode ? {} : { backgroundColor: '{{ $bg }}' }">

                {{-- Left: icon --}}
                <div class="flex items-center justify-center p-6 sm:w-40 shrink-0">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-amber-500/20 dark:bg-amber-500/15 scale-110 group-hover:scale-125 blur-md transition-transform duration-500"></div>
                        <div class="relative h-16 w-16 rounded-full bg-white/70 dark:bg-[#4B2E2B]/60 flex items-center justify-center
                                    group-hover:bg-white dark:group-hover:bg-[#4B2E2B]/90 transition-colors duration-300 shadow-md">
                            @if($category->icon)
                                {!! svg($category->icon, '', ['style' => 'width:1.75rem;height:1.75rem;color:#d97706'])->toHtml() !!}
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.75rem;height:1.75rem;color:#d97706">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: content --}}
                <div class="flex flex-1 flex-col justify-center px-5 pb-5 sm:pl-0 sm:py-5 sm:pr-6">
                    <span class="mb-1.5 text-xs font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400">Featured</span>
                    <h3 class="text-lg font-bold text-[#2C1A0E] dark:text-[#FFF8D4] mb-3 leading-tight">{{ $category->name }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-amber-600 dark:bg-[#8C5A3C] px-3 py-1 text-xs font-semibold text-white shadow-sm">
                            {{ $category->contents_count }} {{ $category->contents_count === 1 ? 'article' : 'articles' }}
                        </span>
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/50 dark:bg-[#6B4540]/50
                                     group-hover:bg-amber-600 dark:group-hover:bg-[#8C5A3C] transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                 class="h-3 w-3 text-[#5C3A1E] dark:text-[#E8C9A8] group-hover:text-white group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/>
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Background image --}}
                @if($category->image)
                <div class="absolute bottom-0 right-0 w-24 h-24 pointer-events-none opacity-20 group-hover:opacity-35 transition-opacity duration-300 origin-bottom-right">
                    <img src="{{ asset("storage/{$category->image}") }}" alt="{{ $category->name }}"
                         loading="lazy" class="w-full h-full object-cover rounded-tl-3xl">
                </div>
                @endif
            </a>

            @else
            {{-- ── REGULAR card ── --}}
            <a href="{{ route('home', ['category' => $category->id]) }}"
               class="card-animate group relative overflow-hidden rounded-2xl p-4 flex flex-col items-center text-center
                      dark:bg-[#5C3835] border border-transparent dark:border-[#6B4540]
                      shadow-sm transition-all duration-300
                      hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(202,138,4,0.25)]
                      dark:hover:shadow-[0_8px_30px_rgba(140,90,60,0.4)]
                      hover:border-[#C8B870] dark:hover:border-[#8C5A3C]"
               :style="darkMode ? {} : { backgroundColor: '{{ $bg }}' }">

                {{-- Icon with glow ring --}}
                <div class="relative mb-3 mt-1">
                    <div class="absolute inset-0 rounded-xl bg-amber-500/20 dark:bg-amber-500/10 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500"></div>
                    <div class="relative h-12 w-12 rounded-xl bg-white/70 dark:bg-[#4B2E2B]/60 flex items-center justify-center shadow-sm
                                group-hover:bg-white dark:group-hover:bg-[#4B2E2B]/90
                                group-hover:scale-110 transition-all duration-300">
                        @if($category->icon)
                            {!! svg($category->icon, '', ['style' => 'width:1.25rem;height:1.25rem;color:#d97706'])->toHtml() !!}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:#d97706">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Name --}}
                <h3 class="text-sm font-bold text-[#2C1A0E] dark:text-[#FFF8D4] leading-tight mb-2">{{ $category->name }}</h3>

                {{-- Count pill --}}
                <span class="inline-flex items-center rounded-full border border-amber-600/30 dark:border-[#8C5A3C]
                             bg-amber-600/10 dark:bg-[#8C5A3C]/20 px-2.5 py-0.5 text-xs font-semibold
                             text-amber-700 dark:text-amber-400
                             group-hover:bg-amber-600 group-hover:text-white group-hover:border-amber-600
                             dark:group-hover:bg-[#8C5A3C] dark:group-hover:text-[#FFF8D4]
                             transition-all duration-300">
                    {{ $category->contents_count }} {{ $category->contents_count === 1 ? 'article' : 'articles' }}
                </span>

                {{-- Image --}}
                @if($category->image)
                <div class="absolute bottom-0 right-0 w-12 h-12 pointer-events-none opacity-25 group-hover:opacity-40 transition-opacity duration-300 origin-bottom-right">
                    <img src="{{ asset("storage/{$category->image}") }}" alt="{{ $category->name }}"
                         loading="lazy" class="w-full h-full object-cover rounded-tl-2xl">
                </div>
                @endif
            </a>
            @endif

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

        {{-- Heading + search --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-10">
            <div class="flex items-center gap-3 flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">
                    @if($search) Search Results @else Latest Content @endif
                </h2>
            </div>

            <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2 w-full sm:w-auto sm:min-w-72">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#8C6040] dark:text-[#C4A080] pointer-events-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search articles…"
                           class="w-full rounded-xl border border-[#DDD090] dark:border-[#6B4540] bg-[#FFFEF0] dark:bg-[#5C3835] pl-9 pr-4 py-2.5 text-sm text-[#2C1A0E] dark:text-[#FFF8D4] placeholder-[#A87850] dark:placeholder-[#C4A080] focus:outline-none focus:border-amber-500 dark:focus:border-[#8C5A3C] transition-colors">
                </div>
                <button type="submit" class="rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors shrink-0">Search</button>
                @if($search)
                <a href="{{ route('search') }}" class="rounded-xl border border-[#DDD090] dark:border-[#6B4540] px-4 py-2.5 text-sm text-[#8C6040] dark:text-[#C4A080] hover:bg-[#EDE5A8] dark:hover:bg-[#6B4540] transition-colors shrink-0">Clear</a>
                @endif
            </form>
        </div>

        @if($search)
        <p class="mb-6 text-sm text-[#8C6040] dark:text-[#C4A080]">
            {{ $latestContents->total() }} {{ Str::plural('result', $latestContents->total()) }} for
            <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">"{{ $search }}"</span>
        </p>
        @endif

        @if($latestContents->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-[#C8A878] dark:text-[#6B4540] mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <p class="text-[#A87850] dark:text-[#C4A080] text-lg">No content published yet.</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestContents as $content)
                <article class="group flex flex-col rounded-2xl overflow-hidden bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                    <div class="relative aspect-video overflow-hidden bg-[#EDE5A8] dark:bg-[#6B4540]">
                        <img src="{{ asset("storage/{$content->header_image}") }}"
                             alt="{{ $content->title }}"
                             loading="lazy"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($content->category)
                        <span class="absolute top-3 left-3 rounded-full bg-amber-600 dark:bg-amber-500 px-3 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Meta strip --}}
                    <div class="flex items-center gap-3 px-5 py-2.5 text-xs text-[#8C6040] dark:text-[#C4A080] border-b border-[#DDD090] dark:border-[#6B4540] bg-[#FFFDF0] dark:bg-[#4B3030]">
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-amber-500 dark:text-amber-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                            </svg>
                            {{ $content->created_at->format('M d, Y') }}
                        </span>
                        <span class="w-px h-3 bg-[#DDD090] dark:bg-[#6B4540] shrink-0"></span>
                        <span class="inline-flex items-center gap-1.5 min-w-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-amber-500 dark:text-amber-400 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            <span class="truncate">{{ $content->user->name }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 shrink-0 ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-amber-500 dark:text-amber-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            {{ number_format($content->views) }}
                        </span>
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
                        <a href="{{ route('content.show', $content->slug) }}"
                           class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-amber-700 dark:text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 transition-colors">
                            Read more
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @if($latestContents->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $latestContents->appends(request()->query())->links('pagination::tailwind') }}
            </div>
            @endif
        @endif
    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- MOST POPULAR --}}
{{-- ─────────────────────────────────────────── --}}
@if($popularContents->isNotEmpty())
<section class="bg-[#FFFDF0] dark:bg-[#3D2220] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z"/>
            </svg>
            <h2 class="text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Most Popular</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Featured #1 (large card) --}}
            @php $top = $popularContents->first(); @endphp
            <a href="{{ route('content.show', $top->slug) }}"
               class="card-animate group relative overflow-hidden rounded-2xl bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                <div class="relative aspect-video overflow-hidden bg-[#EDE5A8] dark:bg-[#6B4540]">
                    <img src="{{ asset("storage/{$top->header_image}") }}"
                         alt="{{ $top->title }}"
                         loading="lazy"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-linear-to-t from-[#2C1A0E]/60 to-transparent"></div>
                    {{-- Rank badge --}}
                    <span class="absolute top-3 left-3 inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-600 text-sm font-bold text-white shadow-lg">1</span>
                    @if($top->category)
                    <span class="absolute top-3 right-3 rounded-full bg-white/20 backdrop-blur-sm px-3 py-1 text-xs font-semibold text-white">
                        {{ $top->category->name }}
                    </span>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-lg font-bold text-white line-clamp-2 mb-2">{{ $top->title }}</h3>
                        <div class="flex items-center gap-3 text-xs text-white/80">
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                {{ number_format($top->views) }} views
                            </span>
                            <span>{{ $top->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Ranked list #2–5 --}}
            <div class="flex flex-col gap-3">
                @foreach($popularContents->skip(1) as $i => $item)
                <a href="{{ route('content.show', $item->slug) }}"
                   class="card-animate group flex items-center gap-4 rounded-2xl bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] p-3 hover:shadow-md hover:border-[#C8B870] dark:hover:border-[#8C5A3C] hover:-translate-y-0.5 transition-all duration-300">
                    {{-- Rank number --}}
                    <span class="text-2xl font-black text-[#EDE5A8] dark:text-[#6B4540] w-7 shrink-0 leading-none select-none">
                        {{ $i + 2 }}
                    </span>
                    {{-- Thumbnail --}}
                    <div class="relative w-20 h-16 shrink-0 overflow-hidden rounded-xl bg-[#EDE5A8] dark:bg-[#6B4540]">
                        <img src="{{ asset("storage/{$item->header_image}") }}"
                             alt="{{ $item->title }}"
                             loading="lazy"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-[#2C1A0E] dark:text-[#FFF8D4] line-clamp-2 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors mb-1.5">
                            {{ $item->title }}
                        </h3>
                        <div class="flex items-center gap-2.5 text-xs text-[#8C6040] dark:text-[#C4A080]">
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 w-3 text-amber-500 dark:text-amber-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                {{ number_format($item->views) }}
                            </span>
                            <span class="w-px h-3 bg-[#DDD090] dark:bg-[#6B4540]"></span>
                            <span>{{ $item->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    {{-- Arrow --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                         class="h-4 w-4 text-[#C8B870] dark:text-[#8C5A3C] shrink-0 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<div class="h-px bg-linear-to-r from-transparent via-[#C8B870] dark:via-[#8C5A3C]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- CLASSIFICATIONS --}}
{{-- ─────────────────────────────────────────── --}}
@if($classifications->isNotEmpty())
<section class="relative py-16 overflow-hidden bg-[#4B2E2B] dark:bg-[#2E1A18]">
    {{-- Subtle grid texture --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    {{-- Accent glows --}}
    <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-amber-600/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-[#8C5A3C]/25 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-1.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-amber-600/30 bg-amber-600/15">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#FFF8D4]">Classifications</h2>
            </div>
            <p class="ml-12 text-sm text-[#C4A080]">Browse content by type and format</p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($classifications as $classification)
            <a href="{{ route('home', ['classification' => $classification->id]) }}"
               class="card-animate group flex items-center gap-3 rounded-2xl p-3
                      bg-[#5C3835]/50 backdrop-blur-sm
                      border border-[#6B4540]/60 border-l-2 border-l-amber-600/50
                      hover:bg-[#5C3835] hover:border-l-amber-400
                      hover:shadow-[0_8px_32px_rgba(0,0,0,0.35)]
                      hover:-translate-y-1 transition-all duration-300">

                {{-- Icon --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 rounded-xl bg-amber-500/20 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative h-10 w-10 rounded-xl bg-[#6B4540] flex items-center justify-center
                                group-hover:bg-amber-600 transition-colors duration-300">
                        @if($classification->icon)
                            {!! svg($classification->icon, '', ['style' => 'width:1rem;height:1rem;color:#fbbf24'])->toHtml() !!}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1rem;height:1rem;color:#fbbf24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Text --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs font-bold text-[#FFF8D4] leading-tight truncate">{{ $classification->name }}</h3>
                    <span class="mt-1 inline-flex items-center rounded-full bg-amber-600/20 border border-amber-600/30 px-2 py-0.5 text-xs font-semibold text-amber-400">
                        {{ $classification->contents_count }}
                    </span>
                </div>

                {{-- Image thumbnail --}}
                @if($classification->image)
                <div class="w-12 h-10 shrink-0 overflow-hidden rounded-lg border border-[#8C5A3C]/40
                            group-hover:border-amber-600/50 transition-colors duration-300">
                    <img src="{{ asset("storage/{$classification->image}") }}"
                         alt="{{ $classification->name }}"
                         loading="lazy"
                         class="h-full w-full object-cover opacity-80 group-hover:opacity-100
                                group-hover:scale-110 transition-all duration-300">
                </div>
                @else
                {{-- Arrow when no image --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="h-4 w-4 shrink-0 text-[#8C5A3C] group-hover:text-amber-400 group-hover:translate-x-1 transition-all duration-200">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
                @endif
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    // ── Category section parallax ──
    (function () {
        const section = document.getElementById('cat-section');
        const bg      = document.getElementById('cat-parallax-bg');
        if (!section || !bg) return;

        function onScroll() {
            const rect     = section.getBoundingClientRect();
            const center   = rect.top + rect.height / 2 - window.innerHeight / 2;
            bg.style.transform = 'translateY(' + (center * 0.12).toFixed(2) + 'px)';
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // set initial position
    })();

    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.card-animate').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(22px)';
            el.style.transition = `opacity 0.45s ease ${i * 0.07}s, transform 0.45s ease ${i * 0.07}s`;
            observer.observe(el);
        });
    });
</script>
@endpush
