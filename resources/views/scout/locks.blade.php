@extends('layouts.scout')

@section('title', 'Locks')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Remaining Locks</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="display-3 mb-0">{{ 23 - $history->sum('change') }}</h2>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Locks Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Locks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $record)
                                <tr>
                                    <td>{{ $record->created_at instanceof \Carbon\Carbon ? $record->created_at->format('Y-m-d') : $record->created_at }}</td>
                                    <td>{{ $record->change }}</td>
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