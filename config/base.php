<?php

return [
    'default_password' => env('DEFAULT_PASSWORD', '123456789'),
    'ip_range'         => explode(',', env('CONFIG_IP_RANGE_AA_NETWORK')),
    'copy_right_text'  => env('COPYRIGHT_TEXT_IN_FOOTER', ''),
];
