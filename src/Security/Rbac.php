<?php
declare(strict_types=1);

namespace App\Security;

class Rbac
{
    /**
     * Simple permission map per role.
     * Resource is a string key like 'Users', 'TravelRequests', 'Reports'.
     * Action is a controller action like 'index','view','add','edit','delete'.
     */
    protected static array $map = [
        'admin' => ['*' => ['*']],
        'manager' => [
            'TravelRequests' => ['index','view','approve','reject'],
            'Reports' => ['*'],
            'Users' => ['index','view'],
        ],
        'user' => [
            'TravelRequests' => ['index','view','add','edit'],
        ],
    ];

    public static function can(array $user, string $resource, string $action): bool
    {
        $role = $user['role'] ?? 'user';
        // Admin wildcard
        if (isset(self::$map[$role]['*']) && (in_array('*', self::$map[$role]['*'], true) || in_array($action, self::$map[$role]['*'], true))) {
            return true;
        }
        $allowed = self::$map[$role][$resource] ?? [];
        return in_array('*', $allowed, true) || in_array($action, $allowed, true);
    }
}
