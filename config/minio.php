<?php

return [
    'endpoint' => env('MINIO_ENDPOINT'),
    'port' => env('MINIO_PORT', '9000'),
    'use_ssl' => filter_var(env('MINIO_USE_SSL', true), FILTER_VALIDATE_BOOLEAN),
    'insecure_skip_verify' => filter_var(env('MINIO_INSECURE_SKIP_VERIFY', false), FILTER_VALIDATE_BOOLEAN),
    'key' => env('MINIO_ACCESS_KEY'),
    'secret' => env('MINIO_SECRET_KEY'),
    'bucket' => env('MINIO_BUCKET', 'sci-reg'),
    'region' => env('MINIO_REGION', 'us-east-1'),
    'use_path_style_endpoint' => filter_var(env('MINIO_USE_PATH_STYLE_ENDPOINT', true), FILTER_VALIDATE_BOOLEAN),
];
