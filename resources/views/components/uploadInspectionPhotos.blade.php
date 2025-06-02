
<div class="modal fade" id="uploadInspectionPhotosModal">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" action="{{ session('forward_url') ?? '' }}">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Upload Inspection Photos</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Inspection Photos</label>
                    <input type="file" accept="image/jpeg, image/png" class="form-control @error('photos', 'uploadInspectionPhotos') is-invalid @enderror @error('photos.*', 'uploadInspectionPhotos') is-invalid @enderror" name="photos[]" placeholder="Inspection Photos" multiple required>
                    @error('photos', 'uploadInspectionPhotos')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('photos.*', 'uploadInspectionPhotos')
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
        @if($errors->uploadInspectionPhotos->any())
            $('#uploadInspectionPhotosModal').modal('show'); 
        @endif
    })
    function showUploadInspectionPhotosModal(url) {
        console.log("test");
        $('#uploadInspectionPhotosModal form').attr("action", url);
        $('#uploadInspectionPhotosModal').modal("show");
    }
</script>