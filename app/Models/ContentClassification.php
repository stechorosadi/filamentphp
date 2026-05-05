<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug', 'icon', 'image'])]
class ContentClassification extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name'];

    protected static function booted(): void
    {
        static::creating(function (ContentClassification $model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->getTranslation('name', 'id', false) ?: $model->name);
            }
        });

        static::updating(function (ContentClassification $model): void {
            if ($model->isDirty('image') && $model->getOriginal('image')) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            if ($model->isDirty('icon') && $model->getOriginal('icon')) {
                Storage::disk('public')->delete($model->getOriginal('icon'));
            }
        });

        static::deleting(function (ContentClassification $model): void {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            if ($model->icon) {
                Storage::disk('public')->delete($model->icon);
            }
        });
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
