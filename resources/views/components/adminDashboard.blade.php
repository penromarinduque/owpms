@if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'internal')
    @php
       $counts = [
        'totalUsers' => $_user->activeCount(),
        'admins' => $_user->activeCountByType('admin'),
        'internals' => $_user->activeCountByType('internal'),
        'permittees' => $_user->activeCountByType('permittee')
        ];
       $totalExports = $_specie->totalExport();
    @endphp
    <div class="mb-4 bg-light rounded-3 p-3">
        <h1>Welcome back, <span class="text-primary">{{ Auth::user()->personalInfo->first_name }}</span></h1>
        <p class="text-muted">{{ \Carbon\Carbon::now()->format('l, F jS \\a\\t g:i A') }}</p>
    </div>

    <div class="row p-0 mb-4 flex-row-reverse ">
        <div class="col">
            <div class="row h-100 align-content-center">
                <div class="col col-sm-12 col-md-6 col-lg-6 mb-2">
                    <x-bladewind::statistic number="{{ number_format($counts['totalUsers'], 0, '.', ',')}}" label="Total Users" >
                        <x-slot name="icon">
                           <x-bladewind::icon name="user-group" class="h-16 w-16 p-2 text-white rounded-full bg-blue-500" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
                <div class="col col-sm-12 col-md-6 col-lg-6 mb-2">
                    <x-bladewind::statistic number="{{ number_format($counts['admins'], 0, '.', ',')}}" label="Admins" >
                        <x-slot name="icon">
                           <x-bladewind::icon name="user" class="h-16 w-16 p-2 text-white rounded-full bg-green-500" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
                <div class="col col-sm-12 col-md-6 col-lg-6 mb-2">
                    <x-bladewind::statistic number="{{ number_format($counts['internals'], 0, '.', ',')}}" label="Internals" >
                        <x-slot name="icon">
                           <x-bladewind::icon name="user" class="h-16 w-16 p-2 text-white rounded-full bg-orange-500" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
                <div class="col col-sm-12 col-md-6 col-lg-6 mb-2">
                    <x-bladewind::statistic number="{{ number_format($counts['permittees'], 0, '.', ',')}}" label="Permittees" >
                        <x-slot name="icon">
                           <x-bladewind::icon name="user" class="h-16 w-16 p-2 text-white rounded-full bg-yellow-500" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
            </div>
        </div>
        <div class="col col-lg-3">
            <x-bladewind::card class="h-100">
                @include('components.charts.utype_donut')
            </x-bladewind::card>
        </div>
    </div>


    <div class="row mb-4">
        <div class="col">
            <x-bladewind::card
                title="Most Exported Species"
                class="h-100">
                <x-bladewind::table
                    striped="true">
                    {{-- <x-slot name="header">
                        <th>Specie</th>
                        <th>Total Exports</th>
                    </x-slot> --}}
                    
                    @foreach ($_specie->topExportedSpecie() as $specie)
                        <tr>
                            <td width="30%">{{ $specie->local_name }}({{ $specie->specie_name }})</td>
                            <td width="30%">{{ number_format($specie->exports, 0, '.', ',') }} pcs</td>
                            {{-- <td width="30%">{{ $specie->id }} pcs</td> --}}
                            <td><x-bladewind::progress-bar percentage="{{ $specie->exports / $totalExports * 100 }}" shade="dark" color="blue" /></td>
                        </tr>
                    @endforeach
                </x-bladewind::table>
            </x-bladewind::card>
        </div>
        <div class="col">
            <x-bladewind::card
                title="Top Exporter"
                class="h-100">
                <x-bladewind::table
                    striped="true">
                    {{-- <x-slot name="header">
                        <th>Specie</th>
                        <th>Total Exports</th>
                    </x-slot> --}}
                    @foreach ($_permittee->topExporters() as $permittee)
                        <tr>
                            <td width="30%">{{ $permittee->first_name }} {{ $permittee->last_name }}</td>
                            <td width="30%">{{ number_format($permittee->total_exports, 0, '.', ',') }} pcs</td>
                            <td><x-bladewind::progress-bar percentage="{{ ($permittee->total_exports / $totalExports) * 100 }}" shade="dark" color="pink" /></td>
                        </tr>
                    @endforeach
                </x-bladewind::table>
            </x-bladewind::card>
        </div>
    </div>
@endif