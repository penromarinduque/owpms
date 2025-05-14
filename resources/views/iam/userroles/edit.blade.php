@extends('layouts.master')

@section('title')
Edit User Role
@endsection


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">User Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">User Access</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Roles</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit User Roles
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-primary" data-bs-target="#addRoleModal" data-bs-toggle="modal"><i class="fas fa-plus me-1"></i>Add Role</button>
            </div>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <th>Role</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @forelse ($userRoles as $userRole)
                        <tr>
                            <td>{{ $userRole->role->role_name }}</td>
                            <td>
                                <a href="#" onclick="showConfirDeleteModal('{{ route('iam.user_roles.destroy', ['id'=>Crypt::encryptString($userRole->id)]) }}', {{$userRole->id}}, 'This action is irreversible are you sure you want to remove this role from this user?', 'Remove Role')" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-title="Remove Role"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No record found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoleModal">
    <div class="modal-dialog">
        <form action="{{ route('iam.user_roles.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="title">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="role" class="form-label">Role</label>
                    <div class="">
                        <select name="role" id="role" class="form-control select2" ></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save</button>
            </div>
        </form>
    </div>
</div>

@include('components.confirmDelete')
@endsection

@section('script-extra')
    <script>
        $(function(){
            $('#role.select2').select2({
                dropdownParent: $('#addRoleModal'),
                width: "100%",
                ajax: {
                    url: "{{ route('api.userroles.search') }}",  // Your API endpoint
                    dataType: 'json',
                    data: function (params) {
                        // Query parameters will be ?search=[term]&type=public
                        return {
                            search: params.term,  // The search term entered by the user
                            type: 'public'        // Additional parameters
                        };
                    },
                    processResults: function(data) {
                        console.log(data);  // Verify the data structure here
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,     // Adjust the response field names
                                    text: item.role_name   // Adjust the response field names
                                };
                            })
                        };
                    }
                }
            });
        })
    </script>
@endsection




