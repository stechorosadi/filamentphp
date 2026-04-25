<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'icon', 'image'])]
class ContentCategory extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (ContentCategory $model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
