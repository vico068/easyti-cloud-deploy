<nav wire:poll.10000ms="checkStatus" class="pb-6">
    <x-resources.breadcrumbs :resource="$application" :parameters="$parameters" :title="$lastDeploymentInfo" :lastDeploymentLink="$lastDeploymentLink" />
    
    {{-- ============================================== --}}
    {{-- NAVBAR SIMPLIFICADA PARA CLIENTES              --}}
    {{-- ============================================== --}}
    @if(!isEasytiAdmin())
    
    <div class="bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 p-4 mt-4">
        <!-- Status e Nome -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
                @php
                    $status = $application->status ?? 'stopped';
                    $isRunning = str($status)->contains('running');
                    $isBuilding = str($status)->contains('building') || str($status)->contains('starting');
                @endphp
                
                <!-- Status Indicator -->
                <div class="relative">
                    @if($isRunning)
                        <div class="w-4 h-4 bg-green-500 rounded-full">
                            <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-25"></div>
                        </div>
                    @elseif($isBuilding)
                        <div class="w-4 h-4 bg-yellow-500 rounded-full animate-pulse"></div>
                    @else
                        <div class="w-4 h-4 bg-neutral-400 rounded-full"></div>
                    @endif
                </div>
                
                <div>
                    <h2 class="text-xl font-bold dark:text-white">{{ $application->name }}</h2>
                    <div class="flex items-center gap-2 text-sm">
                        @if($isRunning)
                            <span class="text-green-500 font-medium">● Online</span>
                        @elseif($isBuilding)
                            <span class="text-yellow-500 font-medium">● Iniciando...</span>
                        @else
                            <span class="text-neutral-400 font-medium">● Offline</span>
                        @endif
                        
                        @if($application->fqdn)
                            <span class="text-neutral-300 dark:text-neutral-600">•</span>
                            <a href="{{ str($application->fqdn)->before(',') }}" target="_blank" class="text-[#4DC4E0] hover:underline">
                                {{ str($application->fqdn)->before(',')->after('://') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Botões de Ação Simplificados -->
            <div class="flex flex-wrap gap-2">
                @if ($application->build_pack === 'dockercompose' && is_null($application->docker_compose_raw))
                    <div class="text-warning text-sm">Por favor, carregue um arquivo Compose.</div>
                @else
                    @if (!str($application->status)->startsWith('exited'))
                        <!-- Botão Atualizar/Redeploy -->
                        <x-forms.button title="Atualizar aplicação" wire:click='deploy' 
                            class="!bg-gradient-to-r !from-[#4DC4E0] !to-[#2E7D9A] hover:!from-[#7DD8ED] hover:!to-[#4DC4E0] !text-white !border-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Atualizar
                        </x-forms.button>

                        @if ($application->build_pack !== 'dockercompose' && !$application->destination->server->isSwarm())
                            <!-- Botão Reiniciar -->
                            <x-forms.button title="Reiniciar sem reconstruir" wire:click='restart'>
                                <svg class="w-5 h-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reiniciar
                            </x-forms.button>
                        @endif

                        <!-- Botão Parar -->
                        <x-modal-confirmation title="Confirmar parada da aplicação?" buttonTitle="Parar"
                            submitAction="stop" :checkboxes="$checkboxes" :actions="[
                                'Esta aplicação será parada.',
                                'Todos os dados não persistentes serão removidos.',
                            ]" :confirmWithText="false" :confirmWithPassword="false"
                            step1ButtonText="Continuar" step2ButtonText="Confirmar">
                            <x-slot:button-title>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-error" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="6" y="4" width="4" height="16"></rect>
                                    <rect x="14" y="4" width="4" height="16"></rect>
                                </svg>
                                Parar
                            </x-slot:button-title>
                        </x-modal-confirmation>
                    @else
                        <!-- Botão Iniciar -->
                        <x-forms.button wire:click='deploy' 
                            class="!bg-gradient-to-r !from-green-500 !to-green-600 hover:!from-green-400 hover:!to-green-500 !text-white !border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            Iniciar
                        </x-forms.button>
                    @endif
                @endif
            </div>
        </div>

        <!-- Navegação Simplificada -->
        <nav class="flex gap-1 border-t border-neutral-200 dark:border-coolgray-200 pt-4 overflow-x-auto">
            <a class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('project.application.configuration') ? 'bg-[#4DC4E0]/10 text-[#4DC4E0]' : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-coolgray-200' }}" 
               {{ wireNavigate() }}
               href="{{ route('project.application.configuration', $parameters) }}">
                ⚙️ Configurações
            </a>
            <a class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('project.application.environment-variables') ? 'bg-[#4DC4E0]/10 text-[#4DC4E0]' : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-coolgray-200' }}" 
               {{ wireNavigate() }}
               href="{{ route('project.application.environment-variables', $parameters) }}">
                🔐 Variáveis
            </a>
            <a class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('project.application.deployment.index') ? 'bg-[#4DC4E0]/10 text-[#4DC4E0]' : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-coolgray-200' }}" 
               {{ wireNavigate() }}
               href="{{ route('project.application.deployment.index', $parameters) }}">
                🚀 Deploys
            </a>
            <a class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('project.application.logs') ? 'bg-[#4DC4E0]/10 text-[#4DC4E0]' : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-coolgray-200' }}" 
               href="{{ route('project.application.logs', $parameters) }}">
                📋 Logs
                @if ($application->restart_count > 0 && !str($application->status)->startsWith('exited'))
                    <span class="ml-1 text-warning">⚠️</span>
                @endif
            </a>
            <x-applications.links :application="$application" />
        </nav>
    </div>

    {{-- ============================================== --}}
    {{-- NAVBAR ORIGINAL PARA ADMIN                     --}}
    {{-- ============================================== --}}
    @else
    
    <div class="navbar-main">
        <nav class="flex shrink-0 gap-6 items-center whitespace-nowrap scrollbar min-h-10">
            <a class="{{ request()->routeIs('project.application.configuration') ? 'dark:text-white' : '' }}" {{ wireNavigate() }}
                href="{{ route('project.application.configuration', $parameters) }}">
                Configuração
            </a>
            <a class="{{ request()->routeIs('project.application.deployment.index') ? 'dark:text-white' : '' }}" {{ wireNavigate() }}
                href="{{ route('project.application.deployment.index', $parameters) }}">
                Deployments
            </a>
            <a class="{{ request()->routeIs('project.application.logs') ? 'dark:text-white' : '' }}"
                href="{{ route('project.application.logs', $parameters) }}">
                <div class="flex items-center gap-1">
                    Logs
                    @if ($application->restart_count > 0 && !str($application->status)->startsWith('exited'))
                        <svg class="w-4 h-4 dark:text-warning" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" title="Container reiniciou {{ $application->restart_count }} vez{{ $application->restart_count > 1 ? 'es' : '' }}">
                            <path d="M12 2L1 21h22L12 2zm0 4l7.53 13H4.47L12 6zm-1 5v4h2v-4h-2zm0 5v2h2v-2h-2z"/>
                        </svg>
                    @endif
                </div>
            </a>
            @if (!$application->destination->server->isSwarm())
                @can('canAccessTerminal')
                    <a class="{{ request()->routeIs('project.application.command') ? 'dark:text-white' : '' }}"
                        href="{{ route('project.application.command', $parameters) }}">
                        Terminal
                    </a>
                @endcan
            @endif
            <x-applications.links :application="$application" />
        </nav>
        <div class="flex flex-wrap gap-2 items-center">
            @if ($application->build_pack === 'dockercompose' && is_null($application->docker_compose_raw))
                <div>Por favor, carregue um arquivo Compose.</div>
            @else
                @if (!$application->destination->server->isSwarm())
                    <div>
                        <x-applications.advanced :application="$application" />
                    </div>
                @endif
                <div class="flex flex-wrap gap-2">
                    @if (!str($application->status)->startsWith('exited'))
                        @if (!$application->destination->server->isSwarm())
                            <x-forms.button title="Com rolling update se possível" wire:click='deploy'>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 dark:text-orange-400"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.09 4.01l.496 -.495a2 2 0 0 1 2.828 0l7.071 7.07a2 2 0 0 1 0 2.83l-7.07 7.07a2 2 0 0 1 -2.83 0l-7.07 -7.07a2 2 0 0 1 0 -2.83l3.535 -3.535h-3.988">
                                    </path>
                                    <path d="M7.05 11.038v-3.988"></path>
                                </svg>
                                Redeploy
                            </x-forms.button>
                        @endif
                        @if ($application->build_pack !== 'dockercompose')
                            @if ($application->destination->server->isSwarm())
                                <x-forms.button title="Redeploy Swarm Service (rolling update)" wire:click='deploy'>
                                    <svg class="w-5 h-5 dark:text-warning" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M19.933 13.041a8 8 0 1 1-9.925-8.788c3.899-1 7.935 1.007 9.425 4.747" />
                                            <path d="M20 4v5h-5" />
                                        </g>
                                    </svg>
                                    Atualizar Serviço
                                </x-forms.button>
                            @else
                                <x-forms.button title="Reiniciar sem reconstruir" wire:click='restart'>
                                    <svg class="w-5 h-5 dark:text-warning" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M19.933 13.041a8 8 0 1 1-9.925-8.788c3.899-1 7.935 1.007 9.425 4.747" />
                                            <path d="M20 4v5h-5" />
                                        </g>
                                    </svg>
                                    Reiniciar
                                </x-forms.button>
                            @endif
                        @endif
                        <x-modal-confirmation title="Confirmar parada da aplicação?" buttonTitle="Parar"
                            submitAction="stop" :checkboxes="$checkboxes" :actions="[
                                'Esta aplicação será parada.',
                                'Todos os dados não persistentes serão removidos.',
                            ]" :confirmWithText="false" :confirmWithPassword="false"
                            step1ButtonText="Continuar" step2ButtonText="Confirmar">
                            <x-slot:button-title>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-error" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M6 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z"></path>
                                    <path d="M14 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z"></path>
                                </svg>
                                Parar
                            </x-slot:button-title>
                        </x-modal-confirmation>
                    @else
                        <x-forms.button wire:click='deploy'>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 dark:text-warning" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 4v16l13 -8z" />
                            </svg>
                            Deploy
                        </x-forms.button>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    @endif
</nav>
