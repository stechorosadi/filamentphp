@extends('layouts.front')

@section('seo')
<title>Sitemap — {{ $siteSetting->site_title }}</title>
<meta name="description" content="All pages and content available on {{ $siteSetting->site_title }}.">
<meta name="robots" content="noindex, follow">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ── HERO ── --}}
<section class="relative overflow-hidden bg-[#132A13] dark:bg-[#0a1a0a] pt-40 pb-16">
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-[var(--accent)]/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[var(--accent)]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center mb-6">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-[#4F772D]/30 bg-[var(--accent)]/15">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-[#90A955]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/>
                </svg>
            </div>
        </div>
        <p class="mb-2 text-sm font-medium uppercase tracking-widest text-[var(--accent)]">All Pages</p>
        <h1 class="mb-3 text-3xl sm:text-4xl font-bold text-[#ECF39E] leading-tight">Sitemap</h1>
        <p class="text-base text-[#90A955]">
            {{ $contents->count() + $categories->count() + $classifications->count() + $teamMembers->count() + 4 }}
            pages indexed
        </p>
    </div>
</section>

{{-- ── CONTENT ── --}}
<section class="bg-[var(--bg-primary)] py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2">

            {{-- Static Pages --}}
            <div>
                <h2 class="mb-5 flex items-center gap-2 text-lg font-bold text-[var(--text-primary)]">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-(--accent)/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-(--accent)"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    </span>
                    Static Pages
                </h2>
                <ul class="space-y-2">
                    @foreach([
                        ['label' => 'Home', 'url' => route('home')],
                        ['label' => 'Search', 'url' => route('search')],
                        ['label' => 'Team', 'url' => route('team')],
                        ['label' => 'Archive', 'url' => route('archive')],
                    ] as $page)
                    <li>
                        <a href="{{ $page['url'] }}"
                           class="flex items-center gap-2 rounded-xl border border-(--border) bg-(--bg-card) px-4 py-3 text-sm font-medium text-(--text-primary) hover:border-(--accent)/40 hover:text-(--accent) transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-(--accent) shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            {{ $page['label'] }}
                            <span class="ml-auto text-xs text-(--text-muted) truncate">{{ $page['url'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Categories --}}
            <div>
                <h2 class="mb-5 flex items-center gap-2 text-lg font-bold text-[var(--text-primary)]">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-(--accent)/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-(--accent)"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                    </span>
                    Categories
                    <span class="ml-auto text-xs font-normal text-(--text-muted)">{{ $categories->count() }}</span>
                </h2>
                @if($categories->isEmpty())
                <p class="text-sm text-(--text-muted)">No categories yet.</p>
                @else
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}"
                           class="flex items-center gap-2 rounded-xl border border-(--border) bg-(--bg-card) px-4 py-3 text-sm font-medium text-(--text-primary) hover:border-(--accent)/40 hover:text-(--accent) transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-(--accent) shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            {{ $category->name }}
                            <span class="ml-auto text-xs text-(--text-muted)">{{ $category->updated_at->format('M Y') }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            {{-- Classifications --}}
            <div>
                <h2 class="mb-5 flex items-center gap-2 text-lg font-bold text-[var(--text-primary)]">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-(--accent)/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-(--accent)"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                    </span>
                    Classifications
                    <span class="ml-auto text-xs font-normal text-(--text-muted)">{{ $classifications->count() }}</span>
                </h2>
                @if($classifications->isEmpty())
                <p class="text-sm text-(--text-muted)">No classifications yet.</p>
                @else
                <ul class="space-y-2">
                    @foreach($classifications as $classification)
                    <li>
                        <a href="{{ route('classification.show', $classification->slug) }}"
                           class="flex items-center gap-2 rounded-xl border border-(--border) bg-(--bg-card) px-4 py-3 text-sm font-medium text-(--text-primary) hover:border-(--accent)/40 hover:text-(--accent) transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-(--accent) shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            {{ $classification->name }}
                            <span class="ml-auto text-xs text-(--text-muted)">{{ $classification->updated_at->format('M Y') }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            {{-- Team Members --}}
            <div>
                <h2 class="mb-5 flex items-center gap-2 text-lg font-bold text-[var(--text-primary)]">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-(--accent)/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-(--accent)"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </span>
                    Team Members
                    <span class="ml-auto text-xs font-normal text-(--text-muted)">{{ $teamMembers->count() }}</span>
                </h2>
                @if($teamMembers->isEmpty())
                <p class="text-sm text-(--text-muted)">No team members yet.</p>
                @else
                <ul class="space-y-2">
                    @foreach($teamMembers as $member)
                    <li>
                        <a href="{{ route('team.member', $member->id) }}"
                           class="flex items-center gap-2 rounded-xl border border-(--border) bg-(--bg-card) px-4 py-3 text-sm font-medium text-(--text-primary) hover:border-(--accent)/40 hover:text-(--accent) transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-(--accent) shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            {{ $member->fullName() }}
                            <span class="ml-auto text-xs text-(--text-muted)">{{ $member->updated_at->format('M Y') }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

        </div>

        {{-- Articles (full width) --}}
        <div class="mt-10">
            <h2 class="mb-5 flex items-center gap-2 text-lg font-bold text-[var(--text-primary)]">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-(--accent)/15">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-(--accent)"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </span>
                Articles
                <span class="ml-2 text-xs font-normal text-(--text-muted)">{{ $contents->count() }} published</span>
                <a href="{{ route('sitemap') }}" target="_blank"
                   class="ml-auto text-xs text-(--text-muted) hover:text-(--accent) transition-colors">
                    View XML sitemap ↗
                </a>
            </h2>

            @if($contents->isEmpty())
            <p class="text-sm text-(--text-muted)">No published articles yet.</p>
            @else
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($contents as $content)
                <a href="{{ route('content.show', $content->slug) }}"
                   class="flex items-start gap-2 rounded-xl border border-(--border) bg-(--bg-card) px-4 py-3 hover:border-(--accent)/40 hover:text-(--accent) transition-colors duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-(--accent) shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-(--text-primary) group-hover:text-(--accent) transition-colors line-clamp-1">{{ $content->title }}</p>
                        <p class="text-xs text-(--text-muted) mt-0.5">{{ $content->updated_at->format('M d, Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</section>

@endsection
