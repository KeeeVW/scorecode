@extends('layouts.scout')

@section('title', 'Total')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Points Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Average Points Per Day:</strong> {{ $averagePerDay }}
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Uniform</td>
                                <td>{{ $uniformPoints }}</td>
                            </tr>
                            <tr>
                                <td>Korasa</td>
                                <td>{{ $korasaPoints }}</td>
                            </tr>
                            <tr>
                                <td>Badges</td>
                                <td>{{ $badgesPoints }}</td>
                            </tr>
                            <tr>
                                <td> Points</td>
                                <td>{{ $pointsPoints }}</td>
                            </tr>
                            <tr>
                                <td>Attendance</td>
                                <td>{{ $attendancePoints }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $total }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Points Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <canvas id="pointsChart" width="500" height="400" style="width: 500px; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('pointsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Uniform', 'Korasa', 'Badges', ' Points', 'Attendance'],
            datasets: [{
                data: [
                    {{ $uniformPoints ?? 0 }},
                    {{ $korasaPoints ?? 0 }},
                    {{ $badgesPoints ?? 0 }},
                    {{ $pointsPoints ?? $total }},
                    {{ $attendancePoints ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
@endsection 