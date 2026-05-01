<?php

return [
    'enabled' => env('SSO_ENABLED', false),
    'driver'  => env('SSO_DRIVER', 'saml'), // 'saml' | 'oidc' | 'none'

    'map' => [
        'external_id' => env('SSO_MAP_ID', 'nameid'),
        'email'       => env('SSO_MAP_EMAIL', 'email'),
        'name'        => env('SSO_MAP_NAME', 'name'),
        'role'        => env('SSO_MAP_ROLE', null), // optional
    ],

    'saml' => [
        'idp_entity_id' => env('SAML_IDP_ENTITY_ID'),
        'idp_sso_url'   => env('SAML_IDP_SSO_URL'),
        'idp_slo_url'   => env('SAML_IDP_SLO_URL'),
        'idp_x509'      => env('SAML_IDP_X509_CERT'),
    ],
];
