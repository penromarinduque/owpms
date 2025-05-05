
<div class="">
    <div class="accordion" id="accordionLogs">
        @foreach ($logs as $key => $log)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $key }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <div class="badge text-bg-{{ $_helper->setApplicationStatusBsColor($log->status) }}" >
                                    {{ strtoupper($log->status) }}
                                </div>
                                {{ $log->created_at->format('F d, Y') }}</div>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionLogs">
                    <div class="accordion-body">
                        <p>
                            User: 
                            @if ($log->user->personalInfo)
                                {{ $log->user->personalInfo->first_name }} {{ $log->user->personalInfo->middle_name }} {{ $log->user->personalInfo->last_name }} 
                            @else
                                {{ $log->user->email }}
                            @endif
                        </p>
                        <p>Remarks: {!! $log->remarks !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>