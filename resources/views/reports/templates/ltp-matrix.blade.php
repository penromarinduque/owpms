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
    <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid; ">
        <thead>
            <tr>
                <td>
                    <div class="border-0">
                        @include('pdfs.header')
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr><td>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr style="background-color: #c6E0b4;">
                            <th style="background-color: #c6E0b4;" class="text-center align-content-center" colspan="9">MONTHLY CONSOLIDATED REPORT OF LOCAL TRANPORT PERMIT FOR THE MONTH OF <span style="text-transform: uppercase">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</span> C.Y. {{ $year }}</td>
                        </tr>
                        <tr style="background-color: #b4c6e7;">
                            <th style="background-color: #b4c6e7;" colspan="3"></th>
                            <th style="background-color: #b4c6e7;" class="text-center">TYPE OF PERMIT</tthdth>
                            <th style="background-color: #b4c6e7;" colspan="2"class="text-center">Information</th>
                            <th style="background-color: #b4c6e7;" colspan="3"></th>
                        </tr>
                        <tr style="background-color: #b4c6e7;">
                            <th style="background-color: #b4c6e7;" class="text-center align-content-center" width="30px">No.</th>
                            <th style="background-color: #b4c6e7;" class="text-center align-content-center" width="50px">Region</th>
                            <th style="background-color: #b4c6e7;" class="text-center align-content-center" width="50px">Office</th>
                            <th style="background-color: #b4c6e7;" class="text-center align-content-center" width="100px">LTP (1-2 days)</th>
                            <th style="background-color: #b4c6e7;" class="text-center align-content-center" width="200px">Name of Permittee(Name, MI, Surname)</th>
                            <th style="background-color: #b4c6e7;" width="50px" class="text-center align-content-center" width="50px">Permit Number(xxx-####)</th>
                            <th style="background-color: #b4c6e7;" width="50px" class="text-center align-content-center" width="150px">LTP Date Applied</th>
                            <th style="background-color: #b4c6e7;" width="50px" class="text-center align-content-center" width="150px">LTP Date Issued</th>
                            <th style="background-color: #b4c6e7;" width="50px" class="text-center align-content-center" width="150px">Date received by Client</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data as $d)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td class="text-center">MIMAROPA</td>
                                <td class="text-center">Marinduque</td>
                                <td class="text-center">Local Transport Permit</td>
                                <td class="text-center">{{ $d->first_name }} {{ $d->middle_name }} {{ $d->last_name }}</td>
                                <td class="text-center">{{ $d->permit_number }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($d->application_date)->format('F d, Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($d->issuance_date)->format('F d, Y') }}</td>
                                <td class="text-center">
                                    @if($d->released_date)
                                        {{ \Carbon\Carbon::parse($d->released_date)->format('F d, Y') }}
                                    @endif
                                </td>
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
                            <td class="text-center">
                                <h6 style="font-weight: bold; text-transform: uppercase">
                                    {{ auth()->user()->personalInfo->first_name }} {{ auth()->user()->personalInfo->middle_initial }} {{ auth()->user()->personalInfo->last_name }} 
                                    @if (auth()->user()->personalInfo->title)
                                        , {{ auth()->user()->personalInfo->title }}
                                    @endif
                                </h6>
                            </td>
                            <td class="text-center fw-bold"><h6 class="fw-bold" style="font-weight: bold;">CELSO B. ALMAZAN, RPF</h6></td>
                            <td class="text-center fw-bold"><h6 class="fw-bold" style="font-weight: bold;">CESAR P. ODI, RPF</h6></td>
                        </tr>
                        <tr>
                            <td class="text-center">{{ auth()->user()->empPosition->position }}</td>
                            <td class="text-center ">Chief, Technical Services Division</td>
                            <td class="text-center">PENR Officer</td>
                        </tr>
                    </tbody>
                </table>
            </td></tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div class="border-0">
                        @include('pdfs.footer')
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection