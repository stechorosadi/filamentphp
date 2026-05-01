@extends('layouts.front')

@section('seo')
<title>{{ $content->title }} — {{ $siteSetting->site_title }}</title>
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

<main class="bg-[var(--bg-primary)]">

{{-- ── HERO HEADER (breadcrumb → image) ── --}}
<section class="relative bg-[var(--bg-primary)] pt-40 pb-10 overflow-hidden">
    {{-- Square grid background --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000010_1px,transparent_1px),linear-gradient(to_bottom,#00000010_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>
    {{-- Subtle glow --}}
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-2xl rounded-full bg-[var(--accent)]/10 dark:bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="mb-8 flex items-center gap-2 text-sm text-[var(--accent)]">
        <a href="{{ route('home') }}" class="hover:text-[var(--accent)] dark:hover:text-[#90A955] transition-colors">Home</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @if($content->category)
        <a href="{{ route('category.show', $content->category->slug) }}"
           class="hover:text-[var(--accent)] dark:hover:text-[#90A955] transition-colors">{{ $content->category->name }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        @endif
        <span class="truncate text-[var(--text-muted)]">{{ \Illuminate\Support\Str::limit($content->title, 50) }}</span>
    </nav>

    {{-- Title --}}
    <h1 class="mb-5 text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-[var(--text-primary)] leading-tight">
        {{ $content->title }}
    </h1>

    {{-- Badges row --}}
    <div class="mb-8 flex flex-wrap items-center gap-2">
        {{-- Left: classification + category --}}
        @if($content->classification)
        <span class="inline-flex items-center rounded-full bg-[#31572C] dark:bg-[var(--bg-card)] px-3 py-1 text-xs font-semibold text-white dark:text-[var(--text-muted)]">
            {{ $content->classification->name }}
        </span>
        @endif
        @if($content->category)
        <span class="inline-flex items-center rounded-full bg-[var(--accent)] dark:bg-[var(--accent)] px-3 py-1 text-xs font-semibold text-white dark:text-[var(--text-primary)]">
            {{ $content->category->name }}
        </span>
        @endif

        {{-- Right: tags --}}
        @if($content->tags->isNotEmpty())
        <div class="ml-auto flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-[var(--text-muted)]">Tags:</span>
            @foreach($content->tags as $tag)
            <span class="rounded-full border border-[var(--border)] px-3 py-1 text-xs font-medium text-[var(--text-muted)]">
                {{ $tag->name }}
            </span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Full-width header image --}}
    @if($content->header_image)
    <div class="overflow-hidden rounded-2xl shadow-md">
        <img src="{{ asset('storage/' . $content->header_image) }}"
             alt="{{ $content->title }}"
             fetchpriority="high"
             class="w-full aspect-16/9 object-cover">
    </div>
    @endif

    </div>{{-- /max-w-5xl --}}
</section>

<div class="h-px bg-linear-to-r from-transparent via-[var(--accent)] to-transparent opacity-30"></div>

<section class="bg-white/60 dark:bg-[var(--bg-primary)]">
<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 pt-10">

    {{-- ── TWO-COLUMN LAYOUT ── --}}
    <div class="flex flex-col md:flex-row gap-10 lg:gap-14">

        {{-- LEFT SIDEBAR --}}
        <aside class="shrink-0 md:w-44">
            {{-- Mobile: horizontal bar | Desktop: sticky vertical --}}
            <div class="flex flex-row flex-wrap gap-6 md:flex-col md:gap-8 md:sticky md:top-24">

                {{-- Contributor --}}
                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-[var(--accent)]">
                        Contributor
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-[var(--accent)] dark:bg-[var(--accent)] flex items-center justify-center text-sm font-bold text-white select-none">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[var(--text-primary)] truncate">
                                {{ $content->user->name }}
                            </p>
                            <p class="text-xs text-[var(--accent)]">
                                {{ $content->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Reading time --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[var(--accent)]">
                        Reading Time
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[var(--accent)] shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span>{{ $readTime }} {{ Str::plural('Minute', $readTime) }}</span>
                    </div>
                </div>

                {{-- Views --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[var(--accent)]">
                        Views
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[var(--accent)] shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <span>{{ number_format($content->views) }}</span>
                    </div>
                </div>

                {{-- Published date --}}
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[var(--accent)]">
                        Published
                    </p>
                    <div class="flex items-center gap-2 text-sm text-[var(--text-muted)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-[var(--accent)] shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                        <span>{{ $content->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Share --}}
                <div x-data="{ copied: false }">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-(--accent)">Share</p>
                    <div class="flex flex-wrap gap-2">

                        {{-- Twitter / X --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($content->title) }}&url={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" title="Share on X"
                           class="flex h-8 w-8 items-center justify-center rounded-lg border border-(--border) text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" title="Share on Facebook"
                           class="flex h-8 w-8 items-center justify-center rounded-lg border border-(--border) text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($content->title . ' ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" title="Share on WhatsApp"
                           class="flex h-8 w-8 items-center justify-center rounded-lg border border-(--border) text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>

                        {{-- Copy link --}}
                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                :title="copied ? 'Copied!' : 'Copy link'"
                                :class="copied ? 'bg-(--accent) text-white border-(--accent)' : 'text-(--accent) border-(--border)'"
                                class="flex h-8 w-8 items-center justify-center rounded-lg border hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                            <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                            </svg>
                            <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                        </button>

                    </div>
                </div>

                {{-- Export PDF --}}
                <div>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-(--accent)">Export</p>
                    <a href="{{ route('content.pdf', $content->slug) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 rounded-lg border border-(--border) px-3 py-2 text-xs font-semibold text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Export to PDF
                    </a>
                </div>

            </div>
        </aside>

        {{-- RIGHT: MAIN CONTENT --}}
        <article class="flex-1 min-w-0">

            {{-- Excerpt --}}
            @if($content->excerpt)
            <p class="mb-8 text-lg leading-relaxed font-medium text-[var(--text-muted)] border-l-4 border-[#4F772D] pl-5">
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
                <h3 class="mb-4 text-lg font-bold text-[var(--text-primary)]">Video</h3>
                <div class="aspect-video overflow-hidden rounded-2xl shadow-lg">
                    <iframe src="{{ $embedUrl }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            @endif
            @endif

            {{-- Image attachments --}}
            @if($content->imageAttachments->isNotEmpty())
            <div class="mt-12"
                 x-data="{
                     open: false,
                     activeIndex: 0,
                     images: {{ Js::from($content->imageAttachments->map(fn ($img) => ['src' => asset('storage/' . $img->path), 'caption' => $img->caption])) }},
                     openModal(index) {
                         this.activeIndex = index;
                         this.open = true;
                         document.body.style.overflow = 'hidden';
                     },
                     closeModal() {
                         this.open = false;
                         document.body.style.overflow = '';
                     },
                     prev() { this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length; },
                     next() { this.activeIndex = (this.activeIndex + 1) % this.images.length; }
                 }"
                 @keydown.escape.window="open && closeModal()"
                 @keydown.arrow-left.window="open && prev()"
                 @keydown.arrow-right.window="open && next()">

                <h3 class="mb-5 text-lg font-bold text-[var(--text-primary)]">Gallery</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($content->imageAttachments as $index => $img)
                    <div class="group relative cursor-zoom-in overflow-hidden rounded-xl border border-[var(--border)]"
                         @click="openModal({{ $index }})">
                        <img src="{{ asset('storage/' . $img->path) }}"
                             alt="{{ $img->caption ?? 'Image' }}"
                             loading="lazy"
                             class="w-full object-cover aspect-video transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-200 group-hover:bg-black/25">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="h-8 w-8 text-white opacity-0 drop-shadow-lg transition-opacity duration-200 group-hover:opacity-100">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6"/>
                            </svg>
                        </div>
                        @if($img->caption)
                        <p class="px-3 py-2 text-xs text-[var(--accent)]">{{ $img->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Lightbox Modal --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
                     @click.self="closeModal()"
                     style="display:none">

                    {{-- Close --}}
                    <button @click="closeModal()"
                            class="absolute right-4 top-4 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/25"
                            aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Counter --}}
                    <div x-show="images.length > 1"
                         class="absolute left-1/2 top-4 -translate-x-1/2 select-none text-sm text-white/60">
                        <span x-text="activeIndex + 1"></span>&thinsp;/&thinsp;<span x-text="images.length"></span>
                    </div>

                    {{-- Prev --}}
                    <button x-show="images.length > 1"
                            @click.stop="prev()"
                            class="absolute left-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/25 sm:left-5"
                            aria-label="Previous image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                        </svg>
                    </button>

                    {{-- Image + caption --}}
                    <div class="mx-16 flex max-w-4xl w-full flex-col items-center gap-3 sm:mx-20">
                        <img :src="images[activeIndex]?.src"
                             :alt="images[activeIndex]?.caption || 'Image'"
                             class="max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl">
                        <p x-show="images[activeIndex]?.caption"
                           x-text="images[activeIndex]?.caption"
                           class="text-center text-sm text-white/70"></p>
                    </div>

                    {{-- Next --}}
                    <button x-show="images.length > 1"
                            @click.stop="next()"
                            class="absolute right-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/25 sm:right-5"
                            aria-label="Next image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>

                </div>
            </div>
            @endif

            {{-- File attachments --}}
            @if($content->fileAttachments->isNotEmpty())
            <div class="mt-12">
                <h3 class="mb-5 text-lg font-bold text-[var(--text-primary)]">Downloads</h3>
                <div class="flex flex-col gap-3">
                    @foreach($content->fileAttachments as $file)
                    <a href="{{ asset('storage/' . $file->path) }}" target="_blank" download
                       class="flex items-center gap-4 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] px-5 py-4 hover:border-[#4F772D] dark:hover:border-[#4F772D] transition-colors group">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[var(--accent-dim)] text-xs font-bold text-[var(--text-muted)] uppercase">
                            {{ strtoupper(pathinfo($file->path, PATHINFO_EXTENSION)) }}
                        </span>
                        <span class="flex-1 font-medium text-[var(--text-primary)] group-hover:text-[var(--text-muted)] dark:group-hover:text-[#90A955] transition-colors">
                            {{ $file->original_name }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--accent)] group-hover:text-[var(--accent)] transition-colors">
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
                <h3 class="mb-5 text-lg font-bold text-[var(--text-primary)]">Related Links</h3>
                <div class="flex flex-col gap-3">
                    @foreach($content->linkAttachments as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-4 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] px-5 py-4 hover:border-[#4F772D] dark:hover:border-[#4F772D] transition-colors group">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#e8f9a0] dark:bg-[var(--bg-alt)]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--accent)]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                            </svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-[var(--text-primary)] group-hover:text-[var(--text-muted)] dark:group-hover:text-[#90A955] transition-colors">
                                {{ $link->label ?: $link->url }}
                            </p>
                            @if($link->label)
                            <p class="text-xs text-[var(--accent)] truncate">{{ $link->url }}</p>
                            @endif
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0 text-[var(--accent)]">
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
    <div class="mt-16 pt-10 border-t border-[var(--border)]">
        <h3 class="mb-6 text-xl font-bold text-[var(--text-primary)]">You Might Also Like</h3>
        <div class="grid gap-5 sm:grid-cols-3">
            @foreach($relatedContents as $related)
            <a href="{{ route('content.show', $related->slug) }}"
               class="group flex flex-col rounded-2xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="relative aspect-video overflow-hidden bg-[var(--accent-dim)]">
                    <img src="{{ asset("storage/{$related->header_image}") }}"
                         alt="{{ $related->title }}"
                         loading="lazy"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($related->category)
                    <span class="absolute top-2 left-2 rounded-full bg-[var(--accent)]/90 px-2.5 py-0.5 text-xs font-semibold text-white">
                        {{ $related->category->name }}
                    </span>
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h4 class="mb-2 text-sm font-bold text-[var(--text-primary)] line-clamp-2 group-hover:text-[var(--text-muted)] dark:group-hover:text-[#90A955] transition-colors">
                        {{ $related->title }}
                    </h4>
                    <div class="mt-auto flex items-center justify-between text-xs text-[var(--accent)]">
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
    <div class="mt-10 pt-8 pb-16 border-t border-[var(--border)]">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--text-muted)] hover:text-[var(--text-muted)] dark:hover:text-[#90A955] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to all articles
        </a>
    </div>

</div>
</section>
</main>

@endsection
