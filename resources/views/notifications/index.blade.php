@extends('layouts.master')

@section('title')
    Notifications
@endsection

@section('active-myapplication')
active
@endsection

@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">Notifications</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Notifications</li>
    </ol>

    <div class="list-group">
        @forelse ($notifications as $notification)
            @php
                $data = json_decode($notification->data);
            @endphp
            <a href="{{ route('notifications.show', ['id' => Crypt::encryptString($notification->id)])}}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-0">{{ $data->title }}</h6>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ $data->message }}</p>
            </a>
        @empty
            <div class=" ">
                <img src="{{ asset('images/undraw_no-data_ig65.png') }}" alt="No notification" class="img-fluid d-block mx-auto" style="max-width: 200px;">
                <p class="text-center mt-3">You don't have any notifications at the moment. Please check back later.</p>
            </div>
        @endforelse

    </div>
    @if ($notifications->count() > 10)
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection