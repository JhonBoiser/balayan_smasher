<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #4895ef;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --border-radius: 12px;
            --shadow: 0 10px 20px rgba(0,0,0,0.05);
            --transition: all 0.3s ease;
        }

        body {
            background: url("https://scontent.fmnl17-6.fna.fbcdn.net/v/t39.30808-6/528606912_122164303472392515_5841549823835529491_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFFAWIZnSqRpTbPixURpsqDSns-POsxGJxKez486zEYnGvMfF8VCSg8kEGVEN1howQ6hdsh2RDECho6-0LTrY5S&_nc_ohc=kEZ8PK5kXR0Q7kNvwGvJoCp&_nc_oc=AdkO9pwB6-HAL0WkT2pZ6hHbMykdNznF1O0JllPKtmGh-U9IZRfP104zmsDSnjPmvKY&_nc_zt=23&_nc_ht=scontent.fmnl17-6.fna&_nc_gid=tAGMvbHw6eGhgTk2XhX7dA&oh=00_Afg3uex0vo9tM__pbb3K2AG34NafJp3LLF4_-mEsPfKqPg&oe=691349CA");
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .login-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.5rem;
            border-bottom: none;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5ee;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        .input-group-text {
            background-color: white;
            border-right: none;
            color: var(--primary-color);
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-left: none;
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e1e5ee;
        }

        .divider-text {
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e1e5ee;
            color: #6c757d;
            transition: var(--transition);
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .social-btn.google:hover {
            color: #db4437;
            border-color: #db4437;
        }

        .social-btn.facebook:hover {
            color: #4267B2;
            border-color: #4267B2;
        }

        .social-btn.twitter:hover {
            color: #1DA1F2;
            border-color: #1DA1F2;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-card card">
                    <div class="card-header">
                        <i class="fas fa-user-circle me-2"></i>Welcome Back
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Social Login Options -->
                            <div class="social-login">
                                <a href="#" class="social-btn google">
                                    <i class="fab fa-google"></i>
                                </a>
                                <a href="#" class="social-btn facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-btn twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>

                            <div class="divider">
                                <span class="divider-text">Or sign in with email</span>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="forgot-password" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="register-link">
                                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
