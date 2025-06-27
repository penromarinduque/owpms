@php
    $canViewSpecieMaintenance = auth()->user()->can('viewAny', App\Models\WildlifeType::class) ||
                    auth()->user()->can('viewAny', App\Models\SpecieClass::class) ||
                    auth()->user()->can('viewAny', App\Models\SpecieFamily::class) || 
                    auth()->user()->can('viewAny', App\Models\Specie::class);

    $canViewMaintenance = $canViewSpecieMaintenance
                    || auth()->user()->can('viewAny', App\Models\LtpRequirement::class)
                    || auth()->user()->can('viewAny', App\Models\Position::class)
                    || auth()->user()->can('viewAny', App\Models\LtpFee::class)
                    || auth()->user()->can('viewAny', App\Models\Signatory::class);

    $canViewSubmittedTab = auth()->user()->can('viewSubmittedTab', App\Models\LtpApplication::class);
    $canViewReviewedTab = auth()->user()->can('viewReviewedTab', App\Models\LtpApplication::class);
    $canViewAcceptedTab = auth()->user()->can('viewAcceptedTab', App\Models\LtpApplication::class);
    $canViewApprovedTab = auth()->user()->can('viewApprovedTab', App\Models\LtpApplication::class);

    $canViewPaymentOrders = auth()->user()->can('viewAny', App\Models\PaymentOrder::class);
    $canViewIssuedOfficialReceipts = auth()->user()->can('viewIssuedOr', App\Models\PaymentOrder::class);
    $canViewPaymentsNav = $canViewPaymentOrders || $canViewIssuedOfficialReceipts;
@endphp

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
                        <a class="nav-link " href="{{ route('ltps.index') }}">Local Transport Permit</a>
                        @can('viewAny', App\Models\Permittee::class)
                            <a class="nav-link" href="{{route('permittees.index')}}">Permittee</a>
                        @endcan
                        <a class="nav-link" href="">10% Release*</a>
                    </nav>
                </div>
                {{-- PAYMENTS --}}
                @if ($canViewPaymentsNav)
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                        Payment
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="{{ request()->routeIs('paymentorder.index') ? '' : 'collapse'}}" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            @if ($canViewPaymentOrders)
                                <a class="nav-link @yield('active-paymentorder')"  href="{{ route('paymentorder.index') }}">Order of Payment</a>
                            @endif
                            @if ($canViewIssuedOfficialReceipts)
                                <a class="nav-link @yield('active-issuedor')"  href="{{ route('issuedor.index') }}" href="">Issued OR</a>
                            @endif
                        </nav>
                    </div>
                @endif
                {{-- APPLICATIONS --}}
                <a class="nav-link {{ request()->routeIs('ltpapplication.index') ? '' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#adminApplicationsCollapse" >
                    <div class="sb-nav-link-icon"><i class="fas fa-file-import"></i></div>
                    LTP Applications
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->routeIs('ltpapplication.index') ? 'show' : ''}}" id="adminApplicationsCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        {{-- <a class="nav-link {{ request()->routeIs('ltpapplication.index') && (request()->query('status') == 'submitted' || request()->query('status') == null) ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'submitted']) }}">Submitted</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'resubmitted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'resubmitted']) }}">Resubmitted</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'under-review' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'under-review']) }}">Under Review</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'returned' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'returned']) }}">Returned</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'accepted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'accepted']) }}">Accepted</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'payment-in-process' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'payment-in-process']) }}">Payment In Process</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'paid' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'paid']) }}">For Inspection</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'approved']) }}">Approved</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('status') == 'expired' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['status' => 'expired']) }}">Expired</a> --}}
                        @if ($canViewSubmittedTab)
                            <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'submitted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'submitted', 'status' => 'all']) }}">Submitted</a> 
                        @endif
                        @if ($canViewReviewedTab)
                            <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'reviewed' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'reviewed', 'status' => 'all']) }}">Reviewed</a> 
                        @endif
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'returned' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'returned', 'status' => 'all']) }}">Returned</a> 
                        @if ($canViewAcceptedTab)
                            <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'accepted' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'accepted', 'status' => 'all']) }}">Accepted</a> 
                        @endif
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'rejected' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'rejected', 'status' => 'all']) }}">Rejected</a> 
                        @if ($canViewApprovedTab)
                            <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'approved' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'approved', 'status' => 'all']) }}">Approved</a> 
                        @endif
                        <a class="nav-link {{ request()->routeIs('ltpapplication.index') && request()->query('category') == 'expired' ? 'active' : '' }}" href="{{ route('ltpapplication.index', ['category' => 'expired', 'status' => 'all']) }}">Expired</a> 
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
                        {{-- <a class="nav-link {{ request()->routeIs('myapplication.index') && (request()->query('status') == 'draft' || request()->query('status') == null) ? 'active' : '' }}" href="{{route('myapplication.index')}}">Drafts</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'submitted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'submitted']) }}">Submitted</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'resubmitted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'resubmitted']) }}">Resubmitted</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'under-review' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'under-review']) }}">Under Review</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'returned' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'returned']) }}">Returned</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'accepted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'accepted']) }}">Accepted</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'payment-in-process' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'payment-in-process']) }}">Payment In Process</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'paid' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'paid']) }}">For Inspection</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'approved' ? 'active' : '' }}" href="{{ route('myapplication.index', ['status' => 'approved']) }}">Approved</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'rejected' ? 'active' : '' }}"  href="{{ route('myapplication.index', ['status' => 'rejected']) }}">Rejected</a>
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('status') == 'expired' ? 'active' : '' }}"  href="{{ route('myapplication.index', ['status' => 'expired']) }}">Expired</a> --}}
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'draft' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'draft', 'status' => 'draft']) }}">Drafts</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'submitted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'submitted', 'status' => 'all']) }}">Submitted</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'reviewed' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'reviewed', 'status' => 'all']) }}">Reviewed</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'returned' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'returned', 'status' => 'all']) }}">Returned</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'accepted' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'accepted', 'status' => 'all']) }}">Accepted</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'rejected' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'rejected', 'status' => 'all']) }}">Rejected</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'approved' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'approved', 'status' => 'all']) }}">Approved</a> 
                        <a class="nav-link {{ request()->routeIs('myapplication.index') && request()->query('category') == 'expired' ? 'active' : '' }}" href="{{ route('myapplication.index', ['category' => 'expired', 'status' => 'all']) }}">Expired</a> 
                    </nav>
                </div>
            @endif

            {{-- FOR SIGNATURE --}}
            <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseForSignatures" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
                For Signatures
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse @yield('for-signatures')" id="collapseForSignatures" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    {{-- @if (Auth::user()->usertype=='permittee')
                        <a class="nav-link" href="{{ route('for-signatures.index', ['type' => 'request_letter']) }}">LTP Request Letter</a>
                    @endif --}}
                    <a class="nav-link" href="{{ route('for-signatures.index', ['type' => 'inspection_report']) }}">Inspection Reports&nbsp;&nbsp;{!! $_helper->displayBadgeCount('primary', $_helper->getForSignatoriesCount('inspection_report')) !!}</a>
                    @if (Auth::user()->usertype=='admin' || Auth::user()->usertype=='internal')
                        <a class="nav-link" href="{{ route('for-signatures.index', ['type' => 'payment_order']) }}">Order of Payments&nbsp;&nbsp;{!! $_helper->displayBadgeCount('primary', $_helper->getForSignatoriesCount('payment_order')) !!}</a>
                        <a class="nav-link" href="{{ route('for-signatures.index', ['type' => 'ltp']) }}">Local Transport Permits&nbsp;&nbsp;{!! $_helper->displayBadgeCount('primary', $_helper->getForSignatoriesCount('ltp')) !!}</a>
                    @endif
                </nav>
            </div>
            
            @if(Auth::user()->usertype=='admin' || Auth::user()->usertype=='internal' && $canViewMaintenance)
                <div class="sb-sidenav-menu-heading">MAINTENANCE</div>
                @if ($canViewSpecieMaintenance)
                    <a class="nav-link collapsed" href="{{ route('species.index') }}" data-bs-toggle="collapse" data-bs-target="#collapseSpecies" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-feather-alt"></i></div>
                        Species
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse @yield('species-show')" id="collapseSpecies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            @can('viewAny', App\Models\WildlifeType::class)
                                <a class="nav-link @yield('active-types')" href="{{ route('specietypes.index') }}">Wildlife Types</a>
                            @endcan
                            @can('viewAny', App\Models\SpecieClass::class)
                                <a class="nav-link @yield('active-classes')" href="{{ route('specieclasses.index') }}">Class</a>
                            @endcan
                            @can('viewAny', App\Models\SpecieFamily::class)
                                <a class="nav-link @yield('active-families')" href="{{ route('speciefamilies.index') }}">Family</a>
                            @endcan
                            @can('viewAny', App\Models\Specie::class)
                                <a class="nav-link @yield('active-species')" href="{{ route('species.index') }}">List of Species</a>
                            @endcan
                        </nav>
                    </div>
                @endif
                @can('viewAny', App\Models\LtpRequirement::class)
                    <a class="nav-link @yield('active-ltprequirements')" href="{{ route('ltprequirements.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        LTP Requirements
                    </a>
                @endcan
                @can('viewAny', App\Models\Position::class)
                    <a class="nav-link @yield('active-positions')" href="{{ route('positions.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                        Positions
                    </a>
                @endcan
                @can('viewAny', App\Models\LtpFee::class)
                    <a class="nav-link @yield('active-ltpfees')" href="{{ route('ltpfees.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        LTP Fees
                    </a>
                @endcan
                @can('viewAny', App\Models\Signatory::class)
                    <a class="nav-link @yield('active-signatories')" href="{{ route('signatories.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                        Signatories
                    </a>
                @endcan
            @endif

            @if(Auth::user()->usertype=='admin')
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