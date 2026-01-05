<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dairy Farm Management - Login</title>
    <link rel="manifest" href="/manifest.json">

<meta name="theme-color" content="#0d6efd">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="LaravelPWA">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.4);
        }
        
        .demo-credentials {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #2E7D32;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2>
                            <i class="fas fa-cow me-2"></i>Dairy Farm Management
                        </h2>
                        <p class="mb-0">Sign in to your account</p>
                    </div>
                    
                    <div class="login-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" 
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center mb-3">
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1"></i>Forgot Your Password?
                                    </a>
                                </div>
                            @endif

                            @if (Route::has('register'))
                                <div class="text-center">
                                    <p class="mb-0">Don't have an account?
                                        <a class="text-decoration-none fw-bold" href="{{ route('register') }}">
                                            Register here
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </form>
                        
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Install Popup -->
<div id="pwa-install" style="display:none; position:fixed; bottom:20px; left:50%; transform:translateX(-50%);
background:#fff; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.15);
padding:15px 20px; z-index:9999; max-width:320px; width:90%;">
    
    <div style="display:flex; align-items:center; gap:10px;">
        <img src="/logo.jpg" width="40">
        <div style="flex:1">
            <strong>Install App</strong><br>
            <small>Get faster access & offline support</small>
        </div>
        <button onclick="hidePWAInstall()" style="border:none;background:none;font-size:18px;">✕</button>
    </div>

    <button id="install-btn" class="btn btn-primary btn-sm w-100 mt-2">
        Install
    </button>
</div><script>
    let deferredPrompt = null;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
    
        // Show popup if not installed before
        if (!localStorage.getItem('pwa_installed')) {
            document.getElementById('pwa-install').style.display = 'block';
        }
    });
    
    document.getElementById('install-btn')?.addEventListener('click', async () => {
        if (!deferredPrompt) return;
    
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
    
        if (outcome === 'accepted') {
            localStorage.setItem('pwa_installed', 'yes');
        }
    
        deferredPrompt = null;
        hidePWAInstall();
    });
    
    function hidePWAInstall() {
        document.getElementById('pwa-install').style.display = 'none';
    }
    </script>
    <script>
        if (window.matchMedia('(display-mode: standalone)').matches) {
            localStorage.setItem('pwa_installed', 'yes');
        }
        </script>
<script>
    const isIOS = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
    const isInStandalone = window.navigator.standalone;
    
    if (isIOS && !isInStandalone && !localStorage.getItem('ios_install_shown')) {
        const iosPopup = document.createElement('div');
        iosPopup.innerHTML = `
            <div style="position:fixed; bottom:20px; left:50%; transform:translateX(-50%);
            background:#000; color:#fff; padding:12px 16px; border-radius:10px; z-index:9999; width:90%; max-width:320px;">
                <strong>Install App</strong><br>
                Tap <b>Share</b> → <b>Add to Home Screen</b>
            </div>`;
        document.body.appendChild(iosPopup);
        localStorage.setItem('ios_install_shown', 'yes');
    }
    </script>
            
    


    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(() => console.log('Service Worker Registered'));
            });
        }
        </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>