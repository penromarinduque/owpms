@extends('layouts.master')

@section('title')
Permit Details
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{strtoupper($permit_type)}} Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Permittees</li>
        <li class="breadcrumb-item active">Permit Details</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('permittees.index') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <i class="fas fa-file-text me-1"></i>
            Permit Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th >Permit Number</th>
                            <td>{{ $permittee->permit_number }}</td>
                        </tr>
                        <tr>
                            <th >Permit Type</th>
                            <td>{{ strtoupper($permit_type) }}</td>
                        </tr>
                        <tr>
                            <th >Validity</th>
                            <td>{{ Carbon\Carbon::parse($permittee->valid_from)->format('F d, Y') }} - {{ Carbon\Carbon::parse($permittee->valid_to)->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th >Date Issued</th>
                            <td>{{ Carbon\Carbon::parse($permittee->date_of_issue)->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th >Status</th>
                            @php
                                $status_color = [
                                    "valid" => "success",
                                    "expired" => "danger",
                                    "pending" => "secondary"
                        ];
                            @endphp
                            <td><span class="badge bg-{{ $status_color[$permittee->status] }}">{{ ucfirst($permittee->status) }}</span></td>
                        </tr>
                        <tr>
                            <th >Permit File</th>
                            <td>
                                <a href="{{ Storage::url($permittee->document) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                                <a href="#" data-bs-target="#uploadPermitModal" data-bs-toggle="modal" class="btn btn-sm btn-success" >Upload</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadPermitModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('permittees.uploadpermit', Crypt::encryptString($permittee->id)) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Upload Permit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Permit File</label>
                        <input type="file" accept="application/pdf" class="form-control" name="permit_file" placeholder="Permit File" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>  
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">
    // function showDetails(id, show_to) {
    //     $(this).ajaxRequestLaravel({
    //         show_result: ['/permittees/show/'+id, show_to],
    //         show_result_loader: true,
    //     });
    // }

    function ajaxUpdateStatus(chkbox_id, permittee_id) {
        var chkd = $('#'+chkbox_id).is(':checked');
        var stat = 0;
        if (chkd) {
            stat = 1;
        }
        // console.log(stat);
        $.ajax({
            type: 'POST',
            url: "{{ route('permittees.ajaxupdatestatus') }}",
            data: {permittee_id:permittee_id, is_active_permittee:stat},
            success: function (result){
                // console.log(result);
            },
            error: function (result){
                // console.log(result);
                alert('Oops! Something went wrong. Please reload the page and try again.');
            }
        });
    }
</script>
@endsection