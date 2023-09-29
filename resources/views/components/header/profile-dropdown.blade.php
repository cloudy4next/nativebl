<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
    <i class="align-middle" data-feather="settings"></i>
</a>
@php
    $userName = Auth::user()->user_name;
@endphp
<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
    <img class="avatar img-fluid rounded me-1" src="https://dummyimage.com/50x50/d63384/fff.jpg" alt="Charles Hall">
    <span>{{ $userName }}</span>
</a>

<div class="dropdown-menu dropdown-menu-end">
    {{-- <a class="dropdown-item" href="pages-profile.html">
        <i class="align-middle me-1" data-feather="user"></i> Profile
    </a> --}}
    <a class="dropdown-item" href="{{ route('password.change') }}">
        <i class="align-middle me-1" data-feather="pie-chart"></i>Change Password
    </a>
    {{-- <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="pages-settings.html">
        <i class="align-middle me-1" data-feather="settings"></i> Settings &amp; Privacy
    </a> --}}
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{ route('logout') }}">Log out</a>
</div>
