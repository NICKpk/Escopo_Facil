<?php

return [
    // This project deliberately supports simulated payments only.
    'driver' => env('PIX_DRIVER', 'local'),
    'abacate_key' => env('ABACATEPAY_DEV_KEY'),
    'expires_in' => 900,
];
