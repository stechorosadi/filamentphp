<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- ── Static pages ── --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('search') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('team') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('archive') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.4</priority>
    </url>

    {{-- ── Articles ── --}}
    @foreach($contents as $content)
    <url>
        <loc>{{ route('content.show', $content->slug) }}</loc>
        <lastmod>{{ $content->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- ── Categories ── --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('category.show', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- ── Classifications ── --}}
    @foreach($classifications as $classification)
    <url>
        <loc>{{ route('classification.show', $classification->slug) }}</loc>
        <lastmod>{{ $classification->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- ── Tags ── --}}
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('tag.show', $tag->slug) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    {{-- ── Team member profiles ── --}}
    @foreach($teamMembers as $member)
    <url>
        <loc>{{ route('team.member', $member->id) }}</loc>
        <lastmod>{{ $member->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

</urlset>
