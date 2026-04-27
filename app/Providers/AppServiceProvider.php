<?php

namespace App\Providers;

use App\Models\Tag;
use App\Policies\MenuPolicy;
use App\Policies\TagPolicy;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Support\Facades\Gate;
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
    }
}
