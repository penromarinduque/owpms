@extends('layouts.master')

@section('title')
Permittee Species
@endsection

@section('active-permittees')
active
@endsection

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Permittee Species</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('permittees.index') }}">Permittees</a></li>
        <li class="breadcrumb-item active">Species</li>
    </ol>

    <div id="permitteeInfo" class="mb-2"></div>

    <div class="card">
        <div class="card-header">

            <div class="d-flex justify-content-end">
                <a class="btn btn-sm btn-primary" href="{{ route('permitteespecies.create') }}"><i class="fas fa-plus"></i> Add</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="50px" class="text-center">#</th>
                            <th>Species</th>
                            <th>Quantity</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $permittee_species }}
                        @forelse ($permittee_species as $key => $ps)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td >{{ $ps->specie->local_name }} ({{ $ps->specie->specie_name }})</td>
                                <td >{{ $ps->quantity }} pc/s</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger" onclick="showConfirDeleteModal('{{ route('permitteespecies.delete', Crypt::encryptString($ps->id)) }}', {{ $ps->id }}, 'Are you sure you want to delete this permittee species? ' + ' {{ $ps->specie->specie_name }}', 'Delete Permittee Species')">Delete</button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showUpdateQtyModal({{ $ps->id }}, {{ $ps->quantity }})">Update Quantity</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="4">No Records Found </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $permittee_species->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateQuantityModal">
    <form action="{{ route('permitteespecies.update') }}" method="POST" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Quantity</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script-extra')
<script>
    $(function(){
        getPermitteeInfo();
    });

    function showUpdateQtyModal(id, qty){
        console.log("test");
        $modal = $("#updateQuantityModal");
        $modal.find("input[name=id]").val(id);
        $modal.find("input[name=quantity]").val(qty);
        $modal.modal("show");
    }

    function getPermitteeInfo(){
        $.ajax({
            method: "GET",
            url: '{{ route("permittee.cardInfo", ["id" => Crypt::encryptString($permittee->id)]) }}',
            success: function(res){
                $('#permitteeInfo').html(res);
            }
        });
    }
</script>

@include('components.confirmDelete')
@endsection