<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\ScoutProfile;
use App\Models\KorasaRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        })->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.users');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login_at' => Carbon::now()]);
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.users');
            } else {
                // Redirect scouts to profile page
                return redirect()->route('scout.profile');
            }
        }

        return redirect()->route('login')
            ->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        auth()->logout();
        session()->forget('error');
        return redirect()->route('login');
    }

    public function users()
    {
        $users = User::where('is_admin', false)
            ->with(['scoutProfile', 'attendanceRecords'])
            ->get();
        $scoreboardMessages = DB::table('scoreboard_messages')
            ->join('users', 'scoreboard_messages.user_id', '=', 'users.id')
            ->where('users.is_admin', false)
            ->select('scoreboard_messages.*', 'users.name as user_name')
            ->get();
        
        return view('admin.users.index', compact('users', 'scoreboardMessages'));
    }

    public function createUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'theme_primary' => 'required|string|max:7',
                'theme_secondary' => 'required|string|max:7',
            ]);

            DB::beginTransaction();

            // Create the user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->is_admin = false;
            $user->save();

            // Create the scout profile
            $scoutProfile = new ScoutProfile();
            $scoutProfile->user_id = $user->id;
            $scoutProfile->theme_primary = $request->theme_primary;
            $scoutProfile->theme_secondary = $request->theme_secondary;
            $scoutProfile->locks_remaining = 0;
            $scoutProfile->is_active = true;
            $scoutProfile->save();

            DB::commit();

            return back()->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->email === 'admin@wadielnilescouts.com') {
            return redirect()->back()->with('error', 'Cannot modify admin account.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'theme_primary' => 'required|string|max:7',
            'theme_secondary' => 'required|string|max:7',
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        $user->scoutProfile->update([
            'theme_primary' => $request->theme_primary,
            'theme_secondary' => $request->theme_secondary,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->email === 'admin@wadielnilescouts.com') {
            return redirect()->back()->with('error', 'Cannot modify admin account.');
        }

        $user->scoutProfile->update([
            'is_active' => !$user->scoutProfile->is_active,
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->email === 'admin@wadielnilescouts.com') {
            return redirect()->back()->with('error', 'Cannot delete admin account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function korasa()
    {
        $users = User::where('is_admin', false)
            ->with('korasaRecords')
            ->get();
        return view('admin.scout.korasa', compact('users'));
    }

    public function storeKorasa(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($userId);
        $user->korasaRecords()->create($request->all());

        return back()->with('success', 'Notebook record added successfully.');
    }

    public function updateKorasa(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
        ]);

        $record = \App\Models\KorasaRecord::findOrFail($recordId);
        $record->update($request->all());

        return back()->with('success', 'Notebook record updated successfully.');
    }

    public function deleteKorasa($id)
    {
        $record = KorasaRecord::findOrFail($id);
        $record->delete();

        return back()->with('success', 'Korasa record deleted successfully.');
    }

    public function settings()
    {
        $settings = Setting::all();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    public function manageScoreboard(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        DB::table('scoreboard_messages')->updateOrInsert(
            [
                'user_id' => $request->user_id,
                'message' => $request->message,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->back()
            ->with('success', 'Scoreboard message sent to user.');
    }

    public function broadcastScoreboard(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $now = \Carbon\Carbon::now('Africa/Cairo');
        $users = \App\Models\User::where('is_admin', false)->get();
        foreach ($users as $user) {
            DB::table('scoreboard_messages')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'message' => $request->message,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        return redirect()->back()->with('success', 'Scoreboard message sent to all users.');
    }

    public function customBroadcastScoreboard(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'message' => 'required|string',
        ]);

        $now = \Carbon\Carbon::now('Africa/Cairo');
        foreach ($request->user_ids as $userId) {
            DB::table('scoreboard_messages')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'message' => $request->message,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
        return redirect()->back()->with('success', 'Custom message sent to selected users.');
    }

    public function updateLocks(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'locks_remaining' => 'required|integer|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        $oldLocks = $user->scoutProfile->locks_remaining;
        $newLocks = $request->locks_remaining;
        $change = $newLocks - $oldLocks;

        $user->scoutProfile->update([
            'locks_remaining' => $newLocks,
        ]);

        if ($change != 0) {
            $user->lockHistory()->create([
                'description' => $change > 0 ? 'تم إضافة أقفال' : 'تم خصم أقفال',
                'change' => $change,
            ]);
        }

        return back()->with('success', 'Locks updated successfully.');
    }

    public function previewUserView(User $user)
    {
        if ($user->email === 'admin@wadielnilescouts.com') {
            return redirect()->back()->with('error', 'Cannot preview admin view.');
        }

        $data = [
            'uniformRecords' => $user->uniformRecords,
            'korasaRecords' => $user->korasaRecords,
            'badgeRecords' => $user->badgeRecords,
            'pointsRecords' => $user->pointsRecords,
            'attendanceRecords' => $user->attendanceRecords,
            'scoreboardMessage' => $user->scoreboardMessage,
            'scoutProfile' => $user->scoutProfile,
        ];

        return view('scout.preview', compact('user', 'data'));
    }

    public function scoutUniform()
    {
        $users = User::with('scoutProfile')->where('is_admin', false)->get();
        return view('admin.scout.uniform', compact('users'));
    }

    public function scoutBadges()
    {
        $users = User::with('scoutProfile')->where('is_admin', false)->get();
        return view('admin.scout.badges', compact('users'));
    }

    public function scoutPoints()
    {
        $users = User::with('scoutProfile')->where('is_admin', false)->get();
        return view('admin.scout.points', compact('users'));
    }

    public function scoutAttendance()
    {
        $users = User::with('scoutProfile')->where('is_admin', false)->get();
        return view('admin.scout.attendance', compact('users'));
    }

    public function reports()
    {
        $users = User::with('scoutProfile')->where('is_admin', false)->get();
        return view('admin.reports.index', compact('users'));
    }

    public function storeUniform(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($userId);
        $user->uniformRecords()->create($request->all());

        return back()->with('success', 'Uniform record added successfully.');
    }

    public function updateUniform(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
        ]);

        $record = \App\Models\UniformRecord::findOrFail($recordId);
        $record->update($request->all());

        return back()->with('success', 'Uniform record updated successfully.');
    }

    public function deleteUniform($recordId)
    {
        $record = \App\Models\UniformRecord::findOrFail($recordId);
        $userId = $record->user_id;
        $record->delete();

        return back()->with('success', 'Uniform record deleted successfully.');
    }

    public function scoutProfilesIndex()
    {
        $users = User::where('is_admin', false)->get();
        return redirect()->route('admin.users');
    }

    public function showScoutProfile(User $user)
    {
        // Load all related data for the scout profile
        $user->load('scoutProfile', 'uniformRecords', 'korasaRecords', 'badgeRecords', 'pointsRecords', 'attendanceRecords', 'lockHistory');

        return view('admin.scout.profiles.show', compact('user'));
    }

    // Badge Management Methods
    public function storeBadge(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'badge_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = User::findOrFail($userId);
        $user->badgeRecords()->create($request->all());

        return back()->with('success', 'Badge record added successfully.');
    }

    public function updateBadge(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'badge_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $record = \App\Models\BadgeRecord::findOrFail($recordId);
        $record->update($request->all());

        return back()->with('success', 'Badge record updated successfully.');
    }

    public function deleteBadge($recordId)
    {
        $record = \App\Models\BadgeRecord::findOrFail($recordId);
        $userId = $record->user_id;
        $record->delete();

        return back()->with('success', 'Badge record deleted successfully.');
    }

    // Points Management Methods
    public function storePoints(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $user = User::findOrFail($userId);
        $user->pointsRecords()->create($request->all());

        return back()->with('success', 'Point record added successfully.');
    }

    public function updatePoints(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $record = \App\Models\PointsRecord::findOrFail($recordId);
        $record->update($request->all());

        return back()->with('success', 'Point record updated successfully.');
    }

    public function deletePoints($recordId)
    {
        $record = \App\Models\PointsRecord::findOrFail($recordId);
        $userId = $record->user_id;
        $record->delete();

        return back()->with('success', 'Point record deleted successfully.');
    }

    // Attendance Management Methods
    public function storeAttendance(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric',
            'attendance_before' => 'nullable|boolean',
            'attendance_after' => 'nullable|boolean',
            'first_attendance' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['attendance_before'] = $request->has('attendance_before');
        $data['attendance_after'] = $request->has('attendance_after');
        $data['first_attendance'] = $request->has('first_attendance');
        $data['attendance_factor'] = $data['attendance_after'] ? $data['value'] : 0;
        $data['submission_factor'] = $data['attendance_before'] ? $data['value'] : 0;

        $user = User::findOrFail($userId);
        $user->attendanceRecords()->create($data);

        return back()->with('success', 'Attendance record added successfully.');
    }

    public function updateAttendance(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric',
            'attendance_before' => 'nullable|boolean',
            'attendance_after' => 'nullable|boolean',
            'first_attendance' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['attendance_before'] = $request->has('attendance_before');
        $data['attendance_after'] = $request->has('attendance_after');
        $data['first_attendance'] = $request->has('first_attendance');
        $data['attendance_factor'] = $data['attendance_after'] ? $data['value'] : 0;
        $data['submission_factor'] = $data['attendance_before'] ? $data['value'] : 0;

        $record = \App\Models\AttendanceRecord::findOrFail($recordId);
        $record->update($data);

        return back()->with('success', 'Attendance record updated successfully.');
    }

    public function deleteAttendance($recordId)
    {
        $record = \App\Models\AttendanceRecord::findOrFail($recordId);
        $userId = $record->user_id;
        $record->delete();

        return back()->with('success', 'Attendance record deleted successfully.');
    }

    // Locks Management Methods
    public function storeLock(Request $request, $userId)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'change' => 'required|boolean',
        ]);

        $user = User::findOrFail($userId);
        $user->lockHistory()->create($request->all());

        return back()->with('success', 'Lock record added successfully.');
    }

    public function updateLock(Request $request, $recordId)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'change' => 'required|boolean',
        ]);

        $record = \App\Models\LockHistory::findOrFail($recordId);
        $record->update($request->all());

        return back()->with('success', 'Lock record updated successfully.');
    }

    public function deleteLock($recordId)
    {
        $record = \App\Models\LockHistory::findOrFail($recordId);
        $userId = $record->user_id;
        $record->delete();

        return back()->with('success', 'Lock record deleted successfully.');
    }

    public function impersonate(User $user)
    {
        if ($user->email === 'admin@wadielnilescouts.com') {
            return redirect()->back()->with('error', 'Cannot impersonate admin account.');
        }

        // Store admin's ID in session
        session(['impersonator_id' => auth()->id()]);
        
        // Login as the user
        auth()->login($user);
        
        return redirect()->route('scout.uniform')
            ->with('success', 'Now impersonating ' . $user->name);
    }

    public function stopImpersonating()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('admin.users')
                ->with('error', 'Not impersonating any user.');
        }

        // Get the admin user and ensure it exists
        $admin = User::where('id', session('impersonator_id'))
                    ->where('is_admin', true)
                    ->first();
                    
        if (!$admin) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Admin account not found or invalid.');
        }

        // Store admin ID in session before clearing
        $adminId = $admin->id;
        
        // Clear all session data except impersonator_id
        $impersonatorId = session('impersonator_id');
        session()->flush();
        session(['impersonator_id' => $impersonatorId]);
        
        // Login back as admin
        auth()->login($admin);
        
        // Regenerate session
        session()->regenerate();
        
        return redirect()->route('admin.users')
            ->with('success', 'Stopped impersonating user.');
    }

    public function downloadBackup()
    {
        $users = \App\Models\User::with([
            'scoutProfile',
            'uniformRecords',
            'korasaRecords',
            'badgeRecords',
            'pointsRecords',
            'attendanceRecords',
            'scoreboardMessage',
            'lockHistory',
        ])->get();

        $pdf = Pdf::loadView('admin.pdf.backup', compact('users'));
        return $pdf->download('full-backup.pdf');
    }

    public function reportsEvents()
    {
        // Logic for event reports
        return view('admin.reports.events');
    }

    public function reportsCustomQuery()
    {
        // Logic for custom query reports
        return view('admin.reports.custom_query');
    }

    public function reportsCharts()
    {
        // Logic for charts and visualizations
        return view('admin.reports.charts');
    }

    public function exportUsers($format)
    {
        // ... existing code ...
    }

    public function deleteScoreboardMessage($id)
    {
        DB::table('scoreboard_messages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Scoreboard message deleted.');
    }
} 