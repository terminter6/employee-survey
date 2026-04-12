<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gilroy:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --bg-primary: oklch(99% 0 0);
            --bg-secondary: oklch(97% 0.016 294);
            --bg-tertiary: oklch(93% 0.045 294);
            --text-primary: oklch(22% 0.005 248);
            --text-secondary: oklch(40% 0 0);
            --accent: oklch(0.6231 0.188 259.81);
            --accent-hover: oklch(0.55 0.188 259.81);
            --border-color: oklch(90% 0 0);
            --success: oklch(63.9% 0.218 142.495);
            --warning: oklch(80.88% 0.170358 75.3501);
            --error: oklch(58.9% 0.214 26.855);
            --info: oklch(60.1% 0.219 257.63);
        }

        [data-bs-theme="dark"] {
            --bg-primary: oklch(18% 0 0);
            --bg-secondary: oklch(22% 0 0);
            --bg-tertiary: oklch(24% 0 0);
            --text-primary: oklch(90% 0 0);
            --text-secondary: oklch(70% 0 0);
            --accent: oklch(0.6231 0.188 259.81);
            --accent-hover: oklch(0.7 0.188 259.81);
            --border-color: oklch(0.3029 0 259.81);
            --success: oklch(63.9% 0.218 142.495);
            --warning: oklch(89% 0.182 95);
            --error: oklch(59% 0.214 26.8);
            --info: oklch(60.1% 0.219 257.63);
        }

        body {
            font-family: 'Gilroy', 'Roboto', sans-serif;
            font-size: 14px;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .btn, a, .form-control, .form-select, input, textarea, select {
            font-size: 14px;
        }

        .navbar {
            background-color: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
        }

        .navbar-brand, .nav-link {
            color: var(--text-primary) !important;
        }

        .card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }

        .card-header {
            background-color: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .card-body {
            color: var(--text-primary);
        }

        .form-label {
            color: var(--text-primary);
        }

        .form-control, .form-select {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--bg-primary);
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(80, 124, 255, 0.25);
        }

        .form-check-label {
            color: var(--text-secondary);
        }

        .form-check-input {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            color: var(--text-primary);
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: var(--text-primary);
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            transition: color 0.2s, background-color 0.2s;
        }

        .theme-toggle:hover {
            color: var(--text-primary);
            background-color: var(--bg-tertiary);
        }

        .theme-toggle svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .border-bottom {
            border-color: var(--border-color) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="d-flex w-100 justify-content-end">
                <button class="theme-toggle" onclick="toggleTheme()" id="themeToggle" title="Переключить тему">
                    <!-- Sun icon -->
                    <svg class="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon -->
                    <svg class="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>
    @yield('content')
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const sunIcon = document.querySelector('.theme-icon-sun');
            const moonIcon = document.querySelector('.theme-icon-moon');
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            sunIcon.style.display = newTheme === 'dark' ? 'none' : 'block';
            moonIcon.style.display = newTheme === 'dark' ? 'block' : 'none';
            localStorage.setItem('theme', newTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const sunIcon = document.querySelector('.theme-icon-sun');
            const moonIcon = document.querySelector('.theme-icon-moon');
            sunIcon.style.display = savedTheme === 'dark' ? 'none' : 'block';
            moonIcon.style.display = savedTheme === 'dark' ? 'block' : 'none';
        });
    </script>
</body>
</html>