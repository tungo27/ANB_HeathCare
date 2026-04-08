<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hệ thống Quản lý')</title>
    <base href="{{ asset('') }}">
    </base>

    <link rel="stylesheet" href="resources/css/app.css">
    <link rel="stylesheet" href="resources/css/doctor.css">

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        @yield('content') {{-- Nội dung chính sẽ nằm ở đây --}}
    </div>

    <script src="resources/app.js"></script>
    <script src="resources/doctor.js"></script>

    @stack('scripts')
</body>

</html>
