<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application for Local Transport Permit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #000;
        }

        .letterhead {
            padding-bottom: 7px;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            font-size: 1rem;
        }

        .letterhead h4 {
            font-size: 1.1rem;
        }

        .letter-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 20px;
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
                font-size: 1rem;
            }

            .letterhead h4 {
                font-size: 1.2rem;
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
        <div class="letterhead text-center">
            <h5 class="mb-0">Republic of the Philippines</h5>
            <h4 class="fw-bold mt-0 mb-0">{{ $permittee_wfp['farm_name'] }}</h4>
            <p class="mt-0 mb-0">Cawit, Boac, Marinduque 4900</p>
            <p class="mt-1 mb-0">Contact No. 099971036106 | Email: anthonydelapena@gmail.com</p>
        </div>

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
                <p>
                    Pursuant to the provisions provided in the WildLife Farm Permit and the WildLife Collection Permit,
                    the
                    undersigned would like to apply for the <strong>Local Transport Permit</strong> of the following
                    Butterfly Pupae
                    species specified in the table below.
                </p>
            </div>

            <!-- Table -->
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
                    @if(!empty($ltp_species))
                                        @php
                                            $c = 1;
                                            $total = 0;
                                        @endphp
                                        @foreach($ltp_species as $ltp_specie)
                                                        @php
                                                            $total += $ltp_specie->quantity;
                                                        @endphp
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{$ltp_specie->local_name}}</td>
                                                            <td>{{$ltp_specie->specie_name}}</td>
                                                            <td>{{$ltp_specie->family_name}}</td>
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

            <!-- Purpose Statement -->
            <div class="mb-4">
                <p>
                    The listed pupae/live species will be transported on or before
                    <strong>{{date('F j, Y', strtotime($data['transport_date']))}}</strong> for the purpose of
                    <u>&nbsp;{{$data['purpose']}}&nbsp;</u> at <strong>{{ $data['city'] }}, {{ $data['state'] }},
                        {{ $data['country'] }}</strong>.
                </p>
                <p>Thank you.</p>
            </div>

            <!-- Signature Block -->
            <div class="signature-block">
                <p>Sincerely,</p>
                <p class="mt-2"><strong>ANTHONY M. DELA PENA</strong><br>Owner<br>La Anton Insects and Butterfly Farm
                </p>
            </div>
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