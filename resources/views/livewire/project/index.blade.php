<div>
    <x-slot:title>
        {{ isEasytiAdmin() ? 'Projetos | EasyTI Cloud' : 'Minhas Aplicações | EasyTI Cloud' }}
    </x-slot>

    {{-- ============================================== --}}
    {{-- PÁGINA PARA CLIENTES (Simplificada)           --}}
    {{-- ============================================== --}}
    @if(!isEasytiAdmin())

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">📱 Minhas Aplicações</h1>
                <p class="text-neutral-500 dark:text-neutral-400">Gerencie todas as suas aplicações em um só lugar</p>
            </div>
            @can('createAnyResource')
                <x-modal-input title="Novo Projeto">
                    <x-slot:content>
                        <button class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0] text-white rounded-lg font-medium shadow-lg transition-all">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Nova Aplicação
                        </button>
                    </x-slot:content>
                    <livewire:project.add-empty />
                </x-modal-input>
            @endcan
        </div>

        @if($projects->count() > 0)
            <div class="space-y-6">
                @foreach ($projects as $project)
                    @php
                        $apps = $project->applications ?? collect();
                        $services = $project->services ?? collect();
                        $databases = $project->databases() ?? collect();
                        $allResources = $apps->concat($services);
                    @endphp

                    <!-- Card do Projeto -->
                    <div class="bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 overflow-hidden">
                        
                        <!-- Header do Projeto -->
                        <div class="px-5 py-4 border-b border-neutral-100 dark:border-coolgray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#4DC4E0]/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-semibold dark:text-white">{{ $project->name }}</h2>
                                    @if($project->description)
                                        <p class="text-sm text-neutral-500">{{ $project->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($project->environments->first())
                                    @can('createAnyResource')
                                        <a href="{{ route('project.resource.create', [
                                                'project_uuid' => $project->uuid,
                                                'environment_uuid' => $project->environments->first()->uuid,
                                            ]) }}"
                                           {{ wireNavigate() }}
                                           class="px-3 py-1.5 text-sm font-medium text-[#4DC4E0] hover:bg-[#4DC4E0]/10 rounded-lg transition-colors">
                                            + Adicionar
                                        </a>
                                    @endcan
                                @endif
                                @can('update', $project)
                                    <a href="{{ route('project.edit', ['project_uuid' => $project->uuid]) }}"
                                       {{ wireNavigate() }}
                                       class="p-2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                        </svg>
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <!-- Lista de Aplicações -->
                        @if($allResources->count() > 0)
                            <div class="divide-y divide-neutral-100 dark:divide-coolgray-200">
                                @foreach($allResources as $resource)
                                    @php
                                        $status = $resource->status ?? 'stopped';
                                        $isRunning = str($status)->contains('running');
                                        $isBuilding = str($status)->contains('building') || str($status)->contains('starting');
                                    @endphp
                                    <a href="{{ $resource->link() ?? '#' }}" {{ wireNavigate() }} 
                                       class="flex items-center gap-4 px-5 py-4 hover:bg-neutral-50 dark:hover:bg-coolgray-200 transition-colors">
                                        
                                        <!-- Status Indicator -->
                                        <div class="relative">
                                            @if($isRunning)
                                                <div class="w-3 h-3 bg-green-500 rounded-full">
                                                    <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-25"></div>
                                                </div>
                                            @elseif($isBuilding)
                                                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                            @else
                                                <div class="w-3 h-3 bg-neutral-300 dark:bg-neutral-600 rounded-full"></div>
                                            @endif
                                        </div>
                                        
                                        <!-- App Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium dark:text-white truncate">{{ $resource->name }}</h3>
                                            @if($resource->fqdn)
                                                <p class="text-sm text-[#4DC4E0] truncate">
                                                    {{ str($resource->fqdn)->before(',') }}
                                                </p>
                                            @else
                                                <p class="text-sm text-neutral-400">Sem domínio configurado</p>
                                            @endif
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="flex items-center gap-3">
                                            @if($isRunning)
                                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-full">
                                                    Online
                                                </span>
                                            @elseif($isBuilding)
                                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 rounded-full">
                                                    Iniciando...
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-medium bg-neutral-100 dark:bg-neutral-500/20 text-neutral-500 rounded-full">
                                                    Offline
                                                </span>
                                            @endif

                                            <svg class="w-5 h-5 text-neutral-300 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <!-- Estado Vazio do Projeto -->
                            <div class="px-5 py-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-neutral-100 dark:bg-coolgray-200 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                </div>
                                <p class="text-neutral-500 dark:text-neutral-400 mb-3">Nenhuma aplicação neste projeto</p>
                                @if ($project->environments->first())
                                    <a href="{{ route('project.resource.create', ['project_uuid' => $project->uuid, 'environment_uuid' => $project->environments->first()->uuid]) }}"
                                       {{ wireNavigate() }}
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#4DC4E0] hover:bg-[#2E7D9A] text-white rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Criar Aplicação
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado Vazio Global -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-[#4DC4E0]/10 rounded-full mb-6">
                    <svg class="w-10 h-10 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold dark:text-white mb-2">Comece criando sua primeira aplicação! 🚀</h2>
                <p class="text-neutral-500 dark:text-neutral-400 mb-6 max-w-md mx-auto">
                    É super fácil! Conecte seu repositório Git e faça deploy em minutos.
                </p>
                <x-modal-input title="Novo Projeto">
                    <x-slot:content>
                        <button class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0] text-white rounded-xl font-semibold shadow-lg transition-all transform hover:scale-[1.02]">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Criar Primeira Aplicação
                        </button>
                    </x-slot:content>
                    <livewire:project.add-empty />
                </x-modal-input>
            </div>
        @endif

    {{-- ============================================== --}}
    {{-- PÁGINA PARA ADMIN (Original)                  --}}
    {{-- ============================================== --}}
    @else

        <div class="flex gap-2">
            <h1>Projetos</h1>
            @can('createAnyResource')
                <x-modal-input buttonTitle="+ Adicionar" title="Novo Projeto">
                    <livewire:project.add-empty />
                </x-modal-input>
            @endcan
        </div>
        <div class="subtitle">Todos os seus projetos estão aqui.</div>
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 -mt-1">
            @foreach ($projects as $project)
                <div class="relative gap-2 cursor-pointer coolbox group">
                    <a href="{{ $project->navigateTo() }}" class="absolute inset-0"></a>
                    <div class="flex flex-1 mx-6">
                        <div class="flex flex-col justify-center flex-1">
                            <div class="box-title">{{ $project->name }}</div>
                            <div class="box-description">
                                {{ $project->description }}
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center justify-center gap-4 text-xs font-bold">
                            @if ($project->environments->first())
                                @can('createAnyResource')
                                    <a class="hover:underline" {{ wireNavigate() }}
                                        href="{{ route('project.resource.create', [
                                            'project_uuid' => $project->uuid,
                                            'environment_uuid' => $project->environments->first()->uuid,
                                        ]) }}">
                                        + Adicionar Recurso
                                    </a>
                                @endcan
                            @endif
                            @can('update', $project)
                                <a class="hover:underline" {{ wireNavigate() }}
                                    href="{{ route('project.edit', ['project_uuid' => $project->uuid]) }}">
                                    Configurações
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>
