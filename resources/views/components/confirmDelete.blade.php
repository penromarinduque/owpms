
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" method="POST" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="id" >
                <p id="message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Continue</button>
            </div>
        </div>
    </form>
</div>

<script>
    function showConfirDeleteModal (url ,id, message, title) {
        $('#confirmDeleteModal input[name=id]').val(id);
        $('#confirmDeleteModal #message').html(message);
        $('#confirmDeleteModal #title').html(title);
        $('#confirmDeleteModal form').attr("action", url);
        $('#confirmDeleteModal').modal("show");
    }
</script>