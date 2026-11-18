<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::firstOrCreate(
            ['type' => 'organization'],
            ['site_title' => config('app.name')],
        );

        SiteSetting::firstOrCreate(
            ['type' => 'personal'],
            ['site_title' => config('app.name')],
        );
    }
}
