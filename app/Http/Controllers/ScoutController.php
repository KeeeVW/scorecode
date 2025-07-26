<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\ScoreboardMessage;
use Illuminate\Http\Request;

class ScoutController extends Controller
{
    public function uniform()
    {
        $records = auth()->user()->uniformRecords()
            ->orderBy('date', 'desc')
            ->get();
        $total = $records->sum('value');
        return view('scout.uniform', compact('records', 'total'));
    }

    public function korasa()
    {
        $records = auth()->user()->korasaRecords()
            ->orderBy('date', 'desc')
            ->get();
        $total = $records->sum('value');
        return view('scout.korasa', compact('records', 'total'));
    }

    public function badges()
    {
        $records = auth()->user()->badgeRecords()
            ->orderBy('date', 'desc')
            ->get();
        return view('scout.badges', compact('records'));
    }

    public function points()
    {
        $records = auth()->user()->pointsRecords()
            ->orderBy('date', 'desc')
            ->get();
        $total = $records->sum('value');
        return view('scout.points', compact('records', 'total'));
    }

    public function attendance()
    {
        $records = auth()->user()->attendanceRecords()
            ->orderBy('date', 'desc')
            ->get();
        $total = $records->sum('value');
        return view('scout.attendance', compact('records', 'total'));
    }

    public function total()
    {
        $user = auth()->user();
        $total = $user->getTotalScore();

        // Uniform
        $uniformRecords = $user->uniformRecords()->get();
        $korasaRecords = $user->korasaRecords()->get();
        $badgeRecords = $user->badgeRecords()->get();
        $pointsRecords = $user->pointsRecords()->get();
        $attendanceRecords = $user->attendanceRecords()->get();

        $uniformPoints = $uniformRecords->sum('value') * 50;
        $korasaPoints = $korasaRecords->sum('value') * 50;
        $badgesPoints = $badgeRecords->sum(function($record) {
            return \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity);
        });
        $pointsPoints = $pointsRecords->sum('value');
        $attendancePoints = 0;
        foreach ($attendanceRecords as $record) {
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

        // Collect all unique days with any record
        $dates = collect();
        $dates = $dates->merge($uniformRecords->pluck('date'));
        $dates = $dates->merge($korasaRecords->pluck('date'));
        $dates = $dates->merge($badgeRecords->pluck('date'));
        $dates = $dates->merge($pointsRecords->pluck('date'));
        $dates = $dates->merge($attendanceRecords->pluck('date'));
        $uniqueDays = $dates->unique()->filter()->count();
        $averagePerDay = $uniqueDays > 0 ? round($total / $uniqueDays, 2) : 0;

        $scoreBreakdown = [
            'Uniform' => $uniformPoints,
            'Korasa' => $korasaPoints,
            'Badges' => $badgesPoints,
            'Points' => $pointsPoints,
            'Attendance' => $attendancePoints,
        ];

        $total = $uniformPoints + $korasaPoints + $badgesPoints + $pointsPoints + $attendancePoints;

        return view('scout.total', compact('total', 'scoreBreakdown', 'uniformPoints', 'korasaPoints', 'badgesPoints', 'pointsPoints', 'attendancePoints', 'averagePerDay'));
    }

    public function scoreboard()
    {
        $user = auth()->user();
        $message = $user->scoreboardMessage;
        $messages = ScoreboardMessage::all();

        return view('scout.scoreboard', compact('message', 'messages'));
    }

    public function positions()
    {
        $users = User::where('email', '!=', 'admin@wadielnilescouts.com')
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'score' => $user->getTotalScore(),
                ];
            })
            ->sortByDesc('score')
            ->values();
            
        $rank = $users->search(function ($user) {
            return $user['name'] === auth()->user()->name;
        }) + 1;
        
        $currentPosition = $rank;
        $totalScouts = $users->count();
        
        return view('scout.positions', compact('users', 'rank', 'currentPosition', 'totalScouts'));
    }

    public function locks()
    {
        $user = auth()->user();
        $history = $user->lockHistory()->orderBy('created_at', 'desc')->get();
        $locksRemaining = 23 - $history->sum('change');
        return view('scout.locks', compact('locksRemaining', 'history'));
    }

    public function profile()
    {
        $users = \App\Models\User::where('email', '!=', 'admin@wadielnilescouts.com')
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'score' => $user->getTotalScore(),
                ];
            })
            ->sortByDesc('score')
            ->values();

        $rank = $users->search(function ($user) {
            return $user['name'] === auth()->user()->name;
        }) + 1;

        $currentPosition = $rank;
        $totalScouts = $users->count();

        return view('scout.profile', compact('currentPosition', 'totalScouts'));
    }

    public function dashboard()
    {
        // You can add data specific to the scout dashboard here
        return view('scout.dashboard');
    }

    private function calculateBadgeScore($user)
    {
        $badgeRecords = $user->badgeRecords()->get();
        $total = $badgeRecords->sum(function($record) {
            return \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity);
        });
        return $total;
    }

    private function calculateAttendanceScore($user)
    {
        $attendanceScore = $user->attendanceRecords()
            ->where('attendance_after', true)
            ->sum('attendance_factor') * Setting::get('attendance_factor');
            
        $submissionScore = $user->attendanceRecords()
            ->where('submission_before', true)
            ->sum('submission_factor') * Setting::get('submission_factor');
            
        return $attendanceScore + $submissionScore;
    }
} 