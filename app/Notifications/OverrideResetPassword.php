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
//            ->view('mail.forgot_password')
            ->subject(trans('mail_forgot_password.subject'))
            ->greeting(trans('mail_forgot_password.greeting'). $notifiable->name)
            ->line(trans('mail_forgot_password.line1'))
            ->action(trans('mail_forgot_password.btn_reset_text'), url(route('password.reset',['token' => $this->token, 'email' => $notifiable->email])))
            ->line(trans('mail_forgot_password.line2'))
            ->line(url(route('password.reset',['token' => $this->token, 'email' => $notifiable->email])))
            ->line(trans('mail_forgot_password.line3'))
            ->line(trans('mail_forgot_password.line4'))
            ->line( Config::get('base.helpdesk_mail'))
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
