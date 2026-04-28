<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\Tag;
use App\Policies\MenuPolicy;
use App\Policies\TagPolicy;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Explicitly register policies that auto-discovery cannot find.
        // TagPolicy: Tag model exists in App\Models but policy was missing.
        // MenuPolicy: Menu model is from a third-party package, so auto-discovery
        //             cannot match it to App\Policies\MenuPolicy.
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);

        // Share site settings with all views.
        // Cache the raw attributes array (not the model) to avoid unserialize issues.
        // Graceful fallback if table doesn't exist yet (e.g. before migrations).
        try {
            $attributes = Cache::remember('site_setting', 3600, fn () => SiteSetting::instance()->getAttributes());
            $siteSetting = new SiteSetting;
            $siteSetting->setRawAttributes($attributes);
            $siteSetting->exists = true;
        } catch (\Throwable) {
            $siteSetting = new SiteSetting(['site_title' => config('app.name')]);
        }
        View::share('siteSetting', $siteSetting);

        // Share navigation menus with the front layout.
        View::composer('layouts.front', function ($view): void {
            $view->with('navMenuItems',
                Menu::with(['menuItems' => fn ($q) => $q->whereNull('parent_id')->orderBy('order')])
                    ->where('name', 'Header Menu - Top Right')
                    ->first()
                    ?->menuItems
                    ?? collect()
            );

            $view->with('footerMenuItems',
                Menu::with(['menuItems' => fn ($q) => $q->whereNull('parent_id')->orderBy('order')])
                    ->where('name', 'Footer Menu - Bottom Right')
                    ->first()
                    ?->menuItems
                    ?? collect()
            );
        });
    }
}
