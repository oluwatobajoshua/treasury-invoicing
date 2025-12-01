<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Service\MicrosoftGraphService;

/**
 * Microsoft Graph Component
 * 
 * Provides easy access to Microsoft Graph functionality from controllers
 */
class MicrosoftGraphComponent extends Component
{
    /**
     * @var \App\Service\MicrosoftGraphService
     */
    private $graphService;

    /**
     * Initialize
     *
     * @param array $config Configuration
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        
        $session = $this->getController()->getRequest()->getSession();
        $accessToken = $session->read('Auth.AccessToken');
        
        $this->graphService = new MicrosoftGraphService($accessToken);
    }

    /**
     * Get all users from Azure AD
     *
     * @return array
     */
    public function getAllUsers(): array
    {
        return $this->graphService->getAllUsers();
    }

    /**
     * Get users formatted for dropdown
     *
     * @return array
     */
    public function getUsersForDropdown(): array
    {
        return $this->graphService->getUsersForDropdown();
    }

    /**
     * Send email via Microsoft Graph
     *
     * @param array $params Email parameters
     * @return array Result with success status and message
     */
    public function sendEmail(array $params): array
    {
        return $this->graphService->sendEmail($params);
    }

    /**
     * Get user profile by ID
     *
     * @param string $userId User ID
     * @return array|null
     */
    public function getUserProfile(string $userId): ?array
    {
        return $this->graphService->getUserProfile($userId);
    }

    /**
     * Get current authenticated user's profile
     *
     * @return array|null
     */
    public function getMyProfile(): ?array
    {
        return $this->graphService->getMyProfile();
    }
}
