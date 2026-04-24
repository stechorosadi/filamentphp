<?php

namespace Database\Seeders;

use App\Models\ContentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Academic', 'Finance', 'Student Affairs'] as $name) {
            ContentCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
