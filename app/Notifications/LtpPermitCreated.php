<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpPermitCreated extends Notification
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
                    ->line('Good news! Your LTP Permit has been created.')
                    ->line('Your LTP Permit has been created and is now for approval. You will be notified once the Permit is approved and you can proceed to the next step. We appreciate your patience and understanding.')
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
            'title' => 'Your LTP Permit has been created.',
            'message' => 'Your LTP Permit has been created and now for approval.',
        ];
    }
}
