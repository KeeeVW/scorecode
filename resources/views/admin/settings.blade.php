@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Settings</h5>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h5>Data & Privacy</h5>
                        <div class="mb-3">
                            <label class="form-label">Download Data Backup</label><br>
                            <a href="{{ route('admin.settings.backup') }}" class="btn btn-outline-primary">Download Backup</a>
                        </div>
                        <input type="hidden" name="data_retention" value="365">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 