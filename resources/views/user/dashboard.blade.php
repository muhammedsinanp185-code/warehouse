<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .dashboard-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .welcome-text {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Dark Mode Toggle Button -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle Dark Mode">
        <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>

    <div class="dashboard-content">
        <div class="user-icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <div class="auth-header" style="margin-bottom: 1rem;">
            <h1>EMPLOYEE DASHBOARD</h1>
        </div>
        
        <p class="welcome-text">Welcome back, {{ auth()->user()->name }}!</p>

        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
            @csrf
            <button type="submit" class="auth-button">LOGOUT</button>
        </form>
    </div>

    <script>
        // --- Dark Mode Logic ---
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlEl = document.documentElement;
        
        const moonIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />';
        const sunIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />';

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            htmlEl.setAttribute('data-theme', 'dark');
            themeIcon.innerHTML = sunIcon;
        } else if (savedTheme === 'light') {
            htmlEl.removeAttribute('data-theme');
            themeIcon.innerHTML = moonIcon;
        } else {
            themeIcon.innerHTML = moonIcon;
        }

        themeToggle.addEventListener('click', () => {
            themeIcon.classList.remove('animate-rotate');
            
            if (htmlEl.getAttribute('data-theme') === 'dark') {
                htmlEl.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                themeIcon.innerHTML = moonIcon;
            } else {
                htmlEl.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.innerHTML = sunIcon;
            }
            
            setTimeout(() => {
                themeIcon.classList.add('animate-rotate');
            }, 10);
        });
    </script>
</body>
</html>
