<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employer_id',
        'title',
        'type',
        'location',
        'salary_range',
        'description',
        'slots',
        'status',
        'posted_date',
        'deadline',
    ];

    protected $casts = [
        'posted_date' => 'date',
        'deadline' => 'date',
        'slots' => 'integer',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function skills()
    {
        return $this->hasMany(JobSkill::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
