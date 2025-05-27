<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpApplicationPaid extends Notification
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
                    ->line('Your application has been paid. Please stand by for the next step: Inspection. For the meantime you can upload the receipt which will be used as attachment to your application. You will be notified once the inspection is completed and you can proceed to the next step. We appreciate your patience and understanding.')
                    ->action('View Application', URL::route('myapplication.show', ['id' => Crypt::encryptString($this->ltpApplication->id)]))
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
            'url' => URL::route('myapplication.show', ['id' => Crypt::encryptString($this->ltpApplication->id)]),
            'title' => 'LTP Application Paid',
            'message' => 'Your LTP application has been paid.',
        ];
    }
}
