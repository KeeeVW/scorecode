@extends('layouts.scout')

@section('title', 'Scout Dashboard')

@section('content')
<div class="container">
    <h1 class="dashboard-title">Welcome to your Scout Dashboard!</h1>
    <p>This is where your dashboard content will go.</p>
</div>
<style>
@media (max-width: 767.98px) {
    .dashboard-title {
        text-align: center;
        font-size: 2rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
}
</style>
@endsection 