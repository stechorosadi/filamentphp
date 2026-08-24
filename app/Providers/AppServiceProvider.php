<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Policies\MenuPolicy;
use App\Policies\TagPolicy;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
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
        Route::bind('member', fn ($value) => TeamMember::where('nickname', $value)->firstOrFail());

        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);

        // Ensure dompdf font cache directory exists and is writable.
        $fontDir = storage_path('app/fonts');
        if (! is_dir($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        // Share site settings and navigation menus with all views.
        // Uses a per-request guard so the DB/cache hit happens only once.
        // Composer on '*' ensures $siteSetting is available in child view @section blocks
        // that are evaluated before layouts.front itself is rendered.
        View::composer('*', function ($view): void {
            if (request()->attributes->has('_view_data_shared')) {
                return;
            }
            request()->attributes->set('_view_data_shared', true);

            // Detect locale from the URL segment when setlocale middleware hasn't run
            // (e.g. on error pages where no route was matched).
            $urlSegment = request()->segment(1);
            $locale = \in_array($urlSegment, ['en', 'id'])
                ? $urlSegment
                : app()->getLocale();
            app()->setLocale($locale);

            // SiteSetting: cached per locale so translatable fields resolve correctly.
            try {
                $attrs = Cache::remember("site_setting_{$locale}", 300, fn () => SiteSetting::instance()->getAttributes());
                $siteSetting = new SiteSetting;
                $siteSetting->setRawAttributes($attrs);
                $siteSetting->exists = true;
            } catch (\Throwable) {
                $siteSetting = new SiteSetting(['site_title' => config('app.name')]);
            }
            View::share('siteSetting', $siteSetting);

            // Share the linked TeamMember when personal site mode is active.
            $personalMember = null;
            try {
                $attrs = $siteSetting->getAttributes();
                $memberId = (int) ($attrs['personal_member_id'] ?? 0);
                if (($attrs['type'] ?? 'organization') === 'personal' && $memberId) {
                    $personalMember = TeamMember::find($memberId);
                }
            } catch (\Throwable) {}
            View::share('personalMember', $personalMember);

            // Navigation menus: load locale-specific version, fall back to base name.
            $suffix = ' - '.strtoupper($locale);

            View::share('navMenuItems', $this->loadMenu('Header Menu - Top Right', $suffix));
            View::share('footerMenuItems', $this->loadMenu('Footer Menu - Bottom Right', $suffix));
            View::share('topbarMenuItems', $this->loadMenu('Header Menu - Top Left', $suffix));

            $baseNames = ['Footer Menu - Quick Links', 'Footer Menu - Resources', 'Footer Menu - Content'];
            $suffixedNames = array_map(fn ($n) => $n.$suffix, $baseNames);

            // Try locale-specific footer lists first, fall back to base names.
            $footerLists = Menu::with(['menuItems' => fn ($q) => $q->whereNull('parent_id')->orderBy('order')])
                ->whereIn('name', array_merge($suffixedNames, $baseNames))
                ->get()
                ->keyBy('name');

            $resolve = fn (string $base) => $footerLists->get($base.$suffix) ?? $footerLists->get($base);

            View::share('footerList1', $resolve('Footer Menu - Quick Links'));
            View::share('footerList2', $resolve('Footer Menu - Content'));
            View::share('footerList3', $resolve('Footer Menu - Resources'));
        });
    }

    private function loadMenu(string $baseName, string $suffix): Collection
    {
        $withItems = fn ($q) => $q->whereNull('parent_id')->orderBy('order');

        return Menu::with(['menuItems' => $withItems])
            ->where('name', $baseName.$suffix)
            ->first()
            ?->menuItems
            ?? Menu::with(['menuItems' => $withItems])
                ->where('name', $baseName)
                ->first()
                ?->menuItems
            ?? collect();
    }
}
