<header class="sticky top-0 z-50 w-full bg-white border-b flex items-center justify-between px-8 py-4 shadow-sm">
    {{-- Brand --}}
    <div class="flex items-center space-x-3">
        <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-sm">V</div>
        <div class="d-none d-sm-block">
            <h1 class="font-bold text-gray-800 leading-tight m-0" style="font-size: 1.25rem;">V-Hospital</h1>
            <p class="text-sm text-gray-500 m-0">
                {{ match(Auth::user()->role) {
                    'admin' => '👨‍💼 Quản trị hệ thống',
                    'doctor' => '👨‍⚕️ Giao diện Bác sĩ',
                    'patient' => '👤 Cổng bệnh nhân',
                    default => 'V-Hospital'
                } }}
            </p>
        </div>
    </div>

    {{-- Navigation động theo role --}}
    <nav class="d-none d-md-flex align-items-center gap-4">
        @php
            $role = Auth::user()->role;
            
            // 📦 Cấu hình menu theo role (dễ thêm/bớt sau này)
            $menus = match($role) {
                'admin' => [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                    ['label' => 'Quản lý Bác sĩ', 'route' => 'admin.doctors.doctorManagement', 'pattern' => 'admin.doctors.*'],
                    ['label' => 'Lịch làm việc', 'route' => 'admin.schedules.index', 'pattern' => 'admin.schedules.*'],
                ],
                'doctor' => [
                    ['label' => 'Tổng quan', 'route' => 'doctor.dashboard', 'pattern' => 'doctor.dashboard'],
                    ['label' => 'Lịch làm việc', 'route' => 'doctor.schedule.index', 'pattern' => 'doctor.schedule.*'],
                    ['label' => 'Lịch hẹn khám', 'route' => 'doctor.appointments', 'pattern' => 'doctor.appointments*'],
                ],
                'patient' => [
                    ['label' => 'Trang chủ', 'route' => 'patient.dashboard', 'pattern' => 'patient.dashboard'],
                    ['label' => 'Tìm bác sĩ', 'route' => 'patient.search', 'pattern' => 'patient.search'],
                    ['label' => 'Đặt lịch', 'route' => 'patient.booking.doctors', 'pattern' => 'patient.booking.*'],
                    ['label' => 'Lịch của tôi', 'route' => 'patient.appointments.index', 'pattern' => 'patient.appointments.*'],
                ],
                default => []
            };

            $navBase = 'text-decoration-none px-4 py-2 text-base font-semibold transition rounded-lg border-b-2';
            $active  = 'border-teal-600 text-teal-700 bg-teal-50/50';
            $inactive= 'border-transparent text-gray-600 hover:text-teal-600 hover:bg-gray-50';
        @endphp

        @foreach($menus as $item)
            @php
                // ✅ Kiểm tra route có tồn tại trong hệ thống không (tránh crash nếu sai tên)
                $hasRoute = \Illuminate\Support\Facades\Route::has($item['route']);
                $isActive = $hasRoute && request()->routeIs($item['pattern']);
            @endphp

            @if($hasRoute)
                <a href="{{ route($item['route']) }}"
                   class="{{ $navBase }} {{ $isActive ? $active : $inactive }}">
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Profile Dropdown --}}
    <div class="dropdown">
        <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-3 text-dark p-2 hover:bg-gray-50 rounded-lg transition"
                type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold border">
                {{ strtoupper(substr(Auth::user()->full_name ?? (Auth::user()->name ?? 'U'), 0, 1)) }}
            </div>
            <span class="fw-bold text-base text-gray-700 d-none d-lg-block">
                {{ ucfirst($role) }}: {{ Auth::user()->full_name ?? Auth::user()->name ?? 'User' }}
            </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 12px; min-width: 220px;">
            <li class="px-3 py-2 border-bottom mb-2">
                <p class="text-xs text-muted mb-0">Tài khoản</p>
                <p class="text-sm fw-bold mb-0">{{ Auth::user()->email }}</p>
            </li>
            <li><a class="dropdown-item rounded-lg py-2 text-sm" href="{{ route('profile.edit') }}">👤 Hồ sơ cá nhân</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-lg py-2 text-sm text-danger fw-semibold">🚪 Đăng xuất</button>
                </form>
            </li>
        </ul>
    </div>
</header>