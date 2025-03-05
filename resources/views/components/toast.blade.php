<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="customToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Update failed! Please try again.
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- JavaScript to Show Toast -->
<script>
    $(function () {
        @if (session()->has('success'))
            showToast('success', '{{ session('success') }}');
        @elseif (session()->has('error'))
            showToast('danger', '{{ session('error') }}');
        @endif
    });

    function showToast(color, message) {
        var $toast = document.getElementById('customToast');
        if (!$toast) return; // Prevent errors if the toast element is missing

        var toastBody = $toast.querySelector('.toast-body');
        if (!toastBody) return; // Prevent errors if .toast-body is missing

        // Update toast styling and message
        $toast.className = 'toast align-items-center border-0 text-bg-' + color;
        toastBody.innerHTML = message;

        // Show the toast
        var toast = new bootstrap.Toast($toast);
        toast.show();
    }
</script>
