<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['content_id', 'path', 'caption', 'order'])]
class ContentImage extends Model
{
    protected static function booted(): void
    {
        static::updating(function (ContentImage $image): void {
            if ($image->isDirty('path') && $image->getOriginal('path')) {
                Storage::disk('public')->delete($image->getOriginal('path'));
            }
        });

        static::deleting(function (ContentImage $image): void {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        });
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
