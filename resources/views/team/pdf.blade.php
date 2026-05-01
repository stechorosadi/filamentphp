<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $member->fullName() }}</title>
    <style>
        @page {
            margin: 20mm 18mm 20mm 18mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        /* ── Header ── */
        .header {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #4F772D;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header-logo { width: 45px; padding-right: 12px; vertical-align: middle; }
        .header-logo img { height: 40px; width: auto; display: block; }
        .header-text { vertical-align: middle; }

        .site-name {
            font-size: 9pt; font-weight: bold; color: #4F772D;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .site-url { font-size: 8pt; color: #888; }

        /* ── Profile hero ── */
        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #f5fbe8;
            border-radius: 8px;
        }

        .profile-photo-cell {
            width: 100px;
            padding: 16px 16px 16px 16px;
            vertical-align: middle;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            object-position: top;
            border-radius: 8px;
            display: block;
        }

        .profile-initials {
            width: 100px;
            height: 100px;
            background: #4F772D;
            border-radius: 8px;
            display: block;
            text-align: center;
            line-height: 90px;
            font-size: 32pt;
            font-weight: bold;
            color: #ECF39E;
        }

        .profile-info-cell {
            padding: 16px 16px 16px 0;
            vertical-align: middle;
        }

        .profile-front-title {
            font-size: 8pt;
            color: #4F772D;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .profile-name {
            font-size: 14pt;
            font-weight: bold;
            color: #132A13;
            line-height: 1.2;
            margin: 0 0 4px 0;
        }

        .profile-back-title {
            font-size: 10pt;
            color: #4F772D;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .profile-position {
            font-size: 10pt;
            color: #555;
            margin-bottom: 4px;
        }

        .profile-id {
            font-size: 8pt;
            color: #999;
        }

        /* ── Section headings ── */
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #132A13;
            margin: 22px 0 12px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #4F772D;
        }

        /* ── Two-column timeline ── */
        .timeline-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
            margin-bottom: 8px;
        }

        .timeline-col {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .timeline-col-title {
            font-size: 11pt;
            font-weight: bold;
            color: #132A13;
            padding-bottom: 5px;
            border-bottom: 2px solid #4F772D;
            margin-bottom: 12px;
        }

        .timeline-item {
            padding: 0 0 14px 0;
            border-left: 2px solid #ddd;
            margin-left: 8px;
            padding-left: 12px;
            margin-bottom: 4px;
        }

        .timeline-year {
            display: inline-block;
            border: 1px solid #4F772D;
            color: #4F772D;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 2px 7px 3px 7px;
            border-radius: 20px;
            line-height: 1;
            vertical-align: middle;
            margin-bottom: 5px;
        }

        .timeline-org {
            font-size: 8pt;
            color: #888;
            margin-left: 6px;
            vertical-align: middle;
        }

        .timeline-title {
            font-size: 10pt;
            font-weight: bold;
            color: #132A13;
            margin: 3px 0 3px 0;
        }

        .timeline-sub {
            font-size: 9pt;
            color: #555;
        }

        .timeline-desc {
            font-size: 8.5pt;
            color: #666;
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ── Certifications grid ── */
        .cert-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 8px;
        }

        .cert-cell {
            width: 50%;
            vertical-align: top;
            background: #f5fbe8;
            border: 1px solid #d8edb5;
            border-radius: 6px;
            padding: 10px 12px;
        }

        .cert-badge {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            padding: 2px 7px 3px 7px;
            border-radius: 20px;
            line-height: 1;
            vertical-align: middle;
            margin-bottom: 5px;
        }

        .cert-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #132A13;
            margin-bottom: 3px;
        }

        .cert-issuer { font-size: 8.5pt; color: #4F772D; }
        .cert-year {
            display: inline-block;
            border: 1px solid #4F772D;
            color: #4F772D;
            font-size: 7pt;
            font-weight: bold;
            padding: 1px 7px 2px 7px;
            border-radius: 20px;
            line-height: 1;
            vertical-align: middle;
            margin-bottom: 5px;
        }

        /* ── Badge colours ── */
        .bg-training       { background: #dbeafe; color: #1d4ed8; }
        .bg-seminar        { background: #ede9fe; color: #6d28d9; }
        .bg-workshop       { background: #fef3c7; color: #b45309; }
        .bg-professional   { background: #d1fae5; color: #065f46; }
        .bg-online         { background: #e0f2fe; color: #0369a1; }
        .bg-other          { background: #f3f4f6; color: #374151; }

        /* ── Publications ── */
        .pub-item {
            padding: 10px 12px;
            border-left: 3px solid #4F772D;
            background: #f9fafb;
            margin-bottom: 8px;
            border-radius: 0 4px 4px 0;
        }

        .pub-badge {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            padding: 2px 7px 3px 7px;
            border-radius: 20px;
            line-height: 1;
            vertical-align: middle;
            margin-bottom: 4px;
        }

        .bg-book       { background: #d1fae5; color: #065f46; }
        .bg-journal    { background: #dbeafe; color: #1d4ed8; }
        .bg-research   { background: #ede9fe; color: #6d28d9; }
        .bg-conference { background: #fef3c7; color: #b45309; }

        .pub-year {
            display: inline-block;
            border: 1px solid #4F772D;
            color: #4F772D;
            font-size: 7pt;
            font-weight: bold;
            padding: 1px 7px 2px 7px;
            border-radius: 20px;
            line-height: 1;
            vertical-align: middle;
            margin-bottom: 4px;
        }

        .pub-title  { font-size: 10pt; font-weight: bold; color: #132A13; margin-bottom: 3px; }
        .pub-meta   { font-size: 8.5pt; color: #4F772D; }
        .pub-desc   { font-size: 8.5pt; color: #666; margin-top: 4px; line-height: 1.5; }
        .pub-doi    { font-size: 8pt; color: #999; margin-top: 3px; }

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

@php
    $user = $member->user;
    $certLabels = [
        'training'                => ['label' => 'Training',      'class' => 'bg-training'],
        'seminar'                 => ['label' => 'Seminar',       'class' => 'bg-seminar'],
        'workshop'                => ['label' => 'Workshop',      'class' => 'bg-workshop'],
        'professional_certification' => ['label' => 'Professional', 'class' => 'bg-professional'],
        'online_course'           => ['label' => 'Online Course', 'class' => 'bg-online'],
    ];
    $pubLabels = [
        'book'             => ['label' => 'Book',             'class' => 'bg-book'],
        'journal_article'  => ['label' => 'Journal Article',  'class' => 'bg-journal'],
        'research_paper'   => ['label' => 'Research Paper',   'class' => 'bg-research'],
        'conference_paper' => ['label' => 'Conference Paper', 'class' => 'bg-conference'],
        'other'            => ['label' => 'Other',            'class' => 'bg-other'],
    ];
@endphp

{{-- Site header --}}
<table class="header">
    <tr>
        @if($siteSetting->logo_path)
        <td class="header-logo">
            <img src="{{ asset("storage/{$siteSetting->logo_path}") }}" alt="logo">
        </td>
        @endif
        <td class="header-text">
            <div class="site-name">{{ $siteSetting->site_title ?? config('app.name') }}</div>
            <div class="site-url">{{ url('/') }}</div>
        </td>
    </tr>
</table>

{{-- Profile hero --}}
<table class="profile-table">
    <tr>
        <td class="profile-photo-cell">
            @if($member->photo)
            <img class="profile-photo" src="{{ asset("storage/{$member->photo}") }}" alt="{{ $member->fullName() }}">
            @else
            <div class="profile-initials">{{ strtoupper(substr($member->fullName() ?: '?', 0, 1)) }}</div>
            @endif
        </td>
        <td class="profile-info-cell">
            @if($member->front_title)
            <div class="profile-front-title">{{ $member->front_title }}</div>
            @endif
            <div class="profile-name">{{ $user?->name ?? $member->name }}</div>
            @if($member->back_title)
            <div class="profile-back-title">{{ $member->back_title }}</div>
            @endif
            @if($member->position)
            <div class="profile-position">{{ $member->position }}</div>
            @endif
            @if($member->employee_number)
            <div class="profile-id">ID: {{ $member->employee_number }}</div>
            @endif
        </td>
    </tr>
</table>

{{-- Education & Experience side by side --}}
@if($user && ($user->educationHistory->isNotEmpty() || $user->workExperience->isNotEmpty()))
<table class="timeline-table">
    <tr>
        {{-- Education --}}
        <td class="timeline-col">
            @if($user->educationHistory->isNotEmpty())
            <div class="timeline-col-title">Education</div>
            @foreach($user->educationHistory as $edu)
            <div class="timeline-item">
                <span class="timeline-year">
                    {{ $edu->start_year }}{{ $edu->end_year ? ' – ' . $edu->end_year : ' – Present' }}
                </span>
                @if($edu->institution)
                <span class="timeline-org">{{ $edu->institution }}</span>
                @endif
                <div class="timeline-title">{{ $edu->degree }}@if($edu->field_of_study) <span class="timeline-sub">· {{ $edu->field_of_study }}</span>@endif</div>
                @if($edu->gpa)<div class="timeline-sub">GPA: {{ $edu->gpa }}</div>@endif
                @if($edu->description)<div class="timeline-desc">{{ $edu->description }}</div>@endif
            </div>
            @endforeach
            @endif
        </td>

        {{-- Experience --}}
        <td class="timeline-col">
            @if($user->workExperience->isNotEmpty())
            <div class="timeline-col-title">Experience</div>
            @foreach($user->workExperience as $exp)
            <div class="timeline-item">
                <span class="timeline-year">
                    {{ $exp->start_year }}{{ $exp->end_year ? ' – ' . $exp->end_year : ' – Current' }}
                </span>
                @if($exp->company)
                <span class="timeline-org">{{ $exp->company }}</span>
                @endif
                <div class="timeline-title">{{ $exp->job_title }}@if($exp->department) <span class="timeline-sub">· {{ $exp->department }}</span>@endif</div>
                @if($exp->description)<div class="timeline-desc">{{ $exp->description }}</div>@endif
            </div>
            @endforeach
            @endif
        </td>
    </tr>
</table>
@endif

{{-- Certifications --}}
@if($user && $user->certifications->isNotEmpty())
<div class="section-title">Certificates</div>
<table class="cert-table">
    @foreach($user->certifications->chunk(2) as $pair)
    <tr>
        @foreach($pair as $cert)
        @php $meta = $certLabels[$cert->category] ?? ['label' => ucfirst($cert->category ?? ''), 'class' => 'bg-other']; @endphp
        <td class="cert-cell">
            @if($cert->category)
            <span class="cert-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
            @endif
            @if($cert->issue_year)<span class="cert-year">{{ $cert->issue_year }}</span>@endif
            <div class="cert-title">{{ $cert->title }}</div>
            @if($cert->issuing_organization)<div class="cert-issuer">{{ $cert->issuing_organization }}</div>@endif
        </td>
        @endforeach
        @if($pair->count() === 1)<td class="cert-cell" style="background:transparent;border:none;"></td>@endif
    </tr>
    @endforeach
</table>
@endif

{{-- Publications --}}
@if($user && $user->publications->isNotEmpty())
<div class="section-title">Publications</div>
@foreach($user->publications as $pub)
@php $meta = $pubLabels[$pub->type] ?? ['label' => ucfirst(str_replace('_', ' ', $pub->type ?? '')), 'class' => 'bg-other']; @endphp
<div class="pub-item">
    <span class="pub-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
    @if($pub->year)<span class="pub-year">{{ $pub->year }}</span>@endif
    <div class="pub-title">{{ $pub->title }}</div>
    @if($pub->publisher)<div class="pub-meta">{{ $pub->publisher }}</div>@endif
    @if($pub->description)<div class="pub-desc">{{ \Illuminate\Support\Str::limit($pub->description, 200) }}</div>@endif
    @if($pub->doi || $pub->isbn)
    <div class="pub-doi">
        @if($pub->doi)DOI: {{ $pub->doi }}@endif
        @if($pub->doi && $pub->isbn) &nbsp;·&nbsp; @endif
        @if($pub->isbn)ISBN: {{ $pub->isbn }}@endif
    </div>
    @endif
</div>
@endforeach
@endif

{{-- Footer --}}
<div class="footer">
    {{ $member->fullName() }} &mdash; {{ $siteSetting->site_title ?? config('app.name') }}
    &nbsp;|&nbsp; Generated on {{ now()->format('F d, Y') }}
</div>

</body>
</html>
