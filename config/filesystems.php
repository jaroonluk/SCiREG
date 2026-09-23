<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        'minio' => [
            'driver' => 's3',
            'key' => env('MINIO_ACCESS_KEY', env('AWS_ACCESS_KEY_ID')),
            'secret' => env('MINIO_SECRET_KEY', env('AWS_SECRET_ACCESS_KEY')),
            'region' => env('MINIO_REGION', env('AWS_DEFAULT_REGION', 'us-east-1')),
            'bucket' => env('MINIO_BUCKET', env('AWS_BUCKET', 'sci-reg')),
            'url' => env('MINIO_URL'),
            'endpoint' => (static function () {
                $host = trim((string) env('MINIO_ENDPOINT', ''));
                if ($host === '') {
                    return env('AWS_ENDPOINT');
                }

                if (str_starts_with($host, 'http://') || str_starts_with($host, 'https://')) {
                    return $host;
                }

                $scheme = filter_var(env('MINIO_USE_SSL', true), FILTER_VALIDATE_BOOLEAN) ? 'https' : 'http';
                $port = trim((string) env('MINIO_PORT', ''));

                return $port !== ''
                    ? sprintf('%s://%s:%s', $scheme, $host, $port)
                    : sprintf('%s://%s', $scheme, $host);
            })(),
            'use_path_style_endpoint' => filter_var(
                env('MINIO_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', true)),
                FILTER_VALIDATE_BOOLEAN
            ),
            'throw' => true,
            'report' => false,
            'http' => [
                'verify' => ! filter_var(env('MINIO_INSECURE_SKIP_VERIFY', false), FILTER_VALIDATE_BOOLEAN),
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
