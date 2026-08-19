<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SCiREG system administrators
    |--------------------------------------------------------------------------
    |
    | Comma-separated admin emails (lowercase). Admins can access every module
    | and are the only users allowed to view audit logs.
    |
    */

    'admin_emails' => array_values(array_filter(array_map(
        static fn (string $email): string => strtolower(trim($email)),
        explode(',', (string) env('SCIREG_ADMIN_EMAILS', 'jaroonluk@kku.ac.th'))
    ))),

];
