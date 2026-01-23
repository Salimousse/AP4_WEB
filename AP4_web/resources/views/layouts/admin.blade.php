<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-festival-light">
            <!-- Admin Navigation -->
            <nav class="bg-festival-primary text-white p-4 shadow">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <h1 class="text-xl font-bold">Administration</h1>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-festival-secondary rounded hover:bg-festival-dark transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 rounded ml-4 hover:bg-red-700 transition">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </body>
</html>