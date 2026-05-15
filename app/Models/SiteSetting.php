<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'type',
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
    'contact_phone',
    'contact_working_hours',
    'maps_embed_url',
    'mission',
    'vision',
    'is_personal_site',
    'personal_member_id',
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

    public array $translatable = ['site_title', 'site_tagline', 'site_description', 'mission', 'vision'];

    protected function casts(): array
    {
        return ['is_personal_site' => 'boolean'];
    }

    public static function instance(): self
    {
        $org = static::organization();

        return $org->is_personal_site ? static::personal() : $org;
    }

    public static function organization(): self
    {
        return static::where('type', 'organization')->firstOrFail();
    }

    public static function personal(): self
    {
        return static::where('type', 'personal')->firstOrFail();
    }

    public function personalMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'personal_member_id');
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
