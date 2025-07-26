@extends('layouts.scout')

@section('title', 'Scoreboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Previous Messages</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($messages as $msg)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $msg->message }}</span>
                            <span class="badge bg-secondary">{{ $msg->created_at?->format('Y-m-d H:i') }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center">No previous messages</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 