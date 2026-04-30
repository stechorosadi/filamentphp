<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'title', 'issuing_organization', 'category', 'issue_year', 'description', 'certificate_path', 'order'])]
class UserCertification extends Model
{
    protected $table = 'user_certifications';

    protected static function booted(): void
    {
        static::updating(function (UserCertification $certification): void {
            if ($certification->isDirty('certificate_path') && $certification->getOriginal('certificate_path')) {
                Storage::disk('public')->delete($certification->getOriginal('certificate_path'));
            }
        });

        static::deleting(function (UserCertification $certification): void {
            if ($certification->certificate_path) {
                Storage::disk('public')->delete($certification->certificate_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
