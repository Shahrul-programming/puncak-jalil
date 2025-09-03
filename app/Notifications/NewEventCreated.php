<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class NewEventCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_event',
            'title' => 'Event Baru',
            'message' => 'Event baru "' . $this->event->title . '" telah dijadualkan pada ' . $this->event->formatted_date,
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_date' => $this->event->formatted_date,
            'event_location' => $this->event->location,
            'creator_name' => $this->event->user->name,
            'url' => '/events/' . $this->event->id,
            'icon' => 'calendar',
            'color' => 'green'
        ];
    }
}
