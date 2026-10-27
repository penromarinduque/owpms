@extends('layouts.pdf')

@section('title')
    Issuance of Wildlife Local Transport Permit
@endsection

@section('content')
    @php
        function getOrdinal($number) {
            // Ensure the intl extension is installed
            if (class_exists('NumberFormatter')) {
                $formatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);
                return $formatter->format($number);
            }
            
            // Fallback to manual method if extension is missing
            return getOrdinalFallback($number);
        }
    @endphp
    <h5 class="fw-bold">
        CENRO: <br>
        PENRO: MARINDUQUE <br>
        REGION: 4B 
    </h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class=" align-content-center fw-bold" scope="col" colspan="6" >
                    Function: Management of Wildlife Resources <br>
                    Activity: Issuance of Wildlife Local Transport Permit
                </th>
                <th class="text-center align-content-center fw-bold" scope="col" colspan="6">
                    BIODIVERSITY MANAGEMENT BUREAU <br>
                    Statistical Report <br>
                    For the
                    @if($frequency == 1)
                        month of {{ date('F', strtotime($month)) }} {{ $year }}
                    @elseif($frequency == 2)
                        {{ getOrdinal($quarter) }} Quarter C.Y. {{ $year }}
                    @elseif($frequency == 3)
                        {{ getOrdinal($semester) }} Semester C.Y. {{ $year }}
                    @else 
                        C.Y. {{ $year }}
                    @endif
                </th>
                <th class="text-center align-content-center fw-bold" scope="col" colspan="5">
                    DENR Statistical Form 5 <br>
                    BMB Statistical Form 3
                </th>
            </tr>
            <tr>
                <th colspan="2" rowspan="2" class="text-center align-content-center">Permit Holder</th>
                <th colspan="4" class="text-center align-content-center">Permit</th>
                <th colspan="6" class="text-center align-content-center">Species Transported</th>
                <th colspan="5" rowspan="2" class="text-center align-content-center">Purpose (Pls Check)</th>
            </tr>
            <tr>
                <th rowspan="2" class="text-center align-content-center">No.</th>
                <th colspan="3" class="text-center align-content-center">Date</th>
                <th rowspan="2" class="text-center align-content-center">Species Name</th>
                <th rowspan="2" class="text-center align-content-center" width="120px">Description(identifying marks/ age/sex/live/ dead/ herbarium specimen)</th>
                <th rowspan="2" class="text-center align-content-center">Quantity</th>
                <th rowspan="2" class="text-center align-content-center">Unit of Measure</th>
                <th rowspan="2" class="text-center align-content-center">Origin</th>
                <th rowspan="2" class="text-center align-content-center">Destination</th>
            </tr>
            <tr>
                <th class="text-center align-content-center">Name</th>
                <th class="text-center align-content-center">Address</th>
                <th class="text-center align-content-center">Issuance</th>
                <th class="text-center align-content-center">Expiry</th>
                <th class="text-center align-content-center">Fees Collected</th>
                <th width="50px" class="text-center align-content-center">Export/ Indicate Country of Export</th>
                <th width="50px" class="text-center align-content-center">Local Sale</th>
                <th width="50px" class="text-center align-content-center">Research</th>
                <th width="50px" class="text-center align-content-center">Pet/ Personal</th>
                <th width="50px" class="text-center align-content-center">Others, pls. specify</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($data->where('permit_type', 'wcp') as $i => $d)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $d->first_name }} {{ $d->last_name }}</td>
                    <td>{{ $d->barangay_name }}, {{ $d->municipality_name }}, {{ $d->province_name }}</td>
                    <td>{{ $data->where('permit_type', 'wfp')->where('id', $d->id)->first()->farm_name }}</td>
                    @foreach($selectedMonthsConfig as $monthConfig)
                        <td class="text-center">{{ $d->{'m' . $monthConfig->id} > 0 ? $d->{'m' . $monthConfig->id} : '-' }}</td>
                    @endforeach
                    <td class="text-center">{{ array_sum(array_map(function($monthConfig) use ($d){ return $d->{'m' . $monthConfig->id}; }, $selectedMonthsConfig->toArray())) }}</td>
                    <td class="text-center">{{ number_format($d->collected_fees, 2) }}</td>
                    <td class="text-center">{{ number_format($d->transported_species, 0, '.', ',') }}</td>
                </tr>
            @endforeach --}}
            @php
                $prevName = null;
                $prevAddress = null;
                $prevPermitNumber = null;
            @endphp
            @foreach ($data as $i => $d)
                <tr>
                    @php 
                        $name =  $d->first_name . " " . $d->middle_name . " " . $d->last_name; 
                        $specieCountPerPermit = $data->where('permit_number', $d->permit_number)->count();
                    @endphp
                    @if ($prevPermitNumber != $d->permit_number)
                        <td rowspan="{{ $specieCountPerPermit }}">
                            @if ($prevPermitNumber != $d->permit_number)
                                {{ $name }}
                            @endif
                        </td>
                        <td rowspan="{{ $specieCountPerPermit }}">
                            @if ($prevPermitNumber != $d->permit_number)
                                {{ $d->barangay_name }}, {{ $d->municipality_name }}, {{ $d->province_name }}
                            @endif
                        </td>
                        <td rowspan="{{ $specieCountPerPermit }}" >
                            @if ($prevPermitNumber != $d->permit_number)
                                {{ $d->permit_number }}
                            @endif
                        </td>
                        <td rowspan="{{ $specieCountPerPermit }}">
                            @if ($prevPermitNumber != $d->permit_number)
                                {{ \Carbon\Carbon::parse($d->issuance_date)->format('F d, Y') }}
                            @endif
                        </td>
                        <td rowspan="{{ $specieCountPerPermit }}">
                            @if ($prevPermitNumber != $d->permit_number)
                                {{ \Carbon\Carbon::parse($d->expiration_date)->format('F d, Y') }}
                            @endif
                        </td>
                        <td rowspan="{{ $specieCountPerPermit }}">
                            @if ($prevPermitNumber != $d->permit_number)
                                100.00
                            @endif
                        </td>
                    @endif
                    <td><span class="fw-bold">{{ $d->local_name }}</span>(<span class="italic">{{ $d->specie_name }}</span>)</td>
                    <td class="text-center">{{ $d->specie_nature_name }}</td>
                    <td>{{ $d->total_species }}</td>
                    <td>pieces</td>
                    <td>Marinduque</td>
                    <td>{{ $d->province_name }}</td>
                    <td class="text-center">@if($d->purpose == 'export') ✓ @endif </td>
                    <td class="text-center">@if($d->purpose == 'local sale') ✓ @endif </td>
                    <td class="text-center">@if($d->purpose == 'research') ✓ @endif </td>
                    <td></td>
                    <td></td>
                    @php 
                        $prevName = $name;
                        $prevPermitNumber = $d->permit_number;
                    @endphp
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table class="w-100">
        <tbody>
            <tr>
                <td>Prepared/Collated by:</td>
                <td>Reviewed and Attested by:</td>
                <td>Approved by:</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="text-center"><h6 style="font-weight: bold; text-transform: uppercase">{{ auth()->user()->personalInfo->first_name }} {{ auth()->user()->personalInfo->last_name }}</h6></td>
                <td class="text-center fw-bold"><h6 class="fw-bold">CELSO B. ALMAZAN, RPF</h6></td>
                <td class="text-center fw-bold"><h6 class="fw-bold">CESAR P. ODI, RPF</h6></td>
            </tr>
            <tr>
                <td class="text-center">{{ auth()->user()->empPosition->position }}</td>
                <td class="text-center ">OIC Chief, Technical Services Division</td>
                <td class="text-center">PENR Officer</td>
            </tr>
        </tbody>
    </table>
@endsection