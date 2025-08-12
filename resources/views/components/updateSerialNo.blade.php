<div class="modal fade" id="updateSerialNoModal">
    <div class="modal-dialog">
        <form method="POST" action="" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h4 class="modal-title">Update Serial No.</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Serial No.</label>
                    <input type="text" class="form-control @error('serial_no',  'updateSerialNo') is-invalid @enderror" name="serial_no" placeholder="Serial No." value="{{ old('serial_no')  }}">
                    @error('serial_no', 'updateSerialNo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" defer>
    $(function(){
        @if ($errors->updateSerialNo->any())
            $("#updateSerialNoModal").modal("show");
        @endif
    });

    function showUpdateSerialNoModal (url) {
        $('#updateSerialNoModal form').attr("action", url);
        $('#updateSerialNoModal').modal("show");
    }
</script>