<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'institution', 'degree', 'field_of_study', 'start_year', 'end_year', 'gpa', 'description', 'certificate_path', 'order'])]
class UserEducation extends Model
{
    protected $table = 'user_educations';

    protected static function booted(): void
    {
        static::updating(function (UserEducation $education): void {
            if ($education->isDirty('certificate_path') && $education->getOriginal('certificate_path')) {
                Storage::disk('public')->delete($education->getOriginal('certificate_path'));
            }
        });

        static::deleting(function (UserEducation $education): void {
            if ($education->certificate_path) {
                Storage::disk('public')->delete($education->certificate_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
