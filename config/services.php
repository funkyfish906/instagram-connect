<?php

return [

    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
        'redirect_url' => env('INSTAGRAM_REDIRECT_URL'),
        'image_loading_count' => env('INSTAGRAM_IMAGE_LOADING_COUNT', 12),
    ],
];
