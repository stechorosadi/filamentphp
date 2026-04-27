@extends('layouts.front')

@section('seo')
<title>{{ $query ? "Search: {$query}" : 'Search' }} — {{ config('app.name') }}</title>
<meta name="description" content="{{ $query ? "Search results for \"{$query}\" on " . config('app.name') : 'Search ' . config('app.name') }}">
<meta name="robots" content="noindex, follow">
@endsection

@section('content')

{{-- ── SEARCH HEADER ── --}}
<section class="relative overflow-hidden bg-[#2C1A0E] dark:bg-[#1E1008] pt-28 pb-16">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-amber-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[#8C5A3C]/20 blur-3xl pointer-events-none"></div>
    {{-- Grid overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px]"></div>

    <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Label + title --}}
        @if($query)
        <p class="mb-2 text-sm font-medium uppercase tracking-widest text-[#8C6040]">Search results for</p>
        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-[#FFF8D4] leading-tight">
            "{{ $query }}"
        </h1>
        <p class="mb-10 text-[#C4A080]">
            <span class="font-semibold text-amber-400">{{ $results->total() }}</span>
            {{ Str::plural('result', $results->total()) }} found
        </p>
        @else
        <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-amber-500/30 bg-amber-500/15">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-amber-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
        </div>
        <h1 class="mb-3 text-3xl sm:text-4xl font-bold text-[#FFF8D4]">Search Articles</h1>
        <p class="mb-10 text-[#C4A080]">Find articles, topics, categories, and more.</p>
        @endif

        {{-- Search bar --}}
        <form method="GET" action="{{ route('search') }}">
            <div class="flex overflow-hidden rounded-2xl border-2 border-amber-600/30 bg-white/8 backdrop-blur-sm focus-within:border-amber-500/60 transition-all duration-200 shadow-[0_8px_32px_rgba(0,0,0,0.4)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="ml-5 h-5 w-5 shrink-0 self-center text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="q" value="{{ $query }}"
                       placeholder="Search articles, topics, or keywords…"
                       autofocus
                       class="flex-1 bg-transparent px-4 py-4 text-base text-[#FFF8D4] placeholder-[#8C6040] focus:outline-none">
                @if($query)
                <a href="{{ route('search') }}"
                   class="self-center mr-2 rounded-lg p-2 text-[#8C6040] hover:text-[#C4A080] transition-colors"
                   title="Clear search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
                <button type="submit"
                        class="m-2 rounded-xl bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-amber-500 transition-colors duration-200 shrink-0">
                    Search
                </button>
            </div>
        </form>

    </div>
</section>

{{-- ── RESULTS ── --}}
<section class="bg-[#FFF8D4] dark:bg-[#4B2E2B] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if($results->isEmpty())
        {{-- ── EMPTY STATE ── --}}
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-[#EDE5A8] dark:bg-[#5C3835]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#C8A878] dark:text-[#6B4540]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <h2 class="mb-3 text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">No results found</h2>
            <p class="mb-8 max-w-md text-[#8C6040] dark:text-[#C4A080]">
                We couldn't find anything matching <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">"{{ $query }}"</span>.
                Try different keywords or browse a category below.
            </p>

            {{-- Suggestions --}}
            @if($suggestions->isNotEmpty())
            <div class="mb-8">
                <p class="mb-4 text-sm font-medium text-[#8C6040] dark:text-[#C4A080] uppercase tracking-wider">Browse Categories</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($suggestions as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#FFFEF0] dark:bg-[#5C3835] px-4 py-2 text-sm font-medium text-[#5C3A1E] dark:text-[#E8C9A8] hover:bg-amber-600 hover:border-amber-600 hover:text-white transition-all duration-200">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-6 py-3 text-sm font-semibold text-white hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to Home
            </a>
        </div>

        @else
        {{-- ── RESULTS GRID ── --}}
        <div class="mb-8 flex items-center justify-between">
            <p class="text-sm text-[#8C6040] dark:text-[#C4A080]">
                Showing <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $results->firstItem() }}–{{ $results->lastItem() }}</span>
                of <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $results->total() }}</span> results
            </p>
            <a href="{{ route('home') }}" class="text-sm text-[#8C6040] dark:text-[#C4A080] hover:text-amber-700 dark:hover:text-amber-400 transition-colors">
                ← Back to Home
            </a>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($results as $content)
            <article class="group flex flex-col rounded-2xl overflow-hidden bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">
                {{-- Image --}}
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
                        @if($query)
                            {!! preg_replace('/(' . preg_quote(e($query), '/') . ')/i', '<mark class="bg-amber-200 dark:bg-amber-800/60 text-inherit rounded px-0.5">$1</mark>', e($content->title)) !!}
                        @else
                            {{ $content->title }}
                        @endif
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
