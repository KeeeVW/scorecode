@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Charts & Visualizations</h1>
    <p>This page will display charts and other data visualizations.</p>
    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Back to Reports</a>
</div>
@endsection 