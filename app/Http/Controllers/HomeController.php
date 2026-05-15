<?php

namespace App\Http\Controllers;

use App\Enums\TeamMemberStatus;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentClassification;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\TeamMember;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $orgSetting = SiteSetting::organization();
        if ($orgSetting->is_personal_site && SiteSetting::personal()->personal_member_id) {
            return $this->personalHome();
        }

        $locale = app()->getLocale();
        $search = mb_substr(request()->string('search')->trim()->value(), 0, 100);
        $categoryId = request()->integer('category') ?: null;
        $classificationId = request()->integer('classification') ?: null;

        $featuredContents = Content::with(['user', 'category', 'classification'])
            ->where('featured', true)
            ->where('published', true)
            ->where('archived', false)
            ->whereNotNull('featured_image')
            ->latest('article_date')
            ->limit(5)
            ->get();

        $featuredIds = $featuredContents->modelKeys();

        $latestContents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('archived', false)
            ->whereNotNull('header_image')
            ->whereNotIn('id', $featuredIds)
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"$locale\"')) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(excerpt, '$.\"$locale\"')) LIKE ?", ["%{$search}%"])
            ))
            ->when($categoryId, fn ($q) => $q->where('content_category_id', $categoryId))
            ->when($classificationId, fn ($q) => $q->where('content_classification_id', $classificationId))
            ->latest('article_date')
            ->paginate(9);

        $categories = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->get();

        $classifications = ContentClassification::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->get();

        $totalArticles = Content::where('published', true)->count();

        $popularContents = Content::with(['category', 'user'])
            ->where('published', true)
            ->where('archived', false)
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

    private function personalHome(): View
    {
        $personalSetting = SiteSetting::personal();

        $member = TeamMember::where('id', $personalSetting->personal_member_id)
            ->where('is_visible', true)
            ->firstOrFail();

        $member->load([
            'user.educationHistory',
            'user.workExperience',
            'user.certifications',
            'user.publications',
        ]);

        $blogs = collect();
        if ($member->user_id) {
            $blogs = Content::where('user_id', $member->user_id)
                ->where('published', true)
                ->where('archived', false)
                ->with(['category', 'classification'])
                ->latest('article_date')
                ->get();
        }

        return view('personal-home', compact('member', 'blogs'));
    }

    public function search(): View
    {
        $locale = app()->getLocale();
        $query = mb_substr(request()->string('q')->trim()->value(), 0, 100);

        $results = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->when($query, fn ($q) => $q->where(fn ($q) => $q
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"$locale\"')) LIKE ?", ["%{$query}%"])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(excerpt, '$.\"$locale\"')) LIKE ?", ["%{$query}%"])
                ->orWhereHas('category', fn ($c) => $c->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) LIKE ?", ["%{$query}%"]
                ))
                ->orWhereHas('classification', fn ($c) => $c->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) LIKE ?", ["%{$query}%"]
                ))
                ->orWhereHas('tags', fn ($t) => $t->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) LIKE ?", ["%{$query}%"]
                ))
            ))
            ->latest('article_date')
            ->paginate(12);

        $suggestions = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('search', compact('query', 'results', 'suggestions'));
    }

    public function category(string $locale, string $slug): View
    {
        $category = ContentCategory::where('slug', $slug)->firstOrFail();

        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('content_category_id', $category->id)
            ->whereNotNull('header_image')
            ->latest('article_date')
            ->paginate(9);

        $otherCategories = ContentCategory::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->where('id', '!=', $category->id)
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->get();

        return view('category.show', compact('category', 'contents', 'otherCategories'));
    }

    public function classification(string $locale, string $slug): View
    {
        $classification = ContentClassification::where('slug', $slug)->firstOrFail();

        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('content_classification_id', $classification->id)
            ->whereNotNull('header_image')
            ->latest('article_date')
            ->paginate(9);

        $otherClassifications = ContentClassification::withCount(['contents' => fn ($q) => $q->where('published', true)])
            ->having('contents_count', '>', 0)
            ->where('id', '!=', $classification->id)
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->get();

        return view('classification.show', compact('classification', 'contents', 'otherClassifications'));
    }

    public function show(string $locale, string $slug): View
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
            ->where('archived', false)
            ->whereNotNull('header_image')
            ->where('id', '!=', $content->getKey())
            ->when(
                $content->content_category_id,
                fn ($q) => $q->where('content_category_id', $content->content_category_id),
                fn ($q) => $q->where('content_classification_id', $content->content_classification_id)
            )
            ->latest('article_date')
            ->limit(3)
            ->get();

        return view('content.show', compact('content', 'relatedContents'));
    }

    public function tag(string $locale, string $slug): View
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->whereNotNull('header_image')
            ->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))
            ->latest('article_date')
            ->paginate(12);

        $otherTags = Tag::whereHas('contents', fn ($q) => $q->where('published', true))
            ->where('id', '!=', $tag->id)
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->limit(12)
            ->get();

        return view('tag.show', compact('tag', 'contents', 'otherTags'));
    }

    public function archive(): View
    {
        $contents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->where('archived', true)
            ->whereNotNull('header_image')
            ->latest('article_date')
            ->paginate(12);

        return view('archive', compact('contents'));
    }

    public function about(): View
    {
        return view('about', [
            'totalArticles' => Content::where('published', true)->count(),
            'totalMembers' => TeamMember::where('is_visible', true)->count(),
            'totalCategories' => ContentCategory::count(),
        ]);
    }

    public function team(): View
    {
        $all = TeamMember::with('user')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        $activeMembers = $all->filter(fn ($m) => $m->status === TeamMemberStatus::Active)->values();
        $formerMembers = $all->filter(fn ($m) => $m->status !== TeamMemberStatus::Active)->values();

        return view('team.index', compact('activeMembers', 'formerMembers'));
    }

    public function pdf(string $locale, string $slug): Response
    {
        $content = Content::with([
            'user', 'category', 'classification', 'tags',
            'imageAttachments', 'fileAttachments', 'linkAttachments',
        ])
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $pdf = Pdf::loadView('content.pdf', compact('content'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(str($content->slug)->slug().'.pdf');
    }

    public function memberPdf(string $locale, TeamMember $member): Response
    {
        abort_if(! $member->is_visible, 404);

        $member->load([
            'user.educationHistory',
            'user.workExperience',
            'user.certifications',
            'user.publications',
        ]);

        $pdf = Pdf::loadView('team.pdf', compact('member'))
            ->setPaper('a4', 'portrait');

        $filename = str($member->fullName())->slug()->append('.pdf')->value();

        return $pdf->download($filename);
    }

    public function memberShow(string $locale, TeamMember $member): View
    {
        abort_if(! $member->is_visible, 404);

        $member->load([
            'user.educationHistory',
            'user.workExperience',
            'user.certifications',
            'user.publications',
        ]);

        $blogs = collect();

        if ($member->user_id) {
            $blogs = Content::where('user_id', $member->user_id)
                ->where('published', true)
                ->where('archived', false)
                ->whereHas('classification', fn ($q) => $q->where('slug', 'blog'))
                ->with(['category', 'classification'])
                ->latest('article_date')
                ->get();
        }

        return view('team.show', compact('member', 'blogs'));
    }
}
