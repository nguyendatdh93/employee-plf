<?php

return [
    'domain'           => env('CONFIG_DOMAIN'),
    'default_password' => env('DEFAULT_PASSWORD', '123456789'),
    'ip_range'         => explode(',', env('CONFIG_IP_RANGE_AA_NETWORK')),
    'copy_right_text'  => env('COPYRIGHT_TEXT_IN_FOOTER', ''),
    'manager_name'     => env('MANAGER_NAME', 'Manager'),
    'manager_email'    => env('MANAGER_EMAIL', 'manager@aainc.co.jp'),
    'manager_password' => env('MANAGER_PASSWORD', '123456789'),
    'new_user_expired_hours' => env('NEW_USER_EXPIRED_HOURS', 8),
    'slack_webhook_url' => env('SLACK_WEBHOOK_URL'),
    'helpdesk_mail'     => 'http://192.168.255.216/helpdesk/',
];
