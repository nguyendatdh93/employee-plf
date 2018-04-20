<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
class SlackNotification extends Notification
{
    use Queueable;
    private $task;
    private $error;
    private $content_error;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task, $error, $content_error)
    {
        $this->task          = $task;
        $this->error         = $error;
        $this->content_error = $content_error;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $error = $this->error;
        $content_error = $this->content_error;
        return (new SlackMessage)
            ->error()
            ->content($this->task)
            ->attachment(function ($attachment) use ($error, $content_error) {
                $attachment->title($error)
                    ->content($content_error);
            });
    }
}