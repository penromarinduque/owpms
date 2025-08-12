<div class="modal fade" id="updatePaymentModal">
    <form method="POST" action="{{ route('paymentorder.update', Crypt::encryptString($paymentOrder->id)) }}" class="modal-dialog" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Document File</label>
                    <input type="file" accept="application/pdf" class="form-control @error('document_file', 'upload') is-invalid @enderror" name="document_file" placeholder="Document File" required>
                    @error('document_file', 'upload')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>  
                <button type="submit" class="btn btn-primary btn-submit">Upload</button>
            </div>
        </div>
    </form>
</div>

<script>
    function showUploadDocumentModal(encryptedId) {
        $('#updatePaymentModal form').attr('action', "{{ route('paymentorder.update', ':id') }}".replace(':id', encryptedId));
        $('#updatePaymentModal').modal('show');
    }
</script>