@if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'internal')
    {{-- <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" /> --}}
    @php
       $counts = [
        'totalUsers' => $_user->activeCount(),
        'admins' => $_user->activeCountByType('admin'),
        'internals' => $_user->activeCountByType('internal'),
        'permittees' => $_user->activeCountByType('permittee')
        ];
       $totalExports = $_specie->totalExport();
    @endphp
    <div class="card mb-4">
        <div class="card-body">
            <div class="">
                <h3>Welcome back, <span class="text-primary">{{ Auth::user()->personalInfo->first_name }}</span></h3>
                <p class="text-muted">{{ \Carbon\Carbon::now()->format('l, F jS \\a\\t g:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="row align-content-center ">
        <div class="col mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex">
                        <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5"> Total Users</h2>
                                <h3 class="mb-1">{{ number_format($counts['totalUsers'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0"> Total Users</h2>
                                <h3 class="fw-extrabold mb-2">{{ number_format($counts['totalUsers'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col  mb-2">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex">
                        <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5"> Admins</h2>
                                <h3 class="mb-1">{{ number_format($counts['admins'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0"> Admins</h2>
                                <h3 class="fw-extrabold mb-2">{{ number_format($counts['admins'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col  mb-2">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex">
                        <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5"> Internal Users</h2>
                                <h3 class="mb-1">{{ number_format($counts['internals'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0"> Internal Users</h2>
                                <h3 class="fw-extrabold mb-2">{{ number_format($counts['internals'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col  mb-2">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex">
                        <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5"> Permittees</h2>
                                <h3 class="mb-1">{{ number_format($counts['permittees'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0"> Permittees</h2>
                                <h3 class="fw-extrabold mb-2">{{ number_format($counts['permittees'], 0, '.', ',')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row p-0 mb-4 ">
        <div class="col col-lg-4">
            <div class="card">
                <div class="card-body">
                    @include('components.charts.utype_donut')
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Exported Species</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @foreach ($_specie->topExportedSpecie() as $specie)
                                <tr>
                                    <td width="30%">{{ $specie->local_name }}({{ $specie->specie_name }})</td>
                                    <td width="30%">{{ number_format($specie->exports, 0, '.', ',') }} pcs</td>
                                    {{-- <td width="30%">{{ $specie->id }} pcs</td> --}}
                                    <td>
                                        @php
                                            $percentage = ($totalExports > 0) ? ($specie->exports / $totalExports * 100) : 0;
                                        @endphp
                                        
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" 
                                                role="progressbar" 
                                                style="width: {{ $percentage }}%;" 
                                                aria-valuenow="{{ $percentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ number_format($percentage, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Exporter</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            @foreach ($_permittee->topExporters() as $permittee)
                                <tr>
                                    <td width="30%">{{ $permittee->first_name }} {{ $permittee->last_name }}</td>
                                    <td width="30%">{{ number_format($permittee->total_exports, 0, '.', ',') }} pcs</td>
                                    <td><x-bladewind::progress-bar percentage="{{ ($permittee->total_exports / $totalExports) * 100 }}" shade="dark" color="pink" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif