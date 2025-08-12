<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment of Fees and Charges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <style>
        .header * {
            font-family: Times New Roman;
        }
        .header {
            padding-bottom: 1rem;
            border-bottom: 3px solid maroon;
        }
        * {
            font-size: 13px;
        }
        @media print {
            @page {
                /* size: letter; */
                /* margin: 0.5in; */
            }
        }
    </style>
</head>

<body>
    <table class="w-100">
        <thead>
            <tr>
                <td class="">
                    <div class="header d-flex justify-content-center align-items-center mb-3 mx-auto w-fit">
                        <img src="{{ asset('images/denr_logo.png') }}" alt="DENR Logo" class="me-3" style="width: 80px; height: 80px;">
                        <div class="text-center">
                            <p class="m-0 fw-bold ">DEPARTMENT OF ENVIRONMENT AND NATURAL RESOURCES</p>
                            <p class="m-0 ">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
                            <p class="m-0  fw-bold">PENRO MARINDUQUE</p>
                        </div>
                        <img src="{{ asset('images/bagong-pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="ms-3" style="width: 80px; height: 80px;">
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="px-4">
                        <div class=" bg-white p-5">

                            <h4 class="text-center">INSPECTION REPORT OF WILDLIFE</h4><br>

                            <p>TO WHOM IT MAY CONCERN</p>

                            <p class="text-justify">
                                &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that on this date, the undersigned has undertaken the inspection of wildlife in the 
                                <span class="fw-bold"><u>&nbsp;&nbsp;DENR PENRO - Marinduque&nbsp;&nbsp;</u></span>
                                    of <span class="fw-bold"><u>&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                                    and has found the following wildlife.
                            </p>

                            <div class="px-5 py-2">
                                <table class="table border-0">
                                    <thead class="border-0">
                                        <tr class="border-0">
                                            <th scope="col" class="border-0">Kind of Species</th>
                                            <td width="20px" class="border-0"></td>
                                            <th width="100px" scope="col" class="text-end border-0">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @if(!empty($ltp_application->ltpApplicationSpecies))
                                            @foreach($ltp_application->ltpApplicationSpecies as $ltp_specie)
                                                @php
                                                    $total += $ltp_specie->quantity;
                                                @endphp
                                                <tr>
                                                    <td class="border-0 pb-0"><i>{{ $ltp_specie->specie->specie_name }}</i></td>
                                                    <td class="border-0 pb-0"></td>
                                                    <td class="text-end border-0 pb-0">{{ number_format($ltp_specie->quantity, 0, '.', ',') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr class="fw-bold">
                                            <td colspan="2" class="text-end border-0">Total</td>
                                            <td class="text-end border-0">{{ number_format($total, 0, '.', ',') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <p class="text-justify">
                                &nbsp;&nbsp;&nbsp;&nbsp;The inspection was made in the presence of
                                    <span class="fw-bold"><u>&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                                    at the above address.
                            </p>

                            <br><br>

                            <div class="row ">
                                <div class="col-6 px-5 py-4">
                                    <p class="text-center mb-0 fw-bold">{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}</p>
                                    <hr class="my-0 border-dark text-dark">
                                    <p class="text-center">(Signature of Witness)</p>
                                </div>
                                <div class="col-6 px-5 py-4">
                                    <p class="text-center mb-0 fw-bold">{{ auth()->user()->personalInfo->getFullNameAttribute() }}</p>
                                    <hr class="my-0 border-dark">
                                    <p class="text-center">(Inspecting Officer)</p>
                                </div>
                                <div class="col-6 px-5 py-4">
                                    <p class="text-center mb-0 fw-bold">&nbsp;</p>
                                    <hr class="my-0 border-dark">
                                    <p class="text-center">&nbsp;</p>
                                </div>
                                <div class="col-6 px-5 py-4">
                                    <p class="text-center mb-0 fw-bold">Forest Ranger</p>
                                    <hr class="my-0 border-dark">
                                    <p class="text-center">(Designation)</p>
                                </div>
                            </div>

                            <br>
                            <h5 class="text-center">CERTIFICATE OF CONCURRENCE</h5>

                            <p class="text-justify">
                                &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the inspection report of 
                                    <span class="fw-bold"><u>&nbsp;&nbsp;{{ auth()->user()->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                                    of PENRO Marinduque, Region <span class="fw-bold"><u>&nbsp;&nbsp;IV-MIMAROPA&nbsp;&nbsp;</u></span>
                                    is/are true and correct and has been done this 
                                    <span id="inspection_date_text"><span class="fw-bold"><u>&nbsp;&nbsp;{{ old('inspection_date') ? \Carbon\Carbon::parse(old('inspection_date'))->format('jS') : $inspection_report->inspection_date->format('jS') }}&nbsp;&nbsp;</u></span>
                                    day of <span class="fw-bold" ><u>&nbsp;&nbsp;{{ now()->format('F Y') }}&nbsp;&nbsp;</u></span></span>. 
                            </p>

                            <p>&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that this statement was given to me voluntarily and with neither coercion nor promise of reward from the personnel of the Department of Environment and Natural Resources.</p>

                            <br><br>

                            <div class="d-flex align-content-end justify-content-end">
                                <div class="" style="min-width: 200px">
                                    <p class="text-center mb-0 mb-1 fw-bold">
                                        {{ strtoupper($inspection_report->approver->personalInfo->getFullNameAttribute()) }}
                                    </p>
                                    <hr class="my-0 border-dark mb-1">
                                    <p class="text-center">
                                        {{ $inspection_report->approver_position }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>


