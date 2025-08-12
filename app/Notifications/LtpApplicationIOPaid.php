<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpApplicationIOPaid extends Notification
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
                    ->line('Good day!')
                    ->line('Order of Payment for LTP Application '.$this->ltpApplication->application_no.' has been paid. You can now proceed to the next step of your application process.')
                    ->action('View Application', URL::route('ltpapplication.preview', ['id' => Crypt::encryptString($this->ltpApplication->id)]))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
            'url' => URL::route('ltpapplication.preview', ['id' => Crypt::encryptString($this->ltpApplication->id)]),
            'title' => 'LTP Application Paid',
            'message' => 'Order of Payment for LTP Application '.$this->ltpApplication->application_no.' has been paid. You can now proceed to the next step of your application process.',
        ];
    }
}
