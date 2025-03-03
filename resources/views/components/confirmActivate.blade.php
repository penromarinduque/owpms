
<div class="modal fade" id="confirmActivateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" method="POST" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p id="message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Continue</button>
            </div>
        </div>
    </form>
</div>

<script>
    function showConfirActivateModal (url ,id, message, title) {
        $('#confirmActivateModal input[name=id]').val(id);
        $('#confirmActivateModal #message').html(message);
        $('#confirmActivateModal #title').html(title);
        $('#confirmActivateModal form').attr("action", url);
        $('#confirmActivateModal').modal("show");
    }
</script>