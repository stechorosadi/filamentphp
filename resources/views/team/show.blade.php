@extends('layouts.front')

@section('seo')
<title>{{ $member->fullName() }} — {{ $siteSetting->site_title }}</title>
<meta name="description" content="{{ $member->position }}{{ $member->front_title || $member->back_title ? ' · ' . $member->fullName() : '' }}">
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
@endphp

{{-- ── HERO ── --}}
<section class="relative bg-[#132A13] dark:bg-[#0a1a0a] pt-36 pb-16 overflow-hidden">
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-(--accent)/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-(--accent)/15 blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-10 flex items-center gap-2 text-sm text-[#90A955]">
            <a href="{{ lroute('home') }}" class="hover:text-[#ECF39E] transition-colors">{{ __('ui.home') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ lroute('team') }}" class="hover:text-[#ECF39E] transition-colors">{{ __('ui.team') }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="truncate text-[#ECF39E]/70">{{ $member->fullName() }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">

            {{-- Photo --}}
            <div class="shrink-0">
                @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}"
                     alt="{{ $member->fullName() }}"
                     class="h-48 w-48 rounded-2xl object-cover object-top shadow-xl ring-4 ring-[#4F772D]/40">
                @else
                <div class="h-48 w-48 rounded-2xl bg-(--accent)/20 flex items-center justify-center shadow-xl ring-4 ring-[#4F772D]/40">
                    <span class="text-5xl font-black text-[#ECF39E]">{{ strtoupper(substr($member->fullName() ?: '?', 0, 1)) }}</span>
                </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="text-center sm:text-left">
                @if($member->front_title)
                <p class="mb-1 text-sm font-medium text-[#90A955] uppercase tracking-widest">{{ $member->front_title }}</p>
                @endif
                <h1 class="text-3xl sm:text-4xl font-bold text-[#ECF39E] leading-tight">
                    {{ $user?->name ?? $member->name }}
                </h1>
                @if($member->back_title)
                <p class="mt-1 text-base text-[#90A955] font-medium">{{ $member->back_title }}</p>
                @endif
                @if($member->position)
                <p class="mt-3 text-base text-[#90A955]">{{ $member->position }}</p>
                @endif
                @if($member->employee_number)
                <p class="mt-1 text-xs text-[#4F772D]">ID: {{ $member->employee_number }}</p>
                @endif

                {{-- Social links --}}
                @if($socials)
                <div class="mt-5 flex items-center justify-center sm:justify-start gap-3">
                    @foreach($socials as $platform => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($platform) }}"
                       class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#4F772D]/40 text-[#90A955] hover:bg-[#4F772D]/30 hover:text-[#ECF39E] transition-all duration-200">
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
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── EDUCATION & EXPERIENCE TIMELINE ── --}}
@if($user && ($user->educationHistory->isNotEmpty() || $user->workExperience->isNotEmpty()))
<section class="bg-(--bg-primary) py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">

            {{-- Education --}}
            @if($user->educationHistory->isNotEmpty())
            <div>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-(--text-primary)">Education</h2>
                    <div class="mt-2 h-0.5 w-12 bg-(--accent)"></div>
                </div>

                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-3 top-2 bottom-2 w-px bg-(--border)"></div>

                    <div class="space-y-8">
                        @foreach($user->educationHistory as $edu)
                        <div class="relative pl-10">
                            {{-- Dot --}}
                            <div class="absolute left-0 top-1.5 h-6 w-6 rounded-full border-2 border-(--accent) bg-(--bg-primary) flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-(--accent)"></div>
                            </div>

                            {{-- Year + Institution --}}
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full border border-(--accent) px-2.5 py-0.5 text-xs font-semibold text-(--accent)">
                                    {{ $edu->start_year }}{{ $edu->end_year ? ' – ' . $edu->end_year : ' – Present' }}
                                </span>
                                @if($edu->institution)
                                <span class="text-xs text-(--text-muted)">{{ $edu->institution }}</span>
                                @endif
                            </div>

                            {{-- Degree / Title --}}
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
            </div>
            @endif

            {{-- Experience --}}
            @if($user->workExperience->isNotEmpty())
            <div>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-(--text-primary)">Experience</h2>
                    <div class="mt-2 h-0.5 w-12 bg-(--accent)"></div>
                </div>

                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-3 top-2 bottom-2 w-px bg-(--border)"></div>

                    <div class="space-y-8">
                        @foreach($user->workExperience as $exp)
                        <div class="relative pl-10">
                            {{-- Dot --}}
                            <div class="absolute left-0 top-1.5 h-6 w-6 rounded-full border-2 border-(--accent) bg-(--bg-primary) flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-(--accent)"></div>
                            </div>

                            {{-- Year + Company --}}
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center rounded-full border border-(--accent) px-2.5 py-0.5 text-xs font-semibold text-(--accent)">
                                    {{ $exp->start_year }}{{ $exp->end_year ? ' – ' . $exp->end_year : ' – Current' }}
                                </span>
                                @if($exp->company)
                                <span class="text-xs text-(--text-muted)">{{ $exp->company }}</span>
                                @endif
                            </div>

                            {{-- Job title --}}
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
            </div>
            @endif

        </div>
    </div>
</section>
@endif

{{-- ── CERTIFICATES ── --}}
@if($user && $user->certifications->isNotEmpty())
@php
    $certBadgeColors = [
        'training'                => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'seminar'                 => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
        'workshop'                => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'professional_certification' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
        'online_course'           => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
    ];
    $certLabels = [
        'training'                => 'Training',
        'seminar'                 => 'Seminar',
        'workshop'                => 'Workshop',
        'professional_certification' => 'Professional',
        'online_course'           => 'Online Course',
    ];
@endphp
<section class="bg-[#132A13] dark:bg-[#0d1f0d] py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h2 class="text-2xl font-bold text-[#ECF39E]">Certificates</h2>
            <div class="mt-2 h-0.5 w-12 bg-[#4F772D]"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($user->certifications as $cert)
            <div class="flex gap-4 rounded-2xl border border-[#4F772D]/30 bg-[#1a3a1a] p-4 hover:border-[#4F772D]/60 transition-colors duration-200">

                {{-- Icon --}}
                <div class="shrink-0 flex h-12 w-12 items-center justify-center rounded-xl bg-[#4F772D]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-[#90A955]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                    </svg>
                </div>

                {{-- Content --}}
                <div class="min-w-0 flex-1">
                    @if($cert->category)
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mb-1.5 {{ $certBadgeColors[$cert->category] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        {{ $certLabels[$cert->category] ?? ucfirst($cert->category) }}
                    </span>
                    @endif
                    <h3 class="text-sm font-bold text-[#ECF39E] leading-snug">{{ $cert->title }}</h3>
                    @if($cert->issuing_organization)
                    <p class="mt-0.5 text-xs text-[#90A955]">{{ $cert->issuing_organization }}</p>
                    @endif
                    @if($cert->issue_year)
                    <p class="mt-1 text-xs text-[#4F772D]">{{ $cert->issue_year }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ── PUBLICATIONS ── --}}
@if($user && $user->publications->isNotEmpty())
@php
    $pubBadgeColors = [
        'book'             => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        'journal_article'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'research_paper'   => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
        'conference_paper' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'other'            => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    ];
    $pubLabels = [
        'book'             => 'Book',
        'journal_article'  => 'Journal Article',
        'research_paper'   => 'Research Paper',
        'conference_paper' => 'Conference Paper',
        'other'            => 'Other',
    ];
@endphp
<section class="bg-(--bg-alt) dark:bg-(--bg-primary) py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h2 class="text-2xl font-bold text-(--text-primary)">Publications</h2>
            <div class="mt-2 h-0.5 w-12 bg-(--accent)"></div>
        </div>

        <div class="space-y-4">
            @foreach($user->publications as $pub)
            <div class="rounded-2xl border border-(--border) bg-(--bg-card) p-5 hover:border-(--accent)/40 transition-colors duration-200">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                    {{-- Left --}}
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

                    {{-- Link button --}}
                    @if($pub->url)
                    <div class="shrink-0">
                        <a href="{{ $pub->url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-(--border) px-3 py-2 text-xs font-semibold text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                            </svg>
                            View
                        </a>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ── BACK LINK ── --}}
<div class="h-px bg-linear-to-r from-transparent via-(--accent) to-transparent opacity-30"></div>

<div class="bg-(--bg-primary) py-8">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">

        {{-- Left: back link --}}
        <a href="{{ lroute('team') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-(--accent) hover:text-(--text-primary) transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform duration-200">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to Team Members
        </a>

        {{-- Right: export PDF --}}
        <a href="{{ lroute('team.member.pdf', [$member->getKey()]) }}"
           target="_blank"
           class="inline-flex items-center gap-2 rounded-lg border border-(--border) px-4 py-2 text-sm font-semibold text-(--accent) hover:bg-(--accent) hover:text-white hover:border-(--accent) transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
            Export to PDF
        </a>

    </div>
</div>

@endsection
