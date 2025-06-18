<?php

namespace App\Notifications;

use App\Models\LtpApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class LtpApplicationSubmitted extends Notification
{
    use Queueable;

    public LtpApplication $ltpApplication;

    /**
     * Create a new notification instance.
     */
    public function __construct(LtpApplication $ltpApplication)
    {
        //
        $this->ltpApplication = $ltpApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'url' => URL::route('ltpapplication.index', ['category' => 'submitted', 'status' => 'submitted']),
            'title' => 'New LTP Application',
            'message' => 'A new application has been submitted by ' . $this->ltpApplication->permittee->user->personalInfo->getFullNameAttribute(),
        ];
    }
}
