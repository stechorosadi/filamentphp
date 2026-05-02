<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentClassification;
use App\Models\TeamMember;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $contents = Content::select('slug', 'updated_at')
            ->where('published', true)
            ->where('archived', false)
            ->latest('updated_at')
            ->get();

        $categories = ContentCategory::select('slug', 'updated_at')
            ->orderBy('name')
            ->get();

        $classifications = ContentClassification::select('slug', 'updated_at')
            ->orderBy('name')
            ->get();

        $teamMembers = TeamMember::select('id', 'updated_at')
            ->where('is_visible', true)
            ->get();

        $xml = view('sitemap', compact(
            'contents',
            'categories',
            'classifications',
            'teamMembers',
        ))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function html(): View
    {
        $contents = Content::select('slug', 'title', 'updated_at')
            ->where('published', true)
            ->where('archived', false)
            ->latest('updated_at')
            ->get();

        $categories = ContentCategory::select('slug', 'name', 'updated_at')
            ->orderBy('name')
            ->get();

        $classifications = ContentClassification::select('slug', 'name', 'updated_at')
            ->orderBy('name')
            ->get();

        $teamMembers = TeamMember::select('id', 'user_id', 'name', 'front_title', 'back_title', 'updated_at')
            ->with('user:id,name')
            ->where('is_visible', true)
            ->get();

        return view('sitemap-html', compact(
            'contents',
            'categories',
            'classifications',
            'teamMembers',
        ));
    }
}
