<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'visit_date',
        'consultant_name',
        'context',
        'activities_undertaken',
        'progress',
        'key_findings',
        'recommendations',
        'next_visit_date',
        'status',
        'headteacher_feedback',
        'share_token',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($visit) {
            if (!$visit->share_token) {
                $visit->share_token = Str::random(32);
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
