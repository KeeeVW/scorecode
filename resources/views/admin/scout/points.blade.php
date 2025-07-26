@extends('layouts.admin')

@section('title', 'Scout Points Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Scout Points Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Scout</th>
                                    <th>Date</th>
                                    <th>Value</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @foreach($user->pointsRecords as $record)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $record->date ? $record->date->format('Y-m-d') : '' }}</td>
                                            <td>{{ $record->value }}</td>
                                            <td>{{ $record->notes }}</td>
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