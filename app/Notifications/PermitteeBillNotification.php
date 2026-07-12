<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class PermitteeBillNotification extends Notification
{
    use Queueable;
    public $paymentOrder;
    public $ltpApplication;

    /**
     * Create a new notification instance.
     */
    public function __construct($paymentOrder)
    {
        //
        $this->paymentOrder = $paymentOrder;
        $this->ltpApplication = $paymentOrder->ltpApplication;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            'url' => URL::route('myapplication.preview', ['id' => Crypt::encryptString($this->ltpApplication->id)]),
            'title' => 'LTP Application Payment',
            'message' => 'Your LTP Application '.$this->ltpApplication->application_no.' is now ready for payment please pay exactly PHP '.number_format($this->paymentOrder->ltpFee->amount, "2", ".", ",") . " to the Cashiering Unit",
        ];
    }
}
