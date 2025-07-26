@php
use App\Services\BadgeService;
@endphp

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Profile for {{ $user->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Scout Profile Details</h6>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            @if ($user->scoutProfile)
                <p><strong>Primary Color:</strong> {{ $user->scoutProfile->theme_primary ?? 'N/A' }}</p>
                <p><strong>Secondary Color:</strong> {{ $user->scoutProfile->theme_secondary ?? 'N/A' }}</p>
                <p><strong>Active:</strong> {{ $user->scoutProfile->is_active ? 'Yes' : 'No' }}</p>
                <p><strong>Remaining Locks:</strong> {{ 23 - $user->lockHistory->sum('change') }}</p>
            @else
                <p><strong>Scout Profile:</strong> Not available</p>
            @endif
            <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
        </div>
    </div>

    <!-- Navigation for different sections -->
    <ul class="nav nav-tabs" id="scoutProfileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="uniform-tab" data-bs-toggle="tab" data-bs-target="#uniform" type="button" role="tab" aria-controls="uniform" aria-selected="true">Uniform</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="korasa-tab" data-bs-toggle="tab" data-bs-target="#korasa" type="button" role="tab" aria-controls="korasa" aria-selected="false">Korasa</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="badges-tab" data-bs-toggle="tab" data-bs-target="#badges" type="button" role="tab" aria-controls="badges" aria-selected="false">Badges</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="points-tab" data-bs-toggle="tab" data-bs-target="#points" type="button" role="tab" aria-controls="points" aria-selected="false">Points</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">Attendance</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="locks-tab" data-bs-toggle="tab" data-bs-target="#locks" type="button" role="tab" aria-controls="locks" aria-selected="false">Locks</button>
        </li>
    </ul>
    <div class="tab-content" id="scoutProfileTabsContent">
        <div class="tab-pane fade show active" id="uniform" role="tabpanel" aria-labelledby="uniform-tab">
            <h3>Uniform Management</h3>

            <h4>Add New Uniform Record</h4>
            <form action="{{ route('admin.uniform.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="uniform_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="uniform_date" name="date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="uniform_value" class="form-label">Value (will be multiplied by 50)</label>
                        <input type="number" class="form-control" id="uniform_value" name="value" required min="0" step="0.01">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Uniform Record</button>
            </form>

            <h4>Existing Uniform Records</h4>
            @forelse($user->uniformRecords as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>Date: {{ $record->date }} - Value: {{ $record->value }} ({{ $record->value * 50 }} points)</span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editUniformModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.uniform.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Uniform Modal -->
                <div class="modal fade" id="editUniformModal{{ $record->id }}" tabindex="-1" aria-labelledby="editUniformModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUniformModalLabel{{ $record->id }}">Edit Uniform Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.uniform.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_uniform_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_uniform_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_uniform_value{{ $record->id }}" class="form-label">Value (will be multiplied by 50)</label>
                                        <input type="number" class="form-control" id="edit_uniform_value{{ $record->id }}" name="value" value="{{ $record->value }}" required min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No uniform records found.</p>
            @endforelse
        </div>
        <div class="tab-pane fade" id="korasa" role="tabpanel" aria-labelledby="korasa-tab">
            <h3>Korasa Management</h3>
            <h4>Add New Notebook Record</h4>
            <form action="{{ route('admin.korasa.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="korasa_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="korasa_date" name="date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="korasa_value" class="form-label">Value (will be multiplied by 50)</label>
                        <input type="number" class="form-control" id="korasa_value" name="value" required min="0" step="0.01">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Notebook Record</button>
            </form>

            <h4>Existing Notebook Records</h4>
            @forelse($user->korasaRecords as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>Date: {{ $record->date }} - Value: {{ $record->value }} ({{ $record->value * 50 }} points)</span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editKorasaModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.korasa.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Korasa Modal -->
                <div class="modal fade" id="editKorasaModal{{ $record->id }}" tabindex="-1" aria-labelledby="editKorasaModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editKorasaModalLabel{{ $record->id }}">Edit Notebook Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.korasa.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_korasa_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_korasa_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_korasa_value{{ $record->id }}" class="form-label">Value (will be multiplied by 50)</label>
                                        <input type="number" class="form-control" id="edit_korasa_value{{ $record->id }}" name="value" value="{{ $record->value }}" required min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No notebook records found.</p>
            @endforelse
        </div>
        <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
            <h3>Badges Management</h3>
            <h4>Add New Badge Record</h4>
            <form action="{{ route('admin.badges.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="badge_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="badge_date" name="date" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="badge_name" class="form-label">Badge Type</label>
                        <select class="form-select" id="badge_name" name="badge_name" required>
                            <option value="شارات رياضية">شارات رياضية (x300)</option>
                            <option value="شارات دينيه">شارات دينيه (x300)</option>
                            <option value="شارات كشفيه">شارات كشفيه (x500)</option>
                            <option value="شارات مهاريه">شارات مهاريه (x300)</option>
                            <option value="شارات فنيه">شارات فنيه (x300)</option>
                            <option value="شارة الطليعة">شارة الطليعة (x1000)</option>
                            <option value="كشاف تاني">كشاف تاني (x2500)</option>
                            <option value="كشاف أول">كشاف أول (x5000)</option>
                            <option value="مخيم">مخيم (x5000)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="badge_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="badge_quantity" name="quantity" value="1" min="1" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Badge Record</button>
            </form>

            <h4>Existing Badge Records</h4>
            @forelse($user->badgeRecords as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>
                            Date: {{ $record->date }} - 
                            Type: {{ $record->badge_name }} - 
                            Quantity: {{ $record->quantity }} - 
                            Points: {{ \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity) }}
                        </span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editBadgeModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.badges.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Badge Modal -->
                <div class="modal fade" id="editBadgeModal{{ $record->id }}" tabindex="-1" aria-labelledby="editBadgeModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBadgeModalLabel{{ $record->id }}">Edit Badge Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.badges.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_badge_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_badge_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_badge_name{{ $record->id }}" class="form-label">Badge Type</label>
                                        <select class="form-select" id="edit_badge_name{{ $record->id }}" name="badge_name" required>
                                            <option value="شارات رياضية" {{ $record->badge_name === 'شارات رياضية' ? 'selected' : '' }}>شارات رياضية (x300)</option>
                                            <option value="شارات دينيه" {{ $record->badge_name === 'شارات دينيه' ? 'selected' : '' }}>شارات دينيه (x300)</option>
                                            <option value="شارات كشفيه" {{ $record->badge_name === 'شارات كشفيه' ? 'selected' : '' }}>شارات كشفيه (x500)</option>
                                            <option value="شارات مهاريه" {{ $record->badge_name === 'شارات مهاريه' ? 'selected' : '' }}>شارات مهاريه (x300)</option>
                                            <option value="شارات فنيه" {{ $record->badge_name === 'شارات فنيه' ? 'selected' : '' }}>شارات فنيه (x300)</option>
                                            <option value="شارة الطليعة" {{ $record->badge_name === 'شارة الطليعة' ? 'selected' : '' }}>شارة الطليعة</option>
                                            <option value="كشاف تاني" {{ $record->badge_name === 'كشاف تاني' ? 'selected' : '' }}>كشاف تاني (x2500)</option>
                                            <option value="كشاف أول" {{ $record->badge_name === 'كشاف أول' ? 'selected' : '' }}>كشاف أول (x5000)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_badge_quantity{{ $record->id }}" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="edit_badge_quantity{{ $record->id }}" name="quantity" value="{{ $record->quantity }}" min="1" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No badge records found.</p>
            @endforelse
        </div>
        <div class="tab-pane fade" id="points" role="tabpanel" aria-labelledby="points-tab">
            <h3>Points Management</h3>
            <h4>Add New Point Record</h4>
            <form action="{{ route('admin.points.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="point_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="point_date" name="date" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="point_value" class="form-label">Value</label>
                        <input type="number" class="form-control" id="point_value" name="value" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="point_notes" class="form-label">Notes</label>
                        <input type="text" class="form-control" id="point_notes" name="notes">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Point Record</button>
            </form>

            <h4>Existing Point Records</h4>
            @forelse($user->pointsRecords as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>Date: {{ $record->date }} - Value: {{ $record->value }} - Notes: {{ $record->notes }}</span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editPointModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.points.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Point Modal -->
                <div class="modal fade" id="editPointModal{{ $record->id }}" tabindex="-1" aria-labelledby="editPointModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPointModalLabel{{ $record->id }}">Edit Point Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.points.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_point_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_point_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_point_value{{ $record->id }}" class="form-label">Value</label>
                                        <input type="number" class="form-control" id="edit_point_value{{ $record->id }}" name="value" value="{{ $record->value }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_point_notes{{ $record->id }}" class="form-label">Notes</label>
                                        <input type="text" class="form-control" id="edit_point_notes{{ $record->id }}" name="notes" value="{{ $record->notes }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No points records found.</p>
            @endforelse
        </div>
        <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
            <h3>Attendance Management</h3>
            <h4>Add New Attendance Record</h4>
            <form action="{{ route('admin.attendance.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="attendance_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="attendance_date" name="date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="attendance_value" class="form-label">Value (will be multiplied by 100)</label>
                        <input type="number" class="form-control" id="attendance_value" name="value" required min="0" step="0.01">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="attendance_before" name="attendance_before" value="1">
                            <label class="form-check-label" for="attendance_before">
                                Attendant Submission Before (x300)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="attendance_after" name="attendance_after" value="1">
                            <label class="form-check-label" for="attendance_after">
                                Attendant Submission After (x200)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="first_attendance" name="first_attendance" value="1">
                            <label class="form-check-label" for="first_attendance">
                                First Attendance Submission (x500)
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Attendance Record</button>
            </form>

            <h4>Existing Attendance Records</h4>
            @forelse($user->attendanceRecords as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>
                            Date: {{ $record->date }} - 
                            Value: {{ $record->value }} ({{ $record->value * 100 }} points) - 
                            Bonuses: 
                            @if($record->attendance_before)
                                <span class="badge bg-success">Before (x300)</span>
                            @endif
                            @if($record->attendance_after)
                                <span class="badge bg-info">After (x200)</span>
                            @endif
                            @if($record->first_attendance)
                                <span class="badge bg-warning">First (x500)</span>
                            @endif
                            - Total Points: {{ ($record->value * 100) + 
                                ($record->attendance_before ? 300 : 0) + 
                                ($record->attendance_after ? 200 : 0) + 
                                ($record->first_attendance ? 500 : 0) }}
                        </span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editAttendanceModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.attendance.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Attendance Modal -->
                <div class="modal fade" id="editAttendanceModal{{ $record->id }}" tabindex="-1" aria-labelledby="editAttendanceModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAttendanceModalLabel{{ $record->id }}">Edit Attendance Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.attendance.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_attendance_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_attendance_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_attendance_value{{ $record->id }}" class="form-label">Value (will be multiplied by 100)</label>
                                        <input type="number" class="form-control" id="edit_attendance_value{{ $record->id }}" name="value" value="{{ $record->value }}" required min="0" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_attendance_before{{ $record->id }}" name="attendance_before" value="1" {{ $record->attendance_before ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_attendance_before{{ $record->id }}">
                                                Attendant Submission Before (x300)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_attendance_after{{ $record->id }}" name="attendance_after" value="1" {{ $record->attendance_after ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_attendance_after{{ $record->id }}">
                                                Attendant Submission After (x200)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="edit_first_attendance{{ $record->id }}" name="first_attendance" value="1" {{ $record->first_attendance ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_first_attendance{{ $record->id }}">
                                                First Attendance Submission (x500)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No attendance records found.</p>
            @endforelse
        </div>
        <div class="tab-pane fade" id="locks" role="tabpanel" aria-labelledby="locks-tab">
            <h3>Locks Management</h3>
            <h4>Add New Lock Record</h4>
            <form action="{{ route('admin.locks.store', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lock_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="lock_date" name="date" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lock_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="lock_description" name="description" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lock_change" class="form-label">Change</label>
                        <select class="form-select" id="lock_change" name="change" required>
                            <option value="1">Lock</option>
                            <option value="0">Unlock</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Lock Record</button>
            </form>

            <h4>Existing Lock Records</h4>
            @forelse($user->lockHistory as $record)
                <div class="card mb-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <span>Date: {{ $record->date }} - Action: {{ $record->change ? 'Lock' : 'Unlock' }}</span>
                        <div>
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editLockModal{{ $record->id }}">Edit</button>
                            <form action="{{ route('admin.locks.delete', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Lock Modal -->
                <div class="modal fade" id="editLockModal{{ $record->id }}" tabindex="-1" aria-labelledby="editLockModalLabel{{ $record->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLockModalLabel{{ $record->id }}">Edit Lock Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.locks.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_lock_date{{ $record->id }}" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="edit_lock_date{{ $record->id }}" name="date" value="{{ $record->date }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_lock_change{{ $record->id }}" class="form-label">Action</label>
                                        <select class="form-select" id="edit_lock_change{{ $record->id }}" name="change" required>
                                            <option value="1" {{ $record->change ? 'selected' : '' }}>Lock</option>
                                            <option value="0" {{ !$record->change ? 'selected' : '' }}>Unlock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No lock records found.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection 

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Restore last active tab
    var lastTab = localStorage.getItem('adminProfileLastTab');
    if (lastTab) {
        var tab = document.querySelector('button[data-bs-target="' + lastTab + '"]');
        if (tab) {
            var tabInstance = new bootstrap.Tab(tab);
            tabInstance.show();
        }
    }
    // Save tab on click
    var tabLinks = document.querySelectorAll('#scoutProfileTabs button[data-bs-toggle="tab"]');
    tabLinks.forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('adminProfileLastTab', e.target.getAttribute('data-bs-target'));
        });
    });
});
</script> 

<style>
@media (max-width: 576px) {
    .profile-section {
        font-size: 0.95rem !important;
        padding: 0.7rem 0.5rem !important;
    }
    .action-icon {
        font-size: 1.1rem !important;
        margin: 0 0.2rem !important;
    }
}
</style> 