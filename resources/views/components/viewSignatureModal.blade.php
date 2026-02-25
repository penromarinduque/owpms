
<div class="modal fade" id="viewSignatureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img width="100%" src="{{ route('account.viewSignature', Crypt::encryptString(auth()->user()->id))) }}" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
function showViewSignatureModal () {
    $('#viewSignatureModal').modal("show");
}
</script>