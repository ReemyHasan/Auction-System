<?php

namespace App\Notifications;

use App\Models\CustomerBid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBidAdded extends Notification
{
    use Queueable;

    private $bid;
    /**
     * Create a new notification instance.
     */
    public function __construct(CustomerBid $bid)
    {
        $this->bid = $bid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('new bid added to the auction')
            ->greeting('Hello new notification for you')
            ->line('someone add new bids.')
            ->line('go and check')
            ->action('go to app', url('/auctions/'. $this->bid->auction_id.'/bids'))
            ->line('Thank you for using our application!');
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
