<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Config;

class Slack extends Authenticatable
{
    use Notifiable;

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return Config::get('base.slack_webhook_url');
    }
}