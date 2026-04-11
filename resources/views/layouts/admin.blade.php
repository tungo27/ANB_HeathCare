<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hệ thống Quản lý')</title>
    <base href="{{ asset('') }}">
    </base>

    {{-- Nạp file CSS chung của layout --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">


    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        @yield('content')
    </div>


    <script src="{{ asset('js/doctor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Nạp file JS chung của layout --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
