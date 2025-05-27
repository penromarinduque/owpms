<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">MAIN</div>
            <a class="nav-link @yield('active-dashboard')" href="{{ route('dashboard.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @if(Auth::user()->usertype=='admin' || Auth::user()->usertype=='internal')
                {{-- DATA ENTRY --}}
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                    Data Entry
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse @yield('show')" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="">Local Transport Permit</a>
                        <a class="nav-link" href="{{route('permittees.index')}}">Permittee</a>
                        <a class="nav-link" href="">10% Release</a>
                    </nav>
                </div>
                {{-- PAYMENTS --}}
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                    Payment
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="{{ request()->routeIs('paymentorder.index') ? '' : 'collapse'}}" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link @yield('active-paymentorder')"  href="{{ route('paymentorder.index') }}">Order of Payment</a>
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
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'payment-in-process' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'payment-in-process']) }}">Payment In Process</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'approved']) }}">Approved</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                    </nav>
                </div>
                {{-- FOR SIGNATURE --}}
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
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'accepted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'accepted']) }}">Accepted</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'payment-in-process' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'payment-in-process']) }}">Payment In Process</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'paid' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'paid']) }}">For Inspection</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'approved']) }}">Approved</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}"  href="{{ route('myapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                    </nav>
                </div>
            @endif
            
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
                <a class="nav-link @yield('active-positions')" href="{{ route('positions.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                    Positions
                </a>
                <a class="nav-link @yield('active-ltpfees')" href="{{ route('ltpfees.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                    LTP Fees
                </a>
                <a class="nav-link @yield('active-signatories')" href="{{ route('signatories.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                    Signatories
                </a>

                <div class="sb-sidenav-menu-heading">User Access</div>
                <a class="nav-link @yield('active-users')" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Users
                </a>
                <a class="nav-link @yield('active-roles')" href="{{ route('iam.roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-lock"></i></div>
                    Roles
                </a>
            @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        <span>@</span>{{ Auth::user()->username }}
    </div>
</nav>