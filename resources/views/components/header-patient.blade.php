<header class="sticky top-0 z-50 w-full bg-white border-b flex items-center justify-between px-8 py-4 shadow-sm">
    {{-- Brand --}}
    <div class="sb-brand">
        <a href="{{ route('admin.dashboard') }}" class="sb-brand-link">
            <x-application-logo class="sb-logo-img" />
            <span class="sb-name">ANB_HealthCare</span>
        </a>
    </div>



    <nav class="d-none d-md-flex align-items-center gap-4">
        @php
            $navClass = 'text-decoration-none px-4 py-2 text-base font-semibold transition rounded-lg';
            $activeClass = 'bg-teal-50 text-teal-700 border-bottom border-3 border-teal-700';
            $inactiveClass = 'text-gray-600 hover:text-teal-600 hover:bg-gray-50';
        @endphp

        <a href="{{ route(Auth::user()->role . '.dashboard') }}"
            class="{{ $navClass }} {{ request()->routeIs('*.dashboard') ? $activeClass : $inactiveClass }}">
            Trang chủ
        </a>
        <a href="#doctor-team-section" class="{{ $navClass }} {{ $inactiveClass }} scroll-smooth">
            Danh sách bác sĩ
        </a>
        {{-- <a href="{{ route( Auth::user()->role . '.Appointment') }}" class="{{ $navClass }} {{ $inactiveClass }}">Đặt lịch khám</a> --}}
        <a href="{{ route('patient.appointments.index') }}" class="{{ $navClass }} {{ $inactiveClass }}">Lịch sử
            khám</a>
    </nav>

    <div class="dropdown">
        <button
            class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-3 text-dark p-2 hover:bg-gray-50 rounded-lg transition"
            type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div
                class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold border">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span class="fw-bold text-base text-gray-700">{{ Auth::user()->name }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2"
            style="border-radius: 12px; min-width: 200px;">
            <li class="px-3 py-2 border-bottom mb-2">
                <p class="text-xs text-muted mb-0">Tài khoản</p>
                <p class="text-sm fw-bold mb-0 truncate">{{ Auth::user()->email }}</p>
            </li>
            <li><a class="dropdown-item rounded-lg py-2 text-sm" href="{{ route('profile.edit') }}">Hồ sơ cá nhân</a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-lg py-2 text-sm text-danger">Đăng xuất</button>
                </form>
            </li>
        </ul>
    </div>
</header>
