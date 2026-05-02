<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title }}</title>
    <style>
        @page {
            margin: 20mm 18mm 20mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        /* ── Header bar ── */
        .header {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #4F772D;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header-logo {
            width: 45px;
            padding-right: 12px;
            vertical-align: middle;
        }

        .header-logo img {
            height: 44px;
            width: auto;
            display: block;
        }

        .header-text {
            vertical-align: middle;
        }

        .site-name {
            font-size: 9pt;
            font-weight: bold;
            color: #4F772D;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .site-url {
            font-size: 8pt;
            color: #888;
        }

        /* ── Meta badges ── */
        .badges {
            margin-bottom: 10px;
        }

        .badge {
            display: inline-block;
            background: #4F772D;
            color: #fff;
            font-size: 8pt;
            font-weight: bold;
            padding: 2px 8px 4px 8px;
            border-radius: 20px;
            margin-right: 5px;
            line-height: 1;
            vertical-align: middle;
        }

        .badge-light {
            background: #ECF39E;
            color: #31572C;
        }

        /* ── Title ── */
        h1.article-title {
            font-size: 16pt;
            font-weight: bold;
            color: #132A13;
            line-height: 1.3;
            margin: 0 0 12px 0;
            padding: 0;
        }

        /* ── Meta row ── */
        .meta {
            font-size: 9pt;
            color: #555;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #ddd;
        }

        .meta span {
            margin-right: 14px;
        }

        /* ── Header image ── */
        .header-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
            margin-bottom: 18px;
        }

        /* ── Excerpt ── */
        .excerpt {
            background: #f5fbe8;
            border-left: 4px solid #4F772D;
            padding: 10px 14px;
            font-size: 11pt;
            font-style: italic;
            color: #31572C;
            margin-bottom: 22px;
            border-radius: 0 4px 4px 0;
        }

        /* ── Article content ── */
        .content {
            overflow: hidden;
        }

        .content h1, .content h2, .content h3,
        .content h4, .content h5, .content h6 {
            color: #132A13;
            margin-top: 20px;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .content h1 { font-size: 16pt; }
        .content h2 { font-size: 14pt; }
        .content h3 { font-size: 12pt; }

        .content p {
            margin: 0 0 12px 0;
        }

        .content ul, .content ol {
            margin: 0 0 12px 18px;
            padding: 0;
        }

        .content li {
            margin-bottom: 4px;
        }

        .content blockquote {
            border-left: 4px solid #4F772D;
            margin: 14px 0;
            padding: 8px 14px;
            color: #444;
            background: #f9f9f9;
        }

        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .content a {
            color: #4F772D;
            text-decoration: underline;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10pt;
        }

        .content table th {
            background: #4F772D;
            color: #fff;
            padding: 6px 10px;
            text-align: left;
        }

        .content table td {
            padding: 6px 10px;
            border-bottom: 1px solid #ddd;
        }

        .content table tr:nth-child(even) td {
            background: #f5fbe8;
        }

        /* ── Tags ── */
        .tags {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #ddd;
        }

        .tags-label {
            font-size: 9pt;
            font-weight: bold;
            color: #555;
            margin-bottom: 6px;
        }

        .tag {
            display: inline-block;
            border: 1px solid #4F772D;
            color: #4F772D;
            font-size: 8pt;
            padding: 2px 8px 4px 8px;
            border-radius: 20px;
            margin-right: 4px;
            margin-bottom: 4px;
            line-height: 1;
            vertical-align: middle;
        }

        /* ── Attachment sections ── */
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #132A13;
            margin: 28px 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 2px solid #4F772D;
        }

        .yt-block {
            margin-bottom: 18px;
            text-align: center;
            page-break-inside: avoid;
        }

        .yt-thumb {
            width: 70%;
            height: auto;
            border-radius: 6px;
            display: inline-block;
        }

        .yt-url {
            font-size: 8pt;
            color: #4F772D;
            margin-top: 5px;
            word-break: break-all;
        }

        .img-gallery {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 8px;
        }

        .img-gallery td {
            width: 50%;
            vertical-align: top;
            padding: 0 4px 0 0;
        }

        .img-masonry-item {
            margin-bottom: 8px;
            overflow: hidden;
        }

        .img-masonry-item img {
            width: 100%;
            height: 180px;
            border-radius: 4px;
            display: block;
        }

        .img-caption {
            font-size: 8pt;
            color: #666;
            margin-top: 3px;
            font-style: italic;
            text-align: center;
        }

        .file-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .file-list li {
            display: block;
            padding: 8px 10px;
            background: #f5fbe8;
            border: 1px solid #dde;
            border-radius: 4px;
            margin-bottom: 6px;
            font-size: 9pt;
        }

        .file-name {
            font-weight: bold;
            color: #132A13;
        }

        .file-path {
            color: #888;
            font-size: 8pt;
            word-break: break-all;
        }

        .link-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .link-list li {
            padding: 7px 10px;
            border-left: 3px solid #4F772D;
            background: #f9f9f9;
            margin-bottom: 6px;
            font-size: 9pt;
        }

        .link-label {
            font-weight: bold;
            color: #132A13;
        }

        .link-url {
            color: #4F772D;
            word-break: break-all;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 28px;
            padding-top: 10px;
            border-top: 2px solid #4F772D;
            font-size: 8pt;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header">
        <tr>
            @if($siteSetting->logo_path)
            <td class="header-logo">
                <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="logo">
            </td>
            @endif
            <td class="header-text">
                <div class="site-name">{{ $siteSetting->site_title ?? config('app.name') }}</div>
                <div class="site-url">{{ url('/') }}</div>
            </td>
        </tr>
    </table>

    {{-- Badges --}}
    <div class="badges">
        @if($content->classification)
        <span class="badge">{{ $content->classification->name }}</span>
        @endif
        @if($content->category)
        <span class="badge badge-light">{{ $content->category->name }}</span>
        @endif
    </div>

    {{-- Title --}}
    <h1 class="article-title">{{ $content->title }}</h1>

    {{-- Meta --}}
    <div class="meta">
        <span>By {{ $content->user->name }}</span>
        <span>{{ $content->created_at->format('F d, Y') }}</span>
        @if($content->views)
        <span>{{ number_format($content->views) }} views</span>
        @endif
        <span>Source: {{ url()->route('content.show', $content->slug) }}</span>
    </div>

    {{-- Header image --}}
    @if($content->header_image)
    <img class="header-image" src="{{ asset("storage/{$content->header_image}") }}" alt="{{ $content->title }}">
    @endif

    {{-- Excerpt --}}
    @if($content->excerpt)
    <div class="excerpt">{{ $content->excerpt }}</div>
    @endif

    {{-- Main content --}}
    <div class="content">
        {!! $content->content !!}
    </div>

    {{-- Tags --}}
    @if($content->tags->isNotEmpty())
    <div class="tags">
        <div class="tags-label">Tags</div>
        @foreach($content->tags as $tag)
        <span class="tag">{{ $tag->name }}</span>
        @endforeach
    </div>
    @endif

    {{-- YouTube thumbnail --}}
    @if($content->youtube_url)
    @php
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^&\s?]+)/', $content->youtube_url, $m);
        $ytId = $m[1] ?? null;
    @endphp
    @if($ytId)
    <div class="yt-block">
        <div class="section-title">Video</div>
        <img class="yt-thumb" src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" alt="Video thumbnail">
        <div class="yt-url">{{ $content->youtube_url }}</div>
    </div>
    @endif
    @endif

    {{-- Image attachments --}}
    @if($content->imageAttachments->isNotEmpty())
    <div class="section-title">Images</div>
    @php
        $leftImages  = $content->imageAttachments->filter(fn ($img, $i) => $i % 2 === 0)->values();
        $rightImages = $content->imageAttachments->filter(fn ($img, $i) => $i % 2 === 1)->values();
    @endphp
    <table class="img-gallery">
        <tr>
            <td>
                @foreach($leftImages as $img)
                <div class="img-masonry-item">
                    <img src="{{ asset("storage/{$img->path}") }}" alt="{{ $img->caption ?? '' }}">
                    @if($img->caption)<div class="img-caption">{{ $img->caption }}</div>@endif
                </div>
                @endforeach
            </td>
            <td>
                @foreach($rightImages as $img)
                <div class="img-masonry-item">
                    <img src="{{ asset("storage/{$img->path}") }}" alt="{{ $img->caption ?? '' }}">
                    @if($img->caption)<div class="img-caption">{{ $img->caption }}</div>@endif
                </div>
                @endforeach
            </td>
        </tr>
    </table>
    @endif

    {{-- File attachments --}}
    @if($content->fileAttachments->isNotEmpty())
    <div class="section-title">File Attachments</div>
    <ul class="file-list">
        @foreach($content->fileAttachments as $file)
        <li>
            <div>
                <div class="file-name">{{ $file->original_name }}</div>
                <div class="file-path">{{ asset('storage/' . $file->path) }}</div>
            </div>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Link attachments --}}
    @if($content->linkAttachments->isNotEmpty())
    <div class="section-title">Related Links</div>
    <ul class="link-list">
        @foreach($content->linkAttachments as $link)
        <li>
            @if($link->label)<span class="link-label">{{ $link->label }}: </span>@endif
            <span class="link-url">{{ $link->url }}</span>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Footer --}}
    <div class="footer">
        This document was exported from {{ $siteSetting->site_title ?? config('app.name') }} &mdash; {{ url('/') }}
        &nbsp;|&nbsp; Generated on {{ now()->format('F d, Y') }}
    </div>

</body>
</html>
