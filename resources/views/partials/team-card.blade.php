<div class="group relative flex flex-col rounded-2xl overflow-hidden border border-(--border) bg-(--bg-card) hover:-translate-y-1 hover:shadow-lg transition-all duration-300">

    {{-- Full-card link (behind social icons) --}}
    <a href="{{ lroute('team.member', [$member->getKey()]) }}" class="absolute inset-0 z-10" aria-label="{{ $member->fullName() }}"></a>

    {{-- Photo --}}
    <div class="relative aspect-4/3 w-full overflow-hidden bg-(--bg-alt)">
        @if($member->photo)
        <img src="{{ asset("storage/{$member->photo}") }}"
             alt="{{ $member->fullName() }}"
             loading="lazy"
             class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
        @else
        <div class="w-full h-full flex items-center justify-center bg-(--accent)/10">
            <span class="text-4xl font-black text-(--accent)">{{ strtoupper(substr($member->fullName() ?: '?', 0, 1)) }}</span>
        </div>
        @endif

        @if(!empty($showStatusBadge) && $member->status !== \App\Enums\TeamMemberStatus::Active)
        @php
            $badgeClass = $member->status === \App\Enums\TeamMemberStatus::Retired
                ? 'bg-gray-500/80 text-white'
                : 'bg-amber-500/80 text-white';
        @endphp
        <span class="absolute top-2 left-2 z-20 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeClass }} backdrop-blur-sm">
            {{ __('ui.status_' . $member->status->value) }}
        </span>
        @endif
    </div>

    {{-- Info --}}
    <div class="relative p-3 flex flex-col gap-2">
        <div>
            <h3 class="text-base font-bold text-(--text-primary) leading-snug">{{ $member->fullName() }}</h3>
            <p class="text-sm text-(--text-muted) mt-0.5">{{ $member->position }}</p>
        </div>

        {{-- Social icons --}}
        @php
            $socials = array_filter([
                'instagram' => $member->instagram_url,
                'facebook'  => $member->facebook_url,
                'x'         => $member->x_url,
                'threads'   => $member->threads_url,
                'youtube'   => $member->youtube_url,
            ]);
        @endphp
        @if($socials)
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($socials as $platform => $url)
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
               class="relative z-20 text-(--accent) hover:text-(--text-primary) transition-colors"
               aria-label="{{ ucfirst($platform) }}">
                @if($platform === 'instagram')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                @elseif($platform === 'facebook')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                @elseif($platform === 'x')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                @elseif($platform === 'threads')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.589 12c.027 3.086.718 5.496 2.057 7.164 1.43 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.31-.71-.873-1.3-1.634-1.75-.192 1.352-.622 2.446-1.284 3.272-.886 1.102-2.14 1.704-3.73 1.79-1.202.065-2.361-.218-3.259-.801-1.063-.689-1.685-1.74-1.752-2.964-.065-1.19.408-2.285 1.33-3.082.88-.76 2.119-1.207 3.583-1.291a13.853 13.853 0 0 1 3.02.142c-.126-.742-.375-1.332-.75-1.757-.513-.586-1.308-.883-2.359-.89h-.029c-.844 0-1.992.232-2.721 1.32L7.734 9.13c.66-1.04 1.516-1.822 2.545-2.33.984-.482 2.092-.737 3.22-.737h.033c2.508.017 4.178 1.131 4.845 3.23.18.563.28 1.162.298 1.783a8.516 8.516 0 0 1 1.342.492c1.155.533 2.038 1.373 2.554 2.43.78 1.64.882 4.22-.913 5.982-1.816 1.78-4.059 2.595-7.47 2.62l-.002-.62zm.176-8.288c-.085 0-.17.002-.255.006-1.099.06-1.942.372-2.41.876-.359.388-.505.87-.472 1.376.054.964.85 1.617 2.041 1.617h.028c.96-.053 1.682-.43 2.148-1.12.406-.6.622-1.42.644-2.432a11.765 11.765 0 0 0-1.724-.323z"/></svg>
                @elseif($platform === 'youtube')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                @endif
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
