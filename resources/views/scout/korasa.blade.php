@extends('layouts.scout')

@section('title', 'The Worksheet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Total</h3>
                    <h2 class="display-4">{{ $total }}</h2>
                    <div class="mt-2"><strong>Total Worksheet Points:</strong> {{ $total * 50 }}</div>
                </div>
            </div>
        </div>
        <!-- Records Table -->
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Korasa Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->date instanceof \Carbon\Carbon ? $record->date->format('Y-m-d') : $record->date }}</td>
                                    <td>{{ $record->value }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">No records found</td>
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