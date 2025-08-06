<div class="modal fade" id="updatePaymentModal">
    <div class="modal-dialog">
        <form method="POST" action="" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h4 class="modal-title">Update Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status', 'updatePayment') is-invalid @enderror">
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status', 'updatePayment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">OR No.</label>
                    <input type="text" class="form-control @error('or_no',  'updatePayment') is-invalid @enderror" name="or_no" placeholder="OR No." value="{{ old('or_no')  }}">
                    @error('or_no', 'updatePayment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Serial No.</label>
                    <input type="text" class="form-control @error('serial_no',  'updatePayment') is-invalid @enderror" name="serial_no" placeholder="Serial No." value="{{ old('serial_no')  }}">
                    @error('serial_no', 'updatePayment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Receipt</label>
                    <input type="file" id="receipt" accept="image/jpeg, image/png" class="form-control @error('receipt',  'updatePayment') is-invalid @enderror" name="receipt" placeholder="Receipt" >
                    @error('receipt', 'updatePayment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" defer>
    $(function(){
        @if ($errors->updatePayment->any())
            $("#updatePaymentModal").modal("show");
        @endif
    });

    function showUpdatePaymentModal (url) {
        $('#updatePaymentModal form').attr("action", url);
        $('#updatePaymentModal').modal("show");
    }
</script>