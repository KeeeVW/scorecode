@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Event Reports</h1>
    <p>This page will display event-related reports.</p>
    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Back to Reports</a>
</div>
@endsection 