<?php

namespace App\Listeners;

use App\Events\TaskStatusUpdated;
use App\Notifications\TaskStatusUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendTaskStatusUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskStatusUpdated $event): void
    {
        // Guard clause to prevent self-notifications
        if ($event->task->creator->id === $event->task->updater->id || auth()->id === $event->task->updater->id) {
            return;
        }

        // Send notification to the creator
        Notification::send($event->task->creator, new TaskStatusUpdatedNotification($event->task, $event->task->updater));
    }
}
