<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderSuccessfully extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        $url = '';

        return (new MailMessage)->markdown('emails.orders.OrderSuccessfully', [
            'url' => $url,
            'username' => $notifiable->username,
            'transactionId' => $this->order->transaction_id,
            'orderDate' => $this->order->created_at,
            'items' => $this->order->products,
            'totalPrice' => $this->order->total_price,
            'receiverName' => $this->order->receiver_name,
            'receiverPhoneNumber' => $this->order->receiver_phone_number,
            'receiverAddress' => $this->order->receiver_address,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
