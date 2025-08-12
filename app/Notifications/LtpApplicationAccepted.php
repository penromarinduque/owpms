<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpApplicationAccepted extends Notification
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Good news! Your LTP application has been accepted.')
                    ->line('Your application requirements have been officially accepted/received by the Receiving Officer.')
                    ->line('Please wait for the Order of Payment to be created then pay through the cashier.')
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
            'title' => 'LTP Application Accepted',
            'message' => 'Your application requirements have been officially accepted/received by the Receiving Officer.',
        ];
    }
}
