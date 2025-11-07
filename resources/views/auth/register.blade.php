<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .register-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .register-card:hover {
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

        .btn-register {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .strength-meter {
            height: 5px;
            border-radius: 5px;
            background-color: #e9ecef;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0%;
            border-radius: 5px;
            transition: width 0.3s ease;
        }

        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }

        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }

        .strength-strong {
            background-color: #28a745;
            width: 75%;
        }

        .strength-very-strong {
            background-color: #20c997;
            width: 100%;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e9ecef;
            transform: translateY(-50%);
            z-index: 1;
        }

        .progress-step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6c757d;
            position: relative;
            z-index: 2;
        }

        .progress-step.active {
            background-color: var(--primary-color);
            color: white;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }

            .progress-indicator {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-card card">
                    <div class="card-header">
                        <i class="fas fa-user-plus me-2"></i>Create Your Account
                    </div>

                    <div class="card-body">
                        <!-- Progress Indicator -->
                        <div class="progress-indicator">
                            <div class="progress-step active">1</div>
                            <div class="progress-step">2</div>
                            <div class="progress-step">3</div>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a strong password">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- Password Strength Meter -->
                                <div class="password-strength">
                                    <div>Password strength: <span id="password-strength-text">None</span></div>
                                    <div class="strength-meter">
                                        <div class="strength-meter-fill" id="password-strength-meter"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                                    <span class="input-group-text toggle-confirm-password" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div id="password-match" class="mt-1" style="font-size: 0.875rem;"></div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-register">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="login-link">
                                Already have an account? <a href="{{ route('login') }}">Sign in</a>
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

        // Toggle confirm password visibility
        document.querySelector('.toggle-confirm-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password-confirm');
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

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('password-strength-meter');
            const strengthText = document.getElementById('password-strength-text');

            // Reset classes
            strengthMeter.className = 'strength-meter-fill';

            // Calculate strength
            let strength = 0;

            // Length check
            if (password.length >= 8) strength += 1;

            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 1;

            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 1;

            // Contains numbers
            if (/[0-9]/.test(password)) strength += 1;

            // Contains special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Update strength meter
            if (password.length === 0) {
                strengthText.textContent = 'None';
            } else if (strength <= 2) {
                strengthText.textContent = 'Weak';
                strengthMeter.classList.add('strength-weak');
            } else if (strength === 3) {
                strengthText.textContent = 'Medium';
                strengthMeter.classList.add('strength-medium');
            } else if (strength === 4) {
                strengthText.textContent = 'Strong';
                strengthMeter.classList.add('strength-strong');
            } else {
                strengthText.textContent = 'Very Strong';
                strengthMeter.classList.add('strength-very-strong');
            }
        });

        // Password match indicator
        document.getElementById('password-confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchIndicator = document.getElementById('password-match');

            if (confirmPassword.length === 0) {
                matchIndicator.textContent = '';
                matchIndicator.className = 'mt-1';
            } else if (password === confirmPassword) {
                matchIndicator.textContent = 'Passwords match';
                matchIndicator.className = 'mt-1 text-success';
            } else {
                matchIndicator.textContent = 'Passwords do not match';
                matchIndicator.className = 'mt-1 text-danger';
            }
        });
    </script>
</body>
</html>
