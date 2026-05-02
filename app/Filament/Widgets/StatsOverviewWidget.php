<?php

namespace App\Filament\Widgets;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentClassification;
use App\Models\TeamMember;
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;

    protected function getViewData(): array
    {
        $totalContent = Content::count();
        $published = Content::where('published', true)->count();
        $archived = Content::where('archived', true)->count();
        $featured = Content::where('featured', true)->count();
        $draft = $totalContent - $published;
        $totalViews = (int) Content::sum('views');
        $categories = ContentCategory::count();
        $classifications = ContentClassification::count();
        $teamTotal = TeamMember::count();
        $teamVisible = TeamMember::where('is_visible', true)->count();

        return [
            'totalContent' => $totalContent,
            'published' => $published,
            'archived' => $archived,
            'featured' => $featured,
            'draft' => $draft,
            'totalViews' => $totalViews,
            'categories' => $categories,
            'classifications' => $classifications,
            'teamTotal' => $teamTotal,
            'teamVisible' => $teamVisible,
        ];
    }
}
