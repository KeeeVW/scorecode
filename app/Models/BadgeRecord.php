<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_name',
        'date',
        'quantity',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 