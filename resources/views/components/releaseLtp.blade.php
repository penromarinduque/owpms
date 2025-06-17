<div class="modal  fade" id="releaseLtpModal" tabindex="-1" aria-labelledby="returnApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ session('forward_url') ? session('forward_url') : '' }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">Release LTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($errors->releaseLtp as $error)
                    <span>{{ $error }} - </span>
                @endforeach
                <div class="mb-2">
                    <label for="ltp" class="form-label">Signed & Dry Sealed LTP Document <span class="text-danger">*</span></label>
                    <input type="file" accept="application/pdf" class="form-control @error('ltp') is-invalid @enderror" name="ltp" id="ltp">
                    @error('ltp', 'releaseLtp')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Release</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function(){
        @if($errors->releaseLtp->any())
            $('#releaseLtpModal').modal('show');
        @endif
    })

    function showReleaseLtpModal(url) {
        $('#releaseLtpModal form').attr('action', url);
        $('#releaseLtpModal').modal('show');
    }
</script>



