@extends('layouts.master')

@section('title')
For Signatures
@endsection

@section('for-signatures')
    show
@endsection


@section('content') 
<div class="container-fluid px-4">
    <h2 class="mt-4">For Signatures</h2>
    {{-- <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Applications</li>
    </ol> --}}
    <br>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            List of {{ $_helper->setForSignatoriesDocumentName(request('type')) }} for signature.
        </div>
        <div class="card-body">

            {{-- <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Dropdown</a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul> --}}

            <div class="table-responsive">
                @php $totalSpan = 4 @endphp
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            @if (request('type') == 'inspection_report')
                                @php $totalSpan = 3 @endphp
                                <th>Document</th>
                                <th>Application No.</th>
                                <th width="500px" class="text-center">Actions</th>
                            @endif
                            @if (request('type') == 'ltp')
                                @php $totalSpan = 4 @endphp
                                <th>Document</th>
                                <th>Permit No.</th>
                                <th>Application No.</th>
                                <th width="500px" class="text-center">Actions</th>
                            @endif
                            @if (request('type') == 'payment_order')
                                @php $totalSpan = 4 @endphp
                                <th>Document</th>
                                <th>Order Number</th>
                                <th width="500px" class="text-center">Actions</th>
                            @endif
                            @if (request('type') == 'billing_statement')
                                @php $totalSpan = 4 @endphp
                                <th>Document</th>
                                <th>Order Number</th>
                                <th width="500px" class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docs as $doc)
                            <tr>
                                @if (request('type') == 'inspection_report')   
                                    <td>{{ $_helper->setForSignatoriesDocumentName(request('type')) }}</td>
                                    <td>
                                        {{ $doc->ltpApplication->application_no }}
                                    </td>
                                    <td class="text-center">
                                        @can('inspectorSign', $doc)
                                            <button class="btn btn-outline-primary"  onclick="showConfirmModal ('{{ route('for-signatures.inspectionReportInspectorSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                        @can('approverSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.inspectionReportApproverSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Approved</button>
                                        @endcan
                                        @can('permitteeSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.inspectionReportPermitteeSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                        @can('rpsSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.inspectionReportRpsSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                    </td>
                                @endif
                                @if (request('type') == 'ltp')   
                                    <td>{{ $_helper->setForSignatoriesDocumentName(request('type')) }}</td>
                                    <td>
                                        {{ $doc->permit_number }}
                                    </td>
                                    <td>
                                        {{ $doc->ltpApplication->application_no }}
                                    </td>
                                    <td class="text-center">
                                        @can('chiefRpsSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.ltpChiefRpsSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                        @can('chiefTsdSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.ltpChiefTsdSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                        @can('penroSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.ltpPenroSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                    </td>
                                @endif
                                @if (request('type') == 'billing_statement')   
                                    <td>{{ $_helper->setForSignatoriesDocumentName(request('type')) }}</td>
                                    <td>
                                        {{ $doc->order_number }}
                                    </td>
                                    <td class="text-center">
                                        @can('preparerSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.paymentOrderPreparerSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Signed</button>
                                        @endcan
                                        @can('approverSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.paymentOrderApproverSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Approved</button>
                                        @endcan
                                    </td>
                                @endif
                                @if (request('type') == 'payment_order')   
                                    <td>{{ $_helper->setForSignatoriesDocumentName(request('type')) }}</td>
                                    <td>
                                        {{ $doc->order_number }}
                                    </td>
                                    <td class="text-center">
                                        @can('oopApproverSign', $doc)
                                            <button class="btn btn-outline-primary" onclick="showConfirmModal ('{{ route('for-signatures.paymentOrderOopApproverSign', Crypt::encryptString($doc->id)) }}', 'Do you want to proceed with marking this document as signed? This will automatically forward it to the next designated signatory.', 'Confirm Signature', 'POST')">Approved</button>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $totalSpan }}" class="text-center">No Documents found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $docs->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('includes')
    @include('components.confirm')
@endsection

