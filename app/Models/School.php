<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'access_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($school) {
            if (!$school->access_token) {
                $school->access_token = Str::random(32);
            }
        });
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function headteacher()
    {
        return $this->hasOne(User::class)->where('role', 'headteacher');
    }
}
