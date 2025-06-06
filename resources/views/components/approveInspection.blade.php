<div class="modal fade" id="approveInspectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">Approve Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    By approving this inspection the system will automatically generate the following unsigned documents:
                    <ul>
                        <li>Wildlife Inspection Report</li>
                        {{-- <li>Local Transport Permit (LTP) and other relevant permits</li> --}}
                    </ul>
                    <p>Note that you can always change the signatories of the Wildlife Inspection Report based on the availability of the in-charges.</p>
                    <p class="text-danger">Please ensure that all the submitted documents are correct and complete before approving the inspection.</p>
                </p>
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


