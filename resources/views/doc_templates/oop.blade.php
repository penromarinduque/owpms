<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment of Fees and Charges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: letter;
                margin: 0.5in;
            }
        }
    </style>
</head>

<body class="p-4 font-monospace">
    <div class="header d-flex justify-content-center align-items-center mb-3">
        <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="me-3" style="width: 70px; height: 70px;">
        <div class="text-center">
            <p class="m-0">Republic of the Philippines</p>
            <p class="m-0">Department of Environment and Natural Resources</p>
            <p class="m-0">PENRO Marinduque</p>
        </div>
        <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="ms-3" style="width: 70px; height: 70px;">
    </div>

    <div class="text-center fw-bold my-3 py-2 border-top  border-dark">Assessment of Fees and Charges</div>

    <div class="row justify-content-end mb-3">
        <div class="col-6 text-end">
            <div class="mb-2">
                <span class="fw-bold">Bill No._________</span>
            </div>
            <div class="mb-2">
                <span class="fw-bold">Date: ____________</span>

            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="fw-bold mb-1">Name/Payee:</label>
        <div class=" border-dark"></div>
    </div>

    <div class="mb-3">
        <label class="fw-bold mb-1">Address:</label>
        <div class=" border-dark"></div>
    </div>

    <div class="mb-3">
        <label class="fw-bold mb-1">Nature of Application/Permit/Documents being secured:</label>
        <div class=" border-dark"></div>
    </div>

    <hr class="border border-dark">

    
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th scope="col" class="fw-bold">Legal Basis (DAO/SEC)</th>
                <th scope="col" class="fw-bold">Description and Computation of Fees and Charges Assessed</th>
                <th scope="col" class="fw-bold">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="p-3"></td>
                <td class="p-3"></td>
                <td class="p-3"></td>
            </tr>
            <tr>
                <td class="p-3"></td>
                <td class="p-3"></td>
                <td class="p-3"></td>
            </tr>
            <tr>
                <td class="p-3"></td>
                <td class="p-3"></td>
                <td class="p-3"></td>
            </tr>
            <tr>
                <td class="p-3"></td>
                <td class="p-3"></td>
                <td class="p-3"></td>
            </tr>
            <tr>
                <td class="p-3"></td>
                <td class="p-3"></td>
                <td class="p-3"></td>
            </tr>

            <tr>
                <td colspan="2" class="text-center fw-bold p-2">TOTAL</td>
                <td class="p-2"></td>
            </tr>
        </tbody>
    </table>


    <div class="row mt-4">
        <div class="col-4">
            <div class="mb-2">
                <label class="fw-bold mb-1">Prepared By:</label>
                <div class=" border-dark mt-4"></div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <div class="border-top border-dark pt-2 text-center">Signature of Immediate Head</div>
        </div>
        <div class="col-6">
            <div class="border-top border-dark pt-2 text-center">Signature of Division Chief</div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <div class="mb-2">
                <label class="fw-bold">Received: <span
                        class=" border-dark px-4">_______________</span></label>
            </div>
            <div class="mb-2">
                <label class="fw-bold">Date: <span class=" border-dark px-4">_______________</span></label>
            </div>
        </div>
        <div class="col-6">
            <div class="mb-2">
                <label class="fw-bold">Tracking No. <span
                        class=" border-dark px-4">_______________</span></label>
            </div>
            <div class="mb-1">
                <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="mb-1">
                <label class="fw-bold">Released Date: <span
                        class="border-dark px-4">_______________</span></label>
                <label class="fw-bold ms-5">Time: <span
                        class=" border-dark px-4">_______________</span></label>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>

</html>