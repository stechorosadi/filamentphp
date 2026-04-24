<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['content_id', 'url', 'label', 'order'])]
class ContentLink extends Model
{
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
