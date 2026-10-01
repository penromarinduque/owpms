@extends('layouts.pdf')

@section('title')
    Summary of Transported Species
@endsection

@section('content')
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
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
    <h6 class="fw-bold">
        Summary of Transported Species
    </h6>
    <h6 class="fw-bold">
        @if($frequency == 1)
            Month: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $month }}&nbsp;&nbsp;</span> C.Y.: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $year }}&nbsp;&nbsp;</span>
        @elseif($frequency == 2)
            Quarter: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $quarter }}&nbsp;&nbsp;</span> C.Y.: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $year }}&nbsp;&nbsp;</span>
        @elseif($frequency == 3)
            Semester: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $semester }}&nbsp;&nbsp;</span> C.Y.: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $year }}&nbsp;&nbsp;</span>
        @else 
            C.Y.: <span style="text-decoration: underline">&nbsp;&nbsp;{{ $year }}&nbsp;&nbsp;</span>
        @endif
    </h6>
    <table class="table table-sm">
        <thead>
            <tr class="border-bottom border-top border-dark">
                <th class="text-center align-content-center" scope="col" >Permit No.</th>
                <th class="text-center align-content-center" scope="col" >Issuance</th>
                <th class="text-center align-content-center" scope="col" >Expiration</th>
                <th class="text-center align-content-center" scope="col" >Destination</th>
                <th class="text-center align-content-center" scope="col" >Purpose</th>
                <th class="text-center align-content-center" scope="col" >Total No. of Species</th>
                <th class="text-center align-content-center" scope="col" >Revenue Generated</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subTotalSpecie = 0;
                $subTotalRevenue = 0;
                $grandTotalSpecie = 0;
                $grandTotalRevenue = 0;
                $oldPermitteeId = null;
            @endphp
            @foreach ($data as $i => $d)
                @if($oldPermitteeId != $d->permittee_id && $oldPermitteeId != null)
                    <tr class="border-0">
                        <td colspan="5" class="text-end fw-bold border-0">SUB TOTAL:</td>
                        <td class="text-center border-0 fw-bold">{{ number_format($subTotalSpecie, 0, '.', ',') }}</td>
                        <td class="text-center border-0 fw-bold">{{ number_format($subTotalRevenue, 2, '.', ',') }}</td>
                    </tr>
                    @php
                        $subTotalSpecie = 0;
                        $subTotalRevenue = 0;
                    @endphp
                @endif
                @if($oldPermitteeId != $d->permittee_id)
                    <tr class="border-bottom-0 border-top-0 border-0 ">
                        <td colspan="7" class=" fw-bold border-0">{{ $d->first_name }} {{ $d->last_name }}</td>
                    </tr>
                @endif
                @php
                    $oldPermitteeId = $d->permittee_id;
                    $subTotalSpecie += $d->total_species;
                    $subTotalRevenue += $d->amount;
                    $grandTotalSpecie += $d->total_species;
                    $grandTotalRevenue += $d->amount;
                @endphp
                <tr class="border-0 border-none border-bottom-0 border-top-0">
                    <td class="text-center border-0">{{ $d->permit_number }}</td>
                    <td class="text-center border-0">{{ date('d-M-Y', strtotime($d->issuance_date)) }}</td>
                    <td class="text-center border-0">{{ date('d-M-Y', strtotime($d->expiration_date)) }}</td>
                    <td class="text-center border-0">
                        {{ $d->province_name }}
                    </td>
                    <td class="text-center border-0">{{ Str::title($d->purpose) }}</td>
                    <td class="text-center border-0">{{ number_format($d->total_species, 0, '.', ',') }}</td>
                    <td class="text-center border-0">{{ number_format($d->amount, 2, '.', ',') }}</td>
                </tr>
            @endforeach
            
            <tr class="border-0">
                <td colspan="5" class="text-end fw-bold border-0">SUB TOTAL:</td>
                <td class="text-center border-0 fw-bold">{{ number_format($subTotalSpecie, 0, '.', ',') }}</td>
                <td class="text-center border-0 fw-bold">{{ number_format($subTotalRevenue, 2, '.', ',') }}</td>
            </tr>
            <tr class="border-bottom-0 border-dark border-top">
                <td colspan="5" class="text-end fw-bold border-0">GRAND TOTAL:</td>
                <td class="text-center border-0 fw-bold">{{ number_format($grandTotalSpecie, 0, '.', ',') }}</td>
                <td class="text-center border-0 fw-bold">{{ number_format($grandTotalRevenue, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="w-100" style="page-break-inside: avoid;">
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
                <td class="text-center"><h6 style="font-weight: bold; text-transform: uppercase">CELSO B. ALMAZAN, RPF</h6></td>
                <td class="text-center"><h6 style="font-weight: bold; text-transform: uppercase">CESAR P. ODI, RPF</h6></td>
            </tr>
            <tr>
                <td class="text-center">{{ auth()->user()->empPosition->position }}</td>
                <td class="text-center">OIC Chief, Technical Services Division</td>
                <td class="text-center">PENR Officer</td>
            </tr>
        </tbody>
    </table>
@endsection