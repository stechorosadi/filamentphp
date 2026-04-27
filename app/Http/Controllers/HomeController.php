<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentClassification;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $search = request()->string('search')->trim()->value();
        $categoryId = request()->integer('category') ?: null;
        $classificationId = request()->integer('classification') ?: null;

        $featuredContents = Content::with(['user', 'category', 'classification'])
            ->where('featured', true)
            ->where('published', true)
            ->whereNotNull('featured_image')
            ->latest()
            ->limit(5)
            ->get();

        $latestContents = Content::with(['user', 'category', 'classification'])
            ->where('published', true)
            ->whereNotNull('header_image')
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

        return view('welcome', compact(
            'featuredContents', 'latestContents', 'categories', 'classifications',
            'search', 'totalArticles', 'popularContents'
        ));
    }

    public function search(): View
    {
        $query = request()->string('q')->trim()->value();

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
}
