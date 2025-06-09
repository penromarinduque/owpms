@extends('layouts.master')

@section('title')
Inspection Report
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inspection Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Inspection Report</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-pdf me-1"></i>
            Inspection Report Template
        </div>
        <div class="card-body">
            <form class="row bg-light py-4 align-content-center justify-content-center" method="POST" action="{{ route('inspection.update',['ltp_application_id' => Crypt::encryptString($ltp_application->id) , 'id' => Crypt::encryptString($inspection_report->id)]) }}" enctype="multipart/form-data">
                @csrf
                <div class="col-8 bg-white p-5">

                    {{-- SELECT INSPECTION DATE --}}
                    <div class="modal fade" id="selectInspectionDateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Select Inspection Date</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <input name="inspection_date" onchange="inspectionDateChanged(event)" max="{{ date('Y-m-d') }}" type="date" name="inspection_date" id="inspection_date" value="{{ old('inspection_date') ?? $inspection_report->inspection_date->format('Y-m-d') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-center">INSPECTION REPORT OF WILDLIFE</h3><br>
        
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
                                            <td class="border-0 pb-0">{{ $ltp_specie->specie->specie_name }}</td>
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

                    <h5 class="text-center">CERTIFICATE OF CONCURRENCE</h5>

                    <p class="text-justify">
                        &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the inspection report of 
                         <span class="fw-bold"><u>&nbsp;&nbsp;{{ auth()->user()->personalInfo->getFullNameAttribute() }}&nbsp;&nbsp;</u></span>
                         of PENRO Marinduque, Region <span class="fw-bold"><u>&nbsp;&nbsp;IV-MIMAROPA&nbsp;&nbsp;</u></span>
                         is/are true and correct and has been done this 
                         <span id="inspection_date_text"><span class="fw-bold"><u>&nbsp;&nbsp;{{ old('inspection_date') ? \Carbon\Carbon::parse(old('inspection_date'))->format('jS') : $inspection_report->inspection_date->format('jS') }}&nbsp;&nbsp;</u></span>
                         day of <span class="fw-bold" ><u>&nbsp;&nbsp;{{ now()->format('F Y') }}&nbsp;&nbsp;</u></span></span>. 
                        <button type="button" class="btn btn-sm btn-outline-secondary p-1 py-0" data-bs-target="#selectInspectionDateModal" data-bs-toggle="modal"><i class="fa fa-calendar-days "></i></button>
                    </p>

                    <p>&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that this statement was given to me voluntarily and with neither coercion nor promise of reward from the personnel of the Department of Environment and Natural Resources.</p>

                    <br><br>

                    <div class="d-flex align-content-end justify-content-end">
                        <div class="" style="min-width: 200px">
                            <p class="text-center mb-0 mb-1">
                                <select class="form-select select2 @error('approver') is-invalid @enderror" name="approver" id="approver" onchange="changeApprover(event)">
                                    <option value="">-Select Approver-</option>
                                    @foreach($_user->getAllInternals() as $internal)
                                        <option value="{{ $internal->id }}" data-position-id="{{ $internal->position }}" {{ old('approver') ? old('approver') == $internal->id ? 'selected' : '' : ($internal->id == $inspection_report->approver_id ? 'selected' : '') }}>{{ $internal->personalInfo->getFullNameAttribute() }}</option>
                                    @endforeach
                                </select>
                                @error('approver')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </p>
                            <hr class="my-0 border-dark mb-1">
                            <p class="text-center">
                                <input type="text" id="approver_position" name="approver_position" value="{{ old('approver_position') ?? $inspection_report->approver_position }}" class="form-control text-center @error('approver_position') is-invalid @enderror" placeholder="Designation">
                                @error('approver_position')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </p>
                        </div>
                    </div>

                </div>
                <div class="d-flex justify-content-end gap-1">
                    @can('view', $inspection_report)
                        <a href="{{ route('inspection.print', ['id' => Crypt::encryptString($inspection_report->id), 'ltp_application_id' => Crypt::encryptString($inspection_report->ltp_application_id)]) }}" target="_blank" type="button" class="btn btn-outline-primary btn-submit"><i class="fas fa-print me-2"></i>Print</a>
                    @endcan
                    @can('update', $inspection_report)
                        <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save Changes</button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script-extra')
<script>
    const positions = JSON.parse('{!! json_encode($_position->getAllPositions()) !!}');
    $(function(){
        console.log(positions);
        $('.select2').select2();
    })

    function changeApprover(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const positionId = selectedOption.dataset.positionId;

        console.log("Position ID from data attribute:", positionId);
        const position = positions.find(x => x.id == positionId);
        $("#approver_position").val(position ? position.position : ''); 
    }

    function inspectionDateChanged(ev) {
        console.log(ev.target.value);
        const date = new Date(ev.target.value);
        const ordinal = getOrdinal(date.getDate());
        const month = date.toLocaleString('default', { month: 'long' });
        const year = date.getFullYear();
        $("#inspection_date_text").html(` <span class="fw-bold"><u>&nbsp;&nbsp;${ordinal}&nbsp;&nbsp;</u></span> day of <span class="fw-bold" ><u>&nbsp;&nbsp;${month} ${year}&nbsp;&nbsp;</u></span>`);
    }

    function getOrdinal(number) {
        const suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        
        if (number % 100 >= 11 && number % 100 <= 13) {
            return number + 'th';
        } else {
            return number + suffixes[number % 10];
        }
    }

</script>   
@endsection