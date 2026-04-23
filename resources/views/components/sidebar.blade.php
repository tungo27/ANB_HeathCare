<aside class="sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <a href="{{ route('admin.dashboard') }}" class="sb-brand-link">
            <x-application-logo class="sb-logo-img" />
            <span class="sb-name">ANB_HealthCare</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="sb-nav">

        <p class="sb-section">Menu chính</p>

        {{-- 🏠 Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="1.5" y="1.5" width="5" height="5" rx="1" />
                    <rect x="9.5" y="1.5" width="5" height="5" rx="1" />
                    <rect x="1.5" y="9.5" width="5" height="5" rx="1" />
                    <rect x="9.5" y="9.5" width="5" height="5" rx="1" />
                </svg>
            </span>
            {{ __('Dashboard') }}
        </a>

        {{-- 👨‍💼 ADMIN MENUS --}}
        <p class="sb-section" style="margin-top: 12px;">{{ __('👨‍💼 Quản trị hệ thống') }}</p>

        {{-- 👨‍⚕️ Quản lý Bác sĩ --}}
        <div class="nav-group {{ request()->routeIs('admin.doctors.*') ? 'active-group' : '' }}">
            <a href="{{ route('admin.doctors.doctorManagement') }}"
                class="nav-item {{ request()->routeIs('admin.doctors.doctorManagement') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="6" cy="5" r="3" />
                        <path d="M1 14c0-2.761 2.239-5 5-5h2" />
                        <path d="M11 10v4M9 12h4" />
                    </svg>
                </span>
                {{ __('Quản lý Bác sĩ') }}
            </a>
            <div class="nav-submenu" style="display: {{ request()->routeIs('admin.doctors.*') ? 'block' : 'none' }}">
            </div>
        </div>

        {{-- 📋 Quản lý Ca làm việc (Schedules) --}}
        <div class="nav-group {{ request()->routeIs('admin.schedules.*') ? 'active-group' : '' }}">
            <a href="{{ route('admin.schedules.index') }}"
                class="nav-item {{ request()->routeIs('admin.schedules.index') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="12" height="11" rx="2" />
                        <path d="M5 1v2M11 1v2M2 7h12" />
                        <path d="M6 10l2 2 4-4" />
                    </svg>
                </span>
                {{ __('Ca làm việc') }}
            </a>
            <div class="nav-submenu" style="display: {{ request()->routeIs('admin.schedules.*') ? 'block' : 'none' }}">
            </div>
        </div>

        {{-- COMMON MENUS --}}
        <hr class="sb-divider">
        <p class="sb-section">{{ __('⚙️ Hệ thống') }}</p>

        {{-- 👤 Profile --}}
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="8" cy="8" r="6.5" />
                    <circle cx="8" cy="6" r="2.5" />
                    <path d="M3 13.5c.5-2 2.5-3.5 5-3.5s4.5 1.5 5 3.5" />
                </svg>
            </span>
            {{ __('Profile') }}
        </a>

        {{-- 🔔 Thông báo --}}
        {{-- <a href="#" class="nav-item disabled" title="Sắp triển khai">
            <span class="nav-icon">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M8 2a4 4 0 0 0-4 4v2.5c0 .7-.3 1.4-.8 1.9L2 11.5h12l-1.2-1.1c-.5-.5-.8-1.2-.8-1.9V6a4 4 0 0 0-4-4Z" />
                    <path d="M6 14a2 2 0 0 0 4 0" />
                </svg>
            </span>
            {{ __('Thông báo') }}
            <span class="badge badge-sm bg-danger ms-auto">3</span>
        </a> --}}

        {{-- 🚪 Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item logout-item">
                <span class="nav-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 2H3a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3" />
                        <path d="M10.5 11L14 8l-3.5-3M14 8H6" />
                    </svg>
                </span>
                {{ __('Đăng xuất') }}
            </button>
        </form>

    </nav>

    {{-- Footer: user info --}}
    <div class="sb-footer">
        <a href="{{ route('profile.edit') }}" class="profile-row">
            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="profile-role">👨‍💼 Quản trị viên</div>
            </div>
            <svg style="width:14px;height:14px;color:var(--color-text-tertiary)" viewBox="0 0 14 14" fill="none"
                stroke="currentColor" stroke-width="1.5">
                <circle cx="7" cy="3" r="1" />
                <circle cx="7" cy="7" r="1" />
                <circle cx="7" cy="11" r="1" />
            </svg>
        </a>
    </div>

</aside>
