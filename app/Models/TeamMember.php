<?php

namespace App\Models;

use App\Enums\TeamMemberStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'user_id',
    'name',
    'word_of_wisdom',
    'front_title',
    'back_title',
    'position',
    'employee_number',
    'photo',
    'instagram_url',
    'facebook_url',
    'x_url',
    'threads_url',
    'youtube_url',
    'sort_order',
    'is_visible',
    'status',
])]
class TeamMember extends Model
{
    use HasTranslations;

    public array $translatable = ['front_title', 'back_title', 'position'];

    protected function casts(): array
    {
        return [
            'status' => TeamMemberStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fullName(): string
    {
        $name = $this->user?->name ?? $this->name ?? '';

        return trim(
            ($this->front_title ? $this->front_title.' ' : '').
            $name.
            ($this->back_title ? ', '.$this->back_title : '')
        );
    }

    protected static function booted(): void
    {
        static::updating(function (TeamMember $member): void {
            if ($member->isDirty('photo') && $member->getOriginal('photo')) {
                Storage::disk('public')->delete($member->getOriginal('photo'));
            }
        });

        static::deleting(function (TeamMember $member): void {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        });
    }
}
