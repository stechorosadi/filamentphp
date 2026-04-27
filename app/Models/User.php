<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'avatar_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected static function booted(): void
    {
        static::updating(function (User $user): void {
            if ($user->isDirty('avatar_url') && $user->getOriginal('avatar_url')) {
                Storage::disk('public')->delete($user->getOriginal('avatar_url'));
            }
        });

        static::deleting(function (User $user): void {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url
            ? Storage::disk('public')->url($this->avatar_url)
            : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // All authenticated users may enter the panel.
        // Users without roles see only the Dashboard — FilamentShield
        // policies block access to every resource for role-less users.
        return true;
    }

    public function educationHistory(): HasMany
    {
        return $this->hasMany(UserEducation::class)->orderBy('order');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(UserPublication::class)->orderBy('order');
    }
}
