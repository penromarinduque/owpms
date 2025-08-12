<div class="modal fade" id="submitApplicationModal">
    <div class="modal-dialog">
        <form action="{{ session('forward_url') ? session('forward_url') : '' }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Submit Application</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit this application? This action cannot be undone</p>

                <div class="mb-2">
                    <label for="document" class="form-label">Signed Request Letter Generated from the system <span class="text-danger">*</span></label>
                    <input type="file" accept="application/pdf" name="document" id="document" class="form-control @error('document', 'submitApplication') is-invalid @enderror" required>
                    @error('record', 'submitApplication')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
            </div>
        </form >
    </div>
</div>

<script>
    $(function(){
        @if($errors->submitApplication->any())
            $('#submitApplicationModal').modal('show');
        @endif
    })
    function showSubmitApplicationModal(action){
        $('#submitApplicationModal form').attr('action', action);
        $('#submitApplicationModal').modal('show');
    }
</script>