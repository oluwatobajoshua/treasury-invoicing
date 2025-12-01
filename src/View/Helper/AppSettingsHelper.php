<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * AppSettings Helper
 *
 * Provides easy access to application settings throughout views
 */
class AppSettingsHelper extends Helper
{
    /**
     * Get a setting value
     *
     * @param string $key The setting key (dot notation supported)
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Configure::read('AppSettings.' . $key, $default);
    }
    
    /**
     * Get app name
     *
     * @return string
     */
    public function appName(): string
    {
        return $this->get('app_name', 'Treasury Invoicing System');
    }
    
    /**
     * Get company name
     *
     * @return string
     */
    public function companyName(): string
    {
        return $this->get('company_name', '');
    }
    
    /**
     * Get company address
     *
     * @return string
     */
    public function companyAddress(): string
    {
        return $this->get('company_address', '');
    }
    
    /**
     * Get company phone
     *
     * @return string
     */
    public function companyPhone(): string
    {
        return $this->get('company_phone', '');
    }
    
    /**
     * Get company email
     *
     * @return string
     */
    public function companyEmail(): string
    {
        return $this->get('company_email', '');
    }
    
    /**
     * Get company website
     *
     * @return string
     */
    public function companyWebsite(): string
    {
        return $this->get('company_website', '');
    }
    
    /**
     * Get tax ID
     *
     * @return string
     */
    public function taxId(): string
    {
        return $this->get('tax_id', '');
    }
    
    /**
     * Get currency code
     *
     * @return string
     */
    public function currency(): string
    {
        return $this->get('currency', 'USD');
    }
    
    /**
     * Get currency symbol
     *
     * @return string
     */
    public function currencySymbol(): string
    {
        return $this->get('currency_symbol', '$');
    }
    
    /**
     * Format money with currency symbol
     *
     * @param float|int $amount The amount
     * @param int $decimals Number of decimal places
     * @return string
     */
    public function formatMoney($amount, int $decimals = 2): string
    {
        return $this->currencySymbol() . number_format((float)$amount, $decimals);
    }
    
    /**
     * Get date format
     *
     * @return string
     */
    public function dateFormat(): string
    {
        return $this->get('date_format', 'Y-m-d');
    }
    
    /**
     * Format a date according to settings
     *
     * @param \Cake\I18n\FrozenTime|string|null $date The date
     * @return string
     */
    public function formatDate($date): string
    {
        if (empty($date)) {
            return '';
        }
        
        if (is_string($date)) {
            $date = new \Cake\I18n\FrozenTime($date);
        }
        
        return $date->format($this->dateFormat());
    }
    
    /**
     * Get timezone
     *
     * @return string
     */
    public function timezone(): string
    {
        return $this->get('timezone', 'UTC');
    }
    
    /**
     * Check if email notifications are enabled
     *
     * @return bool
     */
    public function emailNotificationsEnabled(): bool
    {
        return (bool)$this->get('enable_email_notifications', true);
    }
    
    /**
     * Check if audit logging is enabled
     *
     * @return bool
     */
    public function auditLogEnabled(): bool
    {
        return (bool)$this->get('enable_audit_log', true);
    }
    
    /**
     * Get items per page
     *
     * @return int
     */
    public function itemsPerPage(): int
    {
        return (int)$this->get('items_per_page', 25);
    }
    
    /**
     * Get session timeout in minutes
     *
     * @return int
     */
    public function sessionTimeout(): int
    {
        return (int)$this->get('session_timeout', 30);
    }
}
