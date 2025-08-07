<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="{{ asset('css/theme.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <style>
        .header {
            font-family: 'Times New Roman', Times, serif;
            border-color: maroon !important;
        }
        p, table{
            font-size: 12px !important;
        }
        table td {
            padding-bottom: 0px !important;
            padding-top: 0px !important;
        }
        .qr-code {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 150px;
            height: 150px;
            border: 1px solid black;
        }
    </style>
    <title>Print Local Transport Permit</title>
</head>
<body>
    <div class="header d-flex justify-content-center align-items-center mb-3 border-bottom pb-3 border-2">
        <img src="{{ asset('images/denr_logo.png') }}" alt="DENR Logo" class="me-3" style="width: 70px; height: 70px;">
        <div class="text-center">
            <p class="m-0 fw-bold">DEPARTMENT OF ENVIRONTMENT AND NATURAL RESOURCES</p>
            <p class="m-0">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
            <p class="m-0 fw-bold">PENRO MARINDUQUE</p>
        </div>
        <img src="{{ asset('images/bagong-pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="ms-3" style="width: 70px; height: 70px;">
    </div>

    <div class="row  px-4 align-content-center justify-content-center" >
        <div class="p-4 bg-white mb-4">
            <p class="mb-0">Wildlife Transport</p>
            <p class="mb-2">Permit No. <u>{{ $permit->permit_number }}</u> </p>
            <h5 class="text-center">LOCAL TRANSPORT PERMIT</h5><br>

            <p >
                &nbsp;&nbsp;&nbsp;&nbsp;Pursuant to the Republic Act 9147 dated July 30, 2001. Mr./Ms. 
                <u class="fw-bold" style="text-transform: uppercase">&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u> of 
                <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->permittee->user->personalInfo->getAddressAttribute() }}&nbsp;&nbsp;</u>
                is authorized to transport to 
                <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->transportDestination }}&nbsp;&nbsp;</u>
                the following wildlife for <u class="fw-bold">&nbsp;&nbsp;export&nbsp;&nbsp;</u> purpose.
            </p>

            {{-- <hr style="
                border: none;
                height: 2px;
                background-image: repeating-linear-gradient(to right, black 0 10px, white 10px 20px);
                background-repeat: repeat-x;
                background-color: white;
                opacity: 1;
            "> --}}
            <hr style="
                border: none;
                border-top: 2px dashed black;
                opacity: 1;
            ">
            <div class="px-4">
                <table class="table border-0 align-middle ">
                    <tbody>
                        <tr class="border-0">
                            <td class="text-center border-0 align-middle" colspan="3">Description</td>
                        </tr>
                        <tr class="border-0">
                            <td width="33%" class=" border-0 align-middle">Common/Scientific Name</th>
                            <td width="33%" class="text-center border-0 align-middle">Description (including parts, derivatives, marks, numbers, age and sex if any)</th>
                            <td width="" class="text-end border-0  align-middle">Quantity</th>
                        </tr>
                        <tr>
                            <td colspan="3" class="border-0"></td>
                        </tr>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($ltp_application->ltpApplicationSpecies as $specie)
                            <tr>
                                <td class="border-0 align-middle">{{ $specie->specie->specie_name }}</td>
                                <td class="text-center border-0 align-middle">pupae</td>
                                <td class="text-end border-0 align-middle">{{ number_format($specie->quantity, 0, '.', ',') }}</td>
                                @php
                                    $count += $specie->quantity;
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-end fw-bold border-0" colspan="3">Total &nbsp;&nbsp;{{ number_format($count, 0, '.', ',') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr style="
                border: none;
                border-top: 2px dashed black;
                opacity: 1;
            ">
            {{-- <hr style="
                border: none;
                height: 2px;
                background-image: repeating-linear-gradient(to right, black 0 10px, white 10px 20px);
                background-repeat: repeat-x;
                background-color: white;
                opacity: 1;
            "> --}}
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;The above mentioned specimens shall be transported by Air/Sea express on or before 
                <u class="fw-bold">&nbsp;&nbsp;{{$ltp_application->transport_date->format('F d, Y')}}&nbsp;&nbsp;</u>
                and have been inspected, verified and found in accordance with existing wildlife laws, rules and regulations.
            </p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;Local Transport fee in the amount of <u class="fw-bold">&nbsp;&nbsp; &#x20B1; {{ number_format($ltp_application->paymentOrder->ltpFee->amount, 2, '.', ',') }}&nbsp;&nbsp;</u>
                was paid under the DENR PENRO Official Receipt No. <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->paymentOrder->payment_reference }}&nbsp;&nbsp;</u>
                dated <u class="fw-bold">&nbsp;&nbsp;{{ $ltp_application->paymentOrder->issued_date->format('F d, Y') }}&nbsp;&nbsp;</u>.
            </p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;This permit is not valid without the dry seal of the signing officer or if it contains erasure or alteration.
            </p>

            <br>

            <div class="d-flex justify-content-end">
                <div class="d-flex flex-column align-items-center   ">
                    <p class="text-center border-bottom border-dark mb-0 fw-bold " style="min-width: 200px; text-transform: uppercase">{{ $permit->approver->personalInfo->getFullNameAttribute() }}</p>
                    <p class="text-center" style="min-width: 200px">{{ $permit->approver_designation }}</p>
                </div>
            </div>
        </div>
    </div>

    <img 
                class="qr-code" 
                src="{{ $_helper->generateQrCode(urlencode(route('qr.index', ['id' => Crypt::encryptString($ltp_application->id), 'document_type' => 'ltp']))) }}" 
                alt="">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            window.print();
        });
    </script>

</body>
</html>