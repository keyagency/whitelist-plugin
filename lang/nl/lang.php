<?php

return [
    'plugin' => [
        'name' => 'Backend IP Whitelist',
        'description' => 'Beperkt backend toegang tot toegestane IP-adressen en ranges.',
    ],
    'permissions' => [
        'access_settings' => 'Beheer IP whitelist instellingen',
    ],
    'settings' => [
        'label' => 'IP Whitelist',
        'description' => 'Configureer IP-adressen en ranges die toegang hebben tot de backend.',
        'keywords' => 'beveiliging ip whitelist backend toegang',
    ],
    'fields' => [
        'whitelist_enabled' => [
            'label' => 'IP Whitelist Inschakelen',
            'comment' => 'Schakel IP whitelist bescherming voor de backend in of uit',
        ],
        'allowed_ips' => [
            'label' => 'Toegestane IP-adressen',
            'comment' => 'Voer IP-adressen en CIDR ranges in (één per regel). Voorbeeld: 192.168.1.1 of 192.168.1.0/24',
        ],
        'block_message' => [
            'label' => 'Blokkeer Bericht',
            'comment' => 'Bericht dat wordt weergegeven aan geblokkeerde gebruikers',
            'default' => 'Toegang geweigerd. Uw IP-adres is niet geautoriseerd om toegang te krijgen tot dit gebied.',
        ],
        'protect_entire_site' => [
            'label' => 'Bescherm Gehele Website',
            'comment' => 'Pas whitelist toe op de gehele website, niet alleen de backend',
        ],
        'allow_localhost' => [
            'label' => 'Sta Localhost Altijd Toe',
            'comment' => 'Sta altijd toegang toe vanaf localhost (127.0.0.1, ::1), zelfs wanneer whitelist is ingeschakeld',
        ],
        'log_blocked_attempts' => [
            'label' => 'Log Geblokkeerde Pogingen',
            'comment' => 'Log geblokkeerde toegangspogingen voor beveiligingsmonitoring',
        ],
        'ip_format_help' => [
            'label' => 'IP-adres Formaten',
            'comment' => 'Ondersteunde formaten - Enkel IP (192.168.1.100), IPv4 CIDR (192.168.1.0/24), IPv6 (2001:db8::1), IPv6 CIDR (2001:db8::/32). Laat leeg om alle IP-adressen toe te staan wanneer whitelist is ingeschakeld.',
        ],
    ],
    'tabs' => [
        'general' => 'Algemeen',
        'help' => 'Hulp',
    ],
];