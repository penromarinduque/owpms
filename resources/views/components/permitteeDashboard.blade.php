@if (Auth::user()->usertype == 'permittee')
        <div class="row align-items-stretch mb-2 ">
            <div class="col col-sm-4 col-lg-2 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center h-100">
                            <div class="">
                                <h2>Welcome back <br> <span class="text-primary">{{ Auth::user()->personalInfo->first_name }}</span>!</h2>
                                <span class="badge  bg-primary">Permittee</span>
                                <p class="text-muted">{{ \Carbon\Carbon::now()->format('l, F jS \\a\\t g:i A') }}</p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-sm-8 col-lg-10 mb-4">
                {{-- <div class="card">
                    <div class="card-body"> --}}
                        <h4>Permits</h4>
                        <div class="row m-0 p-0 gap-2   ">
                            <div class="col-md p-0  ">
                                <div class="card {{ $_helper->setPermitStatusColor(auth()->user()->wfp()) }}">
                                    <div class="card-body">
                                        <h5 class="fw-normal">Wildlife Farm Permit</h5>
                                        <h4>#{{ auth()->user()->wfp()->permit_number }}</h4>
                                        <p>Expires on {{ auth()->user()->wfp()->valid_to->format('F d, Y') }} • {{ auth()->user()->wfp()->valid_to->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md p-0  ">
                                <div class="card {{ $_helper->setPermitStatusColor(auth()->user()->wcp()) }}">
                                    <div class="card-body">
                                        <h5 class="fw-normal">Wildlife Collectors Permit</h5>
                                        <h4>#{{ auth()->user()->wcp()->permit_number }}</h4>
                                        <p>Expires on {{ auth()->user()->wcp()->valid_to->format('F d, Y') }} • {{ auth()->user()->wcp()->valid_to->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- </div>
                </div> --}}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>100</h1>
                        <p>Applications Created</p>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>100</h1>
                        <p>Applications Approved</p>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>100</h1>
                        <p>Applications Ongoing</p>
                    </div>
                </div>
            </div>
            <div class="col mb-4">
                <div class="card">
                    <div class="card-body">
                        <h1>100</h1>
                        <p>Applications Expired</p>
                    </div>
                </div>
            </div>
        </div>
    @endif