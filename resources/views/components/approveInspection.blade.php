<div class="modal fade" id="approveInspectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">Approve Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this inspection? This action is irreversible</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Approve</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showApproveInspectionModal(url) {
        $('#approveInspectionModal form').attr('action', url);
        $('#approveInspectionModal').modal('show');
    }
</script>


