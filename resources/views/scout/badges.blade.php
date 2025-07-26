@extends('layouts.scout')

@section('title', 'Badges')

@section('content')
@php
    use App\Services\BadgeService;
    $totalPoints = $records->sum(function($record) { return \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity); });
@endphp
<div class="container-fluid">
    <div class="row g-3 flex-wrap">
        <!-- Statistics Cards -->
        <div class="col-12 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Badges</h3>
                    <h2 class="display-4">{{ $records->sum('quantity') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Badge Points</h3>
                    <h2 class="display-4">{{ $totalPoints }}</h2>
                </div>
            </div>
        </div>
        <!-- Records Table -->
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Badge Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Badge Name</th>
                                    <th>Quantity</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->date instanceof \Carbon\Carbon ? $record->date->format('Y-m-d') : $record->date }}</td>
                                    <td>{{ $record->badge_name }}</td>
                                    <td>{{ $record->quantity }}</td>
                                    <td>{{ \App\Services\BadgeService::calculatePoints($record->badge_name, $record->quantity) }}</td>
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