<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand" href="{{ route('dashboard') }}">
        <img style="width: 100%; height: 50px;" src="{{ asset($content->logo) }}" alt="">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button> 
    <!-- Navbar Clock-->
    <p class="text-white dashboard-date mb-0"><i class="far fa-clock"></i> {{ date('l, j F Y,') }} <span id="timer"></span></p>
    <!-- Navbar Search-->
    <span style="font-size: 18px; font-weight: 500;" class="text-white d-none d-md-inline-block form-inline ms-auto  me-md-3 my-2 my-md-0">
        {{ $content->name }}
    </span>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img class="profile-img" src="{{ asset(Auth::user()->image != null ? Auth::user()->image :'images/profile.png') }}" alt=""> <spanm class="common-text">{{ Auth::user()->name }}</span></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a></li>
                <li><hr class="dropdown-divider" /></li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <li>
                        <button class="dropdown-item" type="submit"><i class="fa fa-sign-out-alt"></i> Logout</button>
                    </li>
                </form>
            </ul>
        </li>
    </ul>
</nav>