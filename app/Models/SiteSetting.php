<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'site_title',
    'site_tagline',
    'site_description',
    'logo_path',
    'favicon_path',
    'facebook_url',
    'instagram_url',
    'x_url',
    'youtube_url',
    'contact_email',
    'contact_address',
    'color_light_bg',
    'color_dark_bg',
    'color_light_text',
    'color_dark_text',
    'color_accent',
    'color_accent_dark',
])]
class SiteSetting extends Model
{
    use HasTranslations;

    public array $translatable = ['site_title', 'site_tagline', 'site_description'];

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

        static::deleting(function (SiteSetting $settings): void {
            foreach (['logo_path', 'favicon_path'] as $field) {
                if ($settings->{$field}) {
                    Storage::disk('public')->delete($settings->{$field});
                }
            }
        });
    }
}
