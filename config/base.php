<?php

return [
    'default_password' => env('DEFAULT_PASSWORD', '123456789'),
    'ip_range'         => explode(',', env('CONFIG_IP_RANGE_AA_NETWORK')),
    'copy_right_text'  => env('COPYRIGHT_TEXT_IN_FOOTER', ''),
    'manager_name'     => env('MANAGER_NAME', 'Manager'),
    'manager_email'    => env('MANAGER_EMAIL', 'manager@aainc.co.jp'),
    'manager_password' => env('MANAGER_PASSWORD', '123456789'),
];
