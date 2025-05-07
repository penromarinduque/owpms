<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">MAIN</div>
            <a class="nav-link @yield('active-dashboard')" href="{{ route('dashboard.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @if(Auth::user()->usertype=='admin' || Auth::user()->usertype=='internal')
            <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                Data Entry
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse @yiled('show')" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="">Local Transport Permit</a>
                    <a class="nav-link" href="{{route('permittees.index')}}">Permittee</a>
                    {{-- <a class="nav-link" href="{{ route('permitteespecies.index') }}">Permittee Species</a> --}}
                    <a class="nav-link" href="">10% Release</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                Payment
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="">Order of Payment</a>
                    <a class="nav-link" href="">Issued OR</a>
                </nav>
            </div>

            {{-- APPLICATIONS --}}
            <a class="nav-link {{ request()->routeIs('ltpapplication.index') ? '' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#adminApplicationsCollapse" >
                <div class="sb-nav-link-icon"><i class="fas fa-file-import"></i></div>
                LTP Applications
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ request()->routeIs('ltpapplication.index') ? 'show' : ''}}" id="adminApplicationsCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && (request()->query('status') == 'submitted' || request()->query('status') == null) ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'submitted']) }}">Submitted</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'resubmitted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'resubmitted']) }}">Resubmitted</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'under-review' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'under-review']) }}">Under Review</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'returned' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'returned']) }}">Returned</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'accepted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'accepted']) }}">Accepted</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'approved']) }}">Approved</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                </nav>
            </div>
            @endif

            @if(Auth::user()->usertype=='permittee')
            <a class="nav-link {{ request()->routeIs('myapplication.index') ? '' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                My Applications
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse {{ request()->routeIs('myapplication.index') ? 'show' : ''}}" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && (request()->query('status') == 'draft' || request()->query('status') == null) ? 'active' : '' }}" href="{{route('myapplication.index')}}">Drafts</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'submitted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'submitted']) }}">Submitted</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'resubmitted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'resubmitted']) }}">Resubmitted</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'under-review' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'under-review']) }}">Under Review</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'returned' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'returned']) }}">Returned</a>
                    <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'accepted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'accepted']) }}">Accepted</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'approved']) }}">Approved</a>
                    <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}"  href="{{ route('myapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                </nav>
            </div>
            @endif
            <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Payment
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                        Authentication
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="login.html">Login</a>
                            <a class="nav-link" href="register.html">Register</a>
                            <a class="nav-link" href="password.html">Forgot Password</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                        Error
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="401.html">401 Page</a>
                            <a class="nav-link" href="404.html">404 Page</a>
                            <a class="nav-link" href="500.html">500 Page</a>
                        </nav>
                    </div>
                </nav>
            </div> -->
            
            @if(Auth::user()->usertype=='admin' || Auth::user()->usertype=='internal')
                <div class="sb-sidenav-menu-heading">MAINTENANCE</div>
                <a class="nav-link collapsed" href="{{ route('species.index') }}" data-bs-toggle="collapse" data-bs-target="#collapseSpecies" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-feather-alt"></i></div>
                    Species
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse @yield('species-show')" id="collapseSpecies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link @yield('active-types')" href="{{ route('specietypes.index') }}">Wildlife Types</a>
                        <a class="nav-link @yield('active-classes')" href="{{ route('specieclasses.index') }}">Class</a>
                        <a class="nav-link @yield('active-families')" href="{{ route('speciefamilies.index') }}">Family</a>
                        <a class="nav-link @yield('active-species')" href="{{ route('species.index') }}">List of Species</a>
                    </nav>
                </div>
                <a class="nav-link @yield('active-ltprequirements')" href="{{ route('ltprequirements.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    LTP Requirements
                </a>
            @endif
            @if(Auth::user()->usertype=='admin')
                <a class="nav-link @yield('active-users')" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Users
                </a>
                <a class="nav-link @yield('active-positions')" href="{{ route('positions.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                    Positions
                </a>
                <a class="nav-link @yield('active-ltpfees')" href="{{ route('ltpfees.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                    LTP Fees
                </a>
            @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ Auth::user()->username }}
    </div>
</nav>