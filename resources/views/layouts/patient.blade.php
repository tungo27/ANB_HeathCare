<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANB Healthcare - Patient</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex"> <aside class="w-64 bg-white border-end shadow-sm" style="height: 100vh; position: sticky; top: 0;">
            @include('components.header-patient')
        </aside>
            <div class="flex-1 flex flex-col">
                        <header class="h-16 bg-white border-b px-8 flex items-center justify-end sticky top-0 z-10 shadow-sm">
                <div class="flex items-center space-x-4">
                    
                    <div class="flex items-center space-x-3 bg-gray-50 py-1 pl-1 pr-4 rounded-full border border-gray-100">
                        <div class="w-9 h-9 bg-teal-600 text-white rounded-full flex items-center justify-center font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ Auth::user()->full_name }}
                        </span>
                    </div>

                    <div class="h-6 w-px bg-gray-200 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Đăng xuất
                        </button>
                    </form>
                    
                </div>
            </header>
        <div class="flex-1">
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>