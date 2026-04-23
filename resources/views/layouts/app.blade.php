<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    <link rel="stylesheet" href="{{ asset('css/doctor.css')}}">
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">

    @vite(['public/css/app.css', 'public/js/app.js'])
</head>

<body class="bg-light">
        <div class="d-flex min-vh-100"> 
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="sidebar-wrapper" style="width: 250px; flex-shrink: 0;">
                <x-sidebar />
            </div>
        @endif
        <div class="d-flex flex-column flex-grow-1">
            <main class="flex-grow-1">
                <div class="container-fluid py-4 px-3"> 
                    @yield('main_content')
                </div>
            </main>
            <x-footer />
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>