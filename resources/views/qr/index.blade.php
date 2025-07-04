@extends('qr.master')

@section('content')
<div class="container ">
    <div class="card">
        <div class="card-body">
            <h5>LTP Application</h5>

            <h6>Details</h6>
            <div class="bg-light rounded-2 p-3 mb-3">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <p class="mb-0 fw-bold">Permittee</p>
                        <p class="mb-0">{{ $ltp_application->permittee->user->personalInfo->getFullNameAttribute() }}</p>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <p class="mb-0 fw-bold">Application Status</p>
                        <span class="badge  bg-{{ $_helper->setApplicationStatusBgColor($ltp_application->application_status) }}">{{ $_helper->formatApplicationStatus($ltp_application->application_status) }}</span>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <p class="mb-0 fw-bold">Expiration</p>
                        <p class="mb-0">{{ $ltp_application->transport_date->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-light rounded-2 p-3">
                <x-bladewind::timelines position="left" stacked="true">
                    @foreach ($logs as $key => $log)
                        <x-bladewind::timeline
                            completed="true"
                            date="{{ $log->created_at->format('F d, Y h:i A') }}"
                            content="{!! $_helper->formatTimelineDescription($log) !!}"
                            {{-- content="{{ $log->description }}" --}}
                            last="{{ $key == count($logs) - 1 ? true : false }}" />
                    @endforeach

                    {{-- <x-bladewind::timeline
                        completed="true"
                        date="just now"
                        content="database server restarted" />

                    <x-bladewind::timeline date="30 minutes ago">
                        <x-slot:content>
                            <a>2 endpoints</a> are failing on bladewindui-data EC2
                            bucket. You may want to login and check the logs
                        </x-slot:content>
                    </x-bladewind::timeline>

                    <x-bladewind::timeline date="1 hour ago">
                        <x-slot:content>
                            There have been 200 failed log in attempts from
                            <a>mike@bladewindui.com</a>. Possibly a DDos attack
                            attempt. Secure the server.
                        </x-slot:content>
                    </x-bladewind::timeline>

                    <x-bladewind::timeline date="Yesterday"
                        content="Data recovery completed with 2 errors" /> --}}

                </x-bladewind::timelines>
            </div>
        </div>
    </div>
</div>
@endsection