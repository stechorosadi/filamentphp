@extends('layouts.front')

@section('seo')
<title>{{ $siteSetting->site_title }}{{ $siteSetting->site_tagline ? ' — ' . $siteSetting->site_tagline : '' }}</title>
<meta name="description" content="{{ $siteSetting->site_description ?? 'Discover articles, research, and resources curated by our team. Stay informed with the latest content.' }}">
<meta property="og:title" content="{{ $siteSetting->site_title }}">
<meta property="og:description" content="{{ $siteSetting->site_description ?? 'Discover articles, research, and resources curated by our team.' }}">
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
<section class="relative flex min-h-screen items-center bg-[var(--bg-primary)] overflow-hidden">
    {{-- Background grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000010_1px,transparent_1px),linear-gradient(to_bottom,#00000010_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-size-[48px_48px]"></div>
    {{-- Glow --}}
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-128 w-lg rounded-full bg-[var(--accent)]/20 dark:bg-[var(--accent)]/30 blur-3xl pointer-events-none"></div>

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
                                <span class="inline-flex items-center rounded-full bg-[#31572C] dark:bg-[var(--bg-card)] px-3 py-1 text-xs font-semibold text-white dark:text-[var(--text-muted)]">
                                    {{ $slide->classification->name }}
                                </span>
                                @endif
                                @if($slide->category?->name)
                                <span class="inline-flex items-center rounded-full bg-[var(--accent)] dark:bg-[var(--accent)] px-3 py-1 text-xs font-semibold text-white dark:text-[var(--text-primary)]">
                                    {{ $slide->category->name }}
                                </span>
                                @endif
                                <span class="inline-flex items-center rounded-full border border-[var(--accent-dim)] dark:border-[var(--border)] bg-[var(--accent-dim)] px-3 py-1 text-xs font-semibold text-[var(--text-primary)] dark:text-[var(--text-muted)]">
                                    {{ $slide->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[var(--text-primary)] leading-tight">
                                {{ $slide->title }}
                            </h1>

                            {{-- Excerpt --}}
                            @if($slide->excerpt)
                            <p class="text-md leading-relaxed text-[var(--text-muted)] max-w-lg">
                                {{ \Illuminate\Support\Str::limit($slide->excerpt, 160) }}
                            </p>
                            @endif

                            {{-- CTA buttons --}}
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="{{ route('content.show', $slide->slug) }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-(--accent) px-6 py-3 text-sm font-semibold text-white shadow-lg hover:opacity-90 transition-opacity duration-200">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                                <button @click="preview = true"
                                        class="inline-flex items-center gap-2.5 rounded-xl border-2 border-(--accent) px-6 py-3 text-sm font-semibold text-(--text-primary) hover:bg-(--accent-dim) transition-colors duration-200">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-(--accent)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3 text-white translate-x-0.5">
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
                            <div class="absolute inset-0 -z-10 scale-110 rounded-3xl bg-[var(--accent-dim)]/20 dark:bg-[var(--accent)]/25 blur-2xl"></div>

                            {{-- Mobile-only prev/next arrows (positioned on sides of image) --}}
                            @if($featuredContents->count() > 1)
                            <button @click="prev()" class="lg:hidden absolute left-2 top-1/2 -translate-y-1/2 z-10 rounded-full border border-[var(--border)] bg-white/70 dark:bg-[var(--bg-card)]/70 p-2 text-[var(--text-muted)] shadow-md backdrop-blur hover:bg-[var(--accent)] hover:text-white transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                                </svg>
                            </button>
                            <button @click="next()" class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 z-10 rounded-full border border-[var(--border)] bg-white/70 dark:bg-[var(--bg-card)]/70 p-2 text-[var(--text-muted)] shadow-md backdrop-blur hover:bg-[var(--accent)] hover:text-white transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                </svg>
                            </button>
                            @endif

                            {{-- Browser card --}}
                            <div class="relative overflow-hidden rounded-2xl border border-[var(--border)] bg-[var(--bg-card)] shadow-2xl dark:shadow-[0_25px_60px_rgba(0,0,0,0.5)]">
                                {{-- Browser chrome --}}
                                <div class="flex items-center gap-1.5 border-b border-[var(--border)] bg-[var(--bg-alt)] dark:bg-[var(--dark-section)] px-4 py-3">
                                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                    <span class="h-3 w-3 rounded-full bg-[var(--accent-dim)]"></span>
                                    <span class="h-3 w-3 rounded-full bg-green-400"></span>
                                    <div class="ml-3 h-4 max-w-48 flex-1 rounded-md bg-white/60 dark:bg-[#132A13]/60"></div>
                                </div>
                                {{-- Featured image --}}
                                <img src="{{ asset("storage/{$slide->featured_image}") }}"
                                     alt="{{ $slide->title }}"
                                     class="aspect-video lg:aspect-4/3 w-full object-cover"
                                     @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                            </div>

                            {{-- Author badge (bottom-left) --}}
                            <div class="absolute -bottom-3 -left-3 hidden sm:flex items-center gap-2 rounded-xl bg-(--accent) px-4 py-2 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-white shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                <span class="text-xs font-semibold text-white">{{ $slide->user?->name ?? $siteSetting->site_title }}</span>
                            </div>

                            {{-- Featured badge (top-right) --}}
                            <div class="absolute -top-3 -right-3 hidden sm:flex items-center gap-1.5 rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-4 py-2 shadow-lg">
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
                    :class="current === {{ $i }} ? 'w-6 bg-[var(--accent)]' : 'w-2 bg-[var(--accent-dim)] dark:bg-[var(--accent)]'"
                    class="h-2 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>

        {{-- Prev arrow (desktop only) --}}
        <button @click="prev()" class="hidden lg:flex absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full border border-[var(--border)] bg-white/70 dark:bg-[var(--bg-card)]/70 p-2.5 text-[var(--text-muted)] shadow-md backdrop-blur hover:bg-[var(--accent)] hover:text-white dark:hover:bg-[var(--accent)] transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </button>

        {{-- Next arrow (desktop only) --}}
        <button @click="next()" class="hidden lg:flex absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full border border-[var(--border)] bg-white/70 dark:bg-[var(--bg-card)]/70 p-2.5 text-[var(--text-muted)] shadow-md backdrop-blur hover:bg-[var(--accent)] hover:text-white dark:hover:bg-[var(--accent)] transition-all duration-200">
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
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#132A13]/60 backdrop-blur-sm">

            <div x-show="preview"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg rounded-2xl bg-[var(--bg-card)] border border-[var(--border)] shadow-2xl p-8">

                {{-- Close --}}
                <button @click="preview = false"
                        class="absolute top-4 right-4 rounded-lg p-1.5 text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-[#2a5c2a] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Category badge --}}
                <div class="mb-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--accent-dim)] px-3 py-1 text-xs font-semibold text-[var(--text-muted)] dark:text-[var(--accent)]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[var(--accent)]"></span>
                        <span x-text="slideData[current]?.category ?? 'Featured'"></span>
                    </span>
                </div>

                {{-- Title --}}
                <h2 class="mb-3 text-xl font-bold text-[var(--text-primary)] leading-snug"
                    x-text="slideData[current]?.title"></h2>

                {{-- Excerpt --}}
                <p class="mb-5 text-sm leading-relaxed text-[var(--text-muted)]"
                   x-text="slideData[current]?.excerpt || 'No preview available.'"></p>

                {{-- Date --}}
                <p class="mb-6 text-xs text-[var(--accent)]" x-text="slideData[current]?.date"></p>

                {{-- CTA --}}
                <a :href="slideData[current]?.url"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-5 py-3 text-sm font-semibold text-white dark:text-[var(--text-primary)] hover:bg-[var(--accent)] dark:hover:bg-[#6B9A38] transition-colors">
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
        <p class="animate-fade-up mb-4 inline-block rounded-full border border-[var(--accent-dim)] dark:border-[var(--accent)] bg-[var(--accent-dim)] dark:bg-[var(--accent)]/20 px-4 py-1 text-sm font-medium text-[var(--text-muted)] dark:text-[var(--accent)]">
            Welcome
        </p>
        <h1 class="animate-fade-up-delay-1 text-4xl font-bold tracking-tight text-[var(--text-primary)] sm:text-6xl lg:text-7xl">
            {{ $siteSetting->site_title }}
        </h1>
        <p class="animate-fade-up-delay-2 mt-6 max-w-2xl mx-auto text-lg leading-8 text-[var(--text-muted)]">
            Discover articles, research, and resources curated by our team. Stay informed with the latest content.
        </p>
        <div class="animate-fade-up-delay-2 mt-10">
            <a href="#content" class="inline-flex items-center gap-2 rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-7 py-3.5 text-sm font-semibold text-white dark:text-[var(--text-primary)] shadow-lg hover:bg-[var(--accent)] dark:hover:bg-[#6B9A38] transition-colors duration-200">
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
<section class="relative overflow-hidden bg-[#132A13] dark:bg-[#0a1a0a] py-20 sm:py-24">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-[var(--accent)]/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 right-1/4 h-72 w-72 rounded-full bg-[var(--accent)]/25 blur-3xl pointer-events-none"></div>
    {{-- Subtle grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px]"></div>

    <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Icon --}}
        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#4F772D]/30 bg-[var(--accent)]/15">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-[#90A955]">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
        </div>

        {{-- Heading --}}
        <h2 class="mb-4 text-2xl sm:text-3xl lg:text-4xl font-bold text-[#ECF39E] leading-tight">
            Discover Knowledge<br>Without Limits
        </h2>

        {{-- Subtitle --}}
        <p class="mb-10 text-base sm:text-lg leading-relaxed text-[#90A955] max-w-xl mx-auto">
            Search across our entire collection of articles, research, and resources — all curated and organised in one place, ready for you to explore.
        </p>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('search') }}" class="mb-10">
            <div class="flex rounded-2xl border-2 border-[#4F772D]/30 bg-white/8 backdrop-blur-sm focus-within:border-[#4F772D]/60 transition-all duration-200 shadow-[0_8px_32px_rgba(0,0,0,0.4)] p-2 gap-2">
                <div class="flex flex-1 items-center min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                         class="ml-3 h-5 w-5 shrink-0 text-[#90A955]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $search }}" maxlength="100"
                           placeholder="Search articles, topics, or keywords…"
                           class="min-w-0 flex-1 bg-transparent px-3 py-2 text-base text-[#ECF39E] placeholder-[#4F772D] focus:outline-none">
                </div>
                <button type="submit"
                        class="shrink-0 rounded-xl bg-[var(--accent)] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[var(--accent)] transition-colors duration-200">
                    Search
                </button>
            </div>
        </form>

        {{-- Stats pills --}}
        <div class="flex flex-wrap justify-center gap-3">
            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[#90A955] shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#ECF39E]">{{ $totalArticles }}</span>
                <span class="text-sm text-[#90A955]">{{ Str::plural('Article', $totalArticles) }}</span>
            </div>

            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[#90A955] shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#ECF39E]">{{ $categories->count() }}</span>
                <span class="text-sm text-[#90A955]">{{ Str::plural('Category', $categories->count()) }}</span>
            </div>

            <div class="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/8 px-5 py-2.5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[#90A955] shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
                <span class="text-sm font-semibold text-[#ECF39E]">{{ $classifications->count() }}</span>
                <span class="text-sm text-[#90A955]">{{ Str::plural('Classification', $classifications->count()) }}</span>
            </div>
        </div>

    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#90A955] dark:via-[#4F772D]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- CATEGORIES --}}
{{-- ─────────────────────────────────────────── --}}
@if($categories->isNotEmpty())
<section id="cat-section" class="relative bg-[var(--bg-alt)] dark:bg-[var(--dark-section)] py-16 overflow-hidden">
    {{-- Dot grid --}}
    <div class="absolute inset-0 bg-[radial-gradient(#90A95528_1px,transparent_1px)] dark:bg-[radial-gradient(#2a5c2a28_1px,transparent_1px)] bg-size-[28px_28px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-[var(--accent)] dark:text-[var(--accent)]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                <h2 class="text-2xl font-bold text-[var(--text-primary)]">Browse by Category</h2>
            </div>
            <p class="ml-9 text-sm text-[var(--accent)]">Explore our content organised by topic</p>
        </div>

        @php
            // Generate card tints from site settings colors
            $mixHex = function (string $hex1, string $hex2, float $ratio): string {
                $hex1 = ltrim($hex1, '#'); $hex2 = ltrim($hex2, '#');
                return sprintf('#%02x%02x%02x',
                    (int) round(hexdec(substr($hex1,0,2)) * (1-$ratio) + hexdec(substr($hex2,0,2)) * $ratio),
                    (int) round(hexdec(substr($hex1,2,2)) * (1-$ratio) + hexdec(substr($hex2,2,2)) * $ratio),
                    (int) round(hexdec(substr($hex1,4,2)) * (1-$ratio) + hexdec(substr($hex2,4,2)) * $ratio),
                );
            };
            $base   = $siteSetting->color_light_bg ?? '#ECF39E';
            $accent = $siteSetting->color_accent    ?? '#4F772D';
            $catColors = [
                $mixHex($base, '#ffffff', 0.55),
                $mixHex($base, '#ffffff', 0.50),
                $mixHex($base, '#ffffff', 0.60),
                $mixHex($base, '#ffffff', 0.45),
                $mixHex($base, '#ffffff', 0.58),
                $mixHex($base, '#ffffff', 0.48),
            ];
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
            @php $bg = $catColors[$loop->index % count($catColors)]; @endphp

            @if($loop->first)
            {{-- ── FEATURED card (spans 2 cols) ── --}}
            <a href="{{ route('category.show', $category->slug) }}"
               class="card-animate group relative overflow-hidden rounded-2xl col-span-2
                      flex flex-col sm:flex-row
                      dark:bg-[var(--bg-card)] border border-transparent dark:border-[var(--border)]
                      shadow-sm transition-all duration-300
                      hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(202,138,4,0.25)]
                      dark:hover:shadow-[0_8px_30px_rgba(140,90,60,0.4)]
                      hover:border-[var(--accent-dim)] dark:hover:border-[#4F772D]"
               :style="darkMode ? {} : { backgroundColor: '{{ $bg }}' }">

                {{-- Left: icon --}}
                <div class="flex items-center justify-center p-6 sm:w-40 shrink-0">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-[var(--accent)]/20 dark:bg-[var(--accent)]/15 scale-110 group-hover:scale-125 blur-md transition-transform duration-500"></div>
                        <div class="relative h-20 w-20 rounded-full bg-white/70 dark:bg-[#132A13]/60 flex items-center justify-center
                                    group-hover:bg-white dark:group-hover:bg-[#132A13]/90 transition-colors duration-300 shadow-md">
                            @if($category->icon)
                                {!! svg($category->icon, '', ['style' => 'width:2.5rem;height:2.5rem;color:#4F772D'])->toHtml() !!}
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:2.5rem;height:2.5rem;color:#4F772D">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: content --}}
                <div class="flex flex-1 flex-col justify-center px-5 pb-5 sm:pl-0 sm:py-5 sm:pr-6">
                    <span class="mb-1.5 text-xs font-bold uppercase tracking-widest text-[var(--accent)]">Featured</span>
                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1.5 leading-tight">{{ $category->name }}</h3>
                    @if($category->description)
                    <p class="text-xs text-[var(--text-muted)] dark:text-[var(--accent)] leading-relaxed line-clamp-2 mb-3">{{ $category->description }}</p>
                    @else
                    <div class="mb-3"></div>
                    @endif
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-[var(--accent)] dark:bg-[var(--accent)] px-3 py-1 text-xs font-semibold text-white shadow-sm">
                            {{ $category->contents_count }} {{ $category->contents_count === 1 ? 'article' : 'articles' }}
                        </span>
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/50 dark:bg-[var(--bg-alt)]/50
                                     group-hover:bg-[var(--accent)] dark:group-hover:bg-[var(--accent)] transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                 class="h-3 w-3 text-[var(--text-muted)] group-hover:text-white group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all duration-200">
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
            <a href="{{ route('category.show', $category->slug) }}"
               class="card-animate group relative overflow-hidden rounded-2xl p-4 flex flex-col items-center text-center
                      dark:bg-[var(--bg-card)] border border-transparent dark:border-[var(--border)]
                      shadow-sm transition-all duration-300
                      hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(202,138,4,0.25)]
                      dark:hover:shadow-[0_8px_30px_rgba(140,90,60,0.4)]
                      hover:border-[var(--accent-dim)] dark:hover:border-[#4F772D]"
               :style="darkMode ? {} : { backgroundColor: '{{ $bg }}' }">

                {{-- Icon with glow ring --}}
                <div class="relative mb-3 mt-1">
                    <div class="absolute inset-0 rounded-xl bg-[var(--accent)]/20 dark:bg-[var(--accent)]/10 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500"></div>
                    <div class="relative h-12 w-12 rounded-xl bg-white/70 dark:bg-[#132A13]/60 flex items-center justify-center shadow-sm
                                group-hover:bg-white dark:group-hover:bg-[#132A13]/90
                                group-hover:scale-110 transition-all duration-300">
                        @if($category->icon)
                            {!! svg($category->icon, '', ['style' => 'width:1.25rem;height:1.25rem;color:#4F772D'])->toHtml() !!}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:#4F772D">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Name --}}
                <h3 class="text-sm font-bold text-[var(--text-primary)] leading-tight mb-1">{{ $category->name }}</h3>

                {{-- Description --}}
                @if($category->description)
                <p class="text-xs text-[var(--text-muted)] dark:text-[var(--accent)] leading-relaxed line-clamp-2 mb-2 px-1">{{ $category->description }}</p>
                @else
                <div class="mb-2"></div>
                @endif

                {{-- Count pill --}}
                <span class="inline-flex items-center rounded-full border border-[#4F772D]/30 dark:border-[var(--accent)]
                             bg-[var(--accent)]/10 dark:bg-[var(--accent)]/20 px-2.5 py-0.5 text-xs font-semibold
                             text-[var(--text-muted)] dark:text-[var(--accent)]
                             group-hover:bg-[var(--accent)] group-hover:text-white group-hover:border-[#4F772D]
                             dark:group-hover:bg-[var(--accent)] dark:group-hover:text-[#ECF39E]
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

<div class="h-px bg-linear-to-r from-transparent via-[#90A955] dark:via-[#4F772D]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- LATEST CONTENT --}}
{{-- ─────────────────────────────────────────── --}}
<section id="content-section" class="relative py-20 overflow-hidden">
    {{-- Parallax background image --}}
    <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
        <img id="content-parallax-bg"
             src="{{ asset('storage/background/bg-01.jpg') }}"
             alt=""
             class="absolute inset-x-0 w-full object-cover will-change-transform"
             style="height:140%; top:-20%; filter:blur(3px);">
    </div>
    {{-- Warm colour overlay --}}
    <div class="absolute inset-0 bg-[#ECF39E]/70 dark:bg-[#132A13]/82 pointer-events-none"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Heading + search --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-10">
            <div class="flex items-center gap-3 flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-[var(--accent)] dark:text-[var(--accent)] shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <h2 class="text-2xl font-bold text-[var(--text-primary)]">
                    @if($search) Search Results @else Latest Content @endif
                </h2>
            </div>

            <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2 w-full sm:w-auto sm:min-w-72">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[var(--accent)] pointer-events-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $search }}" maxlength="100" placeholder="Search articles…"
                           class="w-full rounded-xl border border-[var(--border)] bg-[var(--bg-card)] pl-9 pr-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#A87850] dark:placeholder-[#90A955] focus:outline-none focus:border-[var(--accent)] dark:focus:border-[var(--accent)] transition-colors">
                </div>
                <button type="submit" class="rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[var(--accent)] dark:hover:bg-[#6B9A38] transition-colors shrink-0">Search</button>
                @if($search)
                <a href="{{ route('search') }}" class="rounded-xl border border-[var(--border)] px-4 py-2.5 text-sm text-[var(--accent)] hover:bg-[var(--accent-dim)] dark:hover:bg-[#2a5c2a] transition-colors shrink-0">Clear</a>
                @endif
            </form>
        </div>

        @if($search)
        <p class="mb-6 text-sm text-[var(--accent)]">
            {{ $latestContents->total() }} {{ Str::plural('result', $latestContents->total()) }} for
            <span class="font-semibold text-[var(--text-primary)]">"{{ $search }}"</span>
        </p>
        @endif

        @if($latestContents->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-[#90A955] dark:text-[#2a5c2a] mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                <p class="text-[#A87850] dark:text-[var(--accent)] text-lg">No content published yet.</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestContents as $content)
                <article class="group flex flex-col rounded-2xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border)] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                    <div class="relative aspect-video overflow-hidden bg-[var(--accent-dim)]">
                        <img src="{{ asset("storage/{$content->header_image}") }}"
                             alt="{{ $content->title }}"
                             loading="lazy"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($content->category)
                        <span class="absolute top-3 left-3 rounded-full bg-[var(--accent)] dark:bg-[var(--accent)] px-3 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Meta strip --}}
                    <div class="flex items-center gap-3 px-5 py-2.5 text-xs text-[var(--accent)] border-b border-[var(--border)] bg-[#f0f9d0] dark:bg-[#142814]">
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-[var(--accent)]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                            </svg>
                            {{ $content->created_at->format('M d, Y') }}
                        </span>
                        <span class="w-px h-3 bg-[#a0c84a] dark:bg-[var(--bg-alt)] shrink-0"></span>
                        <span class="inline-flex items-center gap-1.5 min-w-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-[var(--accent)] shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            <span class="truncate">{{ $content->user->name }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 shrink-0 ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-[var(--accent)]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            {{ number_format($content->views) }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="mb-2 text-lg font-bold text-[var(--text-primary)] line-clamp-2 group-hover:text-[var(--text-muted)] dark:group-hover:text-[#90A955] transition-colors duration-200">
                            {{ $content->title }}
                        </h3>
                        @if($content->excerpt)
                        <p class="mb-4 text-sm leading-relaxed text-[var(--accent)] line-clamp-3 flex-1">
                            {{ $content->excerpt }}
                        </p>
                        @endif
                        <a href="{{ route('content.show', $content->slug) }}"
                           class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-[var(--text-muted)] dark:text-[var(--accent)] hover:text-[var(--accent)] dark:hover:text-[#b8d864] transition-colors">
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

{{-- ─────────────────────────────────────────── --}}
{{-- CLASSIFICATIONS --}}
{{-- ─────────────────────────────────────────── --}}
@if($classifications->isNotEmpty())
<section class="relative py-16 overflow-hidden bg-[#132A13] dark:bg-[var(--dark-section)]">
    {{-- Subtle grid texture --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    {{-- Accent glows --}}
    <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-[var(--accent)]/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-[var(--accent)]/25 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-1.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#4F772D]/30 bg-[var(--accent)]/15">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[#90A955]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#ECF39E]">Classifications</h2>
            </div>
            <p class="ml-12 text-sm text-[#90A955]">Browse content by type and format</p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($classifications as $classification)
            <a href="{{ route('classification.show', $classification->slug) }}"
               class="card-animate group relative overflow-hidden flex items-center gap-3 rounded-2xl p-3
                      bg-[#1e4a1e]/50 backdrop-blur-sm
                      border border-[#2a5c2a]/60 border-l-2 border-l-amber-600/50
                      hover:bg-[#1e4a1e] hover:border-l-amber-400
                      hover:shadow-[0_8px_32px_rgba(0,0,0,0.35)]
                      hover:-translate-y-1 transition-all duration-300">

                {{-- Icon --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 rounded-xl bg-[var(--accent)]/20 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative h-10 w-10 rounded-xl bg-[#2a5c2a] flex items-center justify-center
                                group-hover:bg-[var(--accent)] transition-colors duration-300">
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
                    <h3 class="text-xs font-bold text-[#ECF39E] leading-tight truncate">{{ $classification->name }}</h3>
                    <span class="mt-1 inline-flex items-center rounded-full bg-[var(--accent)]/20 border border-[#4F772D]/30 px-2 py-0.5 text-xs font-semibold text-[#90A955]">
                        {{ $classification->contents_count }}
                    </span>
                </div>

                {{-- Arrow --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="h-4 w-4 shrink-0 text-[var(--accent)] group-hover:text-[#90A955] group-hover:translate-x-1 transition-all duration-200">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>

                {{-- Background image --}}
                @if($classification->image)
                <div class="absolute bottom-0 right-0 w-20 h-full pointer-events-none opacity-15 group-hover:opacity-30 transition-opacity duration-300">
                    <img src="{{ asset("storage/{$classification->image}") }}"
                         alt="{{ $classification->name }}"
                         loading="lazy"
                         class="w-full h-full object-cover rounded-tl-2xl">
                </div>
                @endif
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

<div class="h-px bg-linear-to-r from-transparent via-[#90A955] dark:via-[#4F772D]/50 to-transparent"></div>

{{-- ─────────────────────────────────────────── --}}
{{-- TEAM SECTION --}}
@if($teamMembers->isNotEmpty())
@php
    $tmCount   = $teamMembers->count();
    $useSlider = $tmCount > 4;
    $clones    = $useSlider ? min(4, $tmCount) : 0;
    $headClones = $useSlider ? $teamMembers->slice($tmCount - $clones) : collect();
    $tailClones = $useSlider ? $teamMembers->take($clones) : collect();
@endphp
<section class="py-16 bg-(--bg-primary) overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-1.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-(--border) bg-(--accent)/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--accent)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-(--text-primary)">Our Team</h2>
            </div>
            <p class="ml-12 text-sm text-(--text-muted)">Meet the people behind our work</p>
        </div>

        @if($useSlider)
        {{-- ── INFINITE CAROUSEL ── --}}
        <div
            x-data="{
                pos: {{ $clones }},
                total: {{ $tmCount }},
                clones: {{ $clones }},
                busy: false,
                timer: null,
                get iw() { return this.$refs.track.children[0]?.offsetWidth ?? 0; },
                move(animate) {
                    this.$refs.track.style.transition = animate ? 'transform 0.5s ease' : 'none';
                    this.$refs.track.style.transform  = 'translateX(-' + (this.pos * this.iw) + 'px)';
                },
                next() {
                    if (this.busy) return; this.busy = true;
                    this.pos++; this.move(true);
                    setTimeout(() => {
                        if (this.pos >= this.clones + this.total) { this.pos = this.clones; this.move(false); }
                        this.busy = false;
                    }, 520);
                },
                prev() {
                    if (this.busy) return; this.busy = true;
                    this.pos--; this.move(true);
                    setTimeout(() => {
                        if (this.pos < this.clones) { this.pos = this.clones + this.total - 1; this.move(false); }
                        this.busy = false;
                    }, 520);
                },
                startAuto() { this.timer = setInterval(() => this.next(), 4000); },
                stopAuto()  { clearInterval(this.timer); },
                init() {
                    this.move(false);
                    this.startAuto();
                    window.addEventListener('resize', () => this.move(false));
                }
            }"
            @mouseenter="stopAuto()"
            @mouseleave="startAuto()"
            class="relative">

            {{-- Prev arrow --}}
            <button @click="prev()"
                    class="absolute left-0 top-1/3 -translate-y-1/2 -translate-x-3 z-10
                           flex h-9 w-9 items-center justify-center rounded-full
                           bg-(--accent) text-white shadow-lg hover:opacity-80 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </button>

            {{-- Track --}}
            <div class="overflow-hidden -mx-2.5">
                <div x-ref="track" class="flex">
                    {{-- Head clones: copies of last N real items --}}
                    @foreach($headClones as $member)
                    <div class="w-1/2 md:w-1/4 flex-none px-2.5">
                        @include('partials.team-card', ['member' => $member])
                    </div>
                    @endforeach
                    {{-- Real items --}}
                    @foreach($teamMembers as $member)
                    <div class="w-1/2 md:w-1/4 flex-none px-2.5">
                        @include('partials.team-card', ['member' => $member])
                    </div>
                    @endforeach
                    {{-- Tail clones: copies of first N real items --}}
                    @foreach($tailClones as $member)
                    <div class="w-1/2 md:w-1/4 flex-none px-2.5">
                        @include('partials.team-card', ['member' => $member])
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Next arrow --}}
            <button @click="next()"
                    class="absolute right-0 top-1/3 -translate-y-1/2 translate-x-3 z-10
                           flex h-9 w-9 items-center justify-center rounded-full
                           bg-(--accent) text-white shadow-lg hover:opacity-80 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </button>
        </div>

        @else
        {{-- ── SIMPLE GRID (4 or fewer) ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($teamMembers as $member)
            <div class="card-animate">
                @include('partials.team-card', ['member' => $member])
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>

<div class="h-px bg-linear-to-r from-transparent via-[#90A955] dark:via-[#4F772D]/50 to-transparent"></div>
@endif

{{-- ─────────────────────────────────────────── --}}
{{-- MOST POPULAR --}}
{{-- ─────────────────────────────────────────── --}}
@if($popularContents->isNotEmpty())
<section class="relative bg-[var(--bg-primary)] py-16 overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-[var(--accent-dim)]/10 dark:bg-[var(--accent)]/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/3 h-48 w-48 rounded-full bg-[#FFDAC4]/40 dark:bg-[var(--bg-card)]/30 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Heading --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-[var(--accent)] dark:text-[var(--accent)]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z"/>
                </svg>
                <h2 class="text-2xl font-bold text-[var(--text-primary)]">Most Popular</h2>
            </div>
            <p class="ml-9 text-sm text-[var(--accent)]">The most read articles right now</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- ── FEATURED #1 (full-bleed image) ── --}}
            @php $top = $popularContents->first(); @endphp
            <a href="{{ route('content.show', $top->slug) }}"
               class="card-animate group relative overflow-hidden rounded-2xl min-h-72 lg:min-h-full
                      bg-[var(--accent-dim)]
                      hover:shadow-[0_12px_40px_rgba(202,138,4,0.2)] dark:hover:shadow-[0_12px_40px_rgba(0,0,0,0.5)]
                      hover:-translate-y-1 transition-all duration-300">

                {{-- Full-bleed image --}}
                <img src="{{ asset("storage/{$top->header_image}") }}"
                     alt="{{ $top->title }}"
                     loading="lazy"
                     class="absolute inset-0 h-full w-full object-cover group-hover:scale-105 transition-transform duration-700">

                {{-- Gradient overlays --}}
                <div class="absolute inset-0 bg-linear-to-t from-[#132A13]/85 via-[#132A13]/20 to-transparent"></div>
                <div class="absolute inset-0 bg-linear-to-r from-[#132A13]/30 to-transparent"></div>

                {{-- Rank badge --}}
                <div class="absolute top-4 left-4 flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--accent)] text-sm font-black text-white shadow-lg ring-2 ring-white/20">1</span>
                </div>

                {{-- Category badge --}}
                @if($top->category)
                <div class="absolute top-4 right-4">
                    <span class="rounded-full bg-white/15 backdrop-blur-md border border-white/20 px-3 py-1 text-xs font-semibold text-white">
                        {{ $top->category->name }}
                    </span>
                </div>
                @endif

                {{-- Bottom content --}}
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-xl font-bold text-white leading-snug line-clamp-2 mb-3
                               group-hover:text-[#b8d864] transition-colors duration-300">
                        {{ $top->title }}
                    </h3>
                    <div class="flex items-center gap-4 text-xs text-white/75">
                        @if($top->user)
                        <span class="flex items-center gap-1.5">
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[var(--accent)]/80 text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($top->user->name, 0, 1)) }}
                            </span>
                            {{ $top->user->name }}
                        </span>
                        <span class="h-3 w-px bg-white/30"></span>
                        @endif
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            {{ number_format($top->views) }}
                        </span>
                        <span class="h-3 w-px bg-white/30"></span>
                        <span>{{ $top->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </a>

            {{-- ── RANKED LIST #2–5 ── --}}
            <div class="flex flex-col gap-3">
                @foreach($popularContents->skip(1) as $item)
                @php $rank = $loop->iteration + 1; @endphp
                <a href="{{ route('content.show', $item->slug) }}"
                   class="popular-item group flex items-center gap-4 rounded-2xl p-3.5
                          bg-[var(--bg-card)]
                          border border-[var(--border)]
                          hover:border-[var(--accent-dim)] dark:hover:border-[#4F772D]
                          hover:shadow-[0_4px_20px_rgba(202,138,4,0.15)] dark:hover:shadow-[0_4px_20px_rgba(0,0,0,0.35)]
                          hover:-translate-y-0.5 transition-all duration-300"
                   style="opacity:0;transform:translateX(20px)">

                    {{-- Rank --}}
                    <span class="text-3xl font-black leading-none select-none shrink-0 w-8 text-center
                                 text-[#c8de70] dark:text-[#2a5c2a]
                                 group-hover:text-[#90A955] dark:group-hover:text-[var(--accent)]
                                 transition-colors duration-300">
                        {{ $rank }}
                    </span>

                    {{-- Thumbnail --}}
                    <div class="relative w-20 h-16 shrink-0 overflow-hidden rounded-xl bg-[var(--accent-dim)]">
                        <img src="{{ asset("storage/{$item->header_image}") }}"
                             alt="{{ $item->title }}"
                             loading="lazy"
                             class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                        {{-- Hover shimmer overlay --}}
                        <div class="absolute inset-0 bg-[var(--accent)]/0 group-hover:bg-[var(--accent)]/10 transition-colors duration-300 rounded-xl"></div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-[var(--text-primary)] line-clamp-2 leading-snug
                                   group-hover:text-[var(--text-muted)] dark:group-hover:text-[#90A955]
                                   transition-colors duration-200 mb-2">
                            {{ $item->title }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-(--accent)">
                            @if($item->user)
                            <span class="inline-flex items-center gap-1 shrink-0">
                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-[var(--accent)]/80 dark:bg-[var(--accent)] text-white text-xs font-bold leading-none shrink-0">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </span>
                                <span class="truncate max-w-20">{{ $item->user->name }}</span>
                            </span>
                            <span class="w-px h-3 bg-[#a0c84a] dark:bg-[var(--bg-alt)] shrink-0"></span>
                            @endif
                            <span class="flex items-center gap-1 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3 w-3 text-[var(--accent)]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                {{ number_format($item->views) }}
                            </span>
                            <span class="w-px h-3 bg-[#a0c84a] dark:bg-[var(--bg-alt)] shrink-0"></span>
                            <span class="shrink-0">{{ $item->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                         class="h-4 w-4 shrink-0 text-[#90A955] dark:text-[var(--accent)]
                                group-hover:text-[var(--accent)] dark:group-hover:text-[#90A955]
                                group-hover:translate-x-1 transition-all duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
                @endforeach
            </div>

        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    // ── Most Popular list items: staggered slide-in from right ──
    (function () {
        const items = document.querySelectorAll('.popular-item');
        if (!items.length) return;
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const idx = Array.from(items).indexOf(entry.target);
                    setTimeout(() => {
                        entry.target.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
                        entry.target.style.opacity   = '1';
                        entry.target.style.transform = 'translateX(0)';
                    }, idx * 80);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        items.forEach(el => obs.observe(el));
    })();

    // ── Latest Content section parallax ──
    (function () {
        const section = document.getElementById('content-section');
        const bg      = document.getElementById('content-parallax-bg');
        if (!section || !bg) return;

        function onScroll() {
            const rect   = section.getBoundingClientRect();
            const center = rect.top + rect.height / 2 - window.innerHeight / 2;
            bg.style.transform = 'translateY(' + (center * 0.12).toFixed(2) + 'px)';
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
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
