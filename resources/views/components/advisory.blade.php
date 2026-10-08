<div class="modal fade" id="advisoryModal" tabindex="-1" role="dialog" aria-labelledby="advisoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="advisoryModalLabel">Advisory Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="height: 75vh;">
                <!-- Embedded PDF with hidden toolbar controls -->
                <iframe 
                    src="{{ asset('system_advisory.pdf') }}#toolbar=0&navpanes=0&scrollbar=0" 
                    width="100%" 
                    height="100%" 
                    style="border: none;">
                    <p>Your browser does not support embedded PDFs. 
                       <a href="{{ asset('system_advisory.pdf') }}">Click here to view the PDF.</a>
                    </p>
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 4 JavaScript (jQuery requirement) -->
<script defer >
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery is loaded before calling it
        if (window.jQuery) {
            $('#advisoryModal').modal({
                keyboard: true,
                show: true
            });
        }
    });
</script>