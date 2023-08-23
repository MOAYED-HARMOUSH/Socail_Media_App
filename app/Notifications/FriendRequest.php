<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
class FriendRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private bool $is_sender, protected string $name, public $image = '', public string $message = '')
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        if ($this->message == null) {
            if ($this->is_sender)
                $this->message = 'You Have New Friend Request from: ' . $this->name;
            else
                $this->message = $this->name . ' has just Accept your Friend Request' . '<br>' . 'We wish your friendship won\'t break';
        }

        return new BroadcastMessage([
            'Title' => 'Friend Request',
            'Message' => $this->message
        ]);
    }
    public function toDatabase(object $notifiable)
    {

        if ($this->message == null) {
            if ($this->is_sender)
                $message =  $this->message = 'You Have New Friend Request from: ' . $this->name;
            else
                $message =  $this->message = $this->name . ' has just Accept your Friend Request';
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
