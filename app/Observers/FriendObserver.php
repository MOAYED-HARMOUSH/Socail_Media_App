<?php

namespace App\Observers;

use App\Events\FriendRequest;
use App\Models\Friend;

class FriendObserver
{
    /**
     * Handle the Friend "created" event.
     */
    public function created(Friend $friend): void
    {
        event(
            new FriendRequest(
                $friend
            )
        );
    }

    /**
     * Handle the Friend "updated" event.
     */
    public function updated(Friend $friend): void
    {
        //
    }

    /**
     * Handle the Friend "deleted" event.
     */
    public function deleted(Friend $friend): void
    {
        //
    }

    /**
     * Handle the Friend "restored" event.
     */
    public function restored(Friend $friend): void
    {
        //
    }

    /**
     * Handle the Friend "force deleted" event.
     */
    public function forceDeleted(Friend $friend): void
    {
        //
    }
}
