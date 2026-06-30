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
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar style="min-width: 300px;">
  <div class="sidebar-inner px-2 pt-3">
    {{-- User card --}}
    <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
      {{-- <div class="d-flex align-items-center">
        <div class="avatar-lg me-4">
          <img src="/assets/img/team/profile-picture-3.jpg" class="card-img-top rounded-circle border-white"
            alt="Bonnie Green">
        </div>
        <div class="d-block">
          <h2 class="h5 mb-3">Hi, Jane</h2>
          <a href="/login" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
            <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Sign Out
          </a>
        </div>
      </div> --}}
      <div class="collapse-close d-md-none">
        <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
          aria-expanded="true" aria-label="Toggle navigation">
          <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </a>
      </div>
    </div>
    <ul class="nav flex-column pt-3 pt-md-0">
        <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link d-flex align-items-center @yield('active-dashboard')">
                <span class="sidebar-icon me-3">
                    <img src="{{ asset('images/logo-small.png') }}" height="20" width="20" alt="Volt Logo">
                </span>
                <span class="mt-1 ms-1 sidebar-text">
                    OWPMS
                </span>
            </a>
        </li>
        <li class="nav-item @yield('active-dashboard')">
            <a href="{{ route('dashboard.index') }}" class="nav-link">
            <span class="sidebar-icon"> <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg></span></span>
            <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'internal')
            {{-- DATA ENTRY --}}
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-data-entry">
                    <span>
                        <span class="sidebar-icon"><i class="fas fa-file me-2"></i></span>
                        <span class="sidebar-text">Data Entry</span>
                    </span>
                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                </span>
                <div class="multi-level collapse {{ Request::is('ltps*') || Request::is('permittees*') ? 'show' : '' }}" id="submenu-data-entry" aria-labelledby="headingOne">
                    <ul class="flex-column nav">
                        <li class="nav-item {{ request()->routeIs('ltps.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('ltps.index') }}">
                                <span class="sidebar-text">
                                    Local Transport Permit
                                </span>
                            </a>
                        </li>
                        @can('viewAny', App\Models\Permittee::class)
                        <li class="nav-item {{ request()->routeIs('permittees.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('permittees.index') }}">
                                <span class="sidebar-text">
                                    Permittee
                                </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>

            {{-- PAYMENTS --}}
            @if ($canViewPaymentsNav)
                <li class="nav-item">
                    <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-payments">
                        <span>
                            <span class="sidebar-icon"><i class="fas fa-money-bill me-2"></i></span>
                            <span class="sidebar-text">Payment</span>
                        </span>
                        <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                    </span>
                    <div class="multi-level collapse {{ request()->routeIs('paymentorder.*') || request()->routeIs('issuedor.*') ? 'show' : '' }}" id="submenu-payments">
                        <ul class="flex-column nav">
                            @if ($canViewPaymentOrders)
                            <li class="nav-item {{ request()->routeIs('paymentorder.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('paymentorder.index') }}">
                                    <span class="sidebar-text">Order of Payment</span>
                                </a>
                            </li>
                            @endif
                            @if ($canViewIssuedOfficialReceipts)
                            <li class="nav-item {{ request()->routeIs('issuedor.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('issuedor.index') }}">
                                    <span class="sidebar-text">Issued OR</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            {{-- LTP APPLICATIONS --}}
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-ltp-apps">
                    <span>
                        <span class="sidebar-icon"><i class="fas fa-file-import me-2"></i></span>
                        <span class="sidebar-text">LTP Applications</span>
                    </span>
                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                </span>
                <div class="multi-level collapse {{ request()->routeIs('ltpapplication.index') ? 'show' : '' }}" id="submenu-ltp-apps">
                    <ul class="flex-column nav">
                        @php
                            $appTabs = [
                                ['id' => 'submitted', 'label' => 'Submitted', 'visible' => $canViewSubmittedTab],
                                ['id' => 'reviewed', 'label' => 'Reviewed', 'visible' => $canViewReviewedTab],
                                ['id' => 'returned', 'label' => 'Returned', 'visible' => true],
                                ['id' => 'accepted', 'label' => 'Accepted', 'visible' => $canViewAcceptedTab],
                                ['id' => 'rejected', 'label' => 'Rejected', 'visible' => true],
                                ['id' => 'approved', 'label' => 'Approved', 'visible' => $canViewApprovedTab],
                                ['id' => 'expired', 'label' => 'Expired', 'visible' => true],
                            ];
                        @endphp

                        @foreach($appTabs as $tab)
                            @if($tab['visible'])
                            <li class="nav-item {{ request()->query('category') == $tab['id'] ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('ltpapplication.index', ['category' => $tab['id'], 'status' => 'all']) }}">
                                    <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->ltpApplicationCountByStatus($tab['id']) }}</span>
                                    <span class="sidebar-text">{{ $tab['label'] }}</span>
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif

        @if(Auth::user()->usertype=='permittee')
            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-my-applications" aria-expanded="true">
                    <span>
                        <span class="sidebar-icon"><i class="fas fa-file me-2"></i></span>
                        <span class="sidebar-text" >My Applications</span>
                    </span>
                    <span class="link-arrow"><svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse show" role="list" id="submenu-my-applications" aria-expanded="false">
                    <ul class="flex-column nav">
                        @php
                            $categories = ['draft', 'submitted', 'reviewed', 'returned', 'accepted', 'rejected', 'approved', 'expired'];
                        @endphp
                        @foreach($categories as $cat)
                            <li class="nav-item {{ request()->query('category') == $cat ? 'active' : '' }}">
                                <a href="{{ route('myapplication.index', ['category' => $cat, 'status' => ($cat == 'draft' ? 'draft' : 'all')]) }}" class="nav-link">
                                    <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->ltpApplicationCountByStatus($cat) }}</span>
                                    <span class="sidebar-text">{{ ucfirst($cat) }}</span> 
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif

        {{-- FOR SIGNATURE --}}
        <li class="nav-item">
            <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-for-signatures" aria-expanded="true">
                <span>
                    <span class="sidebar-icon"><i class="fas fa-file-signature me-2"></i></span>
                    <span class="sidebar-text" >For Signatures</span>
                </span>
                <span class="link-arrow"><svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                    </svg>
                </span>
            </span>
            <div class="multi-level collapse show" role="list" id="submenu-for-signatures" aria-expanded="false">
                <ul class="flex-column nav">
                    <li class="nav-item {{ request()->query('type') == 'inspection_report' ? 'active' : '' }}">
                        <a href="{{ route('for-signatures.index', ['type' => 'inspection_report']) }}" class="nav-link">
                            <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->getForSignatoriesCount('inspection_report') }}</span>
                            <span class="sidebar-text">Inspection Reports</span>
                        </a>
                    </li>

                    @if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'internal')
                        <li class="nav-item {{ request()->query('type') == 'payment_order' ? 'active' : '' }}">
                            <a href="{{ route('for-signatures.index', ['type' => 'payment_order']) }}" class="nav-link">
                                <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->getForSignatoriesCount('payment_order') }}</span>
                                <span class="sidebar-text">Order of Payments</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->query('type') == 'billing_statement' ? 'active' : '' }}">
                            <a href="{{ route('for-signatures.index', ['type' => 'billing_statement']) }}" class="nav-link">
                                <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->getForSignatoriesCount('billing_statement') }}</span>
                                <span class="sidebar-text">Assessment of Fees</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->query('type') == 'ltp' ? 'active' : '' }}">
                            <a href="{{ route('for-signatures.index', ['type' => 'ltp']) }}" class="nav-link">
                                <span class="badge rounded-pill bg-secondary me-2">{{ $_helper->getForSignatoriesCount('ltp') }}</span>
                                <span class="sidebar-text">Local Transport Permits</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

        @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'internal' && $canViewMaintenance)
    <li class="nav-item">
        <span class="nav-link d-flex justify-content-between align-items-center opacity-50 pe-none">
            <span class="sidebar-text fw-bold text-uppercase small">Maintenance</span>
        </span>
    </li>

    @if ($canViewSpecieMaintenance)
        {{-- Species Multi-level Menu --}}
        <li class="nav-item">
            <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-species">
                <span>
                    <span class="sidebar-icon"><i class="fas fa-feather-alt me-2"></i></span>
                    <span class="sidebar-text">Species</span>
                </span>
                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
            </span>
            <div class="multi-level collapse {{ Request::is('specietypes*') || Request::is('specieclasses*') || Request::is('speciefamilies*') || Request::is('species*') || Request::is('natureofspecies*') ? 'show' : '' }}" id="submenu-species">
                <ul class="flex-column nav">
                    @can('viewAny', App\Models\WildlifeType::class)
                    <li class="nav-item {{ request()->routeIs('specietypes.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('specietypes.index') }}">
                            <span class="sidebar-text">Wildlife Types</span>
                        </a>
                    </li>
                    @endcan
                    
                    @can('viewAny', App\Models\SpecieClass::class)
                    <li class="nav-item {{ request()->routeIs('specieclasses.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('specieclasses.index') }}">
                            <span class="sidebar-text">Class</span>
                        </a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\SpecieFamily::class)
                    <li class="nav-item {{ request()->routeIs('speciefamilies.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('speciefamilies.index') }}">
                            <span class="sidebar-text">Family</span>
                        </a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\Specie::class)
                    <li class="nav-item {{ request()->routeIs('species.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('species.index') }}">
                            <span class="sidebar-text">List of Species</span>
                        </a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\SpecieNature::class)
                    <li class="nav-item {{ request()->routeIs('natureofspecies.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('natureofspecies.index') }}">
                            <span class="sidebar-text">Nature of Species</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>
        @endif

        {{-- LTP Requirements --}}
        @can('viewAny', App\Models\LtpRequirement::class)
        <li class="nav-item {{ request()->routeIs('ltprequirements.index') ? 'active' : '' }}">
            <a href="{{ route('ltprequirements.index') }}" class="nav-link">
                <span class="sidebar-icon"><i class="fas fa-list me-2"></i></span>
                <span class="sidebar-text">LTP Requirements</span>
            </a>
        </li>
        @endcan

        {{-- Positions --}}
        @can('viewAny', App\Models\Position::class)
        <li class="nav-item {{ request()->routeIs('positions.index') ? 'active' : '' }}">
            <a href="{{ route('positions.index') }}" class="nav-link">
                <span class="sidebar-icon"><i class="fas fa-list-ol me-2"></i></span>
                <span class="sidebar-text">Positions</span>
            </a>
        </li>
        @endcan

        {{-- LTP Fees --}}
        @can('viewAny', App\Models\LtpFee::class)
        <li class="nav-item {{ request()->routeIs('ltpfees.index') ? 'active' : '' }}">
            <a href="{{ route('ltpfees.index') }}" class="nav-link">
                <span class="sidebar-icon"><i class="fas fa-money-bill-wave me-2"></i></span>
                <span class="sidebar-text">LTP Fees</span>
            </a>
        </li>
        @endcan

    @endif

    @if(Auth::user()->usertype == 'admin')
        <li class="nav-item">
            <span class="nav-link d-flex justify-content-between align-items-center opacity-50 pe-none">
                <span class="sidebar-text fw-bold text-uppercase small">User Access</span>
            </span>
        </li>

        <li class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="nav-link">
                <span class="sidebar-icon"><i class="fas fa-users me-2"></i></span>
                <span class="sidebar-text">Users</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('iam.roles.index') ? 'active' : '' }}">
            <a href="{{ route('iam.roles.index') }}" class="nav-link">
                <span class="sidebar-icon"><i class="fas fa-lock me-2"></i></span>
                <span class="sidebar-text">Roles</span>
            </a>
        </li>
    @endif
    </ul>
  </div>
</nav>