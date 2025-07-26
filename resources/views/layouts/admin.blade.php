<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wadi El Nile Scouts</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --admin-primary: #2c3e50;
            --admin-secondary: #34495e;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background-color: var(--admin-primary);
            min-height: 100vh;
            padding-top: 1rem;
        }
        
        .sidebar .nav-link {
            color: #fff;
            padding: 0.5rem 1rem;
            margin: 0.2rem 0;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--admin-secondary);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--admin-secondary);
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        @media (max-width: 576px) {
            .admin-sidebar {
                display: none;
                position: fixed;
                top: 0; left: 0; height: 100vh; width: 220px;
                background: #222; z-index: 1050;
                overflow-y: auto;
                transition: transform 0.3s;
                font-size: 0.95rem;
            }
            .admin-sidebar.show {
                display: block;
                transform: translateX(0);
            }
            .admin-toggle-btn {
                display: block;
                position: fixed;
                top: 10px; left: 10px;
                z-index: 1100;
                background: #222; color: #fff;
                border: none; border-radius: 4px;
                padding: 0.4rem 0.7rem;
                font-size: 1.2rem;
            }
            .admin-content {
                margin-left: 0 !important;
                padding-top: 56px !important;
            }
            .action-icon {
                font-size: 1.1rem !important;
                margin: 0 0.2rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <div class="position-sticky">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/scout-logo.png') }}" alt="Scout Logo" class="img-fluid" style="max-width: 150px;">
                        <h5 class="text-white mt-2">Admin Panel</h5>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                               href="{{ route('admin.users') }}">
                                <i class="fas fa-users me-2"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports') }}">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.settings') }}">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent text-white w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.querySelector('.admin-toggle-btn');
        var sidebar = document.querySelector('.admin-sidebar');
        if(btn && sidebar) {
            btn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    });
    </script>
</body>
</html> 