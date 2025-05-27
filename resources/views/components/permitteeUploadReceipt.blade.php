<div class="modal fade" id="uploadReceiptModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ session('forward_url') }}" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h4 class="modal-title">Upload Receipt</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Receipt File</label>
                    <input type="file" accept="image/jpeg, image/png" class="form-control @error('receipt_file', 'uploadReceipt') is-invalid @enderror" name="receipt_file" placeholder="Receipt File" required>
                    @error('receipt_file', 'uploadReceipt')
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

<script type="text/javascript" defer>
    $(document).ready(function() {
        @if($errors->uploadReceipt->any())
            $('#uploadReceiptModal').modal('show'); 
        @endif
    });

    function showUploadReceiptModal(url) {
        $('#uploadReceiptModal form').attr('action', url);
        $('#uploadReceiptModal').modal('show');
    }
</script>