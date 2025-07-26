<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wadi El Nile Scouts') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <style>
        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            text-decoration: none;
        }
        .nav-link {
            color: #666;
            text-decoration: none;
            padding: 0.5rem 1rem;
        }
        .nav-link:hover {
            color: #333;
        }
        .btn-link {
            color: #666;
            text-decoration: none;
            padding: 0.5rem 1rem;
        }
        .btn-link:hover {
            color: #333;
        }
        .alert {
            margin: 1rem;
            padding: 1rem;
            border-radius: 0.25rem;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="min-h-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex">
                        <!-- Logo -->
                        <div class="d-flex align-items-center">
                            <a class="navbar-brand" href="{{ route('welcome') }}">
                                {{ config('app.name', 'Wadi El Nile Scouts') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="d-none d-lg-flex ms-4">
                            @auth
                                @if(auth()->user()->is_admin)
                                    <a class="nav-link" href="{{ route('admin.users') }}">Users</a>
                                @else
                                    <a class="nav-link" href="{{ route('scout.uniform') }}">Uniform</a>
                                    <a class="nav-link" href="{{ route('scout.korasa') }}">Korasa</a>
                                    <a class="nav-link" href="{{ route('scout.badges') }}">Badges</a>
                                    <a class="nav-link" href="{{ route('scout.points') }}">Points</a>
                                    <a class="nav-link" href="{{ route('scout.attendance') }}">Attendance</a>
                                    <a class="nav-link" href="{{ route('scout.total') }}">Total</a>
                                    <a class="nav-link" href="{{ route('scout.scoreboard') }}">Scoreboard</a>
                                    <a class="nav-link" href="{{ route('scout.positions') }}">Positions</a>
                                    <a class="nav-link" href="{{ route('scout.locks') }}">Locks</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="d-none d-lg-flex align-items-center">
                        @if(auth()->check())
                            <div class="flex items-center space-x-4">
                                @if(session()->has('impersonator_id'))
                                    <form action="{{ route('admin.stop-impersonating') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-white bg-yellow-600 hover:bg-yellow-700 px-3 py-2 rounded-md text-sm font-medium">
                                            <i class="fas fa-user-secret mr-1"></i> Stop Impersonating
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="container py-4">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 