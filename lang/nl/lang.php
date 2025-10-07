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
        'release_notes' => [
            'label' => 'Release Notes',
        ],
        'enable_emergency_access' => [
            'label' => 'Noodtoegang Inschakelen',
            'comment' => 'Sta geblokkeerde gebruikers toe om tijdelijke toegang aan te vragen via e-mail',
        ],
        'emergency_access_emails' => [
            'label' => 'Beheerder E-mailadressen',
            'comment' => 'E-mailadressen die toegangsverzoeken ontvangen (één per regel)',
        ],
        'access_token_duration' => [
            'label' => 'Token Duur (uren)',
            'comment' => 'Hoe lang het toegangstoken geldig blijft',
        ],
        'require_manual_approval' => [
            'label' => 'Handmatige Goedkeuring Vereist',
            'comment' => 'Beheerder moet op goedkeuringslink klikken. Indien uitgeschakeld, wordt IP automatisch toegevoegd bij het verzoek.',
        ],
        'access_request_button_text' => [
            'label' => 'Toegangsverzoek Knoptekst',
            'comment' => 'Tekst die wordt weergegeven op de toegangsverzoek knop (houd discreet om spam te voorkomen)',
            'default' => 'Toegang nodig?',
        ],
    ],
    'tabs' => [
        'general' => 'Algemeen',
        'emergency_access' => 'Noodtoegang',
        'help' => 'Hulp',
        'release_notes' => 'Release Notes',
    ],
    'emergency' => [
        'request_sent_title' => 'Toegangsverzoek Verzonden',
        'request_sent_message' => 'Uw toegangsverzoek is verzonden naar de beheerder. Indien goedgekeurd, kunt u de site benaderen vanaf dit IP-adres.',
        'error_title' => 'Verzoek Mislukt',
        'error_disabled' => 'Noodtoegang is niet ingeschakeld.',
        'error_already_requested' => 'Een toegangsverzoek voor dit IP-adres is al in behandeling.',
        'error_generic' => 'Kan uw verzoek niet verwerken. Neem contact op met de beheerder.',
        'approved_title' => 'Toegang Verleend',
        'approved_message' => 'Uw IP-adres is op de whitelist geplaatst. U kunt nu toegang krijgen tot de site.',
        'token_expired' => 'Dit toegangstoken is verlopen.',
        'token_invalid' => 'Ongeldig toegangstoken.',
    ],
];