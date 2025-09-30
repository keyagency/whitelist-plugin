<?php

return [
    'plugin' => [
        'name' => 'Backend IP Whitelist',
        'description' => 'Restricts backend access to whitelisted IP addresses and ranges.',
    ],
    'permissions' => [
        'access_settings' => 'Manage IP whitelist settings',
    ],
    'settings' => [
        'label' => 'IP Whitelist',
        'description' => 'Configure IP addresses and ranges allowed to access the backend.',
        'keywords' => 'security ip whitelist backend access',
    ],
    'fields' => [
        'whitelist_enabled' => [
            'label' => 'Enable IP Whitelist',
            'comment' => 'Enable or disable IP whitelist protection for the backend',
        ],
        'allowed_ips' => [
            'label' => 'Allowed IP Addresses',
            'comment' => 'Enter IP addresses and CIDR ranges (one per line). Example: 192.168.1.1 or 192.168.1.0/24',
        ],
        'block_message' => [
            'label' => 'Block Message',
            'comment' => 'Message displayed to blocked users',
            'default' => 'Access denied. Your IP address is not authorized to access this area.',
        ],
        'protect_entire_site' => [
            'label' => 'Protect Entire Site',
            'comment' => 'Apply whitelist to the entire website, not just the backend',
        ],
        'allow_localhost' => [
            'label' => 'Always Allow Localhost',
            'comment' => 'Always allow access from localhost (127.0.0.1, ::1) even when whitelist is enabled',
        ],
        'log_blocked_attempts' => [
            'label' => 'Log Blocked Attempts',
            'comment' => 'Log blocked access attempts for security monitoring',
        ],
        'ip_format_help' => [
            'label' => 'IP Address Formats',
            'comment' => 'Supported formats - Single IP (192.168.1.100), IPv4 CIDR (192.168.1.0/24), IPv6 (2001:db8::1), IPv6 CIDR (2001:db8::/32). Leave empty to allow all IPs when whitelist is enabled.',
        ],
    ],
    'tabs' => [
        'general' => 'General',
        'help' => 'Help',
    ],
];