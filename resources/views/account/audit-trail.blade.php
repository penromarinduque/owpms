@extends('layouts.master')

@section('title')
Activity Logs
@endsection

@section('content')
<div class="container px-4">
    <h2 class="mt-4">Activity Logs</h2>

    <div class="bg-light p-4 rounded">
        @forelse ($logs as $log)
            <div class="card mb-2">
                <div class="card-body">
                    <p class="mb-1">User : <span class="text-muted">{{ $log->user->personalInfo->getFullNameAttribute() }}</span></p>
                    <p class="mb-1">Action : <span class="text-muted">{{ $log->event }}</span></p>
                    <p class="mb-1">Model : <span class="text-muted">{{ $log->auditable_type }}</span></p>
                    @if ($log->event == 'updated')
                        <p class="mb-1">From : <span class="text-muted">{{ $log->old_values }}</span></p>
                        <p class="mb-1">To : <span class="text-muted">{{ $log->new_values }}</span></p>
                    @elseif($log->event == 'created')
                        <p class="mb-1">Fields : <span class="text-muted">{{ $log->new_values }}</span></p>
                    @endif
                    <p class="mb-1">Date : <span class="text-muted">{{ $log->created_at->format('F d, Y h:i A') }} {{ $log->created_at->diffForHumans() }}</span></p>
                </div>
            </div>
        @empty
            
            <img width="200px" class="d-block mx-auto" src="{{ asset('images/undraw_photos_09tf.png') }}" alt="">
            <p class="text-muted text-center">No Activity Logs</p>
        @endforelse
        {{  $logs->links() }}
    </div>
</div>
@endsection

@section('script-extra')
    <script>
        $(function(){
            $('.select2').select2();
        })
    </script>
@endsection