<!-- ============================================================================= -->
<!-- 1. LAYOUT MASTER -->
<!-- ============================================================================= -->

<!-- resources/views/layouts/auth.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- CSS -->
    <style>
        /* Base Variables */
        :root {
            --base-bgcolor: #354152;
            --base-color: #7e8ba3;
            --base-font-weight: 300;
            --base-font-size: 1rem;
            --base-line-height: 1.5;
            --input-placeholder-color: #7e8ba3;
            --link-color: #7e8ba3;
            --grid-max-width: 25rem;
            --border-color: #242c37;
            --success-color: #8ceabb;
            --success-dark: #378f7b;
            --error-color: #e74c3c;
            --warning-color: #f39c12;
        }

        /* Reset & Base */
        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
        }

        body {
            background-color: var(--base-bgcolor);
            color: var(--base-color);
            font: var(--base-font-weight) var(--base-font-size)/var(--base-line-height) 'Helvetica Neue', sans-serif;
            margin: 0;
            min-height: 100%;
        }

        /* Layout */
        .align {
            align-items: center;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .align__item {
            flex: 1;
        }

        .grid {
            margin: 0 auto;
            max-width: var(--grid-max-width);
            width: 100%;
            padding: 0 1rem;
        }

        /* Logo */
        .site__logo {
            margin-bottom: 2rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Typography */
        h1,
        h2 {
            font-size: 2.75rem;
            font-weight: 100;
            margin: 0 0 1rem;
            text-transform: uppercase;
            text-align: center;
        }

        @media (max-width: 768px) {

            h1,
            h2 {
                font-size: 2rem;
            }
        }

        /* Links */
        a {
            color: var(--link-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--success-color);
        }

        /* SVG */
        svg {
            height: auto;
            max-width: 100%;
            vertical-align: middle;
        }

        /* Auth Container */
        .auth-container {
            box-shadow: 0 0 250px rgba(0, 0, 0, 0.5);
            text-align: center;
            padding: 4rem 2rem;
            border-radius: 10px;
            background: rgba(52, 58, 70, 0.1);
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 2rem 1rem;
                margin: 1rem;
            }
        }

        /* Form Styles */
        .form {
            margin-top: 2rem;
        }

        .form__field {
            margin-bottom: 1rem;
        }

        input {
            border: 0;
            font: inherit;
            outline: 0;
            padding: 0.75rem 1rem;
        }

        input::placeholder {
            color: var(--input-placeholder-color);
        }

        .form input {
            border: 1px solid var(--border-color);
            border-radius: 999px;
            background-color: transparent;
            text-align: center;
            transition: all 0.3s ease;
            color: var(--base-color);
        }

        .form input:focus {
            border-color: var(--success-color);
            box-shadow: 0 0 0 2px rgba(140, 234, 187, 0.2);
        }

        .form input[type="text"],
        .form input[type="email"],
        .form input[type="password"] {
            width: 100%;
            background-repeat: no-repeat;
            background-size: 1.5rem;
            background-position: 1rem 50%;
        }

        .form input[type="text"] {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23242c37"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>');
        }

        .form input[type="email"] {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23242c37"><path d="M256.017 273.436l-205.17-170.029h410.904l-205.734 170.029zm-.034 55.462l-205.983-170.654v250.349h412v-249.94l-206.017 170.245z"/></svg>');
        }

        .form input[type="password"] {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23242c37"><path d="M195.334 223.333h-50v-62.666c0-61.022 49.645-110.667 110.666-110.667 61.022 0 110.667 49.645 110.667 110.667v62.666h-50v-62.666c0-33.452-27.215-60.667-60.667-60.667-33.451 0-60.666 27.215-60.666 60.667v62.666zm208.666 30v208.667h-296v-208.667h296zm-121 87.667c0-14.912-12.088-27-27-27s-27 12.088-27 27c0 7.811 3.317 14.844 8.619 19.773 4.385 4.075 6.881 9.8 6.881 15.785v22.942h23v-22.941c0-5.989 2.494-11.708 6.881-15.785 5.302-4.93 8.619-11.963 8.619-19.774z"/></svg>');
        }

        .form input[type="submit"],
        .btn-primary {
            background-image: linear-gradient(160deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: #fff;
            width: 100%;
            border: none;
            cursor: pointer;
            font-weight: 500;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .form input[type="submit"]:hover,
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(140, 234, 187, 0.4);
        }

        .form input[type="submit"]:active,
        .btn-primary:active {
            transform: translateY(0);
        }

        /* Checkbox */
        .checkbox-field {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .checkbox-field input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .checkbox-field label {
            margin: 0;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--error-color);
            color: #fff;
        }

        .alert-success {
            background-color: rgba(140, 234, 187, 0.1);
            border: 1px solid var(--success-color);
            color: #fff;
        }

        .alert-warning {
            background-color: rgba(243, 156, 18, 0.1);
            border: 1px solid var(--warning-color);
            color: #fff;
        }

        /* Loading State */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Footer */
        .auth-footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .grid {
                max-width: 100%;
                padding: 0 1rem;
            }

            .auth-container {
                padding: 2rem 1rem;
            }

            h1,
            h2 {
                font-size: 1.75rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="align">
    <div class="grid align__item">
        <div class="auth-container">
            <!-- Logo -->
            <svg xmlns="http://www.w3.org/2000/svg" class="site__logo" width="56" height="84"
                viewBox="77.7 214.9 274.7 412">
                <defs>
                    <linearGradient id="logo-gradient" x1="0%" y1="0%" y2="0%">
                        <stop offset="0%" stop-color="#8ceabb" />
                        <stop offset="100%" stop-color="#378f7b" />
                    </linearGradient>
                </defs>
                <path fill="url(#logo-gradient)"
                    d="M215 214.9c-83.6 123.5-137.3 200.8-137.3 275.9 0 75.2 61.4 136.1 137.3 136.1s137.3-60.9 137.3-136.1c0-75.1-53.7-152.4-137.3-275.9z" />
            </svg>

            <!-- Page Title -->
            <h2>@yield('page-title', 'Admin Panel')</h2>

            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-error">
                    @if (count($errors) == 1)
                        {{ $errors->first() }}
                    @else
                        <ul style="margin: 0; padding-left: 1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')

            <!-- Footer -->
            <div class="auth-footer">
                @yield('footer')
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // CSRF Token setup
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Set CSRF token for all AJAX requests
        if (typeof XMLHttpRequest !== 'undefined') {
            XMLHttpRequest.prototype.open = (function (open) {
                return function (method, url, async, user, pass) {
                    open.call(this, method, url, async, user, pass);
                    if (url.charAt(0) === '/') {
                        this.setRequestHeader('X-CSRF-TOKEN', window.Laravel.csrfToken);
                    }
                };
            })(XMLHttpRequest.prototype.open);
        }

        // Form submission helpers
        function showLoading(button) {
            button.classList.add('loading');
            button.disabled = true;
            button.textContent = 'Đang xử lý...';
        }

        function hideLoading(button, originalText) {
            button.classList.remove('loading');
            button.disabled = false;
            button.textContent = originalText;
        }

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
