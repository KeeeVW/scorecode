<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wad El Nile Scouts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-bg-img {
            position: fixed;
            top: 0;
            bottom: 0;
            width: 320px;
            opacity: 0.25;
            z-index: 0;
            filter: blur(px) grayscale(10%) drop-shadow( 30 0 30px #0002);
            pointer-events: none;
            transition: transform 0.5s cubic-bezier(.4,2,.10,1);
        }
        .login-bg-img.left {
            left: 0;
            transform: scale(1.08) rotate(-6deg);
        }
        .login-bg-img.right {
            right: 0;
            transform: scale(1.08) rotate(6deg);
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            position: relative;
            z-index: 2;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
            padding: 20px;
        }
        .card-header img {
            width: 140px;
            margin-bottom: 24px;
            filter: drop-shadow(0 2px 12px #0001);
            transition: transform 0.3s cubic-bezier(.4,2,.6,1);
        }
        .card-header img:hover {
            transform: scale(1.07) rotate(-2deg);
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
        @media (max-width: 576px) {
            .login-panel {
                max-width: 250px;
                width: 90%;
                margin: 80px auto 0 auto;
                padding: 1.1rem 0.7rem;
                font-size: 0.93rem;
                box-shadow: 0 4px 24px rgba(0,0,0,0.10);
                background: rgba(255,255,255,0.65);
                border-radius: 18px;
                position: relative;
                z-index: 2;
            }
            body {
                background-size: cover !important;
                background-position: center !important;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Decorative background images -->
    <img class="login-bg-img left" src="https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/New_SGWEN_Dark.png" alt="SGWEN Logo">
    <img class="login-bg-img right" src="https://cdn.worldvectorlogo.com/logos/world-scout-movement.svg" alt="World Scout Movement Logo">
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header">
                    <img src="https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-12-22%20at%2013.58.10_9b771d24.jpg" alt="Agency Logo">
                    <h4 class="mb-0">Welcome, Greatest Patrols!</h4>
                    <p class="text-muted">Wadi El Nile Scouts Login now ! </p>
                </div>
                <div class="card-body">
                    {{-- Error message removed as requested --}}

                    <form method="POST" action="{{ route('do_login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 