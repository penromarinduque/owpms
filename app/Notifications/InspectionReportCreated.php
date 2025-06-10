<?php

namespace App\Notifications;

use App\Models\LtpApplication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class InspectionReportCreated extends Notification
{
    use Queueable;

    public $ltpApplication;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Good Day!')
                    ->line('An Inspection Report of wildlife has been successfully created and is now awaiting your sign.')
                    ->line('Application Number: ' . $this->ltpApplication->application_no)
                    ->action('View Application', $notifiable->usertype == 'permittee' ?
                        URL::route('myapplication.show', ['id' => Crypt::encryptString($this->ltpApplication->id)]) :
                        URL::route('ltpapplication.index', ['status' => 'inspected', 'category' => 'accepted']))
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
            'url' => $notifiable->usertype == 'permittee' ?
                 URL::route('myapplication.show', ['id' => Crypt::encryptString($this->ltpApplication->id)]) :
                 URL::route('ltpapplication.index', ['status' => 'inspected', 'category' => 'accepted']),
            'title' => 'Inspection Report Created',
            'message' => 'An Inspection Report of wildlife has been successfully created and is now awaiting your sign.',
        ];
    }
}
