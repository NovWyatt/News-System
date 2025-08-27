<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Admin Panel</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- CSS -->
    <style>
        :root {
            --c-gray-900: #000000;
            --c-gray-800: #1f1f1f;
            --c-gray-700: #2e2e2e;
            --c-gray-600: #313131;
            --c-gray-500: #969593;
            --c-gray-400: #a6a6a6;
            --c-gray-300: #bdbbb7;
            --c-gray-200: #f1f1f1;
            --c-gray-100: #ffffff;

            --c-green-500: #45ffbc;
            --c-olive-500: #e3ffa8;
            --c-blue-500: #4facfe;
            --c-purple-500: #b16cea;
            --c-red-500: #ff4757;
            --c-orange-500: #ffa726;

            --c-white: var(--c-gray-100);
            --c-text-primary: var(--c-gray-100);
            --c-text-secondary: var(--c-gray-200);
            --c-text-tertiary: var(--c-gray-500);
        }

        * {
            box-sizing: border-box;
        }

        body {
            line-height: 1.5;
            min-height: 100vh;
            font-family: "Be Vietnam Pro", sans-serif;
            background-color: var(--c-gray-900);
            color: var(--c-text-primary);
            display: flex;
            padding: 3vw;
            justify-content: center;
            margin: 0;
        }

        .app {
            min-height: 80vh;
            width: 95%;
            max-width: 1600px;
            background-color: var(--c-gray-800);
            padding: 2vw 4vw 6vw;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
        }

        /* Header */
        .app-header {
            display: grid;
            grid-template-columns: minmax(min-content, 175px) minmax(max-content, 1fr) minmax(max-content, 400px);
            column-gap: 4rem;
            align-items: flex-end;
        }

        @media (max-width: 1200px) {
            .app-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 1px solid var(--c-gray-600);
            }
        }

        .app-header-navigation {
            @media (max-width: 1200px) {
                display: none;
            }
        }

        .app-header-actions {
            display: flex;
            align-items: center;

            @media (max-width: 1200px) {
                display: none;
            }
        }

        .app-header-actions-buttons {
            display: flex;
            border-left: 1px solid var(--c-gray-600);
            margin-left: 2rem;
            padding-left: 2rem;
        }

        .app-header-actions-buttons>*+* {
            margin-left: 1rem;
        }

        .app-header-mobile {
            display: none;

            @media (max-width: 1200px) {
                display: flex;
            }
        }

        /* Body */
        .app-body {
            height: 100%;
            display: grid;
            grid-template-columns: minmax(min-content, 175px) minmax(max-content, 1fr) minmax(min-content, 400px);
            column-gap: 4rem;
            padding-top: 2.5rem;
        }

        @media (max-width: 1200px) {
            .app-body {
                grid-template-columns: 1fr;
            }

            .app-body>* {
                margin-bottom: 3.5rem;
            }
        }

        .app-body-navigation {
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            @media (max-width: 1200px) {
                display: none;
            }
        }

        .app-body-main-content {
            overflow: hidden;
        }

        .app-body-sidebar {
            @media (max-width: 1200px) {
                order: -1;
            }
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            padding-bottom: 1rem;
            padding-top: 1rem;
            border-bottom: 1px solid var(--c-gray-600);

            @media (max-width: 1200px) {
                border-bottom: 0;
            }
        }

        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .logo-title {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
            margin-left: 0.75rem;
        }

        .logo-title span:first-child {
            color: var(--c-text-primary);
            font-weight: 600;
        }

        .logo-title span:last-child {
            color: var(--c-text-tertiary);
            font-size: 0.875rem;
        }

        /* Navigation */
        .navigation {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            color: var(--c-text-tertiary);
        }

        .navigation a {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.25s ease;
            padding: 0.5rem 0;
            width: 100%;
        }

        .navigation a * {
            transition: 0.25s ease;
        }

        .navigation a i {
            margin-right: 0.75rem;
            font-size: 1.25em;
            flex-shrink: 0;
        }

        .navigation a+a {
            margin-top: 0.5rem;
        }

        .navigation a:hover,
        .navigation a:focus,
        .navigation a.active {
            transform: translateX(4px);
            color: var(--c-text-primary);
        }

        /* Tabs */
        .tabs {
            display: flex;
            justify-content: space-between;
            color: var(--c-text-tertiary);
            border-bottom: 1px solid var(--c-gray-600);
        }

        .tabs a {
            padding: 1rem 0.5rem;
            text-decoration: none;
            border-top: 2px solid transparent;
            display: inline-flex;
            transition: 0.25s ease;
            font-size: 0.9rem;
        }

        .tabs a.active,
        .tabs a:hover,
        .tabs a:focus {
            color: var(--c-text-primary);
            border-color: var(--c-text-primary);
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            border: 0;
            background: transparent;
            cursor: pointer;
            color: var(--c-text-tertiary);
            transition: 0.25s ease;
        }

        .user-profile:hover,
        .user-profile:focus {
            color: var(--c-text-primary);
        }

        .user-profile:hover span:last-child,
        .user-profile:focus span:last-child {
            box-shadow: 0 0 0 4px var(--c-gray-800), 0 0 0 5px var(--c-text-tertiary);
        }

        .user-profile span:first-child {
            display: flex;
            font-size: 1.125rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--c-gray-600);
            font-weight: 300;
        }

        .user-profile span:last-child {
            transition: 0.25s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            overflow: hidden;
            margin-left: 1.5rem;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--c-gray-900);
        }

        /* Icon Button */
        .icon-button {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid var(--c-gray-500);
            background-color: transparent;
            color: var(--c-text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.25s ease;
            flex-shrink: 0;
        }

        .icon-button.large {
            width: 42px;
            height: 42px;
            font-size: 1.25em;
        }

        .icon-button:hover,
        .icon-button:focus {
            background-color: var(--c-gray-600);
            box-shadow: 0 0 0 4px var(--c-gray-800), 0 0 0 5px var(--c-text-tertiary);
        }

        /* Footer */
        .footer {
            margin-top: auto;
        }

        .footer h1 {
            font-size: 1.5rem;
            line-height: 1.125;
            display: flex;
            align-items: flex-start;
            margin: 0;
        }

        .footer h1 small {
            font-size: 0.5em;
            margin-left: 0.25em;
        }

        .footer div {
            border-top: 1px solid var(--c-gray-600);
            margin-top: 1.5rem;
            padding-top: 1rem;
            font-size: 0.75rem;
            color: var(--c-text-tertiary);
        }

        /* Buttons */
        .flat-button {
            border-radius: 6px;
            background-color: var(--c-gray-700);
            padding: 0.5em 1.5em;
            border: 0;
            color: var(--c-text-secondary);
            transition: 0.25s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .flat-button:hover,
        .flat-button:focus {
            background-color: var(--c-gray-600);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
            color: var(--c-gray-900);
            border: 0;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(69, 255, 188, 0.4);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--c-gray-700);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--c-gray-600);
            transition: 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--c-gray-500);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin: 0 0 0.5rem 0;
            background: linear-gradient(135deg, var(--c-green-500), var(--c-blue-500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card p {
            color: var(--c-text-tertiary);
            margin: 0;
            font-size: 0.875rem;
        }

        /* Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: rgba(69, 255, 188, 0.1);
            border-color: var(--c-green-500);
            color: var(--c-green-500);
        }

        .alert-error {
            background-color: rgba(255, 71, 87, 0.1);
            border-color: var(--c-red-500);
            color: var(--c-red-500);
        }

        .alert-warning {
            background-color: rgba(255, 167, 38, 0.1);
            border-color: var(--c-orange-500);
            color: var(--c-orange-500);
        }

        .alert-info {
            background-color: rgba(79, 172, 254, 0.1);
            border-color: var(--c-blue-500);
            color: var(--c-blue-500);
        }

        /* Tables */
        .table-container {
            background-color: var(--c-gray-700);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--c-gray-600);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--c-gray-600);
        }

        .table th {
            background-color: var(--c-gray-600);
            font-weight: 600;
            color: var(--c-text-primary);
        }

        .table td {
            color: var(--c-text-secondary);
        }

        .table tr:hover {
            background-color: var(--c-gray-600);
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: var(--c-green-500);
            color: var(--c-gray-900);
        }

        .badge-warning {
            background-color: var(--c-orange-500);
            color: var(--c-gray-900);
        }

        .badge-secondary {
            background-color: var(--c-gray-500);
            color: var(--c-white);
        }

        /* Content sections */
        .content-section {
            background-color: var(--c-gray-700);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--c-gray-600);
            margin-bottom: 2rem;
        }

        .content-section h2 {
            font-size: 1.5rem;
            margin: 0 0 1rem 0;
        }

        /* Dropdown menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--c-gray-700);
            border: 1px solid var(--c-gray-600);
            border-radius: 6px;
            padding: 0.5rem 0;
            min-width: 200px;
            z-index: 1000;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--c-text-secondary);
            transition: 0.25s ease;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background-color: var(--c-gray-600);
            color: var(--c-text-primary);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            margin: 0 0 0.5rem 0;
        }

        .page-header p {
            color: var(--c-text-tertiary);
            margin: 0;
        }

        /* Mobile */
        .mobile-only {
            display: none;

            @media (max-width: 1000px) {
                display: block;
            }
        }

        .desktop-only {
            @media (max-width: 1000px) {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app">
        <!-- Header -->
        <header class="app-header">
            <!-- Logo -->
            <div class="app-header-logo">
                <div class="logo">
                    <span class="logo-icon">
                        <i class="ph  ph-newspaper" style="font-size: 1.5rem; color: var(--c-green-500);"></i>
                    </span>
                    <h1 class="logo-title">
                        <span>News</span>
                        <span>Admin</span>
                    </h1>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="app-header-navigation">
                <div class="tabs">
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.articles.index') }}"
                        class="{{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
                        Bài viết
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}"
                            class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            Người dùng
                        </a>
                        <a href="{{ route('admin.logs.index') }}"
                            class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                            Nhật ký
                        </a>
                    @endif
                    <a href="{{ route('admin.profile.show') }}"
                        class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                        Cài đặt
                    </a>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="app-header-actions">
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="user-profile" onclick="toggleDropdown()">
                        <span>{{ auth()->user()->username ?: explode('@', auth()->user()->email)[0] }}</span>
                        <span>
                            {{ strtoupper(substr(auth()->user()->username ?: auth()->user()->email, 0, 2)) }}
                        </span>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="{{ route('admin.profile.show') }}">
                            <i class="ph  ph-user"></i> Hồ sơ cá nhân
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ph  ph-sign-out"></i> Đăng xuất
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                {{-- <div class="app-header-actions-buttons">
                    <button class="icon-button large" title="Tìm kiếm">
                        <i class="ph  ph-magnifying-glass"></i>
                    </button>
                    <button class="icon-button large" title="Thông báo">
                        <i class="ph  ph-bell"></i>
                    </button>
                </div> --}}
            </div>

            <!-- Mobile Header -->
            <div class="app-header-mobile">
                <button class="icon-button large" onclick="toggleMobileMenu()">
                    <i class="ph  ph-list"></i>
                </button>
            </div>
        </header>

        <!-- Body -->
        <div class="app-body">
            <!-- Sidebar Navigation -->
            <div class="app-body-navigation">
                <nav class="navigation">
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <i class="ph  ph-house"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.articles.index') }}"
                        class="{{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
                        <i class="ph  ph-article"></i>
                        <span>Bài viết</span>
                    </a>
                    @if(auth()->user()->hasPermission('articles.create'))
                        <a href="{{ route('admin.articles.create') }}"
                            class="{{ request()->routeIs('admin.articles.create') ? 'active' : '' }}">
                            <i class="ph  ph-plus-circle"></i>
                            <span>Tạo bài viết</span>
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="">
                            <i class="ph  ph-users"></i>
                            <span>Người dùng</span>
                        </a>
                        <a href="{{ route('admin.logs.index') }}"
                            class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                            <i class="ph  ph-list-bullets"></i>
                            <span>Nhật ký</span>
                        </a>
                    @endif
                    <a href="{{ route('admin.profile.show') }}"
                        class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                        <i class="ph  ph-gear"></i>
                        <span>Cài đặt</span>
                    </a>
                </nav>

                <!-- Footer -->
                <footer class="footer">
                    <h1>News<small>©</small></h1>
                    <div>
                        News Admin Panel ©<br />
                        All Rights Reserved {{ date('Y') }}
                    </div>
                </footer>
            </div>

            <!-- Main Content -->
            <div class="app-body-main-content">
                <!-- Page Header -->
                @hasSection('page-header')
                    @yield('page-header')
                @else
                    <div class="page-header">
                        <h1>@yield('title', 'Dashboard')</h1>
                        @hasSection('page-description')
                            <p>@yield('page-description')</p>
                        @endif
                    </div>
                @endif

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

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>

            <!-- Sidebar -->
            @hasSection('sidebar')
                <div class="app-body-sidebar">
                    @yield('sidebar')
                </div>
            @endif
        </div>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- JavaScript -->
    <script>
        // CSRF Token setup
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Dropdown functionality
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('userDropdown');
            const userProfile = document.querySelector('.user-profile');

            if (!userProfile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            console.log('Toggle mobile menu');
        }

        // Auto hide alerts
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

        // Helper functions
        function confirmDelete(message = 'Bạn có chắc chắn muốn xóa?') {
            return confirm(message);
        }

        function showLoading(button) {
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Đang xử lý...';
            button.dataset.originalText = originalText;
        }

        function hideLoading(button) {
            button.disabled = false;
            button.textContent = button.dataset.originalText || 'Submit';
        }

        // Set CSRF token for AJAX requests
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
    </script>

    @stack('scripts')
</body>

</html>
