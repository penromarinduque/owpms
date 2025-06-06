<div class="modal fade" id="submitInspectionModal">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('inspection.submitProofs', Crypt::encryptString($ltp_application->id)) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="submitInspectionModalLabel">Submit Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit the inspection proofs?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Continue</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        @if($errors->submitProofs->any())
            $('#submitInspectionModal').modal('show'); 
        @endif
    })

    function showSubmitInspectionModal() {
        $('#submitInspectionModal').modal('show');
    }
</script>