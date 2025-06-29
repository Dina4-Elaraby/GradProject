

{{-- Admin Dashboard View --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .dashboard-card {
        color: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
        text-decoration: none;
    }

    .dashboard-card:hover {
        transform: scale(1.03);
        text-decoration: none;
    }

    .bg-nature { background-color: #2e7d32; } /* Green for users */
    .bg-earth { background-color: #6d4c41; }  /* Brown for plants */
    .bg-sun   { background-color: #fbc02d; color: #000; } /* Yellow for diseases */
    .bg-water { background-color: #0288d1; } /* Blue for treatments */

    .dashboard-icon {
        font-size: 35px;
        margin-bottom: 10px;
    }
</style>

<div class="container mt-4">
    <h3 class="mb-4">🌿 System Summary</h3>

    <div class="row text-center">
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.users.show') }}" class="dashboard-card bg-nature d-block">
                <div class="dashboard-icon">👤</div>
                <h5>Users</h5>
                <h2>{{ $users->count() }}</h2>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.plants.show') }}" class="dashboard-card bg-earth d-block">
                <div class="dashboard-icon">🌱</div>
                <h5>Plants</h5>
                <h2>{{ $plants->count() }}</h2>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.diseases.index') }}" class="dashboard-card bg-sun d-block">
                <div class="dashboard-icon">🦠</div>
                <h5>Diseases</h5>
                <h2>{{ $diseases->count() }}</h2>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.treatments.index') }}" class="dashboard-card bg-water d-block">
                <div class="dashboard-icon">💊</div>
                <h5>Treatments</h5>
                <h2>{{ $treatments->count() }}</h2>
            </a>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="card mt-5">
        <div class="card-header">
            <h5>📊 Overview Chart</h5>
        </div>
        <div class="card-body">
            <canvas id="summaryChart" height="100"></canvas>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Chart Initialization --}}
<script>
    const ctx = document.getElementById('summaryChart').getContext('2d');
    const summaryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['users', 'plants', 'diseases', 'treatments'],
            datasets: [{
                label: 'Total Count',
                data: [
                    {{ $users->count() }},
                    {{ $plants->count() }},
                    {{ $diseases->count() }},
                    {{ $treatments->count() }}
                ],
                backgroundColor: [
                    'rgba(46, 125, 50, 0.7)',   // Green
                    'rgba(109, 76, 65, 0.7)',   // Brown
                    'rgba(251, 192, 45, 0.7)',  // Yellow
                    'rgba(2, 136, 209, 0.7)'    // Blue
                ],
                borderColor: [
                    'rgba(46, 125, 50, 1)',
                    'rgba(109, 76, 65, 1)',
                    'rgba(251, 192, 45, 1)',
                    'rgba(2, 136, 209, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Number of Records'
                    }
                }
            }
        }
    });
</script>
@endsection
