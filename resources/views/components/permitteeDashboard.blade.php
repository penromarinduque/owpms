@if (Auth::user()->usertype == 'permittee')
    @php
        $wcp = auth()->user()->wcp();
    @endphp
    <div class="mb-4 bg-light rounded-3 p-3">
        <h1>Welcome back, <span class="text-primary">{{ Auth::user()->personalInfo->first_name }}</span></h1>
        <p class="text-muted">{{ \Carbon\Carbon::now()->format('l, F jS \\a\\t g:i A') }}</p>
    </div>

    <div class="row mb-3 gap-2   ">
        <div class="col-md p-0  ">
            <div class="card {{ $_helper->setPermitStatusColor($wcp) }}">
                <div class="card-body">
                    <h5 class="fw-normal">Wildlife Farm Permit</h5>
                    <h4>#{{ $wcp->permit_number }}</h4>
                    <p>Expires on {{ $wcp->valid_to->format('F d, Y') }} • {{ $wcp->valid_to->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md p-0  ">
            <div class="card {{ $_helper->setPermitStatusColor(auth()->user()->wcp()) }}">
                <div class="card-body">
                    <h5 class="fw-normal">Wildlife Collectors Permit</h5>
                    <h4>#{{ $wcp->permit_number }}</h4>
                    <p>Expires on {{ $wcp->valid_to->format('F d, Y') }} • {{ $wcp->valid_to->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h1>{{ $applicationCounts->created }}</h1>
                    <p>Applications Created</p>
                </div>
            </div>
        </div>
        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h1>{{ $applicationCounts->approved }}</h1>
                    <p>Applications Approved</p>
                </div>
            </div>
        </div>
        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h1>{{ $applicationCounts->expired }}</h1>
                    <p>Applications Ongoing</p>
                </div>
            </div>
        </div>
        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h1>{{ $applicationCounts->expired }}</h1>
                    <p>Applications Expired</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Species Allowed for Export
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Scientific Name</th>
                            <th>Local Name</th>
                            <th>Family</th>
                            <th>Class</th>
                            {{-- <th>Exported</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wcp->permittee_species->sortBy('specie_name') as $specie)
                            <tr>
                                <td><i>{{ $specie->specie_name }}</i></td>
                                <td>{{ $specie->local_name }}</td>
                                <td>{{ $specie->family->family }}</td>
                                <td>{{ $specie->family->specieClass->specie_class }}</td>
                                {{-- <td></td> --}}
                            </tr>
                        @empty
                            <tr >
                                <td colspan="4" class="text-center">No Species</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif