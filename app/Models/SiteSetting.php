<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'site_title',
    'site_tagline',
    'site_description',
    'logo_path',
    'favicon_path',
])]
class SiteSetting extends Model
{
    public static function instance(): self
    {
        return static::firstOrFail();
    }

    protected static function booted(): void
    {
        static::updating(function (SiteSetting $settings): void {
            foreach (['logo_path', 'favicon_path'] as $field) {
                if ($settings->isDirty($field) && $settings->getOriginal($field)) {
                    Storage::disk('public')->delete($settings->getOriginal($field));
                }
            }
        });
    }
}
