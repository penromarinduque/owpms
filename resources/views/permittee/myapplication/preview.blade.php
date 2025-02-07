<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application for Local Transport Permit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h5 class="mb-0">Republic of the Philippines</h5>
            <h4 class="fw-bold mt-0 mb-0">LA ANTON INSECTS & BUTTERFLY FARM</h4>
            <p class="mt-0 mb-0">Cawit, Boac, Marinduque 4900</p>
            <p class="mt-0 mb-0">Contact No. 099971036106<br>Email: anthonydelapena@gmail.com</p>
        </div>

        <div class="mb-4">
            <p>{{date('F j, Y')}}</p>
            <p>
                <strong>MS. IMELDA M. DIAZ</strong><br>
                PENR Officer<br>
                Boac, Marinduque
            </p>
        </div>

        <div class="mb-4">
            <h5><strong>Subject:</strong> Application for Local Transport Permit of Butterfly Species</h5>
            <p><strong>Madam:</strong></p>
            <p>Greetings!!</p>
            <p>
                Pursuant to the provisions provided in the WildLife Farm Permit and the WildLife Collection Permit, the undersigned
                would like to apply for the <strong>Local Transport Permit</strong> of the following Butterfly Pupae species
                specified in the table below.
            </p>
        </div>

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

        <div class="mb-4">
            <p>
                The listed pupae/live species will be transported on or before <strong>{{date('F j, Y', strtotime($data['transport_date']))}}</strong> for the purpose of <u>&nbsp;{{$data['purpose']}}&nbsp;</u> at <strong>Cambridge Butterfly Conservatory, 2500 Kossuth Road, Cambridge Ontario,
                Canada, N3H 4R7</strong>.
            </p>
            <p>Thank you.</p>
        </div>

        <div class="mt-4">
            <p>Sincerely,</p>
            <p><strong>ANTHONY M. DELA PENA</strong><br>Owner<br>La Anton Insects and Butterfly Farm</p>
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
