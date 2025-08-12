@extends('layouts.master')

@section('title')
Signatory
@endsection

@section('active-signatories')
active
@endsection


@section('content')
<div class="container-fluid">
    <h2 class="mt-4">Signatory</h2>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item "><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item "><a href="{{ route('signatories.index') }}">Signatory</a></li>
        <li class="breadcrumb-item active">Add Signatory</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus me-2"></i>Add Signatory Form</div>
        </div>
        <div class="card-body">
            <form action="{{ route('signatories.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6 col-md-4 mb-2">
                        <label for="document_type" class="form-label">Document Type <b class="text-danger">*</b></label>
                        <select name="document_type" id="document_type" class="form-select @error('document_type') is-invalid @enderror">
                            <option value="">-Select Document Type-</option>
                            @foreach ($documentTypes as $documentType)
                                <option value="{{ $documentType->id }}" {{ old('document_type') == $documentType->id ? 'selected' : ''}}>{{ $documentType->name }}</option>
                            @endforeach
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-md-4 mb-2">
                        <label for="signee" class="form-label">Signee <b class="text-danger">*</b></label>
                        <select name="signee" id="signee" class="form-select select2 @error('signee') is-invalid @enderror">
                            <option value="">-Select Signee-</option>
                        </select>
                        @error('signee')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-md-4 mb-2">
                        <label for="signatory_role" class="form-label">Signatory Role <b class="text-danger">*</b></label>
                        <select name="signatory_role" id="signatory_role" class="form-select @error('signatory_role') is-invalid @enderror">
                            <option value="">-Select Signatory Role-</option>
                            @foreach ($signatoryRoles as $signatoryRole)
                                <option value="{{ $signatoryRole->id }}" {{ old('signatory_role') == $signatoryRole->id ? 'selected' : ''}}>{{ $signatoryRole->role }}</option>
                            @endforeach
                        </select>
                        @error('signatory_role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- <div class="col-sm-6 col-md-4 mb-2">
                        <label for="order" class="form-label">Order <b class="text-danger">*</b></label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" name="order" id="order" placeholder="Order" value="{{ old('order') }}" min="1">
                        @error('order')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Save</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@section('script-extra')
    <script>
        $(function() {
            $('#signee.select2').select2({
                width: '100%',
                ajax: {
                    url: "{{ route('api.permittees.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            keyword: params.term,
                            type: 'query',
                            usertype: [
                                'admin', 'internal'
                            ]
                        }

                        return query;
                    },
                    processResults: function(data) {
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.first_name + ' ' + item.last_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                }
            });
        })
    </script>
@endsection