@extends('layouts.front')

@section('seo')
<title>{{ __('ui.about_page_title') }} — {{ $siteSetting->site_title }}</title>
<meta name="description" content="{{ __('ui.about_meta_desc') }}">
<meta property="og:title" content="{{ __('ui.about_page_title') }} — {{ $siteSetting->site_title }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 1 · HERO (full-width, immersive)           --}}
{{-- ══════════════════════════════════════════════════ --}}
<section id="about-hero" class="relative min-h-[85vh] flex flex-col justify-center overflow-hidden bg-[var(--bg-primary)]">

    {{-- Background grid --}}
    <div id="about-grid" class="absolute inset-0 bg-[linear-gradient(to_right,#00000008_1px,transparent_1px),linear-gradient(to_bottom,#00000008_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-[size:48px_48px] pointer-events-none"></div>

    {{-- Accent glow blobs --}}
    <div id="about-blob-1" class="absolute left-1/4 top-1/3 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[var(--accent)]/50 pointer-events-none will-change-transform" style="filter:blur(130px)"></div>
    <div id="about-blob-2" class="absolute right-1/4 bottom-1/4 h-[450px] w-[450px] rounded-full bg-[var(--accent)]/40 pointer-events-none will-change-transform" style="filter:blur(110px)"></div>
    <div id="about-blob-3" class="absolute left-1/2 top-1/2 h-[350px] w-[350px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[var(--accent)]/30 pointer-events-none will-change-transform opacity-0" style="filter:blur(100px)"></div>

    {{-- Floating assets --}}
    <img id="aset-1" src="{{ asset('storage/aset/aset-documents.png') }}" alt=""
         class="absolute hidden lg:block w-24 opacity-20 pointer-events-none will-change-transform drop-shadow-lg"
         style="left:15%; top:24%; transform: rotate(-12deg)">
    <img id="aset-2" src="{{ asset('storage/aset/aset-laptop.png') }}" alt=""
         class="absolute hidden lg:block w-28 opacity-20 pointer-events-none will-change-transform drop-shadow-lg"
         style="right:14%; top:20%; transform: rotate(8deg)">
    <img id="aset-3" src="{{ asset('storage/aset/aset-organized.png') }}" alt=""
         class="absolute hidden lg:block w-20 opacity-20 pointer-events-none will-change-transform drop-shadow-lg"
         style="left:18%; bottom:24%; transform: rotate(10deg)">
    <img id="aset-4" src="{{ asset('storage/aset/aset-search.png') }}" alt=""
         class="absolute hidden lg:block w-24 opacity-20 pointer-events-none will-change-transform drop-shadow-lg"
         style="right:16%; bottom:24%; transform: rotate(-8deg)">

    <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 pt-36 pb-20">
        <div class="max-w-4xl mx-auto text-center">

            {{-- Eyebrow label --}}
            <div class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--bg-card)] px-4 py-1.5 mb-8 shadow-sm"
                 style="animation: fadeUp 0.5s ease both;">
                <span class="h-2 w-2 rounded-full bg-[var(--accent)] animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">
                    {{ __('ui.about_hero_label') }}
                </span>
            </div>

            {{-- Main heading --}}
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-[var(--text-primary)] leading-tight tracking-tight mb-6"
                style="animation: fadeUp 0.6s ease 0.1s both;">
                {{ $siteSetting->site_title }}
            </h1>

            {{-- Tagline --}}
            @if($siteSetting->site_tagline)
            <p class="text-lg sm:text-xl text-[var(--accent)] font-semibold mb-6"
               style="animation: fadeUp 0.6s ease 0.2s both;">
                {{ $siteSetting->site_tagline }}
            </p>
            @endif

            {{-- Description --}}
            @if($siteSetting->site_description)
            <p class="text-base sm:text-lg leading-relaxed text-[var(--text-muted)] max-w-2xl mx-auto mb-10"
               style="animation: fadeUp 0.6s ease 0.3s both;">
                {{ $siteSetting->site_description }}
            </p>
            @endif

            {{-- Social icons --}}
            @php
                $socials = array_filter([
                    'facebook'  => $siteSetting->facebook_url  ?? null,
                    'instagram' => $siteSetting->instagram_url ?? null,
                    'x'         => $siteSetting->x_url         ?? null,
                    'youtube'   => $siteSetting->youtube_url   ?? null,
                ]);
            @endphp
            @if($socials)
            <div class="flex items-center justify-center gap-3" style="animation: fadeUp 0.6s ease 0.4s both;">
                @foreach($socials as $platform => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                   aria-label="{{ ucfirst($platform) }}"
                   class="inline-flex h-11 w-11 items-center justify-center rounded-full
                          bg-[var(--bg-card)] border border-[var(--border)]
                          text-[var(--text-muted)] hover:bg-[var(--accent)] hover:text-white hover:border-[var(--accent)]
                          transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    @if($platform === 'facebook')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    @elseif($platform === 'instagram')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    @elseif($platform === 'x')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    @elseif($platform === 'youtube')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    @endif
                </a>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    {{-- Bottom fade-out into next section --}}
    <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-[var(--bg-primary)] to-transparent pointer-events-none"></div>
</section>


{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 2 · STATS (dark high-contrast strip)       --}}
{{-- ══════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-[var(--dark-section)] py-16">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:48px_48px] pointer-events-none"></div>
    <div class="absolute -top-16 right-0 h-56 w-56 rounded-full bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -left-16 h-48 w-48 rounded-full bg-[var(--accent)]/15 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-[var(--on-dark)] mb-3">
                {{ __('ui.about_stats_title') }}
            </h2>
            <p class="text-sm text-[var(--on-dark)]/60">{{ __('ui.about_stats_desc') }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-[var(--on-dark)]/10">

            {{-- Articles --}}
            <div class="flex flex-col items-center text-center px-8 py-6 sm:py-0"
                 x-data="{ count: 0, target: {{ $totalArticles }} }"
                 x-init="const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) { obs.disconnect(); let t = setInterval(() => { count < target ? count = Math.min(count + Math.max(1, Math.ceil(target / 40)), target) : clearInterval(t); }, 30); } }, { threshold: 0.3 }); obs.observe($el);">
                <p class="text-6xl lg:text-7xl font-bold text-[var(--on-dark)] mb-2 tabular-nums" x-text="count.toLocaleString()">0</p>
                <div class="h-0.5 w-10 bg-[var(--accent)] rounded-full mb-3"></div>
                <p class="text-xs font-bold uppercase tracking-widest text-[var(--on-dark)]/60">{{ __('ui.about_stat_articles') }}</p>
            </div>

            {{-- Team Members --}}
            <div class="flex flex-col items-center text-center px-8 py-6 sm:py-0"
                 x-data="{ count: 0, target: {{ $totalMembers }} }"
                 x-init="const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) { obs.disconnect(); let t = setInterval(() => { count < target ? count = Math.min(count + Math.max(1, Math.ceil(target / 40)), target) : clearInterval(t); }, 30); } }, { threshold: 0.3 }); obs.observe($el);">
                <p class="text-6xl lg:text-7xl font-bold text-[var(--accent)] mb-2 tabular-nums" x-text="count.toLocaleString()">0</p>
                <div class="h-0.5 w-10 bg-[var(--accent)] rounded-full mb-3"></div>
                <p class="text-xs font-bold uppercase tracking-widest text-[var(--on-dark)]/60">{{ __('ui.about_stat_members') }}</p>
            </div>

            {{-- Categories --}}
            <div class="flex flex-col items-center text-center px-8 py-6 sm:py-0"
                 x-data="{ count: 0, target: {{ $totalCategories }} }"
                 x-init="const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) { obs.disconnect(); let t = setInterval(() => { count < target ? count = Math.min(count + Math.max(1, Math.ceil(target / 40)), target) : clearInterval(t); }, 30); } }, { threshold: 0.3 }); obs.observe($el);">
                <p class="text-6xl lg:text-7xl font-bold text-[var(--on-dark)] mb-2 tabular-nums" x-text="count.toLocaleString()">0</p>
                <div class="h-0.5 w-10 bg-[var(--accent)] rounded-full mb-3"></div>
                <p class="text-xs font-bold uppercase tracking-widest text-[var(--on-dark)]/60">{{ __('ui.about_stat_categories') }}</p>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 3 · MISSION & VISION                       --}}
{{-- ══════════════════════════════════════════════════ --}}
<section class="relative py-20 overflow-hidden">
    {{-- Background image with overlay --}}
    <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
        <img src="{{ asset('storage/background/bg-02.webp') }}" alt=""
             class="absolute inset-x-0 w-full object-cover"
             style="height:140%; top:-30%; opacity: 0.8;">
    </div>
    <div class="absolute inset-0 bg-(--bg-primary)/75 pointer-events-none"></div>
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-[var(--accent-dim)]/10 dark:bg-[var(--accent)]/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/3 h-48 w-48 rounded-full bg-[#FFDAC4]/40 dark:bg-[var(--bg-card)]/30 blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section heading --}}
        <div class="text-center mb-14">
            <p class="text-xs font-bold uppercase tracking-widest text-[var(--accent)] mb-3">{{ __('ui.about_page_title') }}</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-[var(--text-primary)]">
                {{ $personalMember ? __('ui.about_introduction_label') : __('ui.about_vision_label') }}
                &
                {{ $personalMember ? __('ui.about_biography_label') : __('ui.about_mission_label') }}
            </h2>
            <div class="mx-auto mt-4 h-1 w-16 rounded-full bg-[var(--accent)]"></div>
        </div>

        <div class="flex flex-col gap-6">

            {{-- Vision row --}}
            <div class="group relative rounded-3xl bg-[var(--accent)] overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5">
                {{-- Decorative overlays --}}
                <div class="absolute inset-0 bg-[linear-gradient(135deg,#ffffff15_0%,transparent_55%)] pointer-events-none"></div>
                <div class="absolute -bottom-16 -right-16 h-72 w-72 rounded-full bg-white/8 blur-3xl pointer-events-none"></div>
                <div class="absolute top-0 left-1/2 h-px w-1/2 bg-white/10 pointer-events-none"></div>

                <div class="relative grid lg:grid-cols-[260px_1fr]">
                    {{-- Left label panel --}}
                    <div class="flex flex-col justify-start p-8 lg:p-10 lg:border-r border-white/15">
                        <div class="flex items-center gap-4">
                            <div class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 group-hover:bg-white/30 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="h-7 w-7 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">{{ $personalMember ? __('ui.about_introduction_label') : __('ui.about_vision_label') }}</h3>
                                <div class="mt-2 h-0.5 w-10 rounded-full bg-white/40"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Right content panel --}}
                    <div class="flex items-start p-8 lg:p-10 border-t border-white/15 lg:border-t-0">
                        <div class="prose-inverted w-full">
                            {!! $siteSetting->vision ?: '<p>'.__('ui.about_vision_placeholder').'</p>' !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mission row --}}
            <div class="group relative rounded-3xl bg-[var(--bg-card)] border border-[var(--border)] overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-0.5">
                {{-- Hover accent glow --}}
                <div class="absolute inset-0 rounded-3xl bg-[var(--accent)]/0 group-hover:bg-[var(--accent)]/3 transition-colors duration-300 pointer-events-none"></div>

                <div class="relative grid lg:grid-cols-[260px_1fr]">
                    {{-- Left label panel --}}
                    <div class="flex flex-col justify-start p-8 lg:p-10 lg:border-r border-[var(--border)]">
                        <div class="flex items-center gap-4">
                            <div class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[var(--accent)]/12 group-hover:bg-[var(--accent)]/22 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="h-7 w-7 text-[var(--accent)]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-[var(--text-primary)]">{{ $personalMember ? __('ui.about_biography_label') : __('ui.about_mission_label') }}</h3>
                                <div class="mt-2 h-0.5 w-10 rounded-full bg-[var(--accent)]"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Right content panel --}}
                    <div class="flex items-start p-8 lg:p-10 border-t border-[var(--border)] lg:border-t-0">
                        <div class="prose-themed w-full">
                            {!! $siteSetting->mission ?: '<p>'.__('ui.about_mission_placeholder').'</p>' !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.getElementById('about-hero');
    if (!hero) return;

    // Blob 1 — large slow drift across hero
    gsap.to('#about-blob-1', {
        x: 220, y: 130, duration: 11,
        repeat: -1, yoyo: true, ease: 'sine.inOut'
    });

    // Blob 2 — opposite direction, different speed
    gsap.to('#about-blob-2', {
        x: -180, y: -200, duration: 14,
        repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 3
    });

    // Blob 3 — fade in then breathe scale + drift
    gsap.to('#about-blob-3', {
        opacity: 1, duration: 1.2, ease: 'power2.out',
        onComplete() {
            gsap.to('#about-blob-3', {
                x: 100, y: -80, scale: 1.8, opacity: 0.5, duration: 9,
                repeat: -1, yoyo: true, ease: 'sine.inOut'
            });
        }
    });

    // Grid subtle opacity breathe
    gsap.to('#about-grid', {
        opacity: 0.4, duration: 6,
        repeat: -1, yoyo: true, ease: 'sine.inOut'
    });

    // Floating asset icons — each bobs at a different speed/direction
    gsap.to('#aset-1', { y: -22, rotation: '-=5', duration: 3.5, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    gsap.to('#aset-2', { y:  18, rotation: '+=6', duration: 4.2, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.8 });
    gsap.to('#aset-3', { y: -18, rotation: '+=4', duration: 3.8, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 1.4 });
    gsap.to('#aset-4', { y:  24, rotation: '-=6', duration: 4.6, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.4 });

    // Mouse parallax — blobs follow cursor strongly
    hero.addEventListener('mousemove', (e) => {
        const rect   = hero.getBoundingClientRect();
        const xRatio = (e.clientX - rect.left)  / rect.width  - 0.5;
        const yRatio = (e.clientY - rect.top)   / rect.height - 0.5;

        gsap.to('#about-blob-1', {
            x: xRatio * 380, y: yRatio * 280,
            duration: 1.2, ease: 'power3.out', overwrite: 'auto'
        });
        gsap.to('#about-blob-2', {
            x: -xRatio * 320, y: -yRatio * 260,
            duration: 1.5, ease: 'power3.out', overwrite: 'auto'
        });
        gsap.to('#about-blob-3', {
            x: xRatio * 200, y: yRatio * 200,
            duration: 1, ease: 'power3.out', overwrite: 'auto'
        });

        // Assets react to mouse — deeper parallax depth per icon
        gsap.to('#aset-1', { x: xRatio * -60, y: yRatio * -50, duration: 1.2, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#aset-2', { x: xRatio *  70, y: yRatio *  55, duration: 1.4, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#aset-3', { x: xRatio * -50, y: yRatio *  60, duration: 1.3, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#aset-4', { x: xRatio *  65, y: yRatio * -55, duration: 1.5, ease: 'power2.out', overwrite: 'auto' });
    });

    // Mouse leave — blobs and assets drift back to origin
    hero.addEventListener('mouseleave', () => {
        gsap.to('#about-blob-1', { x: 0, y: 0, duration: 2, ease: 'power2.out' });
        gsap.to('#about-blob-2', { x: 0, y: 0, duration: 2.5, ease: 'power2.out' });
        gsap.to('#about-blob-3', { x: 0, y: 0, duration: 1.5, ease: 'power2.out' });
        gsap.to('#aset-1, #aset-2, #aset-3, #aset-4', { x: 0, y: 0, duration: 1.5, ease: 'power2.out' });
    });
});
</script>
@endpush
