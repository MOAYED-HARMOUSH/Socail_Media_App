<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class Reaction extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $name,
        protected string $type,
        protected string $location,
        protected string $content,
        public string $message = ''
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        if ($this->message == null) {
            $this->message = "$this->name has just $this->type on your $this->location: $this->content";
        }

        return new BroadcastMessage([
            'Title' => 'New Reaction',
            'Message' => $this->message
        ]);
    }
    public function toDatabase(object $notifiable)
    {
        if ($this->message == null) {
         $message=   $this->message = "$this->name has just $this->type on your $this->location: $this->content";
        }

        return[ $message];

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
        ];
    }
}
