
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" method="POST" class="modal-dialog">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Continue</button>
            </div>
        </div>
    </form>
</div>

<script>
    function showConfirmModal (url, message, title, method="POST") {
        $('#confirmModal #message').html(message);
        $('#confirmModal #title').html(title);
        $('#confirmModal form').attr("action", url);
        $('#confirmModal form').attr("method", method);
        $('#confirmModal').modal("show");
    }
</script>