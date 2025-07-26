@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<nav class="navbar navbar-light bg-light d-md-none px-2" style="min-height:48px;">
    <button class="btn btn-outline-secondary me-2" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <span class="navbar-brand mb-0 h5">User Management</span>
</nav>
<div class="d-flex justify-content-center align-items-start w-100" style="min-height:0;">
    <div class="card w-100 w-md-75 w-lg-50 my-2" style="max-width:700px;">
        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
            <h5 class="mb-0">User Management</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>
        <div class="card-body py-2 px-2">
            <div class="admin-user-table-wrapper">
                <table class="table table-striped admin-user-table mb-0">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ ucfirst($user->name) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_admin)
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge {{ $user->scoutProfile->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->scoutProfile->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                                    <td>
                                        <div class="d-flex flex-row flex-nowrap gap-1 align-items-center justify-content-start">
                                            <button type="button" class="btn btn-info btn-action" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(!$user->is_admin)
                                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $user->scoutProfile->is_active ? 'btn-warning' : 'btn-success' }} btn-action" title="{{ $user->scoutProfile->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $user->scoutProfile->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.scout.profiles.show', $user->id) }}" class="btn btn-primary btn-action" title="Manage Profile">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-action" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-action" title="View as User">
                                                        <i class="fas fa-user-secret"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="theme_primary" class="form-label">Primary Theme Color</label>
                        <input type="color" class="form-control form-control-color" id="theme_primary" name="theme_primary" value="#2c3e50" required>
                    </div>
                    <div class="mb-3">
                        <label for="theme_secondary" class="form-label">Secondary Theme Color</label>
                        <input type="color" class="form-control form-control-color" id="theme_secondary" name="theme_secondary" value="#34495e" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modals -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name_{{ $user->id }}" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_name_{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email_{{ $user->id }}" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email_{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_{{ $user->id }}" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit_password_{{ $user->id }}" name="password">
                    </div>
                    @if(!$user->is_admin)
                        <div class="mb-3">
                            <label for="edit_theme_primary_{{ $user->id }}" class="form-label">Primary Theme Color</label>
                            <input type="color" class="form-control form-control-color" id="edit_theme_primary_{{ $user->id }}" name="theme_primary" value="{{ $user->scoutProfile->theme_primary }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_theme_secondary_{{ $user->id }}" class="form-label">Secondary Theme Color</label>
                            <input type="color" class="form-control form-control-color" id="edit_theme_secondary_{{ $user->id }}" name="theme_secondary" value="{{ $user->scoutProfile->theme_secondary }}" required>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

    <div class="card mb-4">
        <div class="card-header">All Scoreboard Messages</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scoreboardMessages as $msg)
                        <tr>
                            <td>{{ $msg->user_name }}</td>
                            <td>{{ $msg->message }}</td>
                            <td>
                                <form action="{{ route('admin.scoreboard.delete', $msg->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<div class="card mb-4">
    <div class="card-header">Send Custom Message to Selected Users</div>
    <div class="card-body">
        <form action="{{ route('admin.scoreboard.custom_broadcast') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_ids" class="form-label">Select Users</label>
                <select name="user_ids[]" id="user_ids" class="form-control" size="6"   multiple required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" class="form-control" rows="10" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
</div>
@endsection 

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Restore last active tab
    var lastTab = localStorage.getItem('adminLastTab');
    if (lastTab) {
        var tab = document.querySelector('a[href="' + lastTab + '"]');
        if (tab) {
            var tabInstance = new bootstrap.Tab(tab);
            tabInstance.show();
        }
    }

    // Save tab on click
    var tabLinks = document.querySelectorAll('#adminTabs a[data-bs-toggle="tab"]');
    tabLinks.forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('adminLastTab', e.target.getAttribute('href'));
        });
    });
});
</script> 

<style>
@media (max-width: 576px) {
    body {
        padding: 0 !important;
        margin: 0 !important;
        background: #f8f9fa !important;
    }
    .card {
        margin: 0 !important;
        border-radius: 10px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
    }
    .admin-user-table-wrapper {
        overflow-x: auto;
        width: 100%;
        display: block;
        margin: 0;
    }
    .admin-user-table {
        min-width: 420px;
        font-size: 0.78rem;
    }
    .admin-user-table th, .admin-user-table td {
        padding: 0.18rem 0.25rem !important;
        height: 32px !important;
        vertical-align: middle !important;
    }
    .admin-user-table .btn {
        font-size: 0.78rem !important;
        padding: 0.18rem 0.4rem !important;
        margin-bottom: 0.12rem !important;
        min-width: 70px;
        height: 28px;
        line-height: 1.1;
    }
    .admin-user-table .action-icon, .admin-user-table i {
        font-size: 0.95rem !important;
    }
    .admin-user-table .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .navbar {
        min-height: 44px !important;
        padding: 0.2rem 0.5rem !important;
        font-size: 1rem !important;
    }
    .navbar .btn {
        font-size: 1.1rem !important;
        padding: 0.2rem 0.5rem !important;
    }
    .admin-user-table {
        font-size: 0.75rem;
    }
    .admin-user-table th, .admin-user-table td {
        padding: 0.08rem 0.15rem !important;
        height: 22px !important;
        vertical-align: middle !important;
        line-height: 1.1 !important;
        background: #fff !important;
    }
    .admin-user-table tr {
        height: 24px !important;
        min-height: 22px !important;
    }
    .admin-user-table .btn {
        font-size: 0.72rem !important;
        padding: 0.08rem 0.25rem !important;
        margin: 0 0.05rem !important;
        min-width: 0;
        height: 22px;
        line-height: 1.1;
        display: inline-block;
    }
    .admin-user-table .d-flex {
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 0.1rem !important;
        align-items: center !important;
        justify-content: flex-start !important;
    }
    .admin-user-table .action-icon, .admin-user-table i {
        font-size: 0.85rem !important;
    }
    .btn-action {
        font-size: 0.9rem !important;
        padding: 0.15rem 0.22rem !important;
        margin: 0 0.05rem !important;
        min-width: 28px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-action i {
        font-size: 1rem !important;
        margin: 0 !important;
    }
}
@media (min-width: 577px) {
    .card {
        margin: 1.5rem auto !important;
        border-radius: 14px !important;
        max-width: 700px;
    }
    .admin-user-table {
        font-size: 0.95rem;
    }
}
</style> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebarToggle = document.getElementById('sidebarToggle');
    if(sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            var sidebar = document.querySelector('.admin-sidebar');
            if(sidebar) sidebar.classList.toggle('show');
        });
    }
    // Dynamic scaling for card
    function scaleCard() {
        var card = document.querySelector('.card');
        if(card) {
            var w = window.innerWidth;
            if(w < 400) card.style.maxWidth = '98vw';
            else if(w < 600) card.style.maxWidth = '99vw';
            else card.style.maxWidth = '700px';
        }
    }
    window.addEventListener('resize', scaleCard);
    scaleCard();
});
</script> 