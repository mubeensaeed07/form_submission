<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'household_size',
        'dependents',
        'family_members',
        'employment_status',
        'employer_name',
        'monthly_income',
        'assistance_amount',
        'assistance_types',
        'assistance_description',
        'ssn',
        'street',
        'city',
        'state',
        'zip',
        'consent',
        'submitted_by_id',
        'submitted_by_role',
        'facebook_user_id',
        'status',
        'approved_url',
        'approved_by_id',
        'approved_at',
    ];

    protected $casts = [
        'dob' => 'date',
        'family_members' => 'array',
        'assistance_types' => 'array',
        'consent' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }

    public function facebookUser()
    {
        return $this->belongsTo(FacebookUser::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
}

