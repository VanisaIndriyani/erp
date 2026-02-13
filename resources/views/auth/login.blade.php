<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory ERP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="login-page">

    <div class="login-container">
        <!-- Left Side: Illustration -->
        <div class="login-illustration">
            <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="width: 70%; max-width: 300px; position: relative; z-index: 2; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));">
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-form-container">
            <div class="brand-logo">
                <i class="bi bi-box-seam-fill fs-3"></i>
                <span>INVENTORY ERP</span>
            </div>

            <h1 class="login-title">Welcome Back!</h1>
            <p class="login-subtitle">Please enter your details to sign in.</p>

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                
                <!-- Username Input -->
                <div class="form-floating mb-4 position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" placeholder="Username" 
                           value="{{ old('username') }}" required autofocus>
                    <label for="username">Username</label>
                    @error('username')
                        <div class="invalid-feedback ps-4">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-floating mb-4 position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    @error('password')
                        <div class="invalid-feedback ps-4">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">
                            Remember me
                        </label>
                    </div>
                    <!-- <a href="#" class="text-decoration-none small text-primary fw-semibold">Forgot Password?</a> -->
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-gradient" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>

            <div class="mt-4 text-center text-muted small">
                &copy; {{ date('Y') }} Inventory ERP System. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the eye slash icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Loading State on Submit
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = loginBtn.querySelector('.btn-text');
        const spinner = loginBtn.querySelector('.spinner-border');

        loginForm.addEventListener('submit', function() {
            if (loginForm.checkValidity()) {
                loginBtn.disabled = true;
                btnText.textContent = 'Signing in...';
                spinner.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
