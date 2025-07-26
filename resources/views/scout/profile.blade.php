@extends('layouts.scout')

@section('title', 'Profile')

@section('content')
<div class="container-fluid" style="background: url('{{ auth()->user()->profile_picture }}') center center/cover no-repeat fixed; min-height: 100vh; position: relative;">
    <div style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);z-index:0;"></div>
    <div class="row justify-content-center" style="position:relative;z-index:1;">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center" style="background: var(--primary); color: #fff;">
                    <img src="{{ auth()->user()->profile_picture }}" alt="Profile Picture" class="rounded-circle mb-2" style="width: 120px; height: 120px; border: 4px solid var(--secondary);">
                    <h2 class="mt-2"><i class="fas fa-user"></i> {{ auth()->user()->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded" style="background: var(--secondary); color: #fff;">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <h4>Total Points</h4>
                                <p class="fs-3 mb-0">{{ auth()->user()->getTotalScore() }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background: var(--secondary); color: #fff;">
                                <i class="fas fa-lock fa-2x mb-2"></i>
                                <h4>Total Locks</h4>
                                <p class="fs-3 mb-0">{{ 23 - auth()->user()->lockHistory->sum('change') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background: var(--primary); color: #fff;">
                                <i class="fas fa-ranking-star fa-2x mb-2"></i>
                                <h4>Position</h4>
                                <p class="fs-3 mb-0">{{ $currentPosition }} <span class="fs-6">out of</span> {{ $totalScouts }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 