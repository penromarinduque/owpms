<div class="modal fade" id="submitApplicationModal">
    <div class="modal-dialog">
        <form action="{{ session('forward_url') ? session('forward_url') : '' }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Submit Application</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="message"></p>
                {{-- <input type="hidden" name="attach_signature_check" id="attach_signature_check">
                <div class="mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="attach_signature" id="attach_signature" required onchange="onAttachSignatureChanged()">
                        <label class="form-check-label" for="attach_signature" >
                            Attach electronic signature and submit.
                        </label>
                    </div>
                    @error('attach_signature', 'submitApplication')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-2" id="document_container">
                    <label for="document" class="form-label">Signed Request Letter Generated from the system <span class="text-danger">*</span></label>
                    <input type="file" accept="application/pdf" name="document" id="document" class="form-control @error('document', 'submitApplication') is-invalid @enderror" required>
                    @error('document', 'submitApplication')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div> --}}
                {{-- <p>
                    By checking the box below and proceeding with the application, I acknowledge that I have read, understood, and agree to comply with the foregoing Terms and Conditions of Use of the OWPMS. I certify that the information and documents submitted through the system are true, complete, accurate, and authentic, and that I understand that the submission of an application does not constitute approval or issuance of a Local Transport Permit.
                </p>
                <div class="d-flex align-items-center justify-content-between mt-1 mb-0">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">
                            I HAVE READ, UNDERSTOOD, AND AGREE TO .
                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-decoration-none fw-semibold">
                                THE TERMS AND CONDITIONS
                            </a>
                            OF USE OF THE OWPMS
                        </label>
                    </div>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
            </div>
        </form >
    </div>
</div>

<script>
    $(function(){
        @if($errors->submitApplication->any())
            $('#submitApplicationModal').modal('show');
        @endif
        onAttachSignatureChanged();
    })
    function showSubmitApplicationModal(action, resubmit = false){
        $('#submitApplicationModal form').attr('action', action);
        $('#submitApplicationModal .modal-title').html(resubmit ? 'Resubmit Application' : 'Submit Application');
        $('#submitApplicationModal #message').html(resubmit ? 'Are you sure you want to resubmit this application? This action cannot be undone' : 'Are you sure you want to submit this application? This action cannot be undone');
        $('#submitApplicationModal .btn-submit').html(resubmit ? 'Resubmit' : 'Submit');
        $('#submitApplicationModal').modal('show');
    }
    function onAttachSignatureChanged() {
        $chkBox = $('#attach_signature');
        if($chkBox.is(':checked')) {
            $('#document').prop('required', true);
            $('#attach_signature_check').val(1);
            $('#document_container').hide();
        }
        else {
            $('#document').prop('required', false);
            $('#attach_signature_check').val(0);
            $('#document_container').show();
        }
    }
</script>