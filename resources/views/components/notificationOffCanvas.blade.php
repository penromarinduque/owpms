<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffCanvas" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" ><i class="fa-solid fa-bell me-2"></i>Notifications</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="list-group">
        @forelse (auth()->user()->unreadNotifications->take(10) as $notification)
            
            <a href="{{ route('notifications.show', ['id' => Crypt::encryptString($notification->id)])}}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-0">{{ $notification->data['title'] }}</h6>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ $notification->data['message'] }}</p>
            </a>
        @empty
            <div class=" ">
                <img src="{{ asset('images/undraw_no-data_ig65.png') }}" alt="No notification" class="img-fluid d-block mx-auto" style="max-width: 200px;">
                <p class="text-center mt-3">You don't have any notifications at the moment. Please check back later.</p>
            </div>
        @endforelse

        @if (auth()->user()->unreadNotifications->count() > 10)

            <p class="text-center mt-3"><a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary w-100">View All</a></p>
        @endif
    </div>

  </div>
</div>