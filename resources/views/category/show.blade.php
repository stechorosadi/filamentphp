@extends('layouts.front')

@section('seo')
<title>{{ $category->name }} — {{ config('app.name') }}</title>
<meta name="description" content="{{ $category->description ?? 'Browse all articles in the ' . $category->name . ' category on ' . config('app.name') }}">
<meta property="og:title" content="{{ $category->name }} — {{ config('app.name') }}">
<meta property="og:description" content="{{ $category->description ?? 'Browse all articles in the ' . $category->name . ' category.' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
@if($category->image)
<meta property="og:image" content="{{ asset('storage/' . $category->image) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ── CATEGORY HEADER ── --}}
<section class="relative overflow-hidden bg-[#2C1A0E] dark:bg-[#1E1008] pt-28 pb-16">
    {{-- Decorative blobs --}}
    <div class="absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-amber-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 right-1/4 h-64 w-64 rounded-full bg-[#8C5A3C]/20 blur-3xl pointer-events-none"></div>
    {{-- Grid overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-size-[48px_48px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">

        {{-- Category image --}}
        @if($category->image)
        <div class="flex justify-center mb-6">
            <div class="h-20 w-20 overflow-hidden rounded-2xl shadow-lg">
                <img src="{{ asset('storage/' . $category->image) }}"
                     alt="{{ $category->name }}"
                     class="h-full w-full object-cover">
            </div>
        </div>
        @endif

        {{-- Category name --}}
        <h1 class="mb-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-[#FFF8D4] leading-tight">
            {{ $category->name }}
        </h1>

        {{-- Description --}}
        @if($category->description)
        <p class="mb-6 text-base leading-relaxed text-[#C4A080] max-w-2xl mx-auto">
            {{ $category->description }}
        </p>
        @endif

        {{-- Stats row --}}
        <div class="flex flex-wrap items-center justify-center gap-3">
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
                All categories
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
                No published articles in <span class="font-semibold text-[#2C1A0E] dark:text-[#FFF8D4]">{{ $category->name }}</span> yet. Check back soon.
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
                        @if($content->classification)
                        <span class="rounded-full bg-[#5C3A1E]/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $content->classification->name }}
                        </span>
                        @endif
                        <span class="rounded-full bg-amber-600/85 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $content->category->name }}
                        </span>
                        <span class="rounded-full bg-[#2C1A0E]/70 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white">
                            {{ $content->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                {{-- Meta strip --}}
                <div class="flex items-center gap-3 px-5 py-2.5 text-xs text-[#8C6040] dark:text-[#C4A080] border-b border-[#DDD090] dark:border-[#6B4540] bg-[#FFFDF0] dark:bg-[#4B3030]">
                    @if($content->user)
                    <span class="inline-flex items-center gap-1.5 min-w-0">
                        <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-amber-600/80 dark:bg-[#8C5A3C] text-white text-xs font-bold leading-none shrink-0">
                            {{ strtoupper(substr($content->user->name, 0, 1)) }}
                        </span>
                        <span class="truncate">{{ $content->user->name }}</span>
                    </span>
                    <span class="w-px h-3 bg-[#DDD090] dark:bg-[#6B4540] shrink-0"></span>
                    @endif
                    <span class="inline-flex items-center gap-1 shrink-0">
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

{{-- ── OTHER CATEGORIES ── --}}
@if($otherCategories->isNotEmpty())
<section class="bg-[#F5EDBA] dark:bg-[#3D2220] py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-amber-600 dark:text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
            </svg>
            <h2 class="text-xl font-bold text-[#2C1A0E] dark:text-[#FFF8D4]">Other Categories</h2>
        </div>

        @php $catColors = ['#FFF8D4','#FFE8CC','#FFDAC4','#FFECD8','#F8E8C0','#FFD8C0']; @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($otherCategories as $cat)
            @php $bg = $catColors[$loop->index % count($catColors)]; @endphp
            <a href="{{ route('category.show', $cat->slug) }}"
               class="group relative overflow-hidden rounded-2xl p-4 flex flex-col items-center text-center
                      dark:bg-[#5C3835] border border-transparent dark:border-[#6B4540] shadow-sm
                      transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#C8B870]
                      dark:hover:shadow-[0_12px_24px_rgba(0,0,0,0.4)] dark:hover:border-[#8C5A3C]"
               :style="darkMode ? {} : { backgroundColor: '{{ $bg }}' }">

                {{-- Icon --}}
                <div class="relative mb-3">
                    <div class="absolute inset-0 rounded-xl bg-amber-500/20 dark:bg-amber-500/10 scale-110 group-hover:scale-125 blur-sm transition-transform duration-500"></div>
                    <div class="relative h-11 w-11 rounded-xl bg-white/70 dark:bg-[#4B2E2B]/60 flex items-center justify-center shadow-sm
                                group-hover:bg-white dark:group-hover:bg-[#4B2E2B]/90 group-hover:scale-110 transition-all duration-300">
                        @if($cat->icon)
                            {!! svg($cat->icon, '', ['style' => 'width:1.25rem;height:1.25rem;color:#d97706'])->toHtml() !!}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:#d97706">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                            </svg>
                        @endif
                    </div>
                </div>

                <h3 class="text-sm font-bold text-[#2C1A0E] dark:text-[#FFF8D4] leading-tight mb-1">{{ $cat->name }}</h3>

                @if($cat->description)
                <p class="text-xs text-[#5C3A1E] dark:text-[#C4A080] leading-relaxed line-clamp-2 mb-2 px-1">{{ $cat->description }}</p>
                @else
                <div class="mb-2"></div>
                @endif

                <span class="inline-flex items-center rounded-full border border-amber-600/30 dark:border-[#8C5A3C]
                             bg-amber-600/10 dark:bg-[#8C5A3C]/20 px-2.5 py-0.5 text-xs font-semibold
                             text-amber-700 dark:text-amber-400
                             group-hover:bg-amber-600 group-hover:text-white group-hover:border-amber-600
                             dark:group-hover:bg-[#8C5A3C] dark:group-hover:text-[#FFF8D4] transition-all duration-300">
                    {{ $cat->contents_count }} {{ $cat->contents_count === 1 ? 'article' : 'articles' }}
                </span>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

@endsection
