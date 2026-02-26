@extends('layouts.master')

@section('title')
    Nature of Species
@endsection

@section('active-specie-natures')
active
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Nature of Species</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('natureofspecies.index') }}">Nature of Species</a></li>
            <li class="breadcrumb-item active">List</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center d-flex">
                    <div class="flex-grow-1">
                        List of Nature of Species
                    </div>
                    <div class="flex-shrink-0">
                        @can('create', App\Models\SpecieNature::class)
                            <a class="btn btn-sm btn-primary float-end" href="{{ route('natureofspecies.create') }}">Add New</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($specie_natures as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('natureofspecies.edit', $item->id) }}"><i class="fa fa-edit"></i></a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="showConfirDeleteModal('{{ route('natureofspecies.destroy', $item->id) }}', {{ $item->id }}, 'Are you sure you want to delete this nature of species?', 'Delete Nature of Species')"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('includes')
    @include('components.confirmDelete')
@endsection