<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-md-none">
    <a class="navbar-brand me-lg-5" href="/index.html">
        <img class="navbar-brand-dark" src="{{ asset('images/logo-small.png') }}" alt="Volt logo" /> <img class="navbar-brand-light" src="{{ asset('images/logo-small.png') }}" alt="Volt logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

{{-- <a class="navbar-brand pr-1 desktop" href="/">
    <img src="{{ asset('images/logo-small.png') }}" class="nav-logo d-inline">
    DENR - PENRO
</a>

@if (auth()->user())
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
@endif

<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>

@if (auth()->user())
<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    @php
        $unreadNotifications = auth()->user()->unreadNotifications()->count();
    @endphp
    <a class="nav-link position-relative" data-bs-toggle="offcanvas" data-bs-target="#notificationOffCanvas" href="#" role="button">
        <i class="fas fa-bell fa-fw"></i>
        @if ($unreadNotifications <= 0)
        @else
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                @if ($unreadNotifications >= 99)
                    99+
                @else
                    {{ $unreadNotifications }}
                @endif
                <span class="visually-hidden">unread messages</span>
            </span>
        @endif
    </a>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ route('account.index') }}">Account</a></li>
            <li><a class="dropdown-item" href="#!">Settings</a></li>
            <li><a class="dropdown-item" href="{{ route('activity-logs') }}">Activity Log</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </li>
</ul>
@endif
 --}}
