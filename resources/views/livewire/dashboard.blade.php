<div>
    <x-slot:title>
        {{ isEasytiAdmin() ? 'Dashboard | EasyTI Cloud' : 'Início | EasyTI Cloud' }}
    </x-slot>
    
    @if (session('error'))
        <span x-data x-init="$wire.emit('error', '{{ session('error') }}')" />
    @endif

    {{-- ============================================== --}}
    {{-- DASHBOARD PARA CLIENTES (Simplificado)        --}}
    {{-- ============================================== --}}
    @if(!isEasytiAdmin())
    
        <!-- Cabeçalho de Boas-vindas -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold dark:text-white">
                👋 Olá, {{ auth()->user()->name }}!
            </h1>
            <p class="text-neutral-500 dark:text-neutral-400 mt-1">
                Gerencie suas aplicações de forma simples e rápida.
            </p>
        </div>

        <!-- Cards de Resumo -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Total de Aplicações -->
            <div class="bg-white dark:bg-coolgray-100 rounded-xl p-6 border border-neutral-200 dark:border-coolgray-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-[#4DC4E0]/10 rounded-lg">
                        <svg class="w-6 h-6 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold dark:text-white">
                            {{ $projects->sum(fn($p) => $p->applications()->count() + $p->services()->count()) }}
                        </p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Aplicações</p>
                    </div>
                </div>
            </div>

            <!-- Aplicações Online -->
            <div class="bg-white dark:bg-coolgray-100 rounded-xl p-6 border border-neutral-200 dark:border-coolgray-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold dark:text-white text-green-500">
                            @php
                                $onlineCount = 0;
                                foreach($projects as $project) {
                                    foreach($project->applications ?? [] as $app) {
                                        if(str($app->status ?? '')->contains('running')) $onlineCount++;
                                    }
                                }
                            @endphp
                            {{ $onlineCount }}
                        </p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Online</p>
                    </div>
                </div>
            </div>

            <!-- Projetos -->
            <div class="bg-white dark:bg-coolgray-100 rounded-xl p-6 border border-neutral-200 dark:border-coolgray-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-[#1B3A5F]/10 rounded-lg">
                        <svg class="w-6 h-6 text-[#1B3A5F] dark:text-[#7DD8ED]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold dark:text-white">{{ $projects->count() }}</p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Projetos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão Grande de Nova Aplicação -->
        @if ($projects->count() > 0)
            <div class="mb-8">
                <a href="/projects" {{ wireNavigate() }}
                   class="flex items-center justify-center gap-3 w-full py-4 px-6 rounded-xl font-semibold text-white text-lg
                          bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0]
                          shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.01]">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Criar Nova Aplicação
                </a>
            </div>
        @endif

        <!-- Lista de Projetos/Aplicações -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold dark:text-white">🚀 Suas Aplicações</h2>
            </div>

            @if ($projects->count() > 0)
                <div class="space-y-4">
                    @foreach ($projects as $project)
                        @php
                            $apps = $project->applications ?? collect();
                            $services = $project->services ?? collect();
                            $allResources = $apps->concat($services);
                        @endphp
                        
                        @if($allResources->count() > 0)
                            @foreach($allResources as $resource)
                                @php
                                    $status = $resource->status ?? 'stopped';
                                    $isRunning = str($status)->contains('running');
                                    $isBuilding = str($status)->contains('building') || str($status)->contains('starting');
                                @endphp
                                <div class="bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 hover:border-[#4DC4E0] transition-colors overflow-hidden">
                                    <a href="{{ $resource->link() ?? '#' }}" {{ wireNavigate() }} class="block p-5">
                                        <div class="flex items-center gap-4">
                                            <!-- Status Indicator -->
                                            <div class="relative">
                                                @if($isRunning)
                                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                                @elseif($isBuilding)
                                                    <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                                @else
                                                    <div class="w-3 h-3 bg-neutral-400 rounded-full"></div>
                                                @endif
                                            </div>
                                            
                                            <!-- App Info -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold dark:text-white truncate">
                                                    {{ $resource->name }}
                                                </h3>
                                                <p class="text-sm text-neutral-500 dark:text-neutral-400 truncate">
                                                    {{ $project->name }}
                                                    @if($resource->fqdn)
                                                        • <span class="text-[#4DC4E0]">{{ str($resource->fqdn)->before(',') }}</span>
                                                    @endif
                                                </p>
                                            </div>

                                            <!-- Status Badge -->
                                            <div>
                                                @if($isRunning)
                                                    <span class="px-3 py-1 text-xs font-medium bg-green-500/10 text-green-500 rounded-full">
                                                        Online
                                                    </span>
                                                @elseif($isBuilding)
                                                    <span class="px-3 py-1 text-xs font-medium bg-yellow-500/10 text-yellow-500 rounded-full">
                                                        Construindo...
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-medium bg-neutral-500/10 text-neutral-500 rounded-full">
                                                        Offline
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Arrow -->
                                            <svg class="w-5 h-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($allResources->count() === 0)
                            <div class="bg-white dark:bg-coolgray-100 rounded-xl border border-neutral-200 dark:border-coolgray-200 p-5">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-neutral-100 dark:bg-coolgray-200 rounded-lg">
                                        <svg class="w-6 h-6 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold dark:text-white">{{ $project->name }}</h3>
                                        <p class="text-sm text-neutral-500">Nenhuma aplicação neste projeto</p>
                                    </div>
                                    <a href="{{ route('project.resource.create', ['project_uuid' => $project->uuid, 'environment_uuid' => $project->environments->first()?->uuid ?? 'production']) }}" 
                                       {{ wireNavigate() }}
                                       class="px-4 py-2 bg-[#4DC4E0] hover:bg-[#2E7D9A] text-white rounded-lg text-sm font-medium transition-colors">
                                        + Adicionar
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Estado Vazio - Primeira Aplicação -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-[#4DC4E0]/10 rounded-full mb-4">
                        <svg class="w-8 h-8 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">
                        Bem-vindo ao EasyTI Cloud! 🎉
                    </h3>
                    <p class="text-neutral-500 dark:text-neutral-400 mb-6 max-w-md mx-auto">
                        Você ainda não tem nenhuma aplicação. Crie sua primeira aplicação em poucos cliques!
                    </p>
                    <x-modal-input buttonTitle="🚀 Criar Minha Primeira Aplicação" title="Novo Projeto">
                        <x-slot:content>
                            <button class="px-6 py-3 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0] text-white rounded-xl font-semibold shadow-lg transition-all">
                                🚀 Criar Minha Primeira Aplicação
                            </button>
                        </x-slot:content>
                        <livewire:project.add-empty />
                    </x-modal-input>
                </div>
            @endif
        </section>

        <!-- Dica de Ajuda -->
        <div class="mt-8 bg-[#4DC4E0]/5 border border-[#4DC4E0]/20 rounded-xl p-5">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-[#4DC4E0]/10 rounded-lg">
                    <svg class="w-5 h-5 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold dark:text-white mb-1">Precisa de ajuda?</h4>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        Nossa equipe está pronta para te ajudar! 
                        <a href="https://easyti.cloud/suporte" target="_blank" class="text-[#4DC4E0] hover:underline font-medium">
                            Fale conosco →
                        </a>
                    </p>
                </div>
            </div>
        </div>

    {{-- ============================================== --}}
    {{-- DASHBOARD PARA ADMIN (Completo)               --}}
    {{-- ============================================== --}}
    @else

        <h1>Dashboard</h1>
        <div class="subtitle">Painel administrativo EasyTI Cloud.</div>
        
        @if (request()->query->get('success'))
            <div class="mb-10 font-bold alert alert-success">
                Assinatura ativada com sucesso! Bem-vindo a bordo!
            </div>
        @endif

        <section class="-mt-2">
            <div class="flex items-center gap-2 pb-2">
                <h3>Projetos</h3>
                @if ($projects->count() > 0)
                    <x-modal-input buttonTitle="Add" title="Novo Projeto">
                        <x-slot:content>
                            <button class="flex items-center justify-center size-4 text-white rounded hover:bg-coolgray-400 dark:hover:bg-coolgray-300 cursor-pointer">
                                <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </x-slot:content>
                        <livewire:project.add-empty />
                    </x-modal-input>
                @endif
            </div>
            @if ($projects->count() > 0)
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                    @foreach ($projects as $project)
                        <div class="relative gap-2 cursor-pointer coolbox group">
                            <a href="{{ $project->navigateTo() }}" {{ wireNavigate() }} class="absolute inset-0"></a>
                            <div class="flex flex-1 mx-6">
                                <div class="flex flex-col justify-center flex-1">
                                    <div class="box-title">{{ $project->name }}</div>
                                    <div class="box-description">{{ $project->description }}</div>
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
            @else
                <div class="flex flex-col gap-1">
                    <div class='font-bold dark:text-warning'>Nenhum projeto encontrado.</div>
                    <div class="flex items-center gap-1">
                        <x-modal-input buttonTitle="Adicionar" title="Novo Projeto">
                            <livewire:project.add-empty />
                        </x-modal-input> seu primeiro projeto.
                    </div>
                </div>
            @endif
        </section>

        <section>
            <div class="flex items-center gap-2 pb-2">
                <h3>Servidores</h3>
                @if ($servers->count() > 0 && $privateKeys->count() > 0)
                    <x-modal-input buttonTitle="Add" title="Novo Servidor" :closeOutside="false">
                        <x-slot:content>
                            <button class="flex items-center justify-center size-4 text-white rounded hover:bg-coolgray-400 dark:hover:bg-coolgray-300 cursor-pointer">
                                <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </x-slot:content>
                        <livewire:server.create />
                    </x-modal-input>
                @endif
            </div>
            @if ($servers->count() > 0)
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                    @foreach ($servers as $server)
                        <a href="{{ route('server.show', ['server_uuid' => data_get($server, 'uuid')]) }}" {{ wireNavigate() }}
                            @class([
                                'gap-2 border cursor-pointer coolbox group',
                                'border-red-500' => !$server->settings->is_reachable || $server->settings->force_disabled,
                            ])>
                            <div class="flex flex-col justify-center mx-6">
                                <div class="box-title">{{ $server->name }}</div>
                                <div class="box-description">{{ $server->description }}</div>
                                <div class="flex gap-1 text-xs text-error">
                                    @if (!$server->settings->is_reachable)
                                        Não acessível
                                    @endif
                                    @if (!$server->settings->is_reachable && !$server->settings->is_usable)
                                        &
                                    @endif
                                    @if (!$server->settings->is_usable)
                                        Não utilizável
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1"></div>
                        </a>
                    @endforeach
                </div>
            @else
                @if ($privateKeys->count() === 0)
                    <div class="flex flex-col gap-1">
                        <div class='font-bold dark:text-warning'>Nenhuma chave privada encontrada.</div>
                        <div class="flex items-center gap-1">
                            Antes de adicionar um servidor, primeiro
                            <x-modal-input buttonTitle="adicione" title="Nova Chave Privada">
                                <livewire:security.private-key.create from="server" />
                            </x-modal-input> uma chave privada.
                        </div>
                    </div>
                @else
                    <div class="flex flex-col gap-1">
                        <div class='font-bold dark:text-warning'>Nenhum servidor encontrado.</div>
                        <div class="flex items-center gap-1">
                            <x-modal-input buttonTitle="Adicionar" title="Novo Servidor" :closeOutside="false">
                                <livewire:server.create />
                            </x-modal-input> seu primeiro servidor.
                        </div>
                    </div>
                @endif
            @endif
        </section>

    @endif
</div>
