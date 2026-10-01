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
    <h5 class="text-center">
        Issuance of Wildlife Local Transport Permit for the 
        @if($frequency == 1)
            Month of {{ $selectedMonthsConfig->first()->fullName }} {{ $year }}
        @elseif($frequency == 2)
            {{ getOrdinal($quarter) }} Quarter C.Y. {{ $year }}
        @elseif($frequency == 3)
            {{ getOrdinal($semester) }} Semester C.Y. {{ $year }}
        @else 
            C.Y. {{ $year }}
        @endif
    </h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center align-content-center" scope="col" rowspan="2">No.</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">PERMITTEE</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">ADDRESS</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">BUSINESS NAME</th>
                <th class="text-center align-content-center" scope="col" colspan="{{ count($selectedMonthsConfig) }}">NUMBER OF PERMITS ISSUED</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">GRAND TOTAL</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">FEES COLLECTED (PHP)</th>
                <th class="text-center align-content-center" scope="col" rowspan="2">TOTAL NO. OF PUPAE/DEADSTOCKS TRANSPORTED</th>
            </tr>
            <tr>
                @foreach($selectedMonthsConfig as $monthConfig)
                    <th class="text-center" scope="col">{{ $monthConfig->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data->where('permit_type', 'wcp') as $i => $d)
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
                <td class="text-center"><h6>CELSO B. ALMAZAN, RPF</h6></td>
                <td class="text-center"><h6>CESAR P. ODI, RPF</h6></td>
            </tr>
            <tr>
                <td class="text-center">{{ auth()->user()->empPosition->position }}</td>
                <td class="text-center">OIC Chief, Technical Services Division</td>
                <td class="text-center">PENR Officer</td>
            </tr>
        </tbody>
    </table>
@endsection