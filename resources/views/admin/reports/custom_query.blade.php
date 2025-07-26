@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Custom Query Reports</h1>
    <p>This page will allow you to run custom queries for reports.</p>
    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Back to Reports</a>
</div>
@endsection 