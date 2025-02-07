@extends('layouts.master')

@section('title')
Users
@endsection

@section('active-users')
active
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Users</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <div class="float-end">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-user-plus"></i> Add New</a>
            </div>
            <i class="fas fa-users me-1"></i>
            List of Users
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ strtoupper($user->first_name.' '.$user->last_name) }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="chkActiveStat{{$user->id}}" onclick="ajaxUpdateStatus('chkActiveStat{{$user->id}}', '{{Crypt::encrypt($user->id)}}');" {{ ($user->is_active_user==1) ? 'checked' : '' }}>
                              <!-- <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label> -->
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('users.edit', ['id'=>Crypt::encrypt($user->id)]) }}" title="Edit" alt="Edit"><i class="fas fa-edit fa-lg"></i></a>
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
    function ajaxUpdateStatus(chkbox_id, user_id) {
        var chkd = $('#'+chkbox_id).is(':checked');
        var stat = 0;
        if (chkd) {
            stat = 1;
        }
        // console.log(stat);
        // $.ajax({
        //     type: 'POST',
        //     url: "{{ route('users.ajaxupdatestatus') }}",
        //     data: {user_id:user_id, is_active_user:stat},
        //     success: function (result){
        //         // console.log(result);
        //     },
        //     error: function (result){
        //         console.log(result);
        //         alert('Oops! Something went wrong. Please reload the page and try again.');
        //     }
        // });
    }
</script>
@endsection