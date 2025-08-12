<?php

namespace App\Notifications;

use App\Models\LtpPermit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class LtpPermitApproved extends Notification
{
    use Queueable;
    public LtpPermit $ltpPermit;

    /**
     * Create a new notification instance.
     */
    public function __construct(LtpPermit $ltpPermit)
    {
        //
        $this->ltpPermit = $ltpPermit;
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
                    ->line('Good news! Your Local Transport Permit has been approved.')
                    ->line('Your Local Transport Permit has been approved and will now proceed to the next step which is releasing the permit. We appreciate your patience and understanding.')
                    ->action('View Application', URL::route('myapplication.preview', ['id' => Crypt::encryptString($this->ltpPermit->ltp_application_id)]))
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
            'url' => URL::route('myapplication.preview', ['id' => Crypt::encryptString($this->ltpPermit->ltp_application_id)]),
            'title' => 'Your Local Transport Permit has been approved.',
            'message' => 'Your LTP Permit has been approved and is now ready for realising.',
        ];
    }
}
