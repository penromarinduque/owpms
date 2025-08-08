<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignedNotification extends Notification
{
    use Queueable;

    public $url;
    public $message;
    public $title;
    
    /**
     * Create a new notification instance.
     */
    public function __construct($url, $message, $title = 'For Signature')
    {
        $this->title = $title;
        $this->url = $url;
        $this->message = $message;
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
                    ->line($this->message)
                    ->action('View Signatories', $this->url)
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
            'url' => $this->url,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
