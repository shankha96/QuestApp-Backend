<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationToStudent extends Notification
{
    use Queueable;

    private $recipient, $payload;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($recipient, $payload)
    {
        $this->recipient = $recipient;
        $this->payload = $payload;

        $this->payload["payload"]->name = ucfirst($this->payload["payload"]->name);
        $this->payload["type"] = ucfirst($this->payload["type"]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $sender = request()->user();
        return (new MailMessage)
            ->subject("Invitation To {$this->payload["type"]} From {$sender->name}")
            ->greeting("Hello, {$this->recipient->name}")
            ->line("{$sender->name} has invited you to '{$this->payload["payload"]->name}'")
            ->action('Join ' . ucwords($this->payload["type"]), url($this->payload["join_url"]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}