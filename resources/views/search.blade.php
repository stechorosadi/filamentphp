@extends('layouts.front')

@section('seo')
<title>{{ $query ? "Search: {$query}" : 'Search' }} — {{ $siteSetting->site_title }}</title>
<meta name="description" content="{{ $query ? "Search results for \"{$query}\" on " . $siteSetting->site_title : 'Search ' . $siteSetting->site_title }}">
<meta name="robots" content="noindex, follow">
@endsection

@section('content')

{{-- ── SEARCH HEADER ── --}}
<section class="relative overflow-hidden bg-[#132A13] dark:bg-[#0a1a0a] pt-40 pb-16">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-[var(--accent)]/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>
    {{-- Grid overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px]"></div>

    <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Label + title --}}
        @if($query)
        <p class="mb-2 text-sm font-medium uppercase tracking-widest text-[var(--accent)]">Search results for</p>
        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-[#ECF39E] leading-tight">
            "{{ $query }}"
        </h1>
        <p class="mb-10 text-[#90A955]">
            <span class="font-semibold text-[#90A955]">{{ $results->total() }}</span>
            {{ Str::plural('result', $results->total()) }} found
        </p>
        @else
        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#4F772D]/30 bg-[var(--accent)]/15">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-[#90A955]">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
        </div>
        <h1 class="mb-3 text-3xl sm:text-4xl font-bold text-[#ECF39E]">Search Articles</h1>
        <p class="mb-10 text-[#90A955]">Find articles, topics, categories, and more.</p>
        @endif

        {{-- Search bar --}}
        <form method="GET" action="{{ route('search') }}">
            <div class="flex overflow-hidden rounded-2xl border-2 border-[#4F772D]/30 bg-white/8 backdrop-blur-sm focus-within:border-[#4F772D]/60 transition-all duration-200 shadow-[0_8px_32px_rgba(0,0,0,0.4)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="ml-5 h-5 w-5 shrink-0 self-center text-[#90A955]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="q" value="{{ $query }}" maxlength="100"
                       placeholder="Search articles, topics, or keywords…"
                       autofocus
                       class="flex-1 bg-transparent px-4 py-4 text-base text-[#ECF39E] placeholder-[#4F772D] focus:outline-none">
                @if($query)
                <a href="{{ route('search') }}"
                   class="self-center mr-2 rounded-lg p-2 text-[var(--accent)] hover:text-[#90A955] transition-colors"
                   title="Clear search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
                <button type="submit"
                        class="m-2 rounded-xl bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-[var(--accent)] transition-colors duration-200 shrink-0">
                    Search
                </button>
            </div>
        </form>

    </div>
</section>

{{-- ── RESULTS ── --}}
<section class="bg-[var(--bg-primary)] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if($results->isEmpty())
        {{-- ── EMPTY STATE ── --}}
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-[var(--accent-dim)] dark:bg-[var(--bg-card)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#90A955] dark:text-[#2a5c2a]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <h2 class="mb-3 text-2xl font-bold text-[var(--text-primary)]">No results found</h2>
            <p class="mb-8 max-w-md text-[var(--accent)]">
                We couldn't find anything matching <span class="font-semibold text-[var(--text-primary)]">"{{ $query }}"</span>.
                Try different keywords or browse a category below.
            </p>

            {{-- Suggestions --}}
            @if($suggestions->isNotEmpty())
            <div class="mb-8">
                <p class="mb-4 text-sm font-medium text-[var(--accent)] uppercase tracking-wider">Browse Categories</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($suggestions as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="rounded-full border border-[var(--accent-dim)] dark:border-[var(--accent)] bg-[var(--bg-card)] px-4 py-2 text-sm font-medium text-[var(--text-muted)] hover:bg-[var(--accent)] hover:border-[#4F772D] hover:text-white transition-all duration-200">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[var(--accent)] dark:bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white hover:bg-[var(--accent)] dark:hover:bg-[#6B9A38] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to Home
            </a>
        </div>

        @else
        {{-- ── RESULTS GRID ── --}}
        <div class="mb-8 flex items-center justify-between">
            <p class="text-sm text-[var(--accent)]">
                Showing <span class="font-semibold text-[var(--text-primary)]">{{ $results->firstItem() }}–{{ $results->lastItem() }}</span>
                of <span class="font-semibold text-[var(--text-primary)]">{{ $results->total() }}</span> results
            </p>
            <a href="{{ route('home') }}" class="text-sm text-[var(--accent)] hover:text-[var(--text-muted)] dark:hover:text-[#90A955] transition-colors">
                ← Back to Home
            </a>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($results as $content)
            <article class="group flex flex-col rounded-2xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border)] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                {{-- Image --}}
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
                        @if($query)
                            {!! preg_replace('/(' . preg_quote(e($query), '/') . ')/i', '<mark class="bg-amber-200 dark:bg-amber-800/60 text-inherit rounded px-0.5">$1</mark>', e($content->title)) !!}
                        @else
                            {{ $content->title }}
                        @endif
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

        {{-- Pagination --}}
        @if($results->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $results->appends(['q' => $query])->links('pagination::tailwind') }}
        </div>
        @endif
        @endif

    </div>
</section>

@endsection
