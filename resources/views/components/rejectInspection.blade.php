<div class="modal fade" id="rejectInspectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">Reject Inspection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Remarks</p>
                <input type="hidden" name="remarks" id="remarks">
                <div id="remarks-quill"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectInspectionModal(url) {
        $('#rejectInspectionModal form').attr('action', url);
        $('#rejectInspectionModal').modal('show');
    }
</script>

<script>
    const quill = new Quill('#remarks-quill', {
      theme: 'snow',
      placeholder: 'Write your remarks here',
    });

    const form = document.querySelector('#rejectInspectionModal form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        document.querySelector('#remarks').value = quill.root.innerHTML;
        form.submit();
    });
</script>


