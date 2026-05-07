<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'email',
    'phone',
    'subject',
    'message',
    'agreed_terms',
    'ip_address',
])]
class ContactSubmission extends Model
{
    protected function casts(): array
    {
        return [
            'agreed_terms' => 'boolean',
        ];
    }
}
