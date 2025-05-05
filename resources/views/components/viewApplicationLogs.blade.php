<div class="modal fade" id="viewApplicationLogsModal" tabindex="-1" aria-labelledby="returnApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">LTP Application Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showViewApplicationLogsModal(application_id) {
        $('#viewApplicationLogsModal #content').html("No Logs Found");
        $.ajax({
            method: "GET",
            url: "{{ route('ltpapplication.renderLogs') }}",
            data: {
                application_id: application_id
            },
            success: function (response) {
                $('#viewApplicationLogsModal #content').html(response);
            }
        });
        $('#viewApplicationLogsModal').modal('show');
    }
</script>

