<?php

/**
 * EASYTI: Configurações do EasyTI Cloud
 * 
 * Este arquivo contém as configurações específicas da plataforma EasyTI Cloud,
 * incluindo limites por plano, configurações de branding e opções de multi-tenancy.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Informações da Plataforma
    |--------------------------------------------------------------------------
    */
    'name' => env('EASYTI_NAME', 'EasyTI Cloud'),
    'tagline' => env('EASYTI_TAGLINE', 'Deploy simplificado para sua aplicação'),
    'url' => env('EASYTI_URL', 'https://easyti.cloud'),
    'support_url' => env('EASYTI_SUPPORT_URL', 'https://easyti.cloud/suporte'),

    /*
    |--------------------------------------------------------------------------
    | Limites por Plano
    |--------------------------------------------------------------------------
    |
    | Configuração dos limites de recursos por plano de assinatura.
    | -1 significa ilimitado.
    |
    */
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'max_applications' => 3,
            'max_domains' => 3,
            'max_deployments_per_day' => 10,
            'max_members' => 2,
            'resources' => [
                'cpu_limit' => '0.5',      // 50% de 1 CPU
                'memory_limit' => '512M',
                'memory_swap' => '1G',
            ],
            'features' => [
                'ssl_auto' => true,
                'custom_domains' => true,
                'env_variables' => true,
                'webhooks' => false,
                'priority_support' => false,
            ],
        ],
        
        'professional' => [
            'name' => 'Professional',
            'max_applications' => 10,
            'max_domains' => 10,
            'max_deployments_per_day' => 50,
            'max_members' => 5,
            'resources' => [
                'cpu_limit' => '1',
                'memory_limit' => '1G',
                'memory_swap' => '2G',
            ],
            'features' => [
                'ssl_auto' => true,
                'custom_domains' => true,
                'env_variables' => true,
                'webhooks' => true,
                'priority_support' => false,
            ],
        ],
        
        'enterprise' => [
            'name' => 'Enterprise',
            'max_applications' => -1,     // Ilimitado
            'max_domains' => -1,
            'max_deployments_per_day' => -1,
            'max_members' => -1,
            'resources' => [
                'cpu_limit' => '2',
                'memory_limit' => '4G',
                'memory_swap' => '8G',
            ],
            'features' => [
                'ssl_auto' => true,
                'custom_domains' => true,
                'env_variables' => true,
                'webhooks' => true,
                'priority_support' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates de Aplicação
    |--------------------------------------------------------------------------
    |
    | Templates pré-configurados para tipos de aplicação comuns.
    |
    */
    'templates' => [
        'nextjs' => [
            'name' => 'Next.js',
            'icon' => 'nextjs',
            'description' => 'Aplicação Next.js 14+ com App Router',
            'build_pack' => 'nixpacks',
            'port' => 3000,
            'env_defaults' => [
                'NODE_ENV' => 'production',
                'NEXT_TELEMETRY_DISABLED' => '1',
            ],
        ],
        
        'nestjs' => [
            'name' => 'NestJS',
            'icon' => 'nestjs',
            'description' => 'API NestJS com TypeScript',
            'build_pack' => 'nixpacks',
            'port' => 3000,
            'env_defaults' => [
                'NODE_ENV' => 'production',
                'PORT' => '3000',
            ],
        ],
        
        'nodejs' => [
            'name' => 'Node.js',
            'icon' => 'nodejs',
            'description' => 'Aplicação Node.js genérica',
            'build_pack' => 'nixpacks',
            'port' => 3000,
            'env_defaults' => [
                'NODE_ENV' => 'production',
                'PORT' => '3000',
            ],
        ],
        
        'docker' => [
            'name' => 'Docker',
            'icon' => 'docker',
            'description' => 'Container Docker personalizado',
            'build_pack' => 'dockerfile',
            'port' => null,
            'env_defaults' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Multi-tenancy
    |--------------------------------------------------------------------------
    */
    'multitenancy' => [
        // Prefixo para containers isolados por team
        'container_prefix' => 'team_',
        
        // Prefixo para networks isoladas por team
        'network_prefix' => 'easyti_team_',
        
        // Sufixo para networks
        'network_suffix' => '_network',
    ],

    /*
    |--------------------------------------------------------------------------
    | Funcionalidades Ocultas para Clientes
    |--------------------------------------------------------------------------
    |
    | Lista de rotas/funcionalidades que devem ser ocultadas para clientes
    | (não-admin). Use o middleware 'easyti.admin' para proteger estas rotas.
    |
    */
    'admin_only_routes' => [
        'servers',
        'server/*',
        'settings',
        'settings/*',
        'terminal',
        'terminal/*',
        'source*',
        'destination*',
        'storages*',
    ],

];

