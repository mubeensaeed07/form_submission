<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookUser extends Model
{
    protected $fillable = [
        'facebook_url',
        'full_name',
        'form_type',
        'generated_url',
        'created_by_id',
        'created_by_role',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
