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
                    <p class="mb-1">Action : <span class="text-muted">{{ Str::title($log->event) }}</span></p>
                    <p class="mb-1">Model : <span class="text-muted">{{ Str::title(Str::snake(class_basename($log->auditable_type), ' ')) }}</span></p>
                    @if (auth()->user()->usertype == 'admin')
                        @if ($log->event == 'updated')
                            <p class="mb-1">From : 
                                <table class="table table-sm table-bordered mb-0">
                                    @foreach ((array)json_decode($log->old_values) as $i => $val)
                                        <tr>
                                            <td>{{ Str::title(Str::replace("_", " ", $i)) }}</td>
                                            <td>{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </p>
                            <p class="mb-1">To : 
                                <table class="table table-sm table-bordered mb-0">
                                    @foreach ((array)json_decode($log->new_values) as $i => $val)
                                        <tr>
                                            <td>{{ Str::title(Str::replace("_", " ", $i)) }}</td>
                                            <td>{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </p>
                            
                        @elseif($log->event == 'created')
                            <p class="mb-1">Fields : 
                                <table class="table table-sm table-bordered mb-0">
                                    @foreach ((array)json_decode($log->new_values) as $i => $val)
                                        <tr>
                                            <td>{{ Str::title(Str::replace("_", " ", $i)) }}</td>
                                            <td>{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </p>
                        @endif
                    @endif
                    <p class="mb-1">Date : <span class="text-muted">{{ $log->created_at->format('F d, Y h:i A') }}</span></p>
                    <p class="mb-1 text-muted text-end">{{ $log->created_at->diffForHumans() }}</span></p>
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