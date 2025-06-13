<div class="modal fade" id="uploadDocumentModal">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Upload Signed Inspection Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="file" name="document" id="document" class="form-control @error('document', 'uploadDocument') is-invalid @enderror">
                @error('document', 'uploadDocument')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function(){
        @if($errors->uploadDocument->any())
            $('#uploadDocumentModal').modal('show');
        @endif
    })

    function showUploadInspectionReportModal(url) {
        $('#uploadDocumentModal form').attr('action', url);
        $('#uploadDocumentModal').modal('show');
    }
</script>