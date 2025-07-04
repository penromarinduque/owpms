<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application for Local Transport Permit</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/customize.css') }}" rel="stylesheet" />
    <style>
        /* body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #000;
        } */

        .letterhead {
            padding-bottom: 7px;
            margin-bottom: 10px;
            border-bottom: 2px solid maroon;
            font-size: 12px;
        }

        .letterhead h4 {
            font-size: 1.1rem;
        }

        .letter-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 10px 10px;

            font-size: 12px
        }

        .recipient-block {
            margin-bottom: 15px;
        }

        .subject-line {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .signature-block {
            margin-top: 20px;
        }

        .table {
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0 10px;
            /* Remove padding from table cells */
        }

        .qr-code {
            text-align: center;
            width: 200px;
            display: block;
            margin: 0rem auto;
        }

        @media print {
            body {
                padding: 20px;
            }

            .container {
                width: 100%;
                max-width: 100%;
            }

            .letterhead {
                position: fixed;
                top: 0;
                width: 100%;
                line-height: 1;
            }


            .main-content {
                margin-top: 60px;
            }

        }
    </style>
</head>

<body>
    <div class="container letter-content">
        <!-- Letterhead -->
        <div class=" text-center letterhead  pb-4 ">
            <p class="mb-0 fw-bold">REPUBLIC OF THE PHILIPPINES</p>
            <p class="mt-0 mb-0 fw-bold">{{ strtoupper($wfp->wildlifeFarm->farm_name) }}</p>
            <p class="mt-0 mb-0">{{ $wfp->wildlifeFarm->barangay->barangay_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $wfp->wildlifeFarm->barangay->municipality->province->province_name }}</p>
            <p class="mt-0 mb-0">Contact No. {{ $wfp->user->personalInfo->contact_no }} | Email: {{ $wfp->user->email }}</p>
        </div>

        <br><br>

        <div class="main-content">

            <!-- Date and Recipient -->
            <div class="recipient-block">
                <p>{{date('F j, Y')}}</p>
                <p>
                    <strong>MS. IMELDA M. DIAZ</strong><br>
                    PENR Officer<br>
                    Boac, Marinduque
                </p>
            </div>

            <!-- Subject and Greeting -->
            <div class="mb-4">
                <p class="subject-line">Subject: Application for Local Transport Permit of Butterfly Species</p>
                <p><strong>Madam:</strong></p>
                <p>Greetings!!</p>
                <p class="text-justify">
                    &nbsp;&nbsp;&nbsp;&nbsp;Pursuant to the provisions provided in the WildLife Farm Permit and the WildLife Collection Permit,
                    the
                    undersigned would like to apply for the <strong>Local Transport Permit</strong> of the following
                    Butterfly Pupae
                    species specified in the table below.
                </p>
            </div>

            <!-- Table -->
            <div class="px-4">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Common Name</th>
                            <th>Scientific Name</th>
                            <th>Family Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($application->ltpApplicationSpecies))
                            @php
                                $c = 1;
                                $total = 0;
                            @endphp
                            @foreach($application->ltpApplicationSpecies as $ltp_specie)
                                @php
                                    $total += $ltp_specie->quantity;
                                @endphp
                                <tr>
                                    <td>{{$c++}}</td>
                                    <td>{{$ltp_specie->specie->local_name}}</td>
                                    <td>{{$ltp_specie->specie->specie_name}}</td>
                                    <td>{{$ltp_specie->specie->family->family}}</td>
                                    <td>{{$ltp_specie->quantity}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">TOTAL</td>
                            <td>{{$total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>
            
            <!-- Purpose Statement -->
            <div class="mb-4">
                <p class="text-justify">
                    &nbsp;&nbsp;&nbsp;&nbsp;The listed pupae/live species will be transported on or before
                    <strong><u>&nbsp;&nbsp;{{date('F j, Y', strtotime($application->transport_date))}}&nbsp;&nbsp;</u></strong> for the purpose of
                    <strong><u>&nbsp;&nbsp;{{$application->purpose}}&nbsp;&nbsp;</u></strong> at 
                    <strong><u>&nbsp;&nbsp;{{ $application->destination }}&nbsp;&nbsp;</u></strong>.
                </p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;Thank you.</p>
            </div>

            <!-- Signature Block -->
            <div class="signature-block">
                <p>Sincerely,</p>
                <p class="mt-2 ">
                    <strong >{{ strtoupper($wcp->user->personalInfo->first_name) }} {{ strtoupper(substr($wcp->user->personalInfo->middle_name, 0, 1)) }}. {{ strtoupper($wcp->user->personalInfo->last_name) }}</strong><br>
                    Owner, {{ $wfp->wildlifeFarm->farm_name }}
                </p>
            </div>
            <img 
                class="qr-code" 
                src="{{ $_helper->generateQrCode(urlencode(route('qr.index', ['id' => Crypt::encryptString($application->id), 'document_type' => 'request_letter']))) }}" 
                alt="">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        // Trigger print preview on page load
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>

</html>