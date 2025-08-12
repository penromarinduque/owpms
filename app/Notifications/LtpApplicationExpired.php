<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpApplicationExpired extends Notification implements ShouldQueue
{
    use Queueable;

    public $ltpApplication;

    /**
     * Create a new notification instance.
     */
    public function __construct($ltpApplication)
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Good day!')
                    ->line('Dear valued customer, this is to inform you that your LTP application has expired due to the transport date being in the past. If you wish to submit another application, please feel free to do so.')
                    ->action('View Application', URL::route('myapplication.preview', ['id' => Crypt::encryptString($this->ltpApplication->id)]))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'url' => URL::route('myapplication.preview', ['id' => Crypt::encryptString($this->ltpApplication->id)]),
            'title' => 'LTP Application Expired',
            'message' => 'Your LTP application has expired.',
        ];
    }
}
