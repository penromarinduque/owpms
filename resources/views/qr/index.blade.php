@extends('qr.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Main Wrapper Card --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                
                {{-- Header Banner --}}
                <div class="card-header bg-gradient bg-primary bg-opacity-10 border-0 p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bold text-dark mb-0">LTP Application</h3>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 small">
                                    Permit Details
                                </span>
                            </div>
                            <p class="text-secondary small mb-0 mt-1">Local Transport Permit status tracking and audit history</p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }} shadow-sm px-3 py-2 fs-6 rounded-pill text-capitalize">
                                <i class="bi bi-circle-fill me-1 small opacity-75"></i>
                                {{ $_helper->formatApplicationStatus($ltp_application->application_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if ($permitUrl)
                <div class="card-body p-0">
                    <div class="w-100 overflow-hidden shadow-sm" style="height: 600px;">
                        <iframe 
                            src="{{ $permitUrl }}#toolbar=0&navpanes=0&scrollbar=0" 
                            class="w-100 h-100 border-0" 
                            type="application/pdf">
                            <p class="p-3 text-muted">Your browser does not support inline PDFs. 
                                <a href="{{ $permitUrl }}" target="_blank">Download PDF</a>
                            </p>
                        </iframe>
                    </div>
                </div>
                @endif

                <div class="card-body p-4">
                    
                    {{-- Key Details Grid --}}
                    <div class="mb-5">
                        <h6 class="text-uppercase text-muted fw-bold small tracking-wide mb-3">
                            <i class="bi bi-info-circle me-1"></i> Application Overview
                        </h6>
                        
                        <div class="row g-3">
                            {{-- Permittee --}}
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border border-light-subtle h-100">
                                    <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem;">Permittee</span>
                                    <span class="fw-bold text-dark fs-6">
                                        {{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border border-light-subtle h-100">
                                    <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem;">Status</span>
                                    <div>
                                        <span class="badge bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }} bg-opacity-10 text-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }} fw-bold px-2.5 py-1.5 rounded-2">
                                            {{ $_helper->formatApplicationStatus($ltp_application->application_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Expiration Date --}}
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border border-light-subtle h-100">
                                    <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem;">Expiration Date</span>
                                    <span class="fw-bold text-dark fs-6">
                                        {{ $ltp_application->transport_date->format('F d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Activity Timeline Section --}}
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small tracking-wide mb-4">
                            <i class="bi bi-clock-history me-1"></i> Application Timeline
                        </h6>

                        <div class="bg-body-tertiary rounded-4 p-3 p-md-4 border border-light-subtle">
                            @if(class_exists('\Bladewind\Components\Timeline'))
                                {{-- Bladewind Fallback Component --}}
                                <x-bladewind::timelines position="left" stacked="true">
                                    @foreach ($logs as $key => $log)
                                        <x-bladewind::timeline
                                            completed="true"
                                            date="{{ $log->created_at->format('F d, Y h:i A') }}"
                                            content="{!! $_helper->formatTimelineDescription($log) !!}"
                                            last="{{ $loop->last ? 'true' : 'false' }}" />
                                    @endforeach
                                </x-bladewind::timelines>
                            @else
                                {{-- Custom Modern Timeline Markup --}}
                                <div class="timeline-container ps-2 ps-md-3">
                                    @foreach ($logs as $key => $log)
                                        <div class="position-relative ps-4 pb-4 {{ $loop->last ? '' : 'border-start border-2 border-primary border-opacity-25' }}">
                                            {{-- Indicator Dot --}}
                                            <div class="position-absolute top-0 start-0 translate-middle-x bg-primary rounded-circle ring-4 ring-white" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                                            
                                            {{-- Log Item Content --}}
                                            <div class="bg-white p-3 rounded-3 shadow-sm border border-light-subtle ms-2">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                                    <span class="small text-muted fw-semibold">
                                                        <i class="bi bi-calendar3 me-1"></i> {{ $log->created_at->format('M d, Y • h:i A') }}
                                                    </span>
                                                </div>
                                                <div class="text-dark small lh-base">
                                                    {!! $_helper->formatTimelineDescription($log) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection