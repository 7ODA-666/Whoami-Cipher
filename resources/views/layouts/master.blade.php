<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CipherViz')</title>

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
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 font-sans antialiased">
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
    @stack('scripts')
</body>
</html>

