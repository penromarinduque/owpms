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
            @php
                $totalPermits = 0;
                $totalFees = [];
                $totalTransported = 0;
                $iteration = 1;
            @endphp
            @php
                // Initialize an array to track totals for each month dynamically
                $totalPermitsPerMonth = [];
                foreach($selectedMonthsConfig as $monthConfig) {
                    $totalPermitsPerMonth[$monthConfig->id] = 0;
                }
                $totalPermits = 0;
                $totalTransported = 0;
                $totalCollectedFees = 0;
            @endphp

            @foreach ($data->where('permit_type', 'wcp') as $i => $d)
                @php
                    // Calculate the row permits total
                    $rowTotalPermits = array_sum(array_map(function($monthConfig) use ($d){ 
                        return $d->{'m' . $monthConfig->id} ?? 0; 
                    }, $selectedMonthsConfig->toArray()));

                    $farm = $data->where('permit_type', 'wfp')->where('id', $d->id)->first();
                @endphp

                @if($rowTotalPermits > 0) 
                    <tr>
                        <td>{{ $iteration++ }}</td>
                        <td>{{ $d->first_name }} {{ $d->last_name }}</td>
                        <td>{{ $d->barangay_name }}, {{ $d->municipality_name }}, {{ $d->province_name }}</td>
                        <td>{{ $farm ? $farm->farm_name : '-' }}</td>

                        {{-- Monthly Permit Counts --}}
                        @foreach($selectedMonthsConfig as $monthConfig)
                            @php
                                $monthlyCount = $d->{'m' . $monthConfig->id} ?? 0;
                                // Accumulate per-month permit totals for the footer
                                $totalPermitsPerMonth[$monthConfig->id] += $monthlyCount;
                            @endphp
                            <td class="text-center">{{ $monthlyCount > 0 ? $monthlyCount : '-' }}</td>
                        @endforeach

                        {{-- Grand Total of Permits for this row --}}
                        <td class="text-center fw-bold">{{ $rowTotalPermits > 0 ? $rowTotalPermits : '-' }}</td>
                        
                        {{-- Fees Collected --}}
                        <td class="text-center">{{ number_format($d->collected_fees ?? 0, 2) }}</td>
                        
                        {{-- Total Transported --}}
                        <td class="text-center">{{ number_format($d->transported_species ?? 0, 0, '.', ',') }}</td>
                        
                        @php
                            // Accumulate grand totals
                            $totalPermits += $rowTotalPermits;
                            $totalTransported += $d->transported_species ?? 0;
                            $totalCollectedFees += $d->collected_fees ?? 0;
                        @endphp
                    </tr>
                @endif
            @endforeach

            {{-- TOTAL ROW --}}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center fw-bold">TOTAL</td>

                {{-- Sum of permits for each month --}}
                @foreach($selectedMonthsConfig as $monthConfig)
                    <td class="text-center fw-bold">
                        {{ number_format($totalPermitsPerMonth[$monthConfig->id], 0, '.', ',') }}
                    </td>
                @endforeach

                {{-- Grand Total Permits --}}
                <td class="text-center fw-bold">{{ number_format($totalPermits, 0, '.', ',') }}</td>
                
                {{-- Grand Total Fees Collected --}}
                <td class="text-center fw-bold">
                    {{ number_format($totalCollectedFees, 2, '.', ',') }}
                </td>
                
                {{-- Grand Total Transported --}}
                <td class="text-center fw-bold">{{ number_format($totalTransported, 0, '.', ',') }}</td>
            </tr>
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
                <td class="text-center">
                    <h6 style="font-weight: bold; text-transform: uppercase">
                        {{ auth()->user()->personalInfo->first_name }} {{ auth()->user()->personalInfo->middle_initial }} {{ auth()->user()->personalInfo->last_name }} 
                        @if (auth()->user()->personalInfo->title)
                            , {{ auth()->user()->personalInfo->title }}
                        @endif
                    </h6>
                </td>
                <td class="text-center fw-bold" style="font-weight: bold;"><h6 style="font-weight: bold;">CELSO B. ALMAZAN, RPF</h6></td>
                <td class="text-center fw-bold" style="font-weight: bold;"><h6 style="font-weight: bold;">CESAR P. ODI, RPF</h6></td>
            </tr>
            <tr>
                <td class="text-center">{{ auth()->user()->empPosition->position }}</td>
                <td class="text-center">OIC Chief, Technical Services Division</td>
                <td class="text-center">PENR Officer</td>
            </tr>
        </tbody>
    </table>
@endsection