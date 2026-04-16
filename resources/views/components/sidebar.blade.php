<aside class="sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="sb-brand-link">
            <x-application-logo class="sb-logo-img" />
            <span class="sb-name">MedApp</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="sb-nav">

        <p class="sb-section">Menu chính</p>

        <a href="{{ route(Auth::user()->role . '.dashboard') }}"
            class="nav-item {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
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

        @if (Auth::user()->role === 'admin')
            <p class="sb-section" style="margin-top: 6px;">{{ __('Quản trị') }}</p>

            <a href="{{ route('admin.doctors.doctorManagement') }}"
                class="nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="6" cy="5" r="3" />
                        <path d="M1 14c0-2.761 2.239-5 5-5h2" />
                        <path d="M11 10v4M9 12h4" />
                    </svg>
                </span>
                {{ __('Quản lý Bác sĩ') }}
            </a>
            <a href="{{ route('admin.schedules.create') }}"
                class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="3" width="12" height="11" rx="2" />
                        <path d="M5 1v2M11 1v2M2 7h12" />
                        <path d="M8 9v4M6 11h4" />
                    </svg>
                </span>
                {{ __('Thiết lập lịch làm việc') }}
            </a>
        @endif

        <hr class="sb-divider">
        <p class="sb-section">{{ __('Hệ thống') }}</p>

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
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <div class="profile-role">{{ ucfirst(Auth::user()->role) }}</div>
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
