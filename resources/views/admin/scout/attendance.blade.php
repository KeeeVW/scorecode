@extends('layouts.admin')

@section('title', 'Scout Attendance Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Scout Attendance Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Scout</th>
                                    <th>Date</th>
                                    <th>Value</th>
                                    <th>Attendance Before</th>
                                    <th>Attendance After</th>
                                    <th>First Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @foreach($user->attendanceRecords as $record)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $record->date ? $record->date->format('Y-m-d') : '' }}</td>
                                            <td>{{ $record->value }}</td>
                                            <td>{{ $record->attendance_before ? 'Yes' : 'No' }}</td>
                                            <td>{{ $record->attendance_after ? 'Yes' : 'No' }}</td>
                                            <td>{{ $record->first_attendance ? 'Yes' : 'No' }}</td>
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
@endsection 