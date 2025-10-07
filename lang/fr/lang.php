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
        'enable_emergency_access' => [
            'label' => 'Activer l\'accès d\'urgence',
            'comment' => 'Permettre aux utilisateurs bloqués de demander un accès temporaire par e-mail',
        ],
        'emergency_access_emails' => [
            'label' => 'Adresses e-mail des administrateurs',
            'comment' => 'Adresses e-mail qui recevront les demandes d\'accès (une par ligne)',
        ],
        'access_token_duration' => [
            'label' => 'Durée du jeton (heures)',
            'comment' => 'Combien de temps le jeton d\'accès reste valide',
        ],
        'require_manual_approval' => [
            'label' => 'Approbation manuelle requise',
            'comment' => 'L\'administrateur doit cliquer sur le lien d\'approbation. Si désactivé, l\'IP est ajoutée automatiquement lors de la demande.',
        ],
        'access_request_button_text' => [
            'label' => 'Texte du bouton de demande d\'accès',
            'comment' => 'Texte affiché sur le bouton de demande d\'accès (restez discret pour éviter le spam)',
            'default' => 'Besoin d\'accès?',
        ],
    ],
    'tabs' => [
        'general' => 'Général',
        'emergency_access' => 'Accès d\'urgence',
        'help' => 'Aide',
        'release_notes' => 'Notes de version',
    ],
    'emergency' => [
        'request_sent_title' => 'Demande d\'accès envoyée',
        'request_sent_message' => 'Votre demande d\'accès a été envoyée à l\'administrateur. Si elle est approuvée, vous pourrez accéder au site depuis cette adresse IP.',
        'error_title' => 'Demande échouée',
        'error_disabled' => 'L\'accès d\'urgence n\'est pas activé.',
        'error_already_requested' => 'Une demande d\'accès pour cette adresse IP est déjà en attente.',
        'error_generic' => 'Impossible de traiter votre demande. Veuillez contacter l\'administrateur.',
        'approved_title' => 'Accès accordé',
        'approved_message' => 'Votre adresse IP a été ajoutée à la liste blanche. Vous pouvez maintenant accéder au site.',
        'token_expired' => 'Ce jeton d\'accès a expiré.',
        'token_invalid' => 'Jeton d\'accès invalide.',
    ],
];