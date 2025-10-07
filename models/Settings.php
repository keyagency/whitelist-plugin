<?php namespace Key\Whitelist\Models;

use October\Rain\Database\Model;
use Cache;

class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'key_whitelist_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'whitelist_enabled' => ['boolean'],
        'allowed_ips' => ['string'],
        'block_message' => ['string'],
        'protect_entire_site' => ['boolean'],
        'allow_localhost' => ['boolean'],
        'log_blocked_attempts' => ['boolean']
    ];

    /**
     * After save event to clear cache
     */
    public function afterSave()
    {
        Cache::forget('key_whitelist_settings');
    }

    /**
     * Parse and validate IP addresses and ranges
     */
    public function getWhitelistedIps()
    {
        $allowedIps = $this->get('allowed_ips', '');

        if (empty($allowedIps)) {
            return [];
        }

        // Split by newlines and filter empty lines
        $ips = array_filter(
            array_map('trim', explode("\n", $allowedIps)),
            function($ip) { return !empty($ip); }
        );

        return $ips;
    }

    /**
     * Check if an IP address is whitelisted
     */
    public function isIpWhitelisted($ip)
    {
        if (!$this->get('whitelist_enabled', false)) {
            return true; // If whitelist is disabled, allow all
        }

        // Allow localhost IPs if setting is enabled
        if ($this->get('allow_localhost', true)) {
            $localhostIps = ['127.0.0.1', '::1', 'localhost'];
            if (in_array($ip, $localhostIps)) {
                return true;
            }
        }

        $whitelistedIps = $this->getWhitelistedIps();

        if (empty($whitelistedIps)) {
            return true; // If no IPs configured, allow all
        }

        foreach ($whitelistedIps as $allowedIp) {
            if ($this->matchesIpPattern($ip, $allowedIp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP matches a pattern (single IP or CIDR range)
     */
    private function matchesIpPattern($ip, $pattern)
    {
        // Direct match
        if ($ip === $pattern) {
            return true;
        }

        // CIDR range matching
        if (strpos($pattern, '/') !== false) {
            list($range, $netmask) = explode('/', $pattern, 2);

            if (filter_var($range, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $this->matchesCidrIPv4($ip, $range, $netmask);
            } elseif (filter_var($range, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return $this->matchesCidrIPv6($ip, $range, $netmask);
            }
        }

        return false;
    }

    /**
     * Check IPv4 CIDR match
     */
    private function matchesCidrIPv4($ip, $range, $netmask)
    {
        $ipLong = ip2long($ip);
        $rangeLong = ip2long($range);
        $mask = -1 << (32 - (int)$netmask);

        return ($ipLong & $mask) === ($rangeLong & $mask);
    }

    /**
     * Check IPv6 CIDR match
     */
    private function matchesCidrIPv6($ip, $range, $netmask)
    {
        $ipBin = inet_pton($ip);
        $rangeBin = inet_pton($range);

        if ($ipBin === false || $rangeBin === false) {
            return false;
        }

        $maskBytes = (int)$netmask >> 3;
        $maskBits = (int)$netmask & 7;

        if ($maskBytes > 0 && substr($ipBin, 0, $maskBytes) !== substr($rangeBin, 0, $maskBytes)) {
            return false;
        }

        if ($maskBits > 0) {
            $mask = 0xFF << (8 - $maskBits);
            $ipByte = ord(substr($ipBin, $maskBytes, 1));
            $rangeByte = ord(substr($rangeBin, $maskBytes, 1));
            return ($ipByte & $mask) === ($rangeByte & $mask);
        }

        return true;
    }
}