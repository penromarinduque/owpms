<div class="modal fade" id="reviewApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('ltpapplication.reviewed', Crypt::encryptString($ltp_application->id)) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="title">Mark as Reviewed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please double check if all the required attachments are physically submitted and complete before accepting the application. This cannot be undone.</p>
                @foreach ($ltp_requirements as $req)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="req_{{ $req->id }}" name="req[]" value="{{ $req->id }}" >
                        <label class="form-check-label" for="req_{{ $req->id }}">
                            {{ $req->requirement_name }} 
                            @if ($req->is_mandatory)
                                <span class="text-danger">*</span>
                            @endif  
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-submit">Confirm</button>
            </div>
        </form>
    </div>
</div>