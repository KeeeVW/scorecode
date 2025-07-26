<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme_primary',
        'theme_secondary',
        'locks_remaining',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 