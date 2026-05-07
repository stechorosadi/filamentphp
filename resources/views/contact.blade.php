@extends('layouts.front')

@section('seo')
<title>{{ __('ui.contact_page_title') }} — {{ $siteSetting->site_title }}</title>
<meta name="description" content="{{ __('ui.contact_meta_desc') }}">
<meta property="og:title" content="{{ __('ui.contact_page_title') }} — {{ $siteSetting->site_title }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 1 · HERO                                   --}}
{{-- ══════════════════════════════════════════════════ --}}
<section class="pt-28 md:pt-36 overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-72 rounded-3xl overflow-hidden shadow-lg">

            {{-- Left: accent bg + big heading --}}
            <div class="relative flex flex-col justify-center px-10 py-14
                        bg-[var(--accent)]">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:40px_40px] pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
                <div class="relative">
                    <p class="mb-4 text-xs font-bold uppercase tracking-widest text-white/70">
                        {{ __('ui.contact_us') }}
                    </p>
                    <h1 class="text-3xl sm:text-4xl xl:text-5xl font-bold text-white leading-tight max-w-md">
                        {{ __('ui.contact_hero_heading') }}
                    </h1>
                </div>
            </div>

            {{-- Right: description + social icons --}}
            <div class="flex flex-col justify-center px-10 py-14 bg-[var(--bg-card)]">
                <p class="text-base leading-relaxed text-[var(--text-muted)] max-w-md mb-10">
                    {{ __('ui.contact_hero_desc') }}
                </p>

                @php
                    $socials = array_filter([
                        'facebook'  => $siteSetting->facebook_url  ?? null,
                        'instagram' => $siteSetting->instagram_url ?? null,
                        'x'         => $siteSetting->x_url         ?? null,
                        'youtube'   => $siteSetting->youtube_url   ?? null,
                    ]);
                @endphp
                @if($socials)
                <div>
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">
                        {{ __('ui.contact_follow_us') }}
                    </p>
                    <div class="flex gap-3">
                        @foreach($socials as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           aria-label="{{ ucfirst($platform) }}"
                           class="inline-flex h-11 w-11 items-center justify-center rounded-full
                                  bg-[var(--text-primary)] text-[var(--bg-primary)]
                                  hover:bg-[var(--accent)] hover:text-white
                                  transition-all duration-200 shadow-md">
                            @if($platform === 'facebook')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            @elseif($platform === 'instagram')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.163 12 18.163s6.162-2.759 6.162-6.162S15.403 5.838 12 5.838zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            @elseif($platform === 'x')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            @elseif($platform === 'youtube')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 2 · CONTACT INFO CARDS                    --}}
{{-- ══════════════════════════════════════════════════ --}}
<section class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @php
            $infoCards = array_filter([
                [
                    'label' => __('ui.contact_info_address'),
                    'value' => $siteSetting->contact_address ?? null,
                    'href'  => ($siteSetting->contact_address ?? null)
                                ? 'https://maps.google.com/?q=' . urlencode($siteSetting->contact_address)
                                : null,
                    'icon'  => 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z',
                ],
                [
                    'label' => __('ui.contact_info_email'),
                    'value' => $siteSetting->contact_email ?? null,
                    'href'  => ($siteSetting->contact_email ?? null)
                                ? 'mailto:' . $siteSetting->contact_email
                                : null,
                    'icon'  => 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75',
                ],
                [
                    'label' => __('ui.contact_info_phone'),
                    'value' => $siteSetting->contact_phone ?? null,
                    'href'  => ($siteSetting->contact_phone ?? null)
                                ? 'tel:' . preg_replace('/\s+/', '', $siteSetting->contact_phone)
                                : null,
                    'icon'  => 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z',
                ],
                [
                    'label' => __('ui.contact_info_hours'),
                    'value' => $siteSetting->contact_working_hours ?? null,
                    'href'  => null,
                    'icon'  => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                ],
            ], fn ($c) => ! empty($c['value']));
        @endphp

        @if($infoCards)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($infoCards as $card)
            <div class="group relative rounded-2xl
                        bg-[var(--bg-card)] border border-[var(--border)]
                        p-6 transition-shadow duration-200 hover:shadow-lg overflow-hidden">
                <div class="absolute inset-0 rounded-2xl bg-[var(--accent)]/0 group-hover:bg-[var(--accent)]/5 transition-colors duration-300 pointer-events-none"></div>

                <div class="relative">
                    <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--accent)]/15">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-[var(--accent)]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-1">
                        {{ $card['label'] }}
                    </p>
                    @if($card['href'])
                    <a href="{{ $card['href'] }}"
                       target="{{ str_starts_with($card['href'], 'http') ? '_blank' : '_self' }}"
                       rel="{{ str_starts_with($card['href'], 'http') ? 'noopener noreferrer' : '' }}"
                       class="text-sm font-semibold text-[var(--text-primary)] hover:text-[var(--accent)] leading-snug break-words transition-colors duration-200">
                        {{ $card['value'] }}
                    </a>
                    @else
                    <p class="text-sm font-semibold text-[var(--text-primary)] leading-snug break-words">
                        {{ $card['value'] }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>


{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 3 · FORM + SIDE PANEL                     --}}
{{-- ══════════════════════════════════════════════════ --}}
<section class="pb-20 pt-4">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">

            {{-- LEFT: decorative block + partnership card --}}
            <div class="flex flex-col gap-5">

                {{-- Map embed --}}
                <div class="relative overflow-hidden rounded-3xl flex-1 min-h-64 bg-[var(--bg-alt)]">
                    @if($siteSetting->maps_embed_url)
                    <iframe
                        src="{{ $siteSetting->maps_embed_url }}"
                        style="border:0; position:absolute; inset:0; width:100%; height:100%;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Office location">
                    </iframe>
                    @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3"
                         style="background: linear-gradient(135deg, var(--accent) 0%, var(--bg-alt) 70%);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-white/70">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <p class="text-sm font-medium text-white/80 px-6 text-center">
                            {{ $siteSetting->contact_address ?? __('ui.contact_info_address') }}
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Partnership card --}}
                @if($siteSetting->contact_email)
                <div class="flex items-center justify-between rounded-2xl bg-[var(--bg-card)] border border-[var(--border)] p-5 gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mb-1">
                            {{ __('ui.contact_partnership_label') }}
                        </p>
                        <a href="mailto:{{ $siteSetting->contact_email }}"
                           class="text-sm font-semibold text-[var(--accent)] hover:underline break-all">
                            {{ $siteSetting->contact_email }}
                        </a>
                    </div>
                    <a href="mailto:{{ $siteSetting->contact_email }}"
                       class="shrink-0 inline-flex h-9 w-9 items-center justify-center rounded-full
                              bg-[var(--text-primary)] text-[var(--bg-primary)]
                              hover:bg-[var(--accent)] hover:text-white transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
                @endif

            </div>

            {{-- RIGHT: contact form --}}
            <div class="rounded-3xl bg-[var(--bg-card)] border border-[var(--border)] p-8 sm:p-10">

                <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-1">
                    {{ __('ui.contact_form_heading') }}
                </h2>
                <div class="h-1 w-12 rounded-full bg-[var(--accent)] mb-8"></div>

                {{-- Success flash --}}
                @if(session('success'))
                <div class="mb-6 rounded-xl bg-[var(--accent)]/15 border border-[var(--accent)]/30 px-5 py-4
                            flex items-start gap-3 text-sm text-[var(--text-primary)]"
                     x-data x-init="setTimeout(() => $el.remove(), 6000)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="h-5 w-5 shrink-0 text-[var(--accent)] mt-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif


                <form method="POST"
                      action="{{ route('contact.store', ['locale' => app()->getLocale()]) }}"
                      novalidate>
                    @csrf

                    {{-- Row 1: First Name + Last Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2"
                                   for="first_name">
                                {{ __('ui.contact_first_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name"
                                   value="{{ old('first_name') }}"
                                   placeholder="{{ __('ui.contact_first_name') }}"
                                   required maxlength="50"
                                   class="w-full border-0 border-b-2 border-[var(--border)] focus:border-[var(--accent)]
                                          bg-transparent text-[var(--text-primary)] placeholder-[var(--text-muted)]/60
                                          py-2.5 text-sm outline-none transition-colors duration-200
                                          @error('first_name') border-red-400 @enderror">
                            @error('first_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2"
                                   for="last_name">
                                {{ __('ui.contact_last_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                   value="{{ old('last_name') }}"
                                   placeholder="{{ __('ui.contact_last_name') }}"
                                   required maxlength="50"
                                   class="w-full border-0 border-b-2 border-[var(--border)] focus:border-[var(--accent)]
                                          bg-transparent text-[var(--text-primary)] placeholder-[var(--text-muted)]/60
                                          py-2.5 text-sm outline-none transition-colors duration-200
                                          @error('last_name') border-red-400 @enderror">
                            @error('last_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Email + Phone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2"
                                   for="email">
                                {{ __('ui.contact_email_label') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="{{ __('ui.contact_email_label') }}"
                                   required maxlength="150"
                                   class="w-full border-0 border-b-2 border-[var(--border)] focus:border-[var(--accent)]
                                          bg-transparent text-[var(--text-primary)] placeholder-[var(--text-muted)]/60
                                          py-2.5 text-sm outline-none transition-colors duration-200
                                          @error('email') border-red-400 @enderror">
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2"
                                   for="phone">
                                {{ __('ui.contact_phone_label') }}
                            </label>
                            <input type="tel" id="phone" name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="{{ __('ui.contact_phone_label') }}"
                                   maxlength="30"
                                   class="w-full border-0 border-b-2 border-[var(--border)] focus:border-[var(--accent)]
                                          bg-transparent text-[var(--text-primary)] placeholder-[var(--text-muted)]/60
                                          py-2.5 text-sm outline-none transition-colors duration-200">
                        </div>
                    </div>

                    {{-- Row 3: Message --}}
                    <div class="mb-6">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2"
                               for="message">
                            {{ __('ui.contact_message') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" name="message"
                                  rows="5" maxlength="2000" required
                                  placeholder="{{ __('ui.contact_message_placeholder') }}"
                                  class="w-full rounded-xl border border-[var(--border)] focus:border-[var(--accent)]
                                         bg-[var(--bg-primary)] text-[var(--text-primary)] placeholder-[var(--text-muted)]/60
                                         px-4 py-3 text-sm resize-y
                                         outline-none transition-colors duration-200
                                         @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Turnstile --}}
                    @if(config('services.turnstile.site_key'))
                    <div class="mb-4">
                        <input type="hidden" name="turnstile_token" value="">
                        @include('partials.turnstile-frontend')
                        @error('turnstile_token')
                        <p class="mt-1 text-center text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    {{-- Row 4: Terms + Submit --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 pt-1">
                        <div>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="agreed_terms" value="1"
                                       {{ old('agreed_terms') ? 'checked' : '' }}
                                       class="mt-0.5 h-4 w-4 shrink-0 rounded border-[var(--border)] cursor-pointer accent-[var(--accent)]">
                                <span class="text-sm text-[var(--text-muted)] leading-snug">
                                    {{ __('ui.contact_terms_text') }}
                                </span>
                            </label>
                            @error('agreed_terms')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="shrink-0 inline-flex items-center gap-2
                                       rounded-xl bg-[var(--text-primary)] hover:bg-[var(--accent)]
                                       px-7 py-3 text-sm font-semibold text-[var(--bg-primary)] hover:text-white
                                       shadow-lg transition-all duration-200">
                            {{ __('ui.contact_send_btn') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

@endsection
