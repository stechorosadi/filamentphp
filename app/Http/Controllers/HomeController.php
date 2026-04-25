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

        $featuredContents = Content::with(['category', 'classification'])
            ->where('featured', true)
            ->where('published', true)
            ->whereNotNull('featured_image')
            ->latest()
            ->limit(5)
            ->get();

        $latestContents = Content::with(['category', 'classification'])
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

        return view('welcome', compact(
            'featuredContents', 'latestContents', 'categories', 'classifications', 'search'
        ));
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

        return view('content.show', compact('content'));
    }
}
