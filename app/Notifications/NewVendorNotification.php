<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVendorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $businessName;
    protected $businessType;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $businessName, $businessType)
    {
        $this->user = $user;
        $this->businessName = $businessName;
        $this->businessType = $businessType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Vendor Baru Mendaftar - Puncak Jalil Community Hub')
            ->greeting('Salam Admin!')
            ->line('Seorang vendor baru telah mendaftar dalam sistem.')
            ->line('**Maklumat Vendor:**')
            ->line('Nama: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Nama Perniagaan: ' . $this->businessName)
            ->line('Jenis Perniagaan: ' . $this->businessType)
            ->line('Nombor Telefon: ' . $this->user->phone)
            ->action('Semak Vendor', url('/admin/users/' . $this->user->id))
            ->line('Sila semak dan approve vendor ini jika sesuai.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Vendor Baru Mendaftar',
            'message' => 'Vendor ' . $this->user->name . ' (' . $this->businessName . ') telah mendaftar.',
            'user_id' => $this->user->id,
            'business_name' => $this->businessName,
            'business_type' => $this->businessType,
            'type' => 'new_vendor',
        ];
    }
}