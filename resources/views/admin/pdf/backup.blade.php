<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 4px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Full Data Backup</h1>
    <p>Date: {{ now()->format('Y-m-d H:i') }}</p>
    <h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Is Admin</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                <td>{{ $user->last_login_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @foreach($users as $user)
        <h2>User #{{ $user->id }}: {{ $user->name }}</h2>
        <h3>Scout Profile</h3>
        @if($user->scoutProfile)
        <table>
            <tr><th>Theme Primary</th><td>{{ $user->scoutProfile->theme_primary }}</td></tr>
            <tr><th>Theme Secondary</th><td>{{ $user->scoutProfile->theme_secondary }}</td></tr>
            <tr><th>Locks Remaining</th><td>{{ $user->scoutProfile->locks_remaining }}</td></tr>
            <tr><th>Is Active</th><td>{{ $user->scoutProfile->is_active ? 'Yes' : 'No' }}</td></tr>
        </table>
        @else
        <p>No profile.</p>
        @endif
        <h3>Uniform Records</h3>
        <table>
            <tr><th>Date</th><th>Status</th><th>Notes</th><th>Value</th></tr>
            @foreach($user->uniformRecords as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->status }}</td><td>{{ $rec->notes }}</td><td>{{ $rec->value }}</td></tr>
            @endforeach
        </table>
        <h3>Korasa Records</h3>
        <table>
            <tr><th>Date</th><th>Status</th><th>Notes</th><th>Value</th></tr>
            @foreach($user->korasaRecords as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->status }}</td><td>{{ $rec->notes }}</td><td>{{ $rec->value }}</td></tr>
            @endforeach
        </table>
        <h3>Badge Records</h3>
        <table>
            <tr><th>Date</th><th>Badge Name</th><th>Quantity</th></tr>
            @foreach($user->badgeRecords as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->badge_name }}</td><td>{{ $rec->quantity }}</td></tr>
            @endforeach
        </table>
        <h3>Points Records</h3>
        <table>
            <tr><th>Date</th><th>Value</th><th>Notes</th></tr>
            @foreach($user->pointsRecords as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->value }}</td><td>{{ $rec->notes }}</td></tr>
            @endforeach
        </table>
        <h3>Attendance Records</h3>
        <table>
            <tr><th>Date</th><th>Attendance Before</th><th>Attendance After</th><th>First Attendance</th><th>Value</th></tr>
            @foreach($user->attendanceRecords as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->attendance_before }}</td><td>{{ $rec->attendance_after }}</td><td>{{ $rec->first_attendance }}</td><td>{{ $rec->value }}</td></tr>
            @endforeach
        </table>
        <h3>Scoreboard Message</h3>
        @if($user->scoreboardMessage)
        <table>
            <tr><th>Message</th></tr>
            <tr><td>{{ $user->scoreboardMessage->message }}</td></tr>
        </table>
        @else
        <p>No message.</p>
        @endif
        <h3>Lock History</h3>
        <table>
            <tr><th>Date</th><th>Description</th><th>Change</th></tr>
            @foreach($user->lockHistory as $rec)
            <tr><td>{{ $rec->date }}</td><td>{{ $rec->description }}</td><td>{{ $rec->change }}</td></tr>
            @endforeach
        </table>
        <hr>
    @endforeach
</body>
</html> 