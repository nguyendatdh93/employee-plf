<?php

namespace App\Notifications;

use App\Services\MailService;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Session;
use Config;

class OverrideResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        session()->put('send_mail_forgot_password', $this->token);
        return (new MailMessage)
            ->subject(view('mail.forgot_password_subject'))
            ->view('mail.forgot_password_body', ['notifiable' => $notifiable, 'url' => url(route('password.reset',['token' => $this->token, 'email' => $notifiable->email]))])
            ->salutation(' ');

    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
