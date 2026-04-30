<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'company', 'job_title', 'department', 'start_year', 'end_year', 'description', 'order'])]
class UserExperience extends Model
{
    protected $table = 'user_experiences';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
