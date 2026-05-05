<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- ── Static pages ── --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ lroute('search') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ lroute('team') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ lroute('archive') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.4</priority>
    </url>

    {{-- ── Articles ── --}}
    @foreach($contents as $content)
    <url>
        <loc>{{ lroute('content.show', ['slug' => $content->slug]) }}</loc>
        <lastmod>{{ $content->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- ── Categories ── --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ lroute('category.show', ['slug' => $category->slug]) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- ── Classifications ── --}}
    @foreach($classifications as $classification)
    <url>
        <loc>{{ lroute('classification.show', ['slug' => $classification->slug]) }}</loc>
        <lastmod>{{ $classification->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- ── Tags ── --}}
    @foreach($tags as $tag)
    <url>
        <loc>{{ lroute('tag.show', ['slug' => $tag->slug]) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    {{-- ── Team member profiles ── --}}
    @foreach($teamMembers as $member)
    <url>
        <loc>{{ lroute('team.member', ['member' => $member->id]) }}</loc>
        <lastmod>{{ $member->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

</urlset>
