<div class="modal  fade" id="returnApplicationModal" tabindex="-1" aria-labelledby="returnApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('ltpapplication.return') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">Return Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Please provide your remarks if you are returning this application. This will help clients identify areas for improvement.</p>
                <input type="hidden" name="remarks" id="remarks">
                <div id="remarks-quill"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Return</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showReturnApplicationModal(ltp_application) {
        $('#returnApplicationModal #id').val(ltp_application.id);
        $('#returnApplicationModal').modal('show');
    }
</script>

<script>
    const quill = new Quill('#remarks-quill', {
      theme: 'snow',
      placeholder: 'Write your remarks here',
    });

    const form = document.querySelector('#returnApplicationModal form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        document.querySelector('#remarks').value = quill.root.innerHTML;
        form.submit();
    });
</script>


