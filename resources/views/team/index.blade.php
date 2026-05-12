@extends('layouts.front')

@section('seo')
<title>Our Team — {{ $siteSetting->site_title }}</title>
<meta name="description" content="Meet the team behind {{ $siteSetting->site_title }}.">
<meta property="og:title" content="Our Team — {{ $siteSetting->site_title }}">
<meta property="og:description" content="Meet the team behind {{ $siteSetting->site_title }}.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ── HEADER ── --}}
<section class="relative overflow-hidden bg-(--dark-section) pt-40 pb-16">
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-(--accent)/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-(--accent)/20 blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="h-16 w-16 rounded-2xl bg-(--accent)/20 border border-(--accent)/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-(--accent-on-dark)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </div>
        </div>

        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-(--on-dark) leading-tight">{{ __('ui.our_team') }}</h1>
        <p class="text-base leading-relaxed text-(--on-dark)/70 max-w-xl mx-auto mb-6">
            {{ __('ui.team_subtitle', ['site' => $siteSetting->site_title]) }}
        </p>

        <div class="flex items-center justify-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-(--accent)/20 border border-(--accent)/30 px-4 py-1.5 text-sm font-semibold text-(--accent-on-dark)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                {{ $activeMembers->count() }} {{ trans_choice('ui.member_label', $activeMembers->count()) }}
            </span>
            <a href="{{ lroute('home') }}"
               class="inline-flex items-center gap-1.5 rounded-full bg-(--accent)/10 border border-(--accent)/20 px-4 py-1.5 text-sm font-semibold text-(--accent-on-dark) hover:bg-(--accent)/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                {{ __('ui.back_to_home') }}
            </a>
        </div>
    </div>
</section>

{{-- ── ACTIVE MEMBERS ── --}}
<section class="py-16 bg-(--bg-primary)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if($activeMembers->isEmpty() && $formerMembers->isEmpty())
        <p class="text-center text-(--text-muted) py-20">{{ __('ui.no_team_members') }}</p>
        @else

        @if($activeMembers->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
            @foreach($activeMembers as $member)
            <div class="card-animate">
                @include('partials.team-card', ['member' => $member])
            </div>
            @endforeach
        </div>
        @endif

        {{-- ── FORMER MEMBERS ── --}}
        @if($formerMembers->isNotEmpty())
        <div class="mt-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="flex-1 h-px bg-(--border)"></div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-(--text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-(--text-muted)">{{ __('ui.former_members') }}</h2>
                </div>
                <div class="flex-1 h-px bg-(--border)"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
                @foreach($formerMembers as $member)
                <div class="card-animate">
                    @include('partials.team-card', ['member' => $member, 'showStatusBadge' => true])
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
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
            el.style.transition = `opacity 0.45s ease ${i * 0.06}s, transform 0.45s ease ${i * 0.06}s`;
            observer.observe(el);
        });
    });
</script>
@endpush
