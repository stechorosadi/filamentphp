<?php

namespace Database\Seeders;

use App\Models\ContentClassification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentClassificationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Article', 'Announcement', 'Blog', 'Opinion'] as $name) {
            ContentClassification::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
