<?php

return [
    'default_password' => env('DEFAULT_PASSWORD', '123456789'),
    'domain'           => env('CONFIG_DOMAIN'),
    'ip_range'         => explode(',', env('CONFIG_IP_RANGE_AA_NETWORK')),
];
