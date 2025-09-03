<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Review;
use App\Models\Shop;

class ShopReviewReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;
    protected $shop;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review, Shop $shop)
    {
        $this->review = $review;
        $this->shop = $shop;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Review Baru untuk Kedai Anda')
                    ->greeting('Helo ' . $notifiable->name . '!')
                    ->line('Kedai anda "' . $this->shop->name . '" telah menerima review baru.')
                    ->line('Rating: ' . str_repeat('⭐', $this->review->rating))
                    ->line('Komen: ' . $this->review->comment)
                    ->line('Review daripada: ' . $this->review->user->name)
                    ->action('Lihat Review', url('/shops/' . $this->shop->id))
                    ->line('Terima kasih kerana menggunakan platform kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'shop_review',
            'title' => 'Review Baru untuk Kedai Anda',
            'message' => 'Kedai "' . $this->shop->name . '" telah menerima review ' . $this->review->rating . ' bintang.',
            'review_id' => $this->review->id,
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->name,
            'reviewer_name' => $this->review->user->name,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment,
            'url' => '/shops/' . $this->shop->id,
            'icon' => 'star',
            'color' => 'yellow'
        ];
    }
}
