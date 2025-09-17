<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubject(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-status-updated',
            with: [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusText' => $this->getStatusText(),
                'message' => $this->getMessage(),
            ],
        );
    }

    /**
     * Get the subject based on status.
     */
    protected function getSubject(): string
    {
        return match($this->newStatus) {
            'confirmed' => 'Pesanan Anda Telah Disahkan - ' . $this->order->order_number,
            'preparing' => 'Pesanan Anda Sedang Disediakan - ' . $this->order->order_number,
            'ready' => 'Pesanan Anda Siap Diambil - ' . $this->order->order_number,
            'delivered' => 'Pesanan Anda Telah Dihantar - ' . $this->order->order_number,
            'cancelled' => 'Pesanan Anda Telah Dibatalkan - ' . $this->order->order_number,
            default => 'Kemaskini Status Pesanan - ' . $this->order->order_number
        };
    }

    /**
     * Get the status text in Malay.
     */
    protected function getStatusText(): string
    {
        return match($this->newStatus) {
            'confirmed' => 'Disahkan',
            'preparing' => 'Sedang Disediakan',
            'ready' => 'Siap Diambil',
            'delivered' => 'Dihantar',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get the message based on status.
     */
    protected function getMessage(): string
    {
        return match($this->newStatus) {
            'confirmed' => 'Pesanan anda telah disahkan oleh restoran. Restoran akan mula menyediakan makanan anda.',
            'preparing' => 'Restoran sedang menyediakan makanan anda. Sila tunggu sebentar.',
            'ready' => 'Makanan anda telah siap! Rider akan mengambil dan menghantar pesanan anda.',
            'delivered' => 'Pesanan anda telah berjaya dihantar. Terima kasih atas pesanan anda!',
            'cancelled' => 'Pesanan anda telah dibatalkan. Jika anda mempunyai sebarang pertanyaan, sila hubungi kami.',
            default => 'Status pesanan anda telah dikemaskini kepada: ' . $this->getStatusText()
        };
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
