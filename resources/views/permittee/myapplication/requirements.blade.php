@extends('layouts.master')

@section('title')
Application Requirements
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{$title}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Requirements</a></li>
        <li class="breadcrumb-item active">{{$title}}</li>
    </ol>
    <div class="card mb-4">
    	<div class="card-header">
            <i class="fas fa-list me-1"></i>
            List of My Requirements
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
            <br>
            <div class="table-responsive">
                <table class="table table-sm table-hover" >
                    <thead>
                        <tr>
                            <th>Requirements Name</th>
                            <th>Mandatory</th>
                            <th>Uploaded Document</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requirements as $req)
                            <tr>
                                <td>{{ $req->requirement_name }}</td>
                                <td>{{ $req->is_mandatory ? 'YES' : 'NO' }}</td>
                                <td>
                                    @php
                                        $attachment = $attachments->firstWhere('ltp_requirement_id', $req->id);
                                    @endphp
                                    @if ($attachment)
                                        <a href="{{ asset('storage/'.$attachment->file_path) }}" target="_blank"><i class="fas fa-eye"></i> View Attachment</a>
                                    @else
                                        <span class="text-secondary">No Uploaded Attachment</span> 
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="Upload Requirement" onclick="showUploadModal('{{ $req->id }}')"><i class="fas fa-upload"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <form action="{{ route('myapplication.upload-requirement', Crypt::encryptString($id)) }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Upload Document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">  
                <input type="hidden" name="requirement_id">  
                <input type="hidden" name="application_id" value="{{$id}}">  
                <div class="mb-3">
                    <label class="form-label">Document File</label>
                    <input type="file" class="form-control" name="document_file" placeholder="Document File" required>
                </div>
            </div>
            <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script-extra')
<script>
    function showUploadModal(requirement_id) {
        $('input[name="requirement_id"]').val(requirement_id);
        $('#uploadModal').modal('show');
    }
</script>
@endsection