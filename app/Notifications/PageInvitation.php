<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PageInvitation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private bool $is_inviter,
        protected string $name,
        protected string $page_name,
        public $image='',
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
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        if ($this->message == null) {
            if ($this->is_inviter)
                $this->message = $this->name . ' Invite You to Follow this Page: ' . $this->page_name;
            else
                $this->message = $this->name . ' has just Accept your Invitation to Follow this Page: ' . $this->page_name;
        }

        return new BroadcastMessage([
            'Title' => 'Friend Request',
            'Message' => $this->message
        ]);
    }
    public function toDatabase(object $notifiable)
    {
        if ($this->message == null) {
            if ($this->is_inviter)
              $message=  $this->message = $this->name . ' Invite You to Follow this Page: ' . $this->page_name;
            else
              $message=  $this->message = $this->name . ' has just Accept your Invitation to Follow this Page: ' . $this->page_name;
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
