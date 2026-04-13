<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">
        {{-- Navigation --}}
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-bottom shadow-sm">
                <div class="container py-4">
                    <h1 class="h4 mb-0 text-dark fw-bold">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endisset

        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </div>
        </main>

        <footer class="mt-auto py-3 bg-white border-top text-center text-muted">
            <small>&copy; {{ date('Y') }} {{ config('app.name') }}</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
