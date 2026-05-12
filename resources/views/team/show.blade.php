@extends('layouts.front')

@section('seo')
<title>{{ $member->fullName() }} — {{ $siteSetting->site_title }}</title>
<meta name="description" content="{{ $member->position }}{{ $member->front_title || $member->back_title ? ' · ' . $member->fullName() : '' }}">
<meta property="og:title" content="{{ $member->fullName() }} — {{ $siteSetting->site_title }}">
<meta property="og:description" content="{{ $member->position }}">
<meta property="og:type" content="profile">
<meta property="og:url" content="{{ url()->current() }}">
@if($member->photo)
<meta property="og:image" content="{{ asset('storage/' . $member->photo) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

@php
    $user = $member->user;
    $socials = array_filter([
        'instagram' => $member->instagram_url,
        'facebook'  => $member->facebook_url,
        'x'         => $member->x_url,
        'threads'   => $member->threads_url,
        'youtube'   => $member->youtube_url,
    ]);
    $hasUser = (bool) $user;
    $eduCount   = $hasUser ? $user->educationHistory->count() : 0;
    $expCount   = $hasUser ? $user->workExperience->count() : 0;
    $certCount  = $hasUser ? $user->certifications->count() : 0;
    $pubCount   = $hasUser ? $user->publications->count() : 0;
    $blogCount  = $blogs->count();
    $certBadgeColors = [
        'training'                   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'seminar'                    => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
        'workshop'                   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'professional_certification' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
        'online_course'              => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
    ];
    $certLabels = [
        'training'                   => __('ui.cert_training'),
        'seminar'                    => __('ui.cert_seminar'),
        'workshop'                   => __('ui.cert_workshop'),
        'professional_certification' => __('ui.cert_professional'),
        'online_course'              => __('ui.cert_online_course'),
    ];
    $pubBadgeColors = [
        'book'             => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        'journal_article'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'research_paper'   => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
        'conference_paper' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'other'            => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    ];
    $pubLabels = [
        'book'             => __('ui.pub_book'),
        'journal_article'  => __('ui.pub_journal_article'),
        'research_paper'   => __('ui.pub_research_paper'),
        'conference_paper' => __('ui.pub_conference_paper'),
        'other'            => __('ui.pub_other'),
    ];
@endphp

{{-- ── HERO ── --}}
<section id="member-hero" class="relative min-h-[90vh] flex flex-col justify-center bg-(--dark-section) overflow-hidden">

    {{-- Background grid --}}
    <div id="member-grid" class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    {{-- Glow blobs --}}
    <div id="member-blob-1" class="absolute left-1/4 top-1/3 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-(--accent)/50 pointer-events-none will-change-transform" style="filter:blur(120px)"></div>
    <div id="member-blob-2" class="absolute right-1/4 bottom-1/3 h-[400px] w-[400px] rounded-full bg-(--accent-on-dark)/25 pointer-events-none will-change-transform" style="filter:blur(100px)"></div>
    <div id="member-blob-3" class="absolute left-1/2 top-1/2 h-[300px] w-[300px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-(--on-dark)/15 pointer-events-none will-change-transform opacity-0" style="filter:blur(90px)"></div>

    {{-- Floating assets --}}
    <img id="masset-1" src="{{ asset('storage/aset-color/lightbulb.png') }}" alt=""
         class="absolute hidden lg:block w-20 opacity-30 pointer-events-none will-change-transform drop-shadow-lg"
         style="left:15%; top:24%; transform:rotate(-12deg)">
    <img id="masset-2" src="{{ asset('storage/aset-color/cpu.png') }}" alt=""
         class="absolute hidden lg:block w-24 opacity-30 pointer-events-none will-change-transform drop-shadow-lg"
         style="right:14%; top:20%; transform:rotate(8deg)">
    <img id="masset-3" src="{{ asset('storage/aset-color/shield.png') }}" alt=""
         class="absolute hidden lg:block w-16 opacity-30 pointer-events-none will-change-transform drop-shadow-lg"
         style="left:18%; bottom:24%; transform:rotate(10deg)">
    <img id="masset-4" src="{{ asset('storage/aset-color/padlock.png') }}" alt=""
         class="absolute hidden lg:block w-20 opacity-30 pointer-events-none will-change-transform drop-shadow-lg"
         style="right:16%; bottom:24%; transform:rotate(-8deg)">

    <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 pt-40 sm:pt-28 pb-16">

        {{-- 3-column hero grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px_1fr] gap-8 lg:gap-10 items-center">

            {{-- ── LEFT COLUMN: Identity ── --}}
            <div id="member-left" class="flex flex-col gap-4 text-center lg:text-right order-2 lg:order-1">

                {{-- Eyebrow --}}
                <div class="flex justify-center lg:justify-end">
                    <span class="inline-flex items-center gap-2 rounded-full border border-(--accent)/50 bg-(--dark-section)/70 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-(--accent-on-dark) backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#90A955] animate-pulse"></span>
                        {{ __('ui.team') }}
                    </span>
                </div>

                {{-- Front title --}}
                @if($member->front_title)
                <p class="text-sm font-semibold text-(--accent-on-dark) uppercase tracking-[0.2em]">{{ $member->front_title }}</p>
                @endif

                {{-- Name --}}
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-(--on-dark) leading-[0.95] tracking-tight uppercase">
                        {{ $user?->name ?? $member->name }}
                    </h1>
                    @if($member->back_title)
                    <p class="mt-2 text-sm font-semibold text-(--accent-on-dark)/80 tracking-wider">{{ $member->back_title }}</p>
                    @endif
                </div>

                {{-- Position card --}}
                @if($member->position)
                <div class="inline-flex w-fit self-center lg:self-end items-center gap-3 bg-(--dark-section)/50 border border-(--accent)/30 rounded-2xl px-4 py-3 backdrop-blur-sm hover:border-(--accent)/60 transition-colors duration-200">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-(--accent)/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--accent-on-dark)">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-(--accent-on-dark)/60 uppercase tracking-widest font-bold">{{ __('ui.position') }}</p>
                        <p class="text-sm font-bold text-(--on-dark) leading-snug mt-0.5">{{ $member->position }}</p>
                    </div>
                </div>
                @endif

                {{-- Employee number card --}}
                @if($member->employee_number)
                <div class="inline-flex w-fit self-center lg:self-end items-center gap-3 bg-(--dark-section)/50 border border-(--accent)/30 rounded-2xl px-4 py-3 backdrop-blur-sm hover:border-(--accent)/60 transition-colors duration-200">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-(--accent)/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--accent-on-dark)">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-(--accent-on-dark)/60 uppercase tracking-widest font-bold">{{ __('ui.employee_number') }}</p>
                        <p class="text-sm font-bold text-(--on-dark) mt-0.5">{{ $member->employee_number }}</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- ── CENTER COLUMN: Photo ── --}}
            <div id="member-photo" class="relative flex justify-center order-1 lg:order-2">
                {{-- Outer glow ring --}}
                <div class="absolute inset-0 rounded-[2rem] bg-(--accent)/25 blur-3xl scale-110 pointer-events-none"></div>
                {{-- Inner decorative ring --}}
                <div class="absolute -inset-3 rounded-[2.5rem] border border-(--accent)/20 pointer-events-none"></div>
                <div class="absolute -inset-6 rounded-[3rem] border border-(--accent)/10 pointer-events-none"></div>

                @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}"
                     alt="{{ $member->fullName() }}"
                     class="relative w-full max-w-[280px] aspect-[3/4] rounded-[2rem] object-cover object-top shadow-2xl ring-2 ring-(--accent)/60 z-10">
                @else
                <div class="relative w-full max-w-[280px] aspect-[3/4] rounded-[2rem] bg-(--dark-section) flex items-center justify-center shadow-2xl ring-2 ring-(--accent)/60 z-10">
                    <span class="text-8xl font-black text-(--on-dark)">{{ strtoupper(substr($member->fullName() ?: '?', 0, 1)) }}</span>
                </div>
                @endif

                {{-- Status badge overlay --}}
                @if($member->status !== \App\Enums\TeamMemberStatus::Active)
                @php
                    $badgeClass = $member->status === \App\Enums\TeamMemberStatus::Retired
                        ? 'bg-gray-600/90 text-white'
                        : 'bg-amber-500/90 text-white';
                @endphp
                <span class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }} backdrop-blur-sm shadow-lg">
                    {{ __('ui.status_' . $member->status->value) }}
                </span>
                @endif
            </div>

            {{-- ── RIGHT COLUMN: Quote, Social, Actions ── --}}
            <div id="member-right" class="flex flex-col gap-4 text-center lg:text-left order-3">

                {{-- Word of wisdom --}}
                @if($member->word_of_wisdom)
                <div class="relative bg-(--dark-section)/50 border border-(--accent)/30 rounded-2xl px-5 py-4 backdrop-blur-sm hover:border-(--accent)/60 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                         class="absolute top-3 left-4 h-6 w-6 text-(--accent)/40">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <blockquote class="text-sm italic text-(--accent-on-dark) leading-relaxed pl-7">
                        {{ $member->word_of_wisdom }}
                    </blockquote>
                </div>
                @endif

                {{-- Social links --}}
                @if($socials)
                <div>
                    <p class="text-[10px] text-(--accent-on-dark)/60 uppercase tracking-widest font-bold mb-2">{{ __('ui.social_media') }}</p>
                    <div class="flex items-center justify-center lg:justify-start gap-2 flex-wrap">
                        @foreach($socials as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($platform) }}"
                           class="flex h-9 w-9 items-center justify-center rounded-xl border border-(--accent)/40 bg-(--dark-section)/50 text-(--accent-on-dark) hover:bg-(--accent)/30 hover:text-(--on-dark) hover:border-(--accent)/70 hover:-translate-y-0.5 transition-all duration-200 backdrop-blur-sm">
                            @if($platform === 'instagram')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            @elseif($platform === 'facebook')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @elseif($platform === 'x')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            @elseif($platform === 'threads')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.589 12c.027 3.086.718 5.496 2.057 7.164 1.43 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.31-.71-.873-1.3-1.634-1.75-.192 1.352-.622 2.446-1.284 3.272-.886 1.102-2.14 1.704-3.73 1.79-1.202.065-2.361-.218-3.259-.801-1.063-.689-1.685-1.74-1.752-2.964-.065-1.19.408-2.285 1.33-3.082.88-.76 2.119-1.207 3.583-1.291a13.853 13.853 0 0 1 3.02.142c-.126-.742-.375-1.332-.75-1.757-.513-.586-1.308-.883-2.359-.89h-.029c-.844 0-1.992.232-2.721 1.32L7.734 9.13c.66-1.04 1.516-1.822 2.545-2.33.984-.482 2.092-.737 3.22-.737h.033c2.508.017 4.178 1.131 4.845 3.23.18.563.28 1.162.298 1.783a8.516 8.516 0 0 1 1.342.492c1.155.533 2.038 1.373 2.554 2.43.78 1.64.882 4.22-.913 5.982-1.816 1.78-4.059 2.595-7.47 2.62l-.002-.62zm.176-8.288c-.085 0-.17.002-.255.006-1.099.06-1.942.372-2.41.876-.359.388-.505.87-.472 1.376.054.964.85 1.617 2.041 1.617h.028c.96-.053 1.682-.43 2.148-1.12.406-.6.622-1.42.644-2.432a11.765 11.765 0 0 0-1.724-.323z"/></svg>
                            @elseif($platform === 'youtube')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Action buttons --}}
                <div class="flex items-center justify-center lg:justify-start gap-3 flex-wrap">
                    <a href="{{ lroute('team') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-(--accent)/40 bg-(--dark-section)/50 px-4 py-2.5 text-sm font-semibold text-(--accent-on-dark) hover:bg-(--accent)/25 hover:text-(--on-dark) hover:border-(--accent)/70 hover:-translate-y-0.5 transition-all duration-200 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        {{ __('ui.back_to_team') }}
                    </a>
                    <a href="{{ lroute('team.member.pdf', [$member->nickname]) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl border border-(--accent)/40 bg-(--dark-section)/50 px-4 py-2.5 text-sm font-semibold text-(--accent-on-dark) hover:bg-(--accent)/25 hover:text-(--on-dark) hover:border-(--accent)/70 hover:-translate-y-0.5 transition-all duration-200 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        {{ __('ui.export_pdf') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom fade into next section --}}
    <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-(--dark-section) to-transparent pointer-events-none"></div>
</section>

{{-- ── CARD-TAB MENU + CONTENT PANELS ── --}}
@if($hasUser)
<section class="bg-(--bg-primary) py-12"
         x-data="{ activeTab: 'blog' }"
         x-cloak>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-8 flex items-center gap-2 text-sm text-(--text-muted)">
            <a href="{{ lroute('home') }}" class="hover:text-(--accent) transition-colors">{{ __('ui.home') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ lroute('team') }}" class="hover:text-(--accent) transition-colors">{{ __('ui.team') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="truncate text-(--text-primary) font-medium">{{ $member->fullName() }}</span>
        </nav>

        {{-- Card grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-8">

            {{-- Blog --}}
            <button type="button"
                    @click="activeTab = activeTab === 'blog' ? null : 'blog'"
                    :class="activeTab === 'blog' ? 'border-(--accent) bg-(--accent)/10 text-(--accent)' : 'border-(--border) bg-(--bg-card) text-(--text-muted) hover:border-(--accent)/50 hover:text-(--text-primary)'"
                    class="flex flex-col items-center gap-2 rounded-2xl border p-4 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ __('ui.blog') }}</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                      :class="activeTab === 'blog' ? 'bg-(--accent) text-white' : 'bg-(--bg-alt) text-(--text-muted)'">
                    {{ $blogCount }}
                </span>
            </button>

            {{-- Education History --}}
            <button type="button"
                    @click="activeTab = activeTab === 'edu' ? null : 'edu'"
                    :class="activeTab === 'edu' ? 'border-(--accent) bg-(--accent)/10 text-(--accent)' : 'border-(--border) bg-(--bg-card) text-(--text-muted) hover:border-(--accent)/50 hover:text-(--text-primary)'"
                    class="flex flex-col items-center gap-2 rounded-2xl border p-4 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ __('ui.education') }}</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                      :class="activeTab === 'edu' ? 'bg-(--accent) text-white' : 'bg-(--bg-alt) text-(--text-muted)'">
                    {{ $eduCount }}
                </span>
            </button>

            {{-- Working Experience --}}
            <button type="button"
                    @click="activeTab = activeTab === 'exp' ? null : 'exp'"
                    :class="activeTab === 'exp' ? 'border-(--accent) bg-(--accent)/10 text-(--accent)' : 'border-(--border) bg-(--bg-card) text-(--text-muted) hover:border-(--accent)/50 hover:text-(--text-primary)'"
                    class="flex flex-col items-center gap-2 rounded-2xl border p-4 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ __('ui.experience') }}</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                      :class="activeTab === 'exp' ? 'bg-(--accent) text-white' : 'bg-(--bg-alt) text-(--text-muted)'">
                    {{ $expCount }}
                </span>
            </button>

            {{-- Certification --}}
            <button type="button"
                    @click="activeTab = activeTab === 'cert' ? null : 'cert'"
                    :class="activeTab === 'cert' ? 'border-(--accent) bg-(--accent)/10 text-(--accent)' : 'border-(--border) bg-(--bg-card) text-(--text-muted) hover:border-(--accent)/50 hover:text-(--text-primary)'"
                    class="flex flex-col items-center gap-2 rounded-2xl border p-4 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ __('ui.certifications') }}</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                      :class="activeTab === 'cert' ? 'bg-(--accent) text-white' : 'bg-(--bg-alt) text-(--text-muted)'">
                    {{ $certCount }}
                </span>
            </button>

            {{-- Publication --}}
            <button type="button"
                    @click="activeTab = activeTab === 'pub' ? null : 'pub'"
                    :class="activeTab === 'pub' ? 'border-(--accent) bg-(--accent)/10 text-(--accent)' : 'border-(--border) bg-(--bg-card) text-(--text-muted) hover:border-(--accent)/50 hover:text-(--text-primary)'"
                    class="flex flex-col items-center gap-2 rounded-2xl border p-4 transition-all duration-200 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-(--accent)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
                <span class="text-xs font-semibold text-center leading-tight">{{ __('ui.publications') }}</span>
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold"
                      :class="activeTab === 'pub' ? 'bg-(--accent) text-white' : 'bg-(--bg-alt) text-(--text-muted)'">
                    {{ $pubCount }}
                </span>
            </button>

        </div>

        {{-- ─── BLOG PANEL ─── --}}
        <div x-show="activeTab === 'blog'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-6">
                <h2 class="text-xl font-bold text-(--text-primary) mb-6">{{ __('ui.blog') }}</h2>
                @if($blogs->isEmpty())
                <p class="text-center text-(--text-muted) py-10">{{ __('ui.no_blog_posts') }}</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($blogs as $post)
                    <a href="{{ lroute('content.show', [$post->slug]) }}"
                       class="group flex flex-col rounded-xl border border-(--border) bg-(--bg-primary) overflow-hidden hover:border-(--accent)/40 hover:-translate-y-1 hover:shadow-md transition-all duration-200">
                        @if($post->featured_image)
                        <div class="aspect-video w-full overflow-hidden">
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                 alt="{{ $post->title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        <div class="flex flex-col flex-1 p-4 gap-2">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($post->article_date)
                                <span class="text-xs text-(--text-muted)">{{ \Carbon\Carbon::parse($post->article_date)->format('d M Y') }}</span>
                                @endif
                                @if($post->category)
                                <span class="inline-flex items-center rounded-full bg-(--accent)/10 px-2 py-0.5 text-xs font-semibold text-(--accent)">
                                    {{ $post->category->name }}
                                </span>
                                @endif
                            </div>
                            <h3 class="text-sm font-bold text-(--text-primary) leading-snug group-hover:text-(--accent) transition-colors line-clamp-2">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                            <p class="text-xs text-(--text-muted) leading-relaxed line-clamp-2 flex-1">{{ $post->excerpt }}</p>
                            @endif
                            <span class="mt-auto text-xs font-semibold text-(--accent) group-hover:underline">{{ __('ui.read_more') }} →</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ─── EDUCATION PANEL ─── --}}
        <div x-show="activeTab === 'edu'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-6">
                <h2 class="text-xl font-bold text-(--text-primary) mb-6">{{ __('ui.education') }}</h2>
                @if($user->educationHistory->isEmpty())
                <p class="text-center text-(--text-muted) py-10">{{ __('ui.no_education') }}</p>
                @else
                <div class="relative">
                    <div class="absolute left-3 top-2 bottom-2 w-px bg-(--border)"></div>
                    <div class="space-y-8">
                        @foreach($user->educationHistory as $edu)
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-6 w-6 rounded-full border-2 border-(--accent) bg-(--bg-card) flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-(--accent)"></div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full border border-(--accent) px-2.5 py-0.5 text-xs font-semibold text-(--accent)">
                                    {{ $edu->start_year }}{{ $edu->end_year ? ' – ' . $edu->end_year : ' – ' . __('ui.present') }}
                                </span>
                                @if($edu->institution)
                                <span class="text-xs text-(--text-muted)">{{ $edu->institution }}</span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-(--text-primary) leading-snug">
                                {{ $edu->degree }}
                                @if($edu->field_of_study)
                                <span class="font-normal text-(--text-muted)">· {{ $edu->field_of_study }}</span>
                                @endif
                            </h3>
                            @if($edu->gpa)
                            <p class="mt-1 text-xs text-(--accent)">GPA: {{ $edu->gpa }}</p>
                            @endif
                            @if($edu->description)
                            <p class="mt-2 text-sm text-(--text-muted) leading-relaxed">{{ $edu->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ─── EXPERIENCE PANEL ─── --}}
        <div x-show="activeTab === 'exp'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-6">
                <h2 class="text-xl font-bold text-(--text-primary) mb-6">{{ __('ui.experience') }}</h2>
                @if($user->workExperience->isEmpty())
                <p class="text-center text-(--text-muted) py-10">{{ __('ui.no_experience') }}</p>
                @else
                <div class="relative">
                    <div class="absolute left-3 top-2 bottom-2 w-px bg-(--border)"></div>
                    <div class="space-y-8">
                        @foreach($user->workExperience as $exp)
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1.5 h-6 w-6 rounded-full border-2 border-(--accent) bg-(--bg-card) flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-(--accent)"></div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full border border-(--accent) px-2.5 py-0.5 text-xs font-semibold text-(--accent)">
                                    {{ $exp->start_year }}{{ $exp->end_year ? ' – ' . $exp->end_year : ' – ' . __('ui.current') }}
                                </span>
                                @if($exp->company)
                                <span class="text-xs text-(--text-muted)">{{ $exp->company }}</span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-(--text-primary) leading-snug">
                                {{ $exp->job_title }}
                                @if($exp->department)
                                <span class="font-normal text-(--text-muted)">· {{ $exp->department }}</span>
                                @endif
                            </h3>
                            @if($exp->description)
                            <p class="mt-2 text-sm text-(--text-muted) leading-relaxed">{{ $exp->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ─── CERTIFICATION PANEL ─── --}}
        <div x-show="activeTab === 'cert'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-6">
                <h2 class="text-xl font-bold text-(--text-primary) mb-6">{{ __('ui.certifications') }}</h2>
                @if($user->certifications->isEmpty())
                <p class="text-center text-(--text-muted) py-10">{{ __('ui.no_certifications') }}</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($user->certifications as $cert)
                    <div class="flex gap-4 rounded-xl border border-(--border) bg-(--bg-primary) p-4 hover:border-(--accent)/40 transition-colors duration-200">
                        <div class="shrink-0 flex h-11 w-11 items-center justify-center rounded-xl bg-(--accent)/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--accent)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            @if($cert->category)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mb-1.5 {{ $certBadgeColors[$cert->category] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                {{ $certLabels[$cert->category] ?? ucfirst($cert->category) }}
                            </span>
                            @endif
                            <h3 class="text-sm font-bold text-(--text-primary) leading-snug">{{ $cert->title }}</h3>
                            @if($cert->issuing_organization)
                            <p class="mt-0.5 text-xs text-(--text-muted)">{{ $cert->issuing_organization }}</p>
                            @endif
                            @if($cert->issue_year)
                            <p class="mt-1 text-xs text-(--accent)">{{ $cert->issue_year }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ─── PUBLICATION PANEL ─── --}}
        <div x-show="activeTab === 'pub'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-6">
                <h2 class="text-xl font-bold text-(--text-primary) mb-6">{{ __('ui.publications') }}</h2>
                @if($user->publications->isEmpty())
                <p class="text-center text-(--text-muted) py-10">{{ __('ui.no_publications') }}</p>
                @else
                <div class="space-y-4">
                    @foreach($user->publications as $pub)
                    <div class="rounded-xl border border-(--border) bg-(--bg-primary) p-5 hover:border-(--accent)/40 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $pubBadgeColors[$pub->type] ?? $pubBadgeColors['other'] }}">
                                        {{ $pubLabels[$pub->type] ?? ucfirst(str_replace('_', ' ', $pub->type)) }}
                                    </span>
                                    @if($pub->year)
                                    <span class="text-xs text-(--text-muted)">{{ $pub->year }}</span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-(--text-primary) leading-snug">{{ $pub->title }}</h3>
                                @if($pub->publisher)
                                <p class="mt-1 text-sm text-(--accent)">{{ $pub->publisher }}</p>
                                @endif
                                @if($pub->description)
                                <p class="mt-2 text-sm text-(--text-muted) leading-relaxed line-clamp-3">{{ $pub->description }}</p>
                                @endif
                                @if($pub->doi || $pub->isbn)
                                <p class="mt-2 text-xs text-(--text-muted)">
                                    @if($pub->doi)<span class="font-medium">DOI:</span> {{ $pub->doi }}@endif
                                    @if($pub->doi && $pub->isbn) &nbsp;·&nbsp; @endif
                                    @if($pub->isbn)<span class="font-medium">ISBN:</span> {{ $pub->isbn }}@endif
                                </p>
                                @endif
                            </div>
                            @if($pub->url)
                            <div class="shrink-0">
                                <a href="{{ $pub->url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-(--border) px-3 py-2 text-xs font-semibold text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                                    </svg>
                                    {{ __('ui.view') }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endif

@endsection

@push('scripts')
<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.getElementById('member-hero');
    if (!hero) return;

    // ── Enter animations ──────────────────────────────────────────
    gsap.set(['#member-left', '#member-photo', '#member-right'], {
        opacity: 0, y: 30
    });

    gsap.timeline({ defaults: { ease: 'power3.out' } })
        .to('#member-photo',  { opacity: 1, y: 0, duration: 0.7 })
        .to('#member-left',   { opacity: 1, y: 0, duration: 0.6 }, '-=0.5')
        .to('#member-right',  { opacity: 1, y: 0, duration: 0.6 }, '-=0.5');

    // ── Blob drift ────────────────────────────────────────────────
    gsap.to('#member-blob-1', { x: 200, y: 120, duration: 12, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    gsap.to('#member-blob-2', { x: -160, y: -180, duration: 15, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 3 });
    gsap.to('#member-blob-3', {
        opacity: 1, duration: 1.2, ease: 'power2.out',
        onComplete() {
            gsap.to('#member-blob-3', { x: 80, y: -70, scale: 1.6, opacity: 0.4, duration: 9, repeat: -1, yoyo: true, ease: 'sine.inOut' });
        }
    });

    // ── Grid breathe ─────────────────────────────────────────────
    gsap.to('#member-grid', { opacity: 0.35, duration: 6, repeat: -1, yoyo: true, ease: 'sine.inOut' });

    // ── Floating assets bob ───────────────────────────────────────
    gsap.to('#masset-1', { y: -20, rotation: '-=5', duration: 3.6, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    gsap.to('#masset-2', { y:  16, rotation: '+=6', duration: 4.3, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.8 });
    gsap.to('#masset-3', { y: -18, rotation: '+=4', duration: 3.9, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 1.4 });
    gsap.to('#masset-4', { y:  22, rotation: '-=6', duration: 4.7, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.4 });

    // ── Mouse parallax ────────────────────────────────────────────
    hero.addEventListener('mousemove', (e) => {
        const rect   = hero.getBoundingClientRect();
        const xRatio = (e.clientX - rect.left)  / rect.width  - 0.5;
        const yRatio = (e.clientY - rect.top)   / rect.height - 0.5;

        gsap.to('#member-blob-1', { x: xRatio * 340, y: yRatio * 260, duration: 1.2, ease: 'power3.out', overwrite: 'auto' });
        gsap.to('#member-blob-2', { x: -xRatio * 280, y: -yRatio * 240, duration: 1.5, ease: 'power3.out', overwrite: 'auto' });
        gsap.to('#member-blob-3', { x: xRatio * 180, y: yRatio * 180, duration: 1.0, ease: 'power3.out', overwrite: 'auto' });

        gsap.to('#masset-1', { x: xRatio * -55, y: yRatio * -45, duration: 1.2, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#masset-2', { x: xRatio *  65, y: yRatio *  50, duration: 1.4, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#masset-3', { x: xRatio * -45, y: yRatio *  55, duration: 1.3, ease: 'power2.out', overwrite: 'auto' });
        gsap.to('#masset-4', { x: xRatio *  60, y: yRatio * -50, duration: 1.5, ease: 'power2.out', overwrite: 'auto' });

        gsap.to('#member-photo', {
            rotateY: xRatio * 6, rotateX: -yRatio * 4,
            duration: 0.8, ease: 'power2.out', overwrite: 'auto', transformPerspective: 800
        });
    });

    hero.addEventListener('mouseleave', () => {
        gsap.to('#member-blob-1', { x: 0, y: 0, duration: 2, ease: 'power2.out' });
        gsap.to('#member-blob-2', { x: 0, y: 0, duration: 2.5, ease: 'power2.out' });
        gsap.to('#member-blob-3', { x: 0, y: 0, duration: 1.5, ease: 'power2.out' });
        gsap.to('#masset-1, #masset-2, #masset-3, #masset-4', { x: 0, y: 0, duration: 1.5, ease: 'power2.out' });
        gsap.to('#member-photo', { rotateY: 0, rotateX: 0, duration: 1, ease: 'power2.out' });
    });
});
</script>
@endpush
