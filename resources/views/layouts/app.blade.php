<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">

        {{-- Navigation Bar --}}
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-bottom shadow-sm py-4 mb-4">
                <div class="container">
                    <h1 class="h4 mb-0 text-dark fw-bold">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endisset

        <main class="container py-3 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12">
                    {{-- Ưu tiên sử dụng @yield('content') cho các trang truyền thống --}}
                    @yield('content')

                    {{-- Hỗ trợ thêm $slot nếu bạn sử dụng Blade Components (Breeze default) --}}
                    {{ $slot ?? '' }}
                </div>
            </div>
        </main>

        <footer class="py-3 bg-white border-top mt-auto">
            <div class="container text-center">
                <p class="text-muted small mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Bảo lưu mọi quyền.
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
