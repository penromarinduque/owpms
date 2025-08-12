
<div class="modal fade" id="viewApplicationLogsModal" tabindex="-1" aria-labelledby="returnApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnApplicationModalLabel">LTP Application Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="bladewind_styles"></div>
                <div id="content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('#viewApplicationLogsModal').on('show.bs.modal', function () {
            // Load Bladewind styles dynamically
            $('#bladewind_styles').html(`
                <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
                <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
            `);
        });
        $('#viewApplicationLogsModal').on('hidden.bs.modal', function () {
            // Clear the content when the modal is closed
            $('#content').html('');
            $('#bladewind_styles').html('');
        });
    });
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

