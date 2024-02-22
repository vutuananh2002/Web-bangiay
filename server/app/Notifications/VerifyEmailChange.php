<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailChange extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return (new MailMessage)->markdown('emails.email-change-verify', [
            'username' => $this->user->username,
            'url' => $this->verifyUrl($notifiable),
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

    public function verifyUrl(AnonymousNotifiable $notifiable)
    {
        $params = [
            'expires' => now()->addHour()->getTimestamp(),
            'id' => $this->user->id,
            'email' => $notifiable->routes['mail'],
            'hash' => hash('sha256', $this->user->id.$notifiable->routes['mail']),
        ];
        ksort($params);

        $url = URL::route('email.verify-change', $params, true);

        $key = config('app.key');
        $signature = hash_hmac('sha256', $url, $key);

        return env('FRONT_URL').'/auth/verify-email-change/'.$params['id'].'/'.$params['hash'].'?email='.$params['email'].'&expires='.$params['expires'].'&signature='.$signature;
    }
}
