<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Microsoft Graph Service
 * 
 * Handles all Microsoft Graph API interactions including:
 * - Fetching users from Azure AD
 * - Sending emails via Microsoft Graph
 * - User profile management
 */
class MicrosoftGraphService
{
    /**
     * @var \Cake\Http\Client
     */
    private $http;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string|null
     */
    private $accessToken;

    /**
     * Constructor
     *
     * @param string|null $accessToken Access token for Microsoft Graph API
     */
    public function __construct(?string $accessToken = null)
    {
        $this->http = new Client(['timeout' => 30]);
        $this->config = Configure::read('Azure');
        $this->accessToken = $accessToken;
    }

    /**
     * Set access token
     *
     * @param string $accessToken Access token
     * @return void
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get all users from Azure AD
     *
     * @return array Array of users with id, name, email, jobTitle
     */
    public function getAllUsers(): array
    {
        if (!$this->accessToken) {
            Log::error('MicrosoftGraphService: No access token provided');
            return [];
        }

        try {
            $allUsers = [];
            $nextLink = $this->config['graphApiEndpoint'] . '/users?$select=id,displayName,mail,userPrincipalName,givenName,surname,jobTitle,department&$top=999';
            
            do {
                $response = $this->http->get(
                    $nextLink,
                    [],
                    ['headers' => ['Authorization' => 'Bearer ' . $this->accessToken]]
                );
                
                if (!$response->isOk()) {
                    $errorBody = $response->getStringBody();
                    Log::error('Microsoft Graph API Error: ' . $errorBody);
                    
                    // Check for expired token
                    if (strpos($errorBody, 'expired') !== false || strpos($errorBody, 'InvalidAuthenticationToken') !== false) {
                        Log::error('Access token has expired');
                    }
                    
                    return [];
                }
                
                $data = $response->getJson();
                
                if (!isset($data['value']) || !is_array($data['value'])) {
                    Log::error('Invalid response from Microsoft Graph API');
                    return [];
                }
                
                // Add users from current page
                foreach ($data['value'] as $graphUser) {
                    $allUsers[] = [
                        'id' => $graphUser['id'] ?? '',
                        'name' => $graphUser['displayName'] ?? 'Unknown User',
                        'email' => $graphUser['mail'] ?? $graphUser['userPrincipalName'] ?? '',
                        'jobTitle' => $graphUser['jobTitle'] ?? '',
                        'department' => $graphUser['department'] ?? '',
                        'firstName' => $graphUser['givenName'] ?? '',
                        'lastName' => $graphUser['surname'] ?? '',
                    ];
                }
                
                // Get next page link if available
                $nextLink = $data['@odata.nextLink'] ?? null;
                
            } while ($nextLink);
            
            return $allUsers;
            
        } catch (\Exception $e) {
            Log::error('Exception in getAllUsers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search users in Azure AD by name or email
     *
     * @param string $query Search text
     * @param int $limit Max results to return
     * @return array List of users: id, name, email, jobTitle, department, firstName, lastName, upn
     */
    public function searchUsers(string $query, int $limit = 10): array
    {
        if (!$this->accessToken) {
            Log::error('MicrosoftGraphService: No access token provided');
            return [];
        }

        $query = trim($query);
        if ($query === '') {
            return [];
        }

        // Escape single quotes for OData filter
        $qEsc = str_replace("'", "''", $query);

        $params = [
            '$select' => 'id,displayName,mail,userPrincipalName,givenName,surname,jobTitle,department',
            '$top' => max(1, min($limit, 50)),
            '$filter' => "contains(displayName,'{$qEsc}') or contains(mail,'{$qEsc}') or contains(userPrincipalName,'{$qEsc}')",
        ];

        try {
            $endpoint = rtrim((string)($this->config['graphApiEndpoint'] ?? 'https://graph.microsoft.com/v1.0'), '/') . '/users';
            $url = $endpoint . '?' . http_build_query($params);

            $response = $this->http->get(
                $url,
                [],
                ['headers' => ['Authorization' => 'Bearer ' . $this->accessToken]]
            );

            if (!$response->isOk()) {
                Log::error('Microsoft Graph search users error: ' . $response->getStringBody());
                return [];
            }

            $data = $response->getJson();
            if (!isset($data['value']) || !is_array($data['value'])) {
                return [];
            }

            $results = [];
            foreach ($data['value'] as $u) {
                $results[] = [
                    'id' => $u['id'] ?? '',
                    'name' => $u['displayName'] ?? '',
                    'email' => $u['mail'] ?? ($u['userPrincipalName'] ?? ''),
                    'jobTitle' => $u['jobTitle'] ?? '',
                    'department' => $u['department'] ?? '',
                    'firstName' => $u['givenName'] ?? '',
                    'lastName' => $u['surname'] ?? '',
                    'upn' => $u['userPrincipalName'] ?? '',
                ];
            }
            return $results;

        } catch (\Throwable $e) {
            Log::error('Exception in searchUsers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get users formatted for dropdown (id => name)
     *
     * @return array Associative array of user_id => display_name
     */
    public function getUsersForDropdown(): array
    {
        $users = $this->getAllUsers();
        $dropdown = [];
        
        foreach ($users as $user) {
            if (!empty($user['id']) && !empty($user['name'])) {
                $display = $user['name'];
                if (!empty($user['email'])) {
                    $display .= ' (' . $user['email'] . ')';
                }
                $dropdown[$user['id']] = $display;
            }
        }
        
        return $dropdown;
    }

    /**
     * Send email via Microsoft Graph API
     *
    * @param array $params Email parameters
     *   - to: array of recipient emails
     *   - cc: array of CC emails (optional)
     *   - subject: email subject
     *   - body: HTML body content
     *   - attachments: array of attachments (optional)
     *     - name: file name
     *     - contentType: MIME type
     *     - contentBytes: base64 encoded file content
    *   - fromUpn: optional UPN/email for sender mailbox when using app-only token
     * @return array Result with success status and message
     */
    public function sendEmail(array $params): array
    {
        if (!$this->accessToken) {
            return [
                'success' => false,
                'message' => 'No access token provided'
            ];
        }

        // Validate required parameters
        if (empty($params['to']) || empty($params['subject']) || empty($params['body'])) {
            return [
                'success' => false,
                'message' => 'Missing required parameters: to, subject, and body are required'
            ];
        }

        try {
            // Build recipients
            $toRecipients = [];
            foreach ((array)$params['to'] as $email) {
                $email = trim($email);
                if (!empty($email)) {
                    $toRecipients[] = [
                        'emailAddress' => [
                            'address' => $email
                        ]
                    ];
                }
            }

            // Build CC recipients
            $ccRecipients = [];
            if (!empty($params['cc'])) {
                foreach ((array)$params['cc'] as $email) {
                    $email = trim($email);
                    if (!empty($email)) {
                        $ccRecipients[] = [
                            'emailAddress' => [
                                'address' => $email
                            ]
                        ];
                    }
                }
            }

            // Build attachments
            $attachments = [];
            if (!empty($params['attachments'])) {
                foreach ($params['attachments'] as $attachment) {
                    $attachments[] = [
                        '@odata.type' => '#microsoft.graph.fileAttachment',
                        'name' => $attachment['name'],
                        'contentType' => $attachment['contentType'] ?? 'application/octet-stream',
                        'contentBytes' => $attachment['contentBytes']
                    ];
                }
            }

            // Build email message
            $message = [
                'message' => [
                    'subject' => $params['subject'],
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $params['body']
                    ],
                    'toRecipients' => $toRecipients,
                ],
                'saveToSentItems' => $params['saveToSentItems'] ?? true
            ];

            // Add CC recipients if present
            if (!empty($ccRecipients)) {
                $message['message']['ccRecipients'] = $ccRecipients;
            }

            // Add attachments if present
            if (!empty($attachments)) {
                $message['message']['attachments'] = $attachments;
            }

            // Decide endpoint: use /users/{upn}/sendMail when a sender UPN is provided (app-only), otherwise /me/sendMail
            $endpoint = $this->config['graphApiEndpoint'] . (
                !empty($params['fromUpn'])
                    ? ('/users/' . rawurlencode((string)$params['fromUpn']) . '/sendMail')
                    : '/me/sendMail'
            );

            // Send email using Graph API
            $response = $this->http->post(
                $endpoint,
                json_encode($message),
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->accessToken,
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            if ($response->isOk() || $response->getStatusCode() === 202) {
                Log::info('Email sent successfully via Microsoft Graph', [
                    'to' => $params['to'],
                    'subject' => $params['subject']
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Email sent successfully'
                ];
            } else {
                $error = $response->getStringBody();
                Log::error('Failed to send email via Microsoft Graph: ' . $error);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send email: ' . $error
                ];
            }

        } catch (\Exception $e) {
            Log::error('Exception in sendEmail: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get user profile by ID
     *
     * @param string $userId User ID
     * @return array|null User profile or null if not found
     */
    public function getUserProfile(string $userId): ?array
    {
        if (!$this->accessToken) {
            return null;
        }

        try {
            $response = $this->http->get(
                $this->config['graphApiEndpoint'] . '/users/' . $userId . '?$select=id,displayName,mail,userPrincipalName,givenName,surname,jobTitle,department',
                [],
                ['headers' => ['Authorization' => 'Bearer ' . $this->accessToken]]
            );
            
            if (!$response->isOk()) {
                Log::error('Failed to get user profile: ' . $response->getStringBody());
                return null;
            }
            
            $data = $response->getJson();
            
            return [
                'id' => $data['id'] ?? '',
                'name' => $data['displayName'] ?? 'Unknown User',
                'email' => $data['mail'] ?? $data['userPrincipalName'] ?? '',
                'jobTitle' => $data['jobTitle'] ?? '',
                'department' => $data['department'] ?? '',
                'firstName' => $data['givenName'] ?? '',
                'lastName' => $data['surname'] ?? '',
            ];
            
        } catch (\Exception $e) {
            Log::error('Exception in getUserProfile: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current authenticated user's profile
     *
     * @return array|null User profile or null if not found
     */
    public function getMyProfile(): ?array
    {
        if (!$this->accessToken) {
            return null;
        }

        try {
            $response = $this->http->get(
                $this->config['graphApiEndpoint'] . '/me?$select=id,displayName,mail,userPrincipalName,givenName,surname,jobTitle,department',
                [],
                ['headers' => ['Authorization' => 'Bearer ' . $this->accessToken]]
            );
            
            if (!$response->isOk()) {
                Log::error('Failed to get my profile: ' . $response->getStringBody());
                return null;
            }
            
            $data = $response->getJson();
            
            return [
                'id' => $data['id'] ?? '',
                'name' => $data['displayName'] ?? 'Unknown User',
                'email' => $data['mail'] ?? $data['userPrincipalName'] ?? '',
                'jobTitle' => $data['jobTitle'] ?? '',
                'department' => $data['department'] ?? '',
                'firstName' => $data['givenName'] ?? '',
                'lastName' => $data['surname'] ?? '',
            ];
            
        } catch (\Exception $e) {
            Log::error('Exception in getMyProfile: ' . $e->getMessage());
            return null;
        }
    }
}
