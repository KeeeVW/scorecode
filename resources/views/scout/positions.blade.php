@extends('layouts.scout')

@section('title', 'Positions')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Scout Positions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                <tr @if($user['name'] === auth()->user()->name) style="background: var(--secondary); color: #fff; font-weight: bold; font-size: 1.1em;" @endif>
                                    <td>
                                        @if($index == 0) 🥇
                                        @elseif($index == 1) 🥈
                                        @elseif($index == 2) 🥉
                                        @elseif($index == 3) 4️⃣
                                        @elseif($index == 4) 5️⃣
                                        @elseif($index == 5) 6️⃣
                                        @else {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user['name'] }}
                                        @if($user['name'] === auth()->user()->name)
                                            <span class="badge bg-warning text-dark ms-2">You</span>
                                        @endif
                                    </td>
                                </tr>
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