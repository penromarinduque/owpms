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
            <li class="breadcrumb-item active">Create</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('natureofspecies.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection