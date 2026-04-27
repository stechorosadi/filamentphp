@extends('layouts.front')

@section('seo')
<title>{{ $content->title }} — {{ config('app.name') }}</title>
<meta name="description" content="{{ $content->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($content->content), 160) }}">
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
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $content->title }}">
<meta name="twitter:description" content="{{ $content->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($content->content), 160) }}">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

@php
    $nameParts = explode(' ', trim($content->user->name));
    $initials  = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');
    $wordCount = str_word_count(strip_tags($content->content));
    $readTime  = max(1, (int) ceil($wordCount / 200));
@endphp

<main class="bg-[#FFF8D4] dark:bg-[#4B2E2B] pt-20 pb-16">
<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="mb-8 flex items-center gap-2 text-sm text-[#8C6040] dark:text-[#C4A080]">
        <a href="{{ route('home') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Home</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @if($content->category)
        <a href="{{ route('category.show', $content->category->slug) }}"
           class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">{{ $content->category->name }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @endif
        <span class="truncate text-[#5C3A1E] dark:text-[#E8C9A8]">{{ \Illuminate\Support\Str::limit($content->title, 50) }}</span>
    </nav>

    {{-- Title --}}
    <h1 class="mb-5 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-[#2C1A0E] dark:text-[#FFF8D4] leading-tight">
        {{ $content->title }}
    </h1>

    {{-- Badges row --}}
    <div class="mb-8 flex flex-wrap gap-2">
        @if($content->classification)
        <span class="rounded-full border border-[#C8B870] dark:border-[#8C5A3C] bg-[#EDE5A8] dark:bg-[#5C3835] px-3 py-1 text-xs font-semibold text-amber-700 dark:text-amber-400">
            {{ $content->classification->name }}
        </span>
        @endif
        @if($content->category)
        <span class="rounded-full bg-amber-600 px-3 py-1 text-xs font-semibold text-white">
            {{ $content->category->name }}
        </span>
        @endif
        @foreach($content->tags as $tag)
        <span class="rounded-full border border-[#DDD090] dark:border-[#6B4540] px-3 py-1 text-xs font-medium text-[#5C3A1E] dark:text-[#E8C9A8]">
            {{ $tag->name }}
        </span>
        @endforeach
    </div>

    {{-- Full-width header image --}}
    @if($content->header_image)
    <div class="mb-10 overflow-hidden rounded-2xl shadow-md">
        <img src="{{ asset('storage/' . $content->header_image) }}"
             alt="{{ $content->title }}"
             fetchpriority="high"
             class="w-full aspect-16/7 object-cover">
    </div>
    @endif

    {{-- ── TWO-COLUMN LAYOUT ── --}}
    <div class="flex flex-col md:flex-row gap-10 lg:gap-14">

        {{-- LEFT SIDEBAR --}}
        <aside class="shrink-0 md:w-44">
            {{-- Mobile: horizontal bar | Desktop: sticky vertical --}}
            <div class="flex flex-row flex-wrap gap-6 md:flex-col md:gap-8 md:sticky md:top-24">

                {{-- Contributor --}}
                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-[#8C6040] dark:text-[#C4A080]">
                        Contributor
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-amber-600 dark:bg-[#8C5A3C] flex items-center justify-center text-sm font-bold text-white select-none">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#2C1A0E] dark:text-[#FFF8D4] truncate">
                                {{ $content->user->name }}
                            </p>
                            <p class="text-xs text-[#8C6040] dark:text-[#C4A080]">
                                {{ $content->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Reading time --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[#8C6040] dark:text-[#C4A080]">
                        Reading Time
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[#5C3A1E] dark:text-[#E8C9A8]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-500 dark:text-amber-400 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span>{{ $readTime }} {{ Str::plural('Minute', $readTime) }}</span>
                    </div>
                </div>

                {{-- Views --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[#8C6040] dark:text-[#C4A080]">
                        Views
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[#5C3A1E] dark:text-[#E8C9A8]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-500 dark:text-amber-400 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <span>{{ number_format($content->views) }}</span>
                    </div>
                </div>

                {{-- Published date --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[#8C6040] dark:text-[#C4A080]">
                        Published
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[#5C3A1E] dark:text-[#E8C9A8]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-amber-500 dark:text-amber-400 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                        <span>{{ $content->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

            </div>
        </aside>

        {{-- RIGHT: MAIN CONTENT --}}
        <article class="flex-1 min-w-0">

            {{-- Excerpt --}}
            @if($content->excerpt)
            <p class="mb-8 text-lg leading-relaxed font-medium text-[#5C3A1E] dark:text-[#E8C9A8] border-l-4 border-amber-500 pl-5">
                {{ $content->excerpt }}
            </p>
            @endif

            {{-- Rich content --}}
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
                    <iframe src="{{ $embedUrl }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
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
                             loading="lazy"
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

        </article>
    </div>

    {{-- Related articles --}}
    @if($relatedContents->isNotEmpty())
    <div class="mt-16 pt-10 border-t border-[#DDD090] dark:border-[#6B4540]">
        <h3 class="mb-6 text-xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">You Might Also Like</h3>
        <div class="grid gap-5 sm:grid-cols-3">
            @foreach($relatedContents as $related)
            <a href="{{ route('content.show', $related->slug) }}"
               class="group flex flex-col rounded-2xl overflow-hidden bg-[#FFFEF0] dark:bg-[#5C3835] border border-[#DDD090] dark:border-[#6B4540] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="relative aspect-video overflow-hidden bg-[#EDE5A8] dark:bg-[#6B4540]">
                    <img src="{{ asset("storage/{$related->header_image}") }}"
                         alt="{{ $related->title }}"
                         loading="lazy"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($related->category)
                    <span class="absolute top-2 left-2 rounded-full bg-amber-600/90 px-2.5 py-0.5 text-xs font-semibold text-white">
                        {{ $related->category->name }}
                    </span>
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h4 class="mb-2 text-sm font-bold text-[#2C1A0E] dark:text-[#FFF8D4] line-clamp-2 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                        {{ $related->title }}
                    </h4>
                    <div class="mt-auto flex items-center justify-between text-xs text-[#8C6040] dark:text-[#C4A080]">
                        <span>{{ $related->created_at->format('M d, Y') }}</span>
                        <span class="inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            {{ number_format($related->views) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Back link --}}
    <div class="mt-10 pt-8 border-t border-[#DDD090] dark:border-[#6B4540]">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[#5C3A1E] dark:text-[#E8C9A8] hover:text-amber-700 dark:hover:text-amber-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to all articles
        </a>
    </div>

</div>
</main>

@endsection
