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
        'release_notes' => [
            'label' => 'Release Notes',
        ],
        'enable_emergency_access' => [
            'label' => 'Enable Emergency Access',
            'comment' => 'Allow blocked users to request temporary access via email',
        ],
        'emergency_access_emails' => [
            'label' => 'Admin Email Addresses',
            'comment' => 'Email addresses that will receive access requests (one per line)',
        ],
        'access_token_duration' => [
            'label' => 'Token Duration (hours)',
            'comment' => 'How long the access token remains valid',
        ],
        'require_manual_approval' => [
            'label' => 'Require Manual Approval',
            'comment' => 'Admin must click approval link. If disabled, IP is added automatically when request is made.',
        ],
        'access_request_button_text' => [
            'label' => 'Access Request Button Text',
            'comment' => 'Text shown on the access request button (keep discreet to avoid spam)',
            'default' => 'Need access?',
        ],
    ],
    'tabs' => [
        'general' => 'General',
        'emergency_access' => 'Emergency Access',
        'help' => 'Help',
        'release_notes' => 'Release Notes',
    ],
    'emergency' => [
        'request_sent_title' => 'Access Request Sent',
        'request_sent_message' => 'Your access request has been sent to the administrator. If approved, you will be able to access the site from this IP address.',
        'error_title' => 'Request Failed',
        'error_disabled' => 'Emergency access is not enabled.',
        'error_already_requested' => 'An access request for this IP address is already pending.',
        'error_generic' => 'Unable to process your request. Please contact the administrator.',
        'approved_title' => 'Access Granted',
        'approved_message' => 'Your IP address has been whitelisted. You can now access the site.',
        'token_expired' => 'This access token has expired.',
        'token_invalid' => 'Invalid access token.',
    ],
];