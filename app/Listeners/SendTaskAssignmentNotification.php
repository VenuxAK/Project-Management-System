<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendTaskAssignmentNotification
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
    public function handle(TaskAssigned $event): void
    {
        // Gurad clause to prevent self-notifications
        // if ($event->task->assignee->id === $event->task->creator->id || auth()->id === $event->task->creator->id) {
        //     return;
        // }
        if ($event->assignedUser->id === $event->actor->id) {
            return;
        }

        // Send notification to the assignee
        // Notification::send($event->task->assignee, new TaskAssignedNotification($event->task, $event->task->creator));
        $event->assignedUser->notify(
            new TaskAssignedNotification(
                task: $event->task,
                actor: $event->actor
            )
        );
    }
}
