<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @php
                    $access = App\Models\UserAccess::where('user_id', auth()->user()->id)
                        ->pluck('permissions')
                        ->toArray();
                @endphp
                {{-- <div class="sb-sidenav-menu-heading">Core</div> --}}
                @if(in_array('dashboard', $access))
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                @endif
                {{-- <div class="sb-sidenav-menu-heading">Interface</div> --}}
                @if(in_array('activities.data entry', $access) ||
                    in_array('activities.data list', $access))

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Activities
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array('activities.data entry', $access))
                            <a class="nav-link" href="{{ route('data.index') }}">Data Entry</a>
                        @endif
                        @if(in_array('activities.data list', $access))
                            <a class="nav-link" href="{{ route('data.list') }}">Data List</a>
                        @endif
                        @if(in_array('activities.picture entry', $access))
                            <a class="nav-link" href="{{ route('take.picture') }}">Take Picture</a>
                        @endif
                        @if(in_array('activities.picture list', $access))
                            <a class="nav-link" href="{{ route('picture.list') }}">Picture List</a>
                        @endif
                        @if(in_array('activities.gaming entry', $access))
                            <a class="nav-link" href="{{ route('gaming.index') }}">My BL Gaming</a>
                        @endif
                        @if(in_array('activities.gaming list', $access))
                            <a class="nav-link" href="{{ route('gaming.list') }}">BL Gaming Record</a>
                        @endif
                    </nav>
                </div>
                @endif
                @if(in_array('settings.company content', $access) ||
                    in_array('settings.area entry', $access))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsesetting" aria-expanded="false" aria-controls="collapsesetting">
                    <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Settings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsesetting" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array('settings.company content', $access))
                            <a class="nav-link" href="{{ route('content.edit') }}">Company Content</a>
                        @endif
                        @if(in_array('settings.area entry', $access))
                            <a class="nav-link" href="{{ route('area.index') }}">Area Entry</a>
                        @endif
                    </nav>
                </div>
                @endif
                @if(in_array('administration.user register', $access) ||
                    in_array('administration.user list', $access))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                    Administration
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array('administration.user register', $access))
                            <a class="nav-link" href="{{ route('user.registration') }}">User Register</a>
                        @endif
                        @if(in_array('administration.user list', $access))
                            <a class="nav-link" href="{{ route('user.list') }}">User List</a>
                        @endif
                    </nav>
                </div>
                @endif
                {{-- <div class="sb-sidenav-menu-heading">Addons</div> --}}
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link" type="submit" style="border: none; background: none;">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                    Log Out
                </button>
                </form>
                {{-- <a class="nav-link {{ Request::is('table') ? 'active' : '' }}" href="{{ route('table') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a>
                <a class="nav-link {{ Request::is('form') ? 'active' : '' }}" href="{{ route('form') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Input Group
                </a> --}}
            </div>
        </div>
    </nav>
</div>