<?php

// Allow overriding scopes via AZURE_SCOPES env (space- or comma-separated)
$scopesEnv = env('AZURE_SCOPES');
if ($scopesEnv) {
    $parsed = preg_split('/[\s,]+/', trim($scopesEnv));
    $scopes = array_values(array_filter($parsed));
} else {
    // Default scopes (full set for app features)
    $scopes = [
        'openid',
        'profile',
        'email',
        'offline_access',
        'User.Read',
        'User.ReadBasic.All',
        'User.Read.All',
        'Chat.Create',
        'Chat.ReadWrite',
        'ChatMessage.Send',
        'Mail.Send'
    ];
}

return [
    'Azure' => [
        'clientId' => env('AZURE_CLIENT_ID', ''),
        'clientSecret' => env('AZURE_CLIENT_SECRET', ''),
        'tenantId' => env('AZURE_TENANT_ID', ''),
        'redirectUri' => env('AZURE_REDIRECT_URI', 'http://localhost:8765/auth/callback'),
            // If your app registration is a Web (confidential) client, set this to true
            // and provide AZURE_CLIENT_SECRET. For public/native client with PKCE, leave false.
            'isConfidential' => env('AZURE_IS_CONFIDENTIAL', false),
        'graphApiEndpoint' => 'https://graph.microsoft.com/v1.0',
        'scopes' => $scopes,
        // Optional: default mailbox to send from when using app-only token (UPN or userPrincipalName)
        'senderUpn' => env('AZURE_SENDER_UPN', ''),
        'teams' => [
            'teamId' => env('AZURE_TEAM_ID', ''),
            'channelId' => env('AZURE_CHANNEL_ID', ''),
        ],
    ],
];
