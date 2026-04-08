<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hệ thống Quản lý')</title>
    <base href="{{ asset('') }}">
    </base>

    {{-- Nạp file CSS chung của layout --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        @yield('content') 
    </div>

    {{-- Nạp file JS chung của layout --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
