<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['public/css/app.css', 'public/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @if (Auth::user()->role === 'patient')
            @include('components.header-patient')
        @elseif(Auth::user()->role === 'doctor')
            @include('components.header-doctor')
        @elseif(Auth::user()->role === 'admin')
            @include('layouts.navigation')
        @endif


        @isset($header)
            <header class="bg-white border-bottom shadow-sm py-4 mb-4">
                <div class="container">
                    <h1 class="h4 mb-0 text-dark fw-bold">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>


    </div>
</body>

</html>
