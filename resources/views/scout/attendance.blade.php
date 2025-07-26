@extends('layouts.scout')
@section('title', 'Attendance')
@section('content')
<div class="container-fluid">
    <div class="row g-3 flex-wrap">
        <!-- Statistics Cards -->
        <div class="col-12 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Attendance</h3>
                    <h2 class="display-4">{{ $records->sum('value') }}</h2>
                    <div class="mt-2">
                        <strong>Total Attendance Points:</strong>
                        {{ $records->sum(function($r) {
                            return $r->value * 100
                                + ($r->attendance_after ? 200 : 0)
                                + ($r->attendance_before ? 300 : 0)
                                + ($r->first_attendance ? 500 : 0);
                        }) }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Records Table -->
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped attendance-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Attendance Value</th>
                                    <th>Points</th>
                                    <th>Badges</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->date instanceof \Carbon\Carbon ? $record->date->format('Y-m-d') : $record->date }}</td>
                                    <td>{{ $record->value }}</td>
                                    <td>{{ $record->value * 100
                                        + ($record->attendance_after ? 200 : 0)
                                        + ($record->attendance_before ? 300 : 0)
                                        + ($record->first_attendance ? 500 : 0) }}</td>
                                    <td>
                                        @if($record->first_attendance)
                                            <span class="badge bg-success">First Attendance <span class="fw-normal">(+500)</span></span>
                                        @endif
                                        @if($record->attendance_before)
                                            <span class="badge bg-primary">Before <span class="fw-normal">(+300)</span></span>
                                        @endif
                                        @if($record->attendance_after)
                                            <span class="badge bg-warning text-dark">After <span class="fw-normal">(+200)</span></span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
@media (max-width: 576px) {
    .attendance-table {
        font-size: 0.85rem !important;
        width: 100% !important;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .attendance-table th, .attendance-table td {
        padding: 0.3rem 0.4rem !important;
    }
}
</style>
