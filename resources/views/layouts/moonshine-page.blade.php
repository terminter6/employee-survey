<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MoonShine')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/moonshine-page.css'])
    @stack('styles')
</head>
<body class="bg-body">
    <div class="wrapper">
        <aside class="layout-sidebar">
            <div class="layout-sidebar-inner">
                <div class="menu-container">
                    <nav class="menu">
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="/admin" class="menu-link">
                                    <span class="menu-link-inner">
                                        <span class="menu-icon">🏠</span>
                                        <span class="menu-text">Главная</span>
                                    </span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/admin/resource/questionnaire-resource/questionnaire-index-page" class="menu-link">
                                    <span class="menu-link-inner">
                                        <span class="menu-icon">📋</span>
                                        <span class="menu-text">Опросы</span>
                                    </span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/admin/resource/questionnaire-category-resource/questionnaire-category-index-page" class="menu-link">
                                    <span class="menu-link-inner">
                                        <span class="menu-icon">📁</span>
                                        <span class="menu-text">Категории опросов</span>
                                    </span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/admin/resource/employee-email-resource/employee-email-index-page" class="menu-link">
                                    <span class="menu-link-inner">
                                        <span class="menu-icon">📧</span>
                                        <span class="menu-text">Email сотрудников</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>

        <div class="layout-main">
            <header class="layout-header">
                <div class="layout-header-inner">
                    <div class="layout-breadcrumbs">
                        @yield('breadcrumbs')
                    </div>
                </div>
            </header>

            <main class="layout-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>

            <footer class="layout-footer">
                <div class="layout-footer-inner">
                    &copy; {{ date('Y') }} Nethammer
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        });
    </script>
</body>
</html>
