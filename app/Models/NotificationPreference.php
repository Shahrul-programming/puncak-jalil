<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'order_status_updates',
        'order_confirmations',
        'order_deliveries',
        'promotional_emails',
        'marketing_emails',
        'email_frequency',
        'notification_channels',
    ];

    protected $casts = [
        'order_status_updates' => 'boolean',
        'order_confirmations' => 'boolean',
        'order_deliveries' => 'boolean',
        'promotional_emails' => 'boolean',
        'marketing_emails' => 'boolean',
        'notification_channels' => 'array',
    ];

    /**
     * Relationship with User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user wants order status update notifications.
     */
    public function wantsOrderStatusUpdates(): bool
    {
        return $this->order_status_updates;
    }

    /**
     * Check if user wants order confirmation notifications.
     */
    public function wantsOrderConfirmations(): bool
    {
        return $this->order_confirmations;
    }

    /**
     * Check if user wants order delivery notifications.
     */
    public function wantsOrderDeliveries(): bool
    {
        return $this->order_deliveries;
    }

    /**
     * Check if user wants promotional emails.
     */
    public function wantsPromotionalEmails(): bool
    {
        return $this->promotional_emails;
    }

    /**
     * Check if user wants marketing emails.
     */
    public function wantsMarketingEmails(): bool
    {
        return $this->marketing_emails;
    }

    /**
     * Get user's preferred notification channels.
     */
    public function getNotificationChannels(): array
    {
        return $this->notification_channels ?? ['mail'];
    }

    /**
     * Check if user wants notifications via specific channel.
     */
    public function wantsChannel(string $channel): bool
    {
        return in_array($channel, $this->getNotificationChannels());
    }

    /**
     * Get user's email frequency preference.
     */
    public function getEmailFrequency(): string
    {
        return $this->email_frequency ?? 'immediate';
    }

    /**
     * Check if notifications should be sent immediately.
     */
    public function shouldSendImmediately(): bool
    {
        return $this->getEmailFrequency() === 'immediate';
    }

    /**
     * Create default preferences for a user.
     */
    public static function createDefaultForUser(int $userId): self
    {
        return static::create([
            'user_id' => $userId,
            'order_status_updates' => true,
            'order_confirmations' => true,
            'order_deliveries' => true,
            'promotional_emails' => false,
            'marketing_emails' => false,
            'email_frequency' => 'immediate',
            'notification_channels' => ['mail'],
        ]);
    }

    /**
     * Get or create preferences for a user.
     */
    public static function getForUser(int $userId): self
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'order_status_updates' => true,
                'order_confirmations' => true,
                'order_deliveries' => true,
                'promotional_emails' => false,
                'marketing_emails' => false,
                'email_frequency' => 'immediate',
                'notification_channels' => ['mail'],
            ]
        );
    }
}
