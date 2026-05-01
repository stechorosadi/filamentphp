<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentClassification;
use App\Models\TeamMember;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $search = mb_substr(request()->string('search')->trim()->value(), 0, 100);
        $categoryId = request()->integer('category') ?: null;
        $classificationId = request()->integer('classification') ?: null;

        $featuredContents = Content::with(['user', 'category', 'classification'])
            ->where('featured', true)
            ->where('published', true)
            ->whereNotNull('featured_image')
            ->latest()
            ->limit(5)
            ->get();

        $featuredIds = $featuredContents->modelKeys();

        $latestContents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->whereNotIn('id', $featuredIds)
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
            ))
            ->when($categoryId, fn ($q) => $q->where('content_category_id', $categoryId))
            ->when($classificationId, fn ($q) => $q->where('content_classification_id', $classificationId))
            ->latest()
            ->paginate(9);

        $categories = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->orderBy('name')
            ->get();

        $classifications = ContentClassification::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->orderBy('name')
            ->get();

        $totalArticles = Content::where('published', true)->count();

        $popularContents = Content::with(['category', 'user'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        $teamMembers = TeamMember::with('user')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact(
            'featuredContents', 'latestContents', 'categories', 'classifications',
            'search', 'totalArticles', 'popularContents', 'teamMembers'
        ));
    }

    public function search(): View
    {
        $query = mb_substr(request()->string('q')->trim()->value(), 0, 100);

        $results = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->when($query, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$query}%")
                ->orWhere('excerpt', 'like', "%{$query}%")
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$query}%"))
                ->orWhereHas('classification', fn ($c) => $c->where('name', 'like', "%{$query}%"))
                ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$query}%"))
            ))
            ->latest()
            ->paginate(12);

        $suggestions = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('search', compact('query', 'results', 'suggestions'));
    }

    public function category(string $slug): View
    {
        $category = ContentCategory::where('slug', $slug)->firstOrFail();

        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('content_category_id', $category->id)
            ->whereNotNull('header_image')
            ->latest()
            ->paginate(9);

        $otherCategories = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('category.show', compact('category', 'contents', 'otherCategories'));
    }

    public function classification(string $slug): View
    {
        $classification = ContentClassification::where('slug', $slug)->firstOrFail();

        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('content_classification_id', $classification->id)
            ->whereNotNull('header_image')
            ->latest()
            ->paginate(9);

        $otherClassifications = ContentClassification::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->where('id', '!=', $classification->id)
            ->orderBy('name')
            ->get();

        return view('classification.show', compact('classification', 'contents', 'otherClassifications'));
    }

    public function show(string $slug): View
    {
        $content = Content::with([
            'user', 'category', 'classification', 'tags',
            'imageAttachments', 'fileAttachments', 'linkAttachments',
        ])
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        // Count once per session per article
        $sessionKey = "viewed_content_{$content->getKey()}";
        if (! session()->has($sessionKey)) {
            $content->increment('views');
            session()->put($sessionKey, true);
        }

        $relatedContents = Content::with(['category', 'user'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->where('id', '!=', $content->getKey())
            ->when(
                $content->content_category_id,
                fn ($q) => $q->where('content_category_id', $content->content_category_id),
                fn ($q) => $q->where('content_classification_id', $content->content_classification_id)
            )
            ->latest()
            ->limit(3)
            ->get();

        return view('content.show', compact('content', 'relatedContents'));
    }

    public function team(): View
    {
        $teamMembers = TeamMember::with('user')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        return view('team.index', compact('teamMembers'));
    }

    public function pdf(string $slug): Response
    {
        $content = Content::with([
            'user', 'category', 'classification', 'tags',
            'imageAttachments', 'fileAttachments', 'linkAttachments',
        ])
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $pdf = Pdf::setOptions(['enable_remote' => true, 'isRemoteEnabled' => true])
            ->loadView('content.pdf', compact('content'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(str($content->slug)->slug().'.pdf');
    }

    public function memberPdf(TeamMember $member): Response
    {
        abort_if(! $member->is_visible, 404);

        $member->load([
            'user.educationHistory',
            'user.workExperience',
            'user.certifications',
            'user.publications',
        ]);

        $pdf = Pdf::setOptions(['enable_remote' => true, 'isRemoteEnabled' => true])
            ->loadView('team.pdf', compact('member'))
            ->setPaper('a4', 'portrait');

        $filename = str($member->fullName())->slug()->append('.pdf')->value();

        return $pdf->download($filename);
    }

    public function memberShow(TeamMember $member): View
    {
        abort_if(! $member->is_visible, 404);

        $member->load([
            'user.educationHistory',
            'user.workExperience',
            'user.certifications',
            'user.publications',
        ]);

        return view('team.show', compact('member'));
    }
}
