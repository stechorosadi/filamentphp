<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['content_id', 'path', 'original_name', 'order'])]
class ContentFile extends Model
{
    protected static function booted(): void
    {
        static::updating(function (ContentFile $file): void {
            if ($file->isDirty('path') && $file->getOriginal('path')) {
                Storage::disk('public')->delete($file->getOriginal('path'));
            }
        });

        static::deleting(function (ContentFile $file): void {
            if ($file->path) {
                Storage::disk('public')->delete($file->path);
            }
        });
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
