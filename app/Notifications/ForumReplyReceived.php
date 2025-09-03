<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ForumReply;
use App\Models\ForumPost;

class ForumReplyReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;
    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(ForumReply $reply, ForumPost $post)
    {
        $this->reply = $reply;
        $this->post = $post;
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
            'type' => 'forum_reply',
            'title' => 'Balasan Baru di Forum',
            'message' => $this->reply->user->name . ' telah membalas post anda "' . $this->post->title . '"',
            'reply_id' => $this->reply->id,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'replier_name' => $this->reply->user->name,
            'url' => '/forum-posts/' . $this->post->id,
            'icon' => 'chat',
            'color' => 'blue'
        ];
    }
}
