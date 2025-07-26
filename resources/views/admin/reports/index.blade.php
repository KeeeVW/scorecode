@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Reports</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>User Activity Reports</h5>
                </div>
                <div class="card-body">
                    <p>Attendance Reports, Login/Logout History, Profile Updates</p>
                    <a href="{{ route('admin.scout.attendance') }}" class="btn btn-primary">View Attendance</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Performance & Progress Reports</h5>
                </div>
                <div class="card-body">
                    <p>Badge Achievements, Points Summary, Uniform/Lock Records</p>
                    <a href="{{ route('admin.scout.badges') }}" class="btn btn-primary">View Badges</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Engagement & Participation</h5>
                </div>
                <div class="card-body">
                    <p>Event Participation, Top Performers</p>
                    <a href="{{ route('admin.reports.events') }}" class="btn btn-primary">View Events</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Administrative Reports</h5>
                </div>
                <div class="card-body">
                    <p>User List Export, Inactive Users, Role Distribution</p>
                    <a href="{{ route('admin.settings.export_users', ['format' => 'csv']) }}" class="btn btn-primary">Export Users</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Custom Reports</h5>
                </div>
                <div class="card-body">
                    <p>Date Range Filters, Group/Team Reports, Custom Queries</p>
                    <a href="{{ route('admin.reports.custom_query') }}" class="btn btn-primary">Custom Query</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Visualizations</h5>
                </div>
                <div class="card-body">
                    <p>Charts & Graphs, Downloadable Reports</p>
                    <a href="{{ route('admin.reports.charts') }}" class="btn btn-primary">View Charts</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 