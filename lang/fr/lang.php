<?php

return [
    'plugin' => [
        'name' => 'Backend IP Whitelist',
        'description' => 'Restreint l\'accès backend aux adresses IP et plages autorisées.',
    ],
    'permissions' => [
        'access_settings' => 'Gérer les paramètres de liste blanche IP',
    ],
    'settings' => [
        'label' => 'Liste blanche IP',
        'description' => 'Configurez les adresses IP et les plages autorisées à accéder au backend.',
        'keywords' => 'sécurité ip liste blanche backend accès',
    ],
    'fields' => [
        'whitelist_enabled' => [
            'label' => 'Activer la liste blanche IP',
            'comment' => 'Activer ou désactiver la protection par liste blanche IP pour le backend',
        ],
        'allowed_ips' => [
            'label' => 'Adresses IP autorisées',
            'comment' => 'Entrez les adresses IP et les plages CIDR (une par ligne). Exemple: 192.168.1.1 ou 192.168.1.0/24',
        ],
        'block_message' => [
            'label' => 'Message de blocage',
            'comment' => 'Message affiché aux utilisateurs bloqués',
            'default' => 'Accès refusé. Votre adresse IP n\'est pas autorisée à accéder à cette zone.',
        ],
        'protect_entire_site' => [
            'label' => 'Protéger l\'ensemble du site',
            'comment' => 'Appliquer la liste blanche à l\'ensemble du site web, pas seulement au backend',
        ],
        'allow_localhost' => [
            'label' => 'Toujours autoriser localhost',
            'comment' => 'Toujours autoriser l\'accès depuis localhost (127.0.0.1, ::1) même lorsque la liste blanche est activée',
        ],
        'log_blocked_attempts' => [
            'label' => 'Journaliser les tentatives bloquées',
            'comment' => 'Journaliser les tentatives d\'accès bloquées pour la surveillance de la sécurité',
        ],
        'ip_format_help' => [
            'label' => 'Formats d\'adresse IP',
            'comment' => 'Formats supportés - IP unique (192.168.1.100), IPv4 CIDR (192.168.1.0/24), IPv6 (2001:db8::1), IPv6 CIDR (2001:db8::/32). Laisser vide pour autoriser toutes les IPs lorsque la liste blanche est activée.',
        ],
        'release_notes' => [
            'label' => 'Notes de version',
        ],
    ],
    'tabs' => [
        'general' => 'Général',
        'help' => 'Aide',
        'release_notes' => 'Notes de version',
    ],
];