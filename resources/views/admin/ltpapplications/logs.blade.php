
<div class="">
    <div class="accordion" id="accordionLogs">
        @foreach ($logs as $key => $log)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $key }}">
                    <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $key }}"
                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                    aria-controls="collapse{{ $key }}">
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                {{ $log->created_at->diffForHumans() }} • {{ $log->created_at->format('F d, Y h:i A') }} </div>
                                <div class="badge text-bg-{{ $_helper->setApplicationStatusBsColor($log->status) }} me-2" >
                                    {{ strtoupper($_helper->formatApplicationStatus($log->status)) }}
                                </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $key }}"
                class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                aria-labelledby="heading{{ $key }}"
                data-bs-parent="#accordionLogs">
                    <div class="accordion-body">
                        <p>
                            <p class="mb-0 fw-semibold">Acted By:</p>
                            @if ($log->user->personalInfo)
                                {{ $log->user->personalInfo->first_name }} {{ $log->user->personalInfo->middle_name }} {{ $log->user->personalInfo->last_name }} 
                            @else
                                {{ $log->user->email }}
                            @endif
                        </p>
                        <p class="mb-0 fw-semibold">Remarks:</p>
                        <div class="p-2 bg-light rounded">
                            @if (!in_array($log->remarks, ['', null, ' ', 'N/A', '<></>', '<p><br></p>']))
                                {!! $log->remarks !!}
                            @else
                                <p class="text-center text-muted my-2">No Remarks</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>