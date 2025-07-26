<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'value',
        'attendance_before',
        'attendance_after',
        'first_attendance',
        'submission_before',
        'attendance_factor',
        'submission_factor',
    ];

    protected $casts = [
        'date' => 'date',
        'attendance_before' => 'boolean',
        'attendance_after' => 'boolean',
        'first_attendance' => 'boolean',
        'submission_before' => 'boolean',
        'attendance_factor' => 'decimal:2',
        'submission_factor' => 'decimal:2',
        'value' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTotalPoints()
    {
        $basePoints = $this->value * 100;
        $bonusPoints = 0;

        if ($this->attendance_before) {
            $bonusPoints += 300;
        }
        if ($this->attendance_after) {
            $bonusPoints += 200;
        }
        if ($this->first_attendance) {
            $bonusPoints += 500;
        }

        return $basePoints + $bonusPoints;
    }
} 