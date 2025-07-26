<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ScoutProfile;
use App\Models\UniformRecord;
use App\Models\KorasaRecord;
use App\Models\BadgeRecord;
use App\Models\PointsRecord;
use App\Models\AttendanceRecord;
use App\Models\ScoreboardMessage;
use App\Models\LockHistory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function scoutProfile()
    {
        return $this->hasOne(ScoutProfile::class);
    }

    public function uniformRecords()
    {
        return $this->hasMany(UniformRecord::class);
    }

    public function korasaRecords()
    {
        return $this->hasMany(KorasaRecord::class);
    }

    public function badgeRecords()
    {
        return $this->hasMany(BadgeRecord::class);
    }

    public function pointsRecords()
    {
        return $this->hasMany(PointsRecord::class);
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function scoreboardMessage()
    {
        return $this->hasOne(ScoreboardMessage::class);
    }

    public function lockHistory()
    {
        return $this->hasMany(LockHistory::class);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function getTotalScore()
    {
        $uniformPoints = $this->uniformRecords()->sum('value') * 50;
        $korasaPoints = $this->korasaRecords()->sum('value') * 50;
        $badgesPoints = $this->badgeRecords->sum(function($record) {
            return \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity);
        });
        $pointsPoints = $this->pointsRecords()->sum('value');
        $attendancePoints = 0;
        foreach ($this->attendanceRecords as $record) {
            $attendancePoints += $record->value * 100;
            if ($record->attendance_after) {
                $attendancePoints += 200;
            }
            if ($record->attendance_before) {
                $attendancePoints += 300;
            }
            if ($record->first_attendance) {
                $attendancePoints += 500;
            }
        }
        return $uniformPoints + $korasaPoints + $badgesPoints + $pointsPoints + $attendancePoints;
    }
}
