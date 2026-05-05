<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug'])]
class Tag extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name'];

    protected static function booted(): void
    {
        static::creating(function (Tag $model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->getTranslation('name', 'id', false) ?: $model->name);
            }
        });
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class);
    }
}
