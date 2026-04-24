<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'title', 'type', 'publisher', 'year', 'isbn', 'doi', 'url', 'description', 'file_path', 'order'])]
class UserPublication extends Model
{
    protected $table = 'user_publications';

    protected static function booted(): void
    {
        static::updating(function (UserPublication $publication): void {
            if ($publication->isDirty('file_path') && $publication->getOriginal('file_path')) {
                Storage::disk('public')->delete($publication->getOriginal('file_path'));
            }
        });

        static::deleting(function (UserPublication $publication): void {
            if ($publication->file_path) {
                Storage::disk('public')->delete($publication->file_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
