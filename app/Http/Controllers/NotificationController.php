<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;


class NotificationController extends Controller
{
    public function show(string $id)
    {
        $notification_id = Crypt::decryptString($id);

        $notification = auth()->user()->notifications()->findOrFail($notification_id);

        Gate::authorize('view', $notification);

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? '/');
    }
}
