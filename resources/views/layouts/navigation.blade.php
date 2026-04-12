<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container-xl">
        <a class="navbar-brand" href="{{ route(Auth::user()->role . '.dashboard') }}">
            <x-application-logo style="height: 36px; width: auto;" class="text-dark" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active border-primary border-bottom' : '' }}"
                       href="{{ route(Auth::user()->role . '.dashboard') }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>

                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active border-primary border-bottom' : '' }}"
                           href="{{ route('admin.doctors.doctorManagement') }}">
                            {{ __('Quản lý Bác sĩ') }}
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-secondary fw-medium" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="profileDropdown">
                        <li>
                            <div class="dropdown-header d-lg-none">
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
