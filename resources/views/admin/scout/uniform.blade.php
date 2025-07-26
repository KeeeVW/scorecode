@extends('layouts.admin')

@section('title', 'Scout Uniform Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Scout Uniform Records</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUniformModal">
                        <i class="fas fa-plus"></i> Add New Record
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Scout</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @foreach($user->uniformRecords as $record)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $record->date?->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge {{ $record->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $record->status ? 'Complete' : 'Incomplete' }}
                                            </span>
                                        </td>
                                        <td>{{ $record->notes }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editUniformModal{{ $record->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.scout.uniform.delete', $record->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Uniform Record Modal -->
<div class="modal fade" id="createUniformModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.scout.uniform.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Uniform Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Scout</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Select Scout</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Complete</option>
                            <option value="0">Incomplete</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Uniform Record Modals -->
@foreach($users as $user)
    @foreach($user->uniformRecords as $record)
    <div class="modal fade" id="editUniformModal{{ $record->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.scout.uniform.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Uniform Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_date_{{ $record->id }}" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date_{{ $record->id }}" 
                                   name="date" value="{{ $record->date?->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status_{{ $record->id }}" class="form-label">Status</label>
                            <select class="form-select" id="edit_status_{{ $record->id }}" name="status" required>
                                <option value="1" {{ $record->status ? 'selected' : '' }}>Complete</option>
                                <option value="0" {{ !$record->status ? 'selected' : '' }}>Incomplete</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes_{{ $record->id }}" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes_{{ $record->id }}" 
                                      name="notes" rows="3">{{ $record->notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endsection 