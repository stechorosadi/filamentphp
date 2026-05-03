<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

// Imported for type-hinting in deleting hook closures
// (DB cascade bypasses Eloquent hooks on child rows)

#[Fillable([
    'user_id',
    'title',
    'slug',
    'content_classification_id',
    'content_category_id',
    'header_image',
    'featured_image',
    'excerpt',
    'content',
    'youtube_url',
    'article_date',
    'published',
    'featured',
    'archived',
])]
class Content extends Model
{
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'featured' => 'boolean',
            'archived' => 'boolean',
            'article_date' => 'date',
            'views' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Content $content): void {
            if ($content->isDirty('header_image') && $content->getOriginal('header_image')) {
                Storage::disk('public')->delete($content->getOriginal('header_image'));
            }
            if ($content->isDirty('featured_image') && $content->getOriginal('featured_image')) {
                Storage::disk('public')->delete($content->getOriginal('featured_image'));
            }
        });

        static::deleting(function (Content $content): void {
            if ($content->header_image) {
                Storage::disk('public')->delete($content->header_image);
            }
            if ($content->featured_image) {
                Storage::disk('public')->delete($content->featured_image);
            }

            // DB cascade bypasses Eloquent hooks on children, so delete
            // attachment files here before the cascade removes the rows.
            $content->imageAttachments->each(
                fn (ContentImage $img) => Storage::disk('public')->delete($img->path)
            );
            $content->fileAttachments->each(
                fn (ContentFile $file) => Storage::disk('public')->delete($file->path)
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(ContentClassification::class, 'content_classification_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class, 'content_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function imageAttachments(): HasMany
    {
        return $this->hasMany(ContentImage::class)->orderBy('order');
    }

    public function fileAttachments(): HasMany
    {
        return $this->hasMany(ContentFile::class)->orderBy('order');
    }

    public function linkAttachments(): HasMany
    {
        return $this->hasMany(ContentLink::class)->orderBy('order');
    }
}
