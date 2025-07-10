@if (Auth::user()->usertype == 'permittee')
    <div class="mb-4 bg-light rounded-3 p-3">
        <h1>Welcome back, <span class="text-primary">{{ Auth::user()->personalInfo->first_name }}</span></h1>
        <p class="text-muted">{{ \Carbon\Carbon::now()->format('l, F jS \\a\\t g:i A') }}</p>
    </div>

    <div class="row mb-3 gap-2   ">
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