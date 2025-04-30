<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a4b8c;  /* Deep blue */
            --accent-blue: #3a7bd5;   /* Medium blue */
            --light-blue: #e6f0ff;    /* Very light blue */
            --primary-yellow: #ffc107; /* Golden yellow */
            --dark-yellow: #ffab00;   /* Darker yellow */
            --card-width: 480px;
            --glass-blur: 12px;
            --input-height: 52px;
            --border-radius: 10px;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('/image/Psulogin1.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-height: 100vh;
            padding-right: 20%;
        }

        .login-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: var(--card-width);
            /* Glass effect styles */
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.2);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            padding: 25px 30px;
            text-align: center;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            /* Glass effect border */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: var(--primary-yellow);
            transform: rotate(-2deg);
            z-index: 1;
        }

        .logo-img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            z-index: 2;
        }

        .login-header h2 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.8rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 2;
        }

        .login-header p {
            font-size: 1rem;
            opacity: 0.9;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            margin-bottom: 0;
            z-index: 2;
        }

        .login-body {
            padding: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        /* Enhanced Form Controls */
        .form-label {
            font-weight: 600;
            color: white;
            margin-bottom: 8px;
            display: block;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border-radius: var(--border-radius);
            padding: 14px 20px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            font-size: 1rem;
            height: var(--input-height);
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(26, 75, 140, 0.3);
            border-color: var(--primary-blue);
            background-color: white;
            outline: none;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            box-shadow: 0 4px 12px rgba(26, 75, 140, 0.2);
            transform: translateY(-2px);
        }

        .input-group-text {
            background-color: var(--primary-blue);
            border: none;
            padding: 0 20px;
            font-size: 1.1rem;
            color: white;
            border-radius: var(--border-radius) 0 0 var(--border-radius) !important;
            width: 55px;
            display: flex;
            justify-content: center;
            transition: all 0.3s;
        }

        .input-group:focus-within .input-group-text {
            background-color: var(--accent-blue);
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0 !important;
            padding-left: 15px;
            padding-right: 50px; /* Make space for the toggle button */
        }

        /* Password toggle button - now inside the input */
        .password-toggle-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--primary-blue);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--accent-blue);
            background: rgba(0, 0, 0, 0.05);
        }

        .password-toggle:active {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Login Button */
        .btn-login {
            background: linear-gradient(to right, var(--primary-blue), var(--accent-blue));
            border: none;
            border-radius: var(--border-radius);
            padding: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s;
            height: var(--input-height);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, var(--primary-yellow), var(--dark-yellow));
            transition: all 0.4s;
            z-index: 0;
        }

        .btn-login:hover::before {
            left: 0;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-login span {
            position: relative;
            z-index: 1;
            color: white;
        }

        /* Remember me & Forgot password */
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.1em;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .form-check-label {
            font-weight: 500;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .forgot-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .forgot-link:hover {
            color: var(--primary-yellow);
            text-decoration: underline;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Floating label animation */
        .form-floating>label {
            transition: all 0.2s;
            color: rgba(0, 0, 0, 0.6);
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            transform: scale(0.85) translateY(-0.8rem) translateX(0.15rem);
            color: var(--primary-blue);
            background: white;
            padding: 0 6px;
            border-radius: 4px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .login-container {
                justify-content: center;
                padding-right: 5%;
                padding-left: 5%;
            }
        }

        @media (max-width: 768px) {
            :root {
                --card-width: 90%;
                --glass-blur: 8px;
                --input-height: 48px;
            }
            
            .login-container {
                padding-right: 15px;
                padding-left: 15px;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-body {
                padding: 25px;
            }

            .logo-img {
                width: 60px;
                height: 60px;
            }
        }

        /* Animation for inputs */
        @keyframes inputHighlighter {
            from { background: var(--primary-yellow); }
            to   { width: 0; background: transparent; }
        }

        /* Highlight effect for focused inputs */
        .input-highlight {
            position: relative;
        }

        .input-highlight:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-yellow);
            transition: width 0.4s ease;
        }

        .input-highlight:focus-within:after {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="/image/logo.png" alt="Company Logo" class="logo-img">
                <h2><i class="fas fa-bell me-2 "></i>TASKNOTIFY</h2>
                <p>Please sign in to continue</p>
            </div>
            
            <div class="login-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4 input-highlight">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="admin@example.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4 input-highlight">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group password-toggle-container">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="••••••••" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-login btn-block w-100 mb-3">
                        <span><i class="fas fa-sign-in-alt me-2"></i>LOGIN</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
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
        
        // Add animation to input groups on focus
        document.querySelectorAll('.input-group').forEach(group => {
            const input = group.querySelector('.form-control');
            
            input.addEventListener('focus', () => {
                group.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', () => {
                group.style.transform = '';
            });
        });
    </script>
</body>
</html>
