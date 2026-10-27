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
    <h5 class="fw-bold text-center">
        Issuance of Wildlife Local Transport Permit <br>
        For the 
        @if($frequency == 1)
            month of {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }} 
        @elseif($frequency == 2)
            {{ getOrdinal($quarter) }} Quarter C.Y. {{ $year }}
        @elseif($frequency == 3)
            {{ getOrdinal($semester) }} Semester C.Y. {{ $year }}
        @else 
            C.Y. {{ $year }}
        @endif
    </h5>
    <br>
    PENRO Marinduque
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="2"class="text-center align-content-center">Permit Holder</th>
                <th colspan="5" class="text-center align-content-center">Permit Details</th>
                <th colspan="9" class="text-center align-content-center">Species Transported</th>
                <th rowspan="2" class="text-center align-content-center">Origin</th>
                <th rowspan="2" class="text-center align-content-center">Destination</th>
                <th rowspan="2" class="text-center align-content-center">Purpose of Transport</th>
                <th rowspan="2" class="text-center align-content-center">Remarks</th>
            </tr>
            <tr>
                <th class="text-center align-content-center">Name of Holder</th>
                <th class="text-center align-content-center">Address of Holder</th>
                <th class="text-center align-content-center">Permit No.</th>
                <th class="text-center align-content-center" width="120px">Date of Application</th>
                <th class="text-center align-content-center">Issuance Date</th>
                <th class="text-center align-content-center">Expiry Date</th>
                <th class="text-center align-content-center">Fees Collected</th>
                <th class="text-center align-content-center">Species Type</th>
                <th class="text-center align-content-center">Class</th>
                <th class="text-center align-content-center">Family</th>
                <th class="text-center align-content-center">Common Name</th>
                <th class="text-center align-content-center">Scientific Name</th>
                <th class="text-center align-content-center">Type of Specimen</th>
                <th class="text-center align-content-center">Quantity</th>
                <th class="text-center align-content-center">Unit of Measure</th>
                <th class="text-center align-content-center">Description</th>
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
                                {{ \Carbon\Carbon::parse($d->application_date)->format('F d, Y') }}
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
                    <td>{{ $d->specie_type }}</td>
                    <td>{{ $d->specie_class }}</td>
                    <td>{{ $d->family }}</td>
                    <td><span >{{ $d->local_name }}</span></td>
                    <td><span >{{ $d->specie_name }}</span></td>
                    <td class="text-center">{{ $d->specie_nature_name }}</td>
                    <td>{{ $d->total_species }}</td>
                    <td>pieces</td>
                    <td class="text-center">{{ $d->specie_nature_name }}</td>
                    <td>Marinduque</td>
                    <td>{{ $d->province_name }}</td>
                    <td class="text-center">{{$d->purpose}}</td>
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