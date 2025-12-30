<?php

// EASYTI: Templates de aplicações com configurações automáticas
// Estes templates são usados para pré-configurar aplicações baseadas no framework detectado

return [
    'templates' => [
        'nextjs' => [
            'name' => 'Next.js',
            'description' => 'Framework React com SSR, API Routes e otimizações automáticas',
            'icon' => 'nextjs',
            'color' => '#000000',
            'detection' => [
                'files' => ['next.config.js', 'next.config.mjs', 'next.config.ts'],
                'package_keywords' => ['next'],
            ],
            'defaults' => [
                'build_pack' => 'nixpacks',
                'ports_exposes' => '3000',
                'install_command' => 'npm install',
                'build_command' => 'npm run build',
                'start_command' => 'npm start',
                'health_check_path' => '/',
                'health_check_interval' => 30,
            ],
            'env_suggestions' => [
                'NODE_ENV' => 'production',
                'NEXT_TELEMETRY_DISABLED' => '1',
            ],
        ],

        'nestjs' => [
            'name' => 'NestJS',
            'description' => 'Framework Node.js progressivo para APIs robustas em TypeScript',
            'icon' => 'nestjs',
            'color' => '#E0234E',
            'detection' => [
                'files' => ['nest-cli.json'],
                'package_keywords' => ['@nestjs/core'],
            ],
            'defaults' => [
                'build_pack' => 'nixpacks',
                'ports_exposes' => '3000',
                'install_command' => 'npm install',
                'build_command' => 'npm run build',
                'start_command' => 'npm run start:prod',
                'health_check_path' => '/health',
                'health_check_interval' => 30,
            ],
            'env_suggestions' => [
                'NODE_ENV' => 'production',
            ],
        ],

        'nodejs' => [
            'name' => 'Node.js',
            'description' => 'Aplicações JavaScript server-side com Express, Fastify ou outros',
            'icon' => 'nodejs',
            'color' => '#339933',
            'detection' => [
                'files' => ['package.json'],
                'package_keywords' => ['express', 'fastify', 'koa', 'hapi'],
            ],
            'defaults' => [
                'build_pack' => 'nixpacks',
                'ports_exposes' => '3000',
                'install_command' => 'npm install',
                'build_command' => '',
                'start_command' => 'npm start',
                'health_check_path' => '/',
                'health_check_interval' => 30,
            ],
            'env_suggestions' => [
                'NODE_ENV' => 'production',
            ],
        ],

        'laravel' => [
            'name' => 'Laravel',
            'description' => 'Framework PHP elegante para aplicações web',
            'icon' => 'laravel',
            'color' => '#FF2D20',
            'detection' => [
                'files' => ['artisan', 'composer.json'],
                'package_keywords' => ['laravel/framework'],
            ],
            'defaults' => [
                'build_pack' => 'nixpacks',
                'ports_exposes' => '8000',
                'install_command' => 'composer install --no-dev --optimize-autoloader',
                'build_command' => 'npm install && npm run build',
                'start_command' => 'php artisan serve --host=0.0.0.0 --port=8000',
                'health_check_path' => '/',
                'health_check_interval' => 30,
            ],
            'env_suggestions' => [
                'APP_ENV' => 'production',
                'APP_DEBUG' => 'false',
            ],
        ],

        'python' => [
            'name' => 'Python',
            'description' => 'Aplicações Python com Django, Flask ou FastAPI',
            'icon' => 'python',
            'color' => '#3776AB',
            'detection' => [
                'files' => ['requirements.txt', 'Pipfile', 'pyproject.toml'],
                'package_keywords' => ['django', 'flask', 'fastapi'],
            ],
            'defaults' => [
                'build_pack' => 'nixpacks',
                'ports_exposes' => '8000',
                'install_command' => 'pip install -r requirements.txt',
                'build_command' => '',
                'start_command' => 'python app.py',
                'health_check_path' => '/',
                'health_check_interval' => 30,
            ],
            'env_suggestions' => [
                'PYTHONUNBUFFERED' => '1',
            ],
        ],

        'static' => [
            'name' => 'Site Estático',
            'description' => 'HTML, CSS, JavaScript estático ou sites gerados (React, Vue, etc)',
            'icon' => 'html',
            'color' => '#E34F26',
            'detection' => [
                'files' => ['index.html'],
            ],
            'defaults' => [
                'build_pack' => 'static',
                'ports_exposes' => '80',
                'install_command' => '',
                'build_command' => '',
                'start_command' => '',
                'health_check_path' => '/',
                'health_check_interval' => 30,
                'is_static' => true,
                'publish_directory' => '/',
            ],
            'env_suggestions' => [],
        ],

        'docker' => [
            'name' => 'Docker',
            'description' => 'Aplicação com Dockerfile customizado',
            'icon' => 'docker',
            'color' => '#2496ED',
            'detection' => [
                'files' => ['Dockerfile'],
            ],
            'defaults' => [
                'build_pack' => 'dockerfile',
                'ports_exposes' => '3000',
                'dockerfile_location' => '/Dockerfile',
            ],
            'env_suggestions' => [],
        ],
    ],

    // Mensagens de ajuda contextuais para o formulário
    'help_messages' => [
        'repository_url' => [
            'title' => 'URL do Repositório',
            'description' => 'Cole a URL do seu repositório Git (público ou privado)',
            'examples' => [
                'https://github.com/seu-usuario/seu-projeto',
                'https://gitlab.com/seu-usuario/seu-projeto',
            ],
        ],
        'branch' => [
            'title' => 'Branch',
            'description' => 'Selecione qual branch você quer fazer deploy',
            'default' => 'main ou master',
        ],
        'build_command' => [
            'title' => 'Comando de Build',
            'description' => 'Comando para compilar sua aplicação (opcional)',
            'examples' => [
                'npm run build',
                'yarn build',
            ],
        ],
        'start_command' => [
            'title' => 'Comando de Início',
            'description' => 'Como iniciar sua aplicação',
            'examples' => [
                'npm start',
                'node server.js',
            ],
        ],
        'port' => [
            'title' => 'Porta',
            'description' => 'Em qual porta sua aplicação roda',
            'default' => '3000',
        ],
        'domain' => [
            'title' => 'Domínio',
            'description' => 'Domínio personalizado (opcional). SSL será configurado automaticamente.',
            'examples' => [
                'app.seusite.com.br',
            ],
        ],
    ],

    // Quick start repos de exemplo
    'example_repos' => [
        [
            'name' => 'Next.js Starter',
            'url' => 'https://github.com/vercel/next.js/tree/canary/examples/hello-world',
            'template' => 'nextjs',
        ],
        [
            'name' => 'Express.js API',
            'url' => 'https://github.com/expressjs/express/tree/master/examples/hello-world',
            'template' => 'nodejs',
        ],
    ],
];

