<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Scout Dashboard')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: {{ auth()->user()->scoutProfile && auth()->user()->scoutProfile->theme_primary ? auth()->user()->scoutProfile->theme_primary : '#007bff' }};
            --secondary: {{ auth()->user()->scoutProfile && auth()->user()->scoutProfile->theme_secondary ? auth()->user()->scoutProfile->theme_secondary : '#6c757d' }};
        }
        body {
            background: url('{{ auth()->user()->profile_picture }}') center center/contain no-repeat fixed;
            min-height: 100vh;
            position: relative;
        }
        .scout-bg-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.45);
            z-index: 0;
            pointer-events: none;
            backdrop-filter: blur(2px) brightness(1.1) saturate(110%);
            transition: background 0.4s;
        }
        .main-content, .container-fluid {
            position: relative;
            z-index: 1;
        }
        .navbar {
            background: var(--primary) !important;
        }
        .navbar .nav-link, .navbar-brand, .profile-name {
            color: #fff !important;
        }
        .navbar .nav-link.active, .navbar .nav-link:hover {
            background: var(--secondary) !important;
            color: #fff !important;
            border-radius: 0.25rem;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--secondary);
        }
        .profile-name {
            margin-left: 10px;
            font-weight: 500;
        }
        .navbar-profile {
            display: flex;
            align-items: center;
        }
        .main-content {
            padding-top: 80px;
        }
        /* MOBILE ONLY STYLES */
        @media (max-width: 767.98px) {
            .navbar {
                flex-direction: column;
                padding: 0.5rem 0.5rem;
            }
            .navbar .navbar-brand {
                font-size: 1.3rem;
                text-align: center;
                width: 100%;
                margin-bottom: 0.5rem;
                font-weight: bold;
                letter-spacing: 1px;
            }
            .navbar .nav-link {
                font-size: 1.1rem;
                padding: 0.75rem 1rem;
                margin-bottom: 2px;
            }
            .navbar-profile {
                flex-direction: row;
                align-items: flex-start;
                margin-top: 0.5rem;
                width: 100%;
                justify-content: flex-start;
            }
            .profile-img {
                width: 36px;
                height: 36px;
                margin-top: 0.2rem;
            }
            .profile-name {
                margin-left: 10px;
                margin-top: 0;
                font-size: 1.1rem;
                font-weight: 600;
            }
            .main-content {
                padding-top: 70px;
            }
            .navbar-toggler {
                margin-bottom: 0.5rem;
            }
            .navbar .nav-link i {
                font-size: 1.2em;
            }
            /* Profile/Welcome always visible */
            .navbar-profile {
                order: -1;
                margin-bottom: 0.5rem;
            }
            /* Panel/box sizing for mobile */
            .container-fluid, .container, .card, .main-content {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            .card {
                border-radius: 1rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            }
            .card-header, .card-body {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            /* Fix background for mobile */
            body {
                background-size: cover !important;
                background-position: center center !important;
            }
        }
        @media (max-width: 576px) {
            .navbar.scout-navbar, .navbar {
                display: flex !important;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                width: 100vw;
                max-width: 100vw;
                margin: 0;
                height: 54px;
                background: var(--primary, #e00) !important;
                border-radius: 0 0 18px 18px;
                font-size: 1.2rem;
                color: #fff;
                font-weight: 600;
                letter-spacing: 0.5px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.07);
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1100;
                padding: 0 0.5rem !important;
            }
            .navbar-toggler {
                position: relative;
                left: 0;
                top: 0;
                background: transparent;
                border: none;
                color: #fff;
                font-size: 1.6rem;
                padding: 0;
                width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0.85;
                z-index: 1101;
                margin-right: 10px;
            }
            .navbar-brand {
                margin: 0 auto;
                font-size: 1.2rem;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-align: center;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .navbar-collapse {
                position: fixed;
                top: 44px;
                left: 0;
                width: 100vw;
                background: rgba(0,0,0,0.85);
                z-index: 1099;
                border-radius: 0 0 14px 14px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
                display: none;
            }
            .navbar-collapse.show {
                display: block !important;
            }
            .navbar-nav .nav-link {
                font-size: 1.1rem;
                padding: 0.7rem 1rem;
                margin-bottom: 8px;
                color: #fff !important;
                border-radius: 8px;
                background: transparent;
                text-align: left;
            }
            .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
                background: var(--secondary, #222) !important;
                color: #fff !important;
            }
            .main-content, .container, .container-fluid {
                margin-top: 64px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="scout-bg-overlay"></div>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand mx-auto" href="#">Scout Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.profile') ? 'active' : '' }}" href="{{ route('scout.profile') }}"><i class="fas fa-user"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.uniform') ? 'active' : '' }}" href="{{ route('scout.uniform') }}"><i class="fas fa-shirt"></i> Uniform</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.korasa') ? 'active' : '' }}" href="{{ route('scout.korasa') }}"><i class="fas fa-book"></i> Worksheet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.points') ? 'active' : '' }}" href="{{ route('scout.points') }}"><i class="fas fa-coins"></i> Points</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.attendance') ? 'active' : '' }}" href="{{ route('scout.attendance') }}"><i class="fas fa-calendar-check"></i> Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.badges') ? 'active' : '' }}" href="{{ route('scout.badges') }}"><i class="fas fa-award"></i> Badges</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.locks') ? 'active' : '' }}" href="{{ route('scout.locks') }}"><i class="fas fa-lock"></i> Locks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.total') ? 'active' : '' }}" href="{{ route('scout.total') }}"><i class="fas fa-chart-pie"></i> Total</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.positions') ? 'active' : '' }}" href="{{ route('scout.positions') }}"><i class="fas fa-ranking-star"></i> Positions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scout.scoreboard') ? 'active' : '' }}" href="{{ route('scout.scoreboard') }}"><i class="fas fa-message"></i> Scoreboard</a>
                </li>
            </ul>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger ms-3">Logout</button>
            </form>
            <div class="navbar-profile ms-3">
                <img src="{{ auth()->user()->profile_picture }}" class="profile-img" alt="Profile">
                <span class="profile-name">Welcome, {{ auth()->user()->name }}!</span>
            </div>
        </div>
    </div>
</nav>
<div class="main-content container-fluid">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
// Persistent page after refresh
if (window.location.pathname !== '/login' && window.location.pathname !== '/logout') {
    localStorage.setItem('lastPage', window.location.pathname + window.location.search);
}
window.addEventListener('DOMContentLoaded', function() {
    if ((window.location.pathname === '/' || window.location.pathname === '/home' || window.location.pathname === '/login') && localStorage.getItem('lastPage')) {
        window.location = localStorage.getItem('lastPage');
    }
});
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('scoutMobileToggle');
    var nav = document.querySelector('.navbar');
    if(toggle && nav) {
        toggle.addEventListener('click', function() {
            nav.classList.toggle('show');
        });
    }
});
</script>
</body>
</html> 