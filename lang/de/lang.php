<?php

return [
    'plugin' => [
        'name' => 'Backend IP Whitelist',
        'description' => 'Beschränkt den Backend-Zugriff auf zugelassene IP-Adressen und Bereiche.',
    ],
    'permissions' => [
        'access_settings' => 'IP-Whitelist-Einstellungen verwalten',
    ],
    'settings' => [
        'label' => 'IP Whitelist',
        'description' => 'Konfigurieren Sie IP-Adressen und Bereiche, die auf das Backend zugreifen dürfen.',
        'keywords' => 'sicherheit ip whitelist backend zugriff',
    ],
    'fields' => [
        'whitelist_enabled' => [
            'label' => 'IP-Whitelist aktivieren',
            'comment' => 'IP-Whitelist-Schutz für das Backend aktivieren oder deaktivieren',
        ],
        'allowed_ips' => [
            'label' => 'Zugelassene IP-Adressen',
            'comment' => 'Geben Sie IP-Adressen und CIDR-Bereiche ein (eine pro Zeile). Beispiel: 192.168.1.1 oder 192.168.1.0/24',
        ],
        'block_message' => [
            'label' => 'Sperrnachricht',
            'comment' => 'Nachricht, die blockierten Benutzern angezeigt wird',
            'default' => 'Zugriff verweigert. Ihre IP-Adresse ist nicht berechtigt, auf diesen Bereich zuzugreifen.',
        ],
        'protect_entire_site' => [
            'label' => 'Gesamte Website schützen',
            'comment' => 'Whitelist auf die gesamte Website anwenden, nicht nur auf das Backend',
        ],
        'allow_localhost' => [
            'label' => 'Localhost immer zulassen',
            'comment' => 'Zugriff von Localhost (127.0.0.1, ::1) immer zulassen, auch wenn Whitelist aktiviert ist',
        ],
        'log_blocked_attempts' => [
            'label' => 'Blockierte Versuche protokollieren',
            'comment' => 'Blockierte Zugriffsversuche für Sicherheitsüberwachung protokollieren',
        ],
        'ip_format_help' => [
            'label' => 'IP-Adressformate',
            'comment' => 'Unterstützte Formate - Einzelne IP (192.168.1.100), IPv4 CIDR (192.168.1.0/24), IPv6 (2001:db8::1), IPv6 CIDR (2001:db8::/32). Leer lassen, um alle IPs zuzulassen, wenn Whitelist aktiviert ist.',
        ],
        'release_notes' => [
            'label' => 'Versionshinweise',
        ],
        'enable_emergency_access' => [
            'label' => 'Notfallzugriff aktivieren',
            'comment' => 'Erlauben Sie blockierten Benutzern, temporären Zugriff per E-Mail anzufordern',
        ],
        'emergency_access_emails' => [
            'label' => 'Administrator-E-Mail-Adressen',
            'comment' => 'E-Mail-Adressen, die Zugriffsanfragen erhalten (eine pro Zeile)',
        ],
        'access_token_duration' => [
            'label' => 'Token-Dauer (Stunden)',
            'comment' => 'Wie lange das Zugangstoken gültig bleibt',
        ],
        'require_manual_approval' => [
            'label' => 'Manuelle Genehmigung erforderlich',
            'comment' => 'Administrator muss auf Genehmigungslink klicken. Falls deaktiviert, wird die IP automatisch bei Anfrage hinzugefügt.',
        ],
        'access_request_button_text' => [
            'label' => 'Zugriffsanfrage Button-Text',
            'comment' => 'Text, der auf dem Zugriffsanfrage-Button angezeigt wird (diskret halten, um Spam zu vermeiden)',
            'default' => 'Zugriff benötigt?',
        ],
    ],
    'tabs' => [
        'general' => 'Allgemein',
        'emergency_access' => 'Notfallzugriff',
        'help' => 'Hilfe',
        'release_notes' => 'Versionshinweise',
    ],
    'emergency' => [
        'request_sent_title' => 'Zugriffsanfrage gesendet',
        'request_sent_message' => 'Ihre Zugriffsanfrage wurde an den Administrator gesendet. Bei Genehmigung können Sie von dieser IP-Adresse auf die Website zugreifen.',
        'error_title' => 'Anfrage fehlgeschlagen',
        'error_disabled' => 'Notfallzugriff ist nicht aktiviert.',
        'error_already_requested' => 'Eine Zugriffsanfrage für diese IP-Adresse ist bereits ausstehend.',
        'error_generic' => 'Ihre Anfrage konnte nicht verarbeitet werden. Bitte kontaktieren Sie den Administrator.',
        'approved_title' => 'Zugriff gewährt',
        'approved_message' => 'Ihre IP-Adresse wurde zur Whitelist hinzugefügt. Sie können jetzt auf die Website zugreifen.',
        'token_expired' => 'Dieses Zugangstoken ist abgelaufen.',
        'token_invalid' => 'Ungültiges Zugangstoken.',
    ],
];