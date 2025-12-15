<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('/images/TeamLogo.png') }}">
    <title>@yield('title', 'Whoami Cipher')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'vibrant-blue': '#3b82f6',
                        'vibrant-orange': '#f97316',
                        'vibrant-purple': '#a855f7',
                        // Light mode specific colors
                        'light-bg': '#f8fafc',
                        'light-card': '#ffffff',
                        'light-border': '#e2e8f0',
                        'light-text': '#1e293b',
                        'light-text-secondary': '#64748b',
                        // Dark mode specific colors
                        'dark-bg': '#0f172a',
                        'dark-card': '#1e293b',
                        'dark-border': '#334155',
                        'dark-text': '#f1f5f9',
                        'dark-text-secondary': '#94a3b8',
                    }
                }
            }
        }
    </script>

    <!-- Initialize theme before page load to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light-bg dark:bg-dark-bg text-light-text dark:text-dark-text font-sans antialiased transition-colors duration-200">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main id="main-content" class="transition-all duration-300 min-h-screen p-4 lg:p-8 ml-0 lg:sidebar-expanded">
        @include('layouts.navbar')

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/ui.js') }}"></script>
    <script src="{{ asset('js/spa.js') }}"></script>
    <script src="{{ asset('js/visualization.js') }}"></script>
    <script src="{{ asset('js/crypto-ajax.js') }}"></script>
    <script src="{{ asset('js/speed-control.js') }}"></script>
    @stack('scripts')
</body>
</html>

