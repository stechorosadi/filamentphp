@extends('layouts.front')

@section('seo')
<title>Archive — {{ $siteSetting->site_title }}</title>
<meta name="description" content="Browse our archived articles and historical content on {{ $siteSetting->site_title }}.">
<meta property="og:title" content="Archive — {{ $siteSetting->site_title }}">
<meta property="og:description" content="Browse our archived articles and historical content.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="noindex, follow">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ── HEADER ── --}}
<section class="relative overflow-hidden bg-(--dark-section) pt-40 pb-16">
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-[var(--accent)]/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-(--accent)/30 bg-(--accent)/15">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-(--accent-on-dark)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
            </div>
        </div>

        <p class="mb-2 text-sm font-medium uppercase tracking-widest text-[var(--accent)]">{{ __('ui.historical_content') }}</p>
        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-(--on-dark) leading-tight">
            {{ __('ui.archive') }}
        </h1>
        <p class="mb-8 text-base leading-relaxed text-(--on-dark)/70 max-w-2xl mx-auto">
            {{ __('ui.archive_subtitle') }}
        </p>

        {{-- Stats + back link --}}
        <div class="flex flex-wrap items-center justify-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-(--accent)/20 border border-(--accent)/30 px-4 py-1.5 text-sm font-semibold text-(--accent-on-dark)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                {{ $contents->total() }} {{ trans_choice('ui.article_label', $contents->total()) }}
            </span>
            <a href="{{ lroute('home') }}"
               class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-sm text-(--accent-on-dark) hover:border-(--accent)/40 hover:bg-white/15 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                {{ __('ui.back') }}
            </a>
        </div>

    </div>
</section>

{{-- ── CONTENT GRID ── --}}
<section class="bg-[var(--bg-primary)] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if($contents->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-[var(--accent-dim)] dark:bg-[var(--bg-card)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10 text-(--accent)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
            </div>
            <h2 class="mb-3 text-2xl font-bold text-[var(--text-primary)]">{{ __('ui.no_archived_articles') }}</h2>
            <p class="mb-8 max-w-md text-[var(--accent)]">{{ __('ui.no_archived_articles_desc') }}</p>
            <a href="{{ lroute('home') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                {{ __('ui.back') }}
            </a>
        </div>

        @else

        {{-- Result count --}}
        <div class="mb-8">
            <p class="text-sm text-[var(--accent)]">
                {{ __('ui.showing') }} <span class="font-semibold text-(--text-primary)">{{ $contents->firstItem() }}–{{ $contents->lastItem() }}</span>
                {{ __('ui.of') }} <span class="font-semibold text-(--text-primary)">{{ $contents->total() }}</span>
                {{ trans_choice('ui.archived_article_label', $contents->total()) }}
            </p>
        </div>

        {{-- Article grid --}}
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($contents as $content)
            <article class="group flex flex-col rounded-2xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border)] shadow-sm hover:shadow-xl dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] hover:-translate-y-1 transition-all duration-300">

                {{-- Image --}}
                <div class="relative aspect-video overflow-hidden bg-[var(--accent-dim)]">
                    <img src="{{ asset("storage/{$content->header_image}") }}"
                         alt="{{ $content->title }}"
                         loading="lazy"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                    {{-- Pills overlay --}}
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        @if($content->classification)
                        <span class="rounded-full bg-(--dark-section)/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-(--on-dark)">
                            {{ $content->classification->name }}
                        </span>
                        @endif
                        @if($content->category)
                        <span class="rounded-full bg-[var(--accent)]/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        @endif
                        <span class="rounded-full bg-gray-500/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">{{ __('ui.archived_badge') }}</span>
                    </div>
                </div>

                {{-- Meta strip --}}
                <div class="flex items-center gap-3 px-5 py-2.5 text-xs text-(--accent) border-b border-(--border) bg-(--bg-alt)">
                    <span class="inline-flex items-center gap-1.5 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-[var(--accent)]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                        {{ ($content->article_date ?? $content->created_at)->format('M d, Y') }}
                    </span>
                    @if($content->user)
                    <span class="w-px h-3 bg-(--accent)/50 shrink-0"></span>
                    <span class="inline-flex items-center gap-1.5 min-w-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5 text-[var(--accent)] shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        <span class="truncate">{{ $content->user->name }}</span>
                    </span>
                    @endif
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
                    <h3 class="mb-2 text-lg font-bold text-[var(--text-primary)] line-clamp-2 group-hover:text-[var(--text-muted)] dark:group-hover:text-(--accent) transition-colors duration-200">
                        {{ $content->title }}
                    </h3>
                    @if($content->excerpt)
                    <p class="mb-4 text-sm leading-relaxed text-[var(--accent)] line-clamp-3 flex-1">
                        {{ $content->excerpt }}
                    </p>
                    @endif
                    <a href="{{ lroute('content.show', [$content->slug]) }}"
                       class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-[var(--text-muted)] dark:text-[var(--accent)] hover:text-[var(--accent)] dark:hover:text-(--accent) transition-colors">
                        {{ __('ui.read_more') }}
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

@endsection
