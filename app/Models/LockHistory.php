<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'change'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 