@extends('layouts.master')

@section('title')
Permittees
@endsection

@section('active-permittees')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Permittees</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Permittees</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('permittees.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-user-plus"></i> Add New</a>
            </div>
            <i class="fas fa-users me-1"></i>
            List of Permittees
        </div>
        <div class="card-body">
            @if(session('failed'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('failed') }}</strong>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
            <table class="table table-sm" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Permittee</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact No</th>
                        <th>Email</th>
                        <th>WFP/WCP Permit Numbers</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($permittees as $permittee)
                    <tr>
                        <td>{{ strtoupper($permittee->last_name.', '.$permittee->first_name.' '.$permittee->middle_name) }}</td>
                        <td>{{ strtoupper($permittee->gender) }}</td>
                        <td>{{ $permittee->barangay_name.', '.$permittee->municipality_name.', '.$permittee->province_name }}</td>
                        <td>{{ $permittee->contact_no }}</td>
                        <td>{{ $permittee->email }}</td>
                        <td>
                        @if(!empty($permittee->wildlifePermits))
                            @foreach($permittee->wildlifePermits as $wildlifepermit)
                            <a href="{{route('permittees.show', [$wildlifepermit->id])}}" title="View Deatils">{{ strtoupper($wildlifepermit->permit_number) }}</a><br>
                            @endforeach
                        @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="chkActiveStat{{$permittee->id}}" onclick="ajaxUpdateStatus(event,'chkActiveStat{{$permittee->id}}', '{{Crypt::encrypt($permittee->id)}}');" {{ ($permittee->is_active_user==1) ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('permittees.edit', ['id'=>Crypt::encrypt($permittee->id)]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script-extra')
<script type="text/javascript">

    function ajaxUpdateStatus(e, chkbox_id, permittee_id) {
        var chkd = $('#'+chkbox_id).is(':checked');
        var stat = 0;
        if (chkd) {
            stat = 1;
        }

        $.ajax({
            type: 'POST',
            url: "{{ route('permittees.ajaxupdatestatus') }}",
            data: {permittee_id:permittee_id, is_active_permittee:stat},
            success: function (result){
                console.log("success" , result);
                showToast("primary", "Permittee Status Updated");
            },
            error: function (result){
                console.log("failed", result);
                showToast("danger", "Oops! Something went wrong. Please reload the page and try again.");
                $(`#${chkbox_id}`).prop('checked', !chkd);
            }
        });
    }
</script>
@endsection

@include('components.confirmActivate');