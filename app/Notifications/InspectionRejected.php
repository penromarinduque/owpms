<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class InspectionRejected extends Notification
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
                    ->greeting('Good Day!')
                    ->line('Your LTP Application inspection has been rejected.')
                    ->line('Application Number: ' . $this->ltpApplication->application_no)
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
            'title' => 'Inspection Rejected',
            'message' => 'Your LTP Application inspection has been rejected. Application Number: ' . $this->ltpApplication->application_no,
        ];
    }
}
