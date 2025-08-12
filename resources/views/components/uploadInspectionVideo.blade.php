
<div class="modal fade" id="uploadInspectionVideoModal">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" action="{{ session('forward_url') ?? '' }}">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Upload Inspection Video</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Inspection Video</label>
                    <input type="file" accept="video/mp4" class="form-control @error('video', 'uploadInspectionVideo') is-invalid @enderror" name="video" placeholder="Inspection Video" multiple required>
                    @error('video', 'uploadInspectionVideo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Upload</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        @if($errors->uploadInspectionVideo->any())
            $('#uploadInspectionVideoModal').modal('show'); 
        @endif
    })
    function showUploadInspectionVideoModal(url) {
        console.log("test");
        $('#uploadInspectionVideoModal form').attr("action", url);
        $('#uploadInspectionVideoModal').modal("show");
    }
</script>