@extends('layouts.front')

@section('seo')
<title>{{ $classification->name }} — {{ config('app.name') }}</title>
<meta name="description" content="Browse all articles classified as {{ $classification->name }} on {{ config('app.name') }}.">
<meta property="og:title" content="{{ $classification->name }} — {{ config('app.name') }}">
<meta property="og:description" content="Browse all articles classified as {{ $classification->name }}.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
@if($classification->image)
<meta property="og:image" content="{{ asset('storage/' . $classification->image) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ── CLASSIFICATION HEADER ── --}}
<section class="relative overflow-hidden bg-[#4B2E2B] dark:bg-[#2E1A18] pt-28 pb-16">
    {{-- Grid texture --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-amber-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[#8C5A3C]/25 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Classification image --}}
        @if($classification->image)
        <div class="flex justify-center mb-6">
            <div class="h-20 w-20 overflow-hidden rounded-2xl shadow-lg">
                <img src="{{ asset('storage/' . $classification->image) }}"
                     alt="{{ $classification->name }}"
                     class="h-full w-full object-cover">
            </div>
        </div>
        @endif

        {{-- Name --}}
        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-[#FFF8D4] leading-tight">
            {{ $classification->name }}
        </h1>

        {{-- Stats row --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-600/20 border border-amber-600/30 px-4 py-1.5 text-sm font-semibold text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                {{ $contents->total() }} {{ Str::plural('article', $contents->total()) }}
            </span>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/8 px-4 py-1.5 text-sm text-[#C4A080] hover:text-amber-400 hover:border-amber-600/40 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to home
            </a>
        </div>

    </div>
</section>

{{-- ── CONTENT GRID ── --}}
<section class="bg-[#FFF8D4] dark:bg-[#4B2E2B] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if($contents->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-[#EDE5A8] dark:bg-[#5C3835]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-[#C8A878] dark:text-[#6B4540]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
            </div>
            <h2 class="mb-3 text-2xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">No articles yet</h2>
            <p class="mb-8 max-w-md text-[#8C6040] dark:text-[#C4A080]">
                No published articles in <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $classification->name }}</span> yet. Check back soon.
            </p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-amber-600 dark:bg-[#8C5A3C] px-6 py-3 text-sm font-semibold text-white hover:bg-amber-700 dark:hover:bg-[#A87050] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to Home
            </a>
        </div>

        @else

        {{-- Result count --}}
        <div class="mb-8 flex items-center justify-between">
            <p class="text-sm text-[#8C6040] dark:text-[#C4A080]">
                Showing <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $contents->firstItem() }}–{{ $contents->lastItem() }}</span>
                of <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $contents->total() }}</span>
                {{ Str::plural('article', $contents->total()) }}
            </p>
        </div>

        {{-- Article grid --}}
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($contents as $content)
            <article class="group flex flex-col rounded-2xl overflow-hidden bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">

                {{-- Image --}}
                <div class="relative aspect-video overflow-hidden bg-[#EDE5A8] dark:bg-[#6B4540]">
                    <img src="{{ asset("storage/{$content->header_image}") }}"
                         alt="{{ $content->title }}"
                         loading="lazy"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    {{-- Pills overlay --}}
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        <span class="rounded-full bg-[#5C3A1E]/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $classification->name }}
                        </span>
                        @if($content->category)
                        <span class="rounded-full bg-amber-600/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Meta strip --}}
                <div class="flex items-center gap-3 px-5 py-2.5 text-xs text-[#8C6040] dark:text-[#C4A080] border-b border-[#DDD090] dark:border-[#6B4540] bg-[#FFFDF0] dark:bg-[#4B3030]">
                    <span class="inline-flex items-center gap-1.5 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-amber-500 dark:text-amber-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                        {{ $content->created_at->format('M d, Y') }}
                    </span>
                    @if($content->user)
                    <span class="w-px h-3 bg-[#DDD090] dark:bg-[#6B4540] shrink-0"></span>
                    <span class="inline-flex items-center gap-1.5 min-w-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-amber-500 dark:text-amber-400 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        <span class="truncate">{{ $content->user->name }}</span>
                    </span>
                    @endif
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

        {{-- Pagination --}}
        @if($contents->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $contents->links('pagination::tailwind') }}
        </div>
        @endif
        @endif

    </div>
</section>

{{-- ── OTHER CLASSIFICATIONS ── --}}
@if($otherClassifications->isNotEmpty())
<section class="relative py-12 overflow-hidden bg-[#4B2E2B] dark:bg-[#2E1A18]">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3 mb-7">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-600/30 bg-amber-600/15">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-[#FFF8D4]">Other Classifications</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($otherClassifications as $cls)
            <a href="{{ route('classification.show', $cls->slug) }}"
               class="group flex items-center gap-3 rounded-2xl p-3
                      bg-[#5C3835]/50 backdrop-blur-sm
                      border border-[#6B4540]/60 border-l-2 border-l-amber-600/50
                      hover:bg-[#5C3835] hover:border-l-amber-400
                      hover:shadow-[0_8px_32px_rgba(0,0,0,0.35)]
                      hover:-translate-y-1 transition-all duration-300">

                {{-- Icon --}}
                <div class="relative shrink-0">
                    <div class="absolute inset-0 rounded-xl bg-amber-500/20 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500 pointer-events-none"></div>
                    <div class="relative h-10 w-10 rounded-xl bg-[#6B4540] flex items-center justify-center group-hover:bg-amber-600 transition-colors duration-300">
                        @if($cls->icon)
                            {!! svg($cls->icon, '', ['style' => 'width:1rem;height:1rem;color:#fbbf24'])->toHtml() !!}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1rem;height:1rem;color:#fbbf24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Text --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs font-bold text-[#FFF8D4] leading-tight truncate">{{ $cls->name }}</h3>
                    <span class="mt-1 inline-flex items-center rounded-full bg-amber-600/20 border border-amber-600/30 px-2 py-0.5 text-xs font-semibold text-amber-400">
                        {{ $cls->contents_count }}
                    </span>
                </div>

                {{-- Image thumbnail --}}
                @if($cls->image)
                <div class="w-12 h-10 shrink-0 overflow-hidden rounded-lg border border-[#8C5A3C]/40 group-hover:border-amber-600/50 transition-colors duration-300">
                    <img src="{{ asset("storage/{$cls->image}") }}" alt="{{ $cls->name }}"
                         loading="lazy"
                         class="h-full w-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300">
                </div>
                @else
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
