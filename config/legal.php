<?php

return [
    'version' => '2026-10-22',
    'responsible' => env('LEGAL_RESPONSIBLE', 'Guilherme Augusto Tavares Cavalcante e Nicollas Ferreira'),
    'institution' => env('LEGAL_INSTITUTION', 'Centro Universitário Senac — Tecnologia em Sistemas para Internet'),
    'contact_emails' => array_values(array_filter(array_map('trim', explode(',', env('LEGAL_CONTACT_EMAILS', 'Guilherme.tavares.cavalcante@gmail.com,Ramosnicollas99@gmail.com'))))),
    'retention_end' => env('LEGAL_RETENTION_END'),
    'documents' => [
        '2026-10-22' => [
            'published_on' => '22/10/2026',
            'terms' => 'legal.versions.v20261022.terms',
            'privacy' => 'legal.versions.v20261022.privacy',
        ],
    ],
];
