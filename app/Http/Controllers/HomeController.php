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
        $latestContents = Content::with(['category', 'classification'])
            ->whereNotNull('header_image')
            ->latest()
            ->limit(6)
            ->get();

        $categories = ContentCategory::withCount('contents')
            ->having('contents_count', '>', 0)
            ->orderBy('name')
            ->get();

        $classifications = ContentClassification::withCount('contents')
            ->having('contents_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('welcome', compact('latestContents', 'categories', 'classifications'));
    }
}
