@extends('layouts.admin')

@section('title', 'Scout Badges Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Scout Badge Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Scout</th>
                                    <th>Date</th>
                                    <th>Badge Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @foreach($user->badgeRecords as $record)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $record->date ? $record->date->format('Y-m-d') : '' }}</td>
                                            <td>{{ $record->badge_name }}</td>
                                            <td>{{ $record->quantity }}</td>
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