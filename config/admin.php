<?php

$emailsEnv = env('ADMIN_EMAILS', '');
$adminEmails = array_values(array_filter(array_map('trim', preg_split('/[,;\s]+/', $emailsEnv))));

return [
    'Admin' => [
        'emails' => $adminEmails,
        // Future: put RBAC permission map/config here if needed
    ],
];
