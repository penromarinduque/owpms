<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment of Fees and Charges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <style>
        * {
            font-size: 12px;
        }
        @media print {
            @page {
                /* size: letter; */
                /* margin: 0.5in; */
            }
        }
    </style>
</head>

<body >
    <div class="row">
        
        <div class="col-6 p-3">
            <div class="header d-flex justify-content-center align-items-center mb-3">
                <img src="{{ asset('images/denr_logo.png') }}" alt="DENR Logo" class="me-3" style="width: 70px; height: 70px;">
                <div class="text-center">
                    <p class="m-0">Republic of the Philippines</p>
                    <p class="m-0">Department of Environment and Natural Resources</p>
                    <p class="m-0">PENRO Marinduque</p>
                </div>
                <img src="{{ asset('images/bagong-pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="ms-3" style="width: 70px; height: 70px;">
            </div>
        
            
            <div class="border border-dark">
                <div class="text-center fw-bold my-3 py-2  border-dark">Assessment of Fees and Charges</div>

                <div class="p-2">
                    <div class="mb-1 row">
                        <span class="fw-semibold col-3">Bill No. : </span>
                        <span class="col-9">{{ $paymentOrder->order_number }}</span>
                    </div>
                    <div class="mb-1 row">
                        <span class="fw-semibold col-3">Date : </span>
                        <span class="col-9">{{ $paymentOrder->created_at->format('F d, Y') }}</span>
                    </div>
                    <br>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Name/Payee : </label>
                        <span class="col-9">{{ $paymentOrder->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $paymentOrder->ltpApplication->permittee->user->personalInfo->last_name }}</span>
                    </div>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Address : </label>
                        <span class="col-9">{{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->barangay_name }}, {{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->municipality->province->province_name }}</span>
                    </div>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Nature of Application/Permit/Documents being secured :</label>
                        <span class="col-9">Local Transport Permit</span>
                    </div>
                
                </div>

                <br>
            
                <table class="table table-bordered border-dark border-end-0 border-start-0">
                    <thead class="text-center">
                        <tr class="">
                            <th scope="col" class="fw-bold border-start-0">Legal Basis (DAO/SEC)</th>
                            <th scope="col" class="fw-bold">Description and Computation of Fees and Charges Assessed</th>
                            <th scope="col" class="fw-bold border-end-0">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-3  border-start-0">{{ $paymentOrder->ltpFee->legal_basis }}</td>
                            <td class="p-3">{{ $paymentOrder->ltpFee->fee_name }}</td>
                            <td class="p-3 border-end-0">{{ $paymentOrder->ltpFee->amount }}</td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
            
                        <tr>
                            <td colspan="2" class="text-center fw-bold p-2  border-start-0">TOTAL</td>
                            <td class="p-2 border-end-0">{{ $paymentOrder->ltpFee->amount }}</td>
                        </tr>
                    </tbody>
                </table>
            
            
                <div class="row p-2">
                    <div class="col-6">
                        <label class="fw-bold mb-1">Prepared By:</label>
                        <div class=" border-dark mt-4 text-center text-uppercase">{{ $paymentOrder->preparedBy->personalInfo->first_name . ' ' . strtoupper(substr($paymentOrder->preparedBy->personalInfo->middle_name, 0, 1)) . '. ' . $paymentOrder->preparedBy->personalInfo->last_name }}</div>
                        <div class="border-top border-dark pt-2 text-center">{{ $paymentOrder->prepared_by_position }}</div>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold mb-1">Approved By:</label>
                        <div class=" border-dark mt-4 text-center text-uppercase">{{ $paymentOrder->approvedBy->personalInfo->first_name . ' ' . strtoupper(substr($paymentOrder->approvedBy->personalInfo->middle_name, 0, 1)) . '. ' . $paymentOrder->approvedBy->personalInfo->last_name }}</div>
                        <div class="border-top border-dark pt-2 text-center ">{{ $paymentOrder->approved_by_position }}</div>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Received: <span
                                class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Release Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Tracking No. <span
                                class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div>
                {{-- <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Release Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div> --}}
            </div>
        </div>
        
        <div class="col-6 p-3">
            <div class="header d-flex justify-content-center align-items-center mb-3">
                <img src="{{ asset('images/denr_logo.png') }}" alt="DENR Logo" class="me-3" style="width: 70px; height: 70px;">
                <div class="text-center">
                    <p class="m-0">Republic of the Philippines</p>
                    <p class="m-0">Department of Environment and Natural Resources</p>
                    <p class="m-0">PENRO Marinduque</p>
                </div>
                <img src="{{ asset('images/bagong-pilipinas.png') }}" alt="Bagong Pilipinas Logo" class="ms-3" style="width: 70px; height: 70px;">
            </div>
        
            
            <div class="border border-dark">
                <div class="text-center fw-bold my-3 py-2  border-dark">Assessment of Fees and Charges</div>

                <div class="p-2">
                    <div class="mb-1 row">
                        <span class="fw-semibold col-3">Bill No. : </span>
                        <span class="col-9">{{ $paymentOrder->order_number }}</span>
                    </div>
                    <div class="mb-1 row">
                        <span class="fw-semibold col-3">Date : </span>
                        <span class="col-9">{{ $paymentOrder->created_at->format('F d, Y') }}</span>
                    </div>
                    <br>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Name/Payee : </label>
                        <span class="col-9">{{ $paymentOrder->ltpApplication->permittee->user->personalInfo->first_name . ' ' . $paymentOrder->ltpApplication->permittee->user->personalInfo->last_name }}</span>
                    </div>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Address : </label>
                        <span class="col-9">{{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->barangay_name }}, {{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->municipality->municipality_name }}, {{ $paymentOrder->ltpApplication->permittee->user->wfp()->wildlifeFarm->barangay->municipality->province->province_name }}</span>
                    </div>
                    <div class="mb-1 row">
                        <label class="fw-semibold mb-1 col-3">Nature of Application/Permit/Documents being secured :</label>
                        <span class="col-9">Local Transport Permit</span>
                    </div>
                
                </div>

                <br>
            
                <table class="table table-bordered border-dark border-end-0 border-start-0">
                    <thead class="text-center">
                        <tr class="">
                            <th scope="col" class="fw-bold border-start-0">Legal Basis (DAO/SEC)</th>
                            <th scope="col" class="fw-bold">Description and Computation of Fees and Charges Assessed</th>
                            <th scope="col" class="fw-bold border-end-0">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-3  border-start-0">{{ $paymentOrder->ltpFee->legal_basis }}</td>
                            <td class="p-3">{{ $paymentOrder->ltpFee->fee_name }}</td>
                            <td class="p-3 border-end-0">{{ $paymentOrder->ltpFee->amount }}</td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
                        <tr>
                            <td class="p-3  border-start-0"></td>
                            <td class="p-3"></td>
                            <td class="p-3 border-end-0"></td>
                        </tr>
            
                        <tr>
                            <td colspan="2" class="text-center fw-bold p-2  border-start-0">TOTAL</td>
                            <td class="p-2 border-end-0">{{ $paymentOrder->ltpFee->amount }}</td>
                        </tr>
                    </tbody>
                </table>
            
            
                <div class="row p-2">
                    <div class="col-6">
                        <label class="fw-bold mb-1">Prepared By:</label>
                        <div class=" border-dark mt-4 text-center text-uppercase">{{ $paymentOrder->preparedBy->personalInfo->first_name . ' ' . strtoupper(substr($paymentOrder->preparedBy->personalInfo->middle_name, 0, 1)) . '. ' . $paymentOrder->preparedBy->personalInfo->last_name }}</div>
                        <div class="border-top border-dark pt-2 text-center">{{ $paymentOrder->prepared_by_position }}</div>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold mb-1">Approved By:</label>
                        <div class=" border-dark mt-4 text-center text-uppercase">{{ $paymentOrder->approvedBy->personalInfo->first_name . ' ' . strtoupper(substr($paymentOrder->approvedBy->personalInfo->middle_name, 0, 1)) . '. ' . $paymentOrder->approvedBy->personalInfo->last_name }}</div>
                        <div class="border-top border-dark pt-2 text-center ">{{ $paymentOrder->approved_by_position }}</div>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Received: <span
                                class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Release Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Tracking No. <span
                                class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div>
                {{-- <div class="col-6">
                    <div class="">
                        <label class="fw-bold">Release Date: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                    <div class="">
                        <label class="fw-bold">Time: <span class=" border-dark px-4">_______________</span></label>
                    </div>
                </div> --}}
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