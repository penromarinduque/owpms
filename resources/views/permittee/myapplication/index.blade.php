@extends('layouts.master')

@section('title')
Permittees
@endsection

@section('active-myapplications')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{$title}}</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('myapplication.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> Create New</a>
            </div>
            <i class="fas fa-list me-1"></i>
            List of My Applications
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
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Draft</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Submitted</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Under Review</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Approved</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Rejected</a>
              </li>
            </ul>
            <table class="table table-sm" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Application Name</th>
                        <th>Date Created</th>
                        <th>Last Modified</th>
                        <th>Draft Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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

