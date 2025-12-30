<nav class="flex flex-col flex-1 px-2 bg-white border-r dark:border-coolgray-200 border-neutral-300 dark:bg-base"
    x-data="{
        init() {
            const userSettings = localStorage.getItem('theme') || 'dark';
            localStorage.setItem('theme', userSettings);
            if (userSettings === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (userSettings === 'light') {
                document.documentElement.classList.remove('dark');
            }
        }
    }">
    
    <!-- EASYTI: Logo e nome da marca -->
    <div class="flex lg:pt-6 pt-4 pb-4 pl-2">
        <div class="flex flex-col w-full">
            <a href="/" {{ wireNavigate() }} class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <img src="{{ asset('images/logo.png') }}" alt="EasyTI Cloud" class="h-10 w-auto" onerror="this.style.display='none'" />
            </a>
            <span class="text-lg font-bold tracking-wide dark:text-white mt-1">EasyTI Cloud</span>
        </div>
        <livewire:settings-dropdown />
    </div>

    <!-- EASYTI: Seletor de workspace simplificado -->
    @if(isEasytiAdmin())
    <div class="px-2 pt-2 pb-4">
        <livewire:switch-team />
    </div>
    @endif

    <ul role="list" class="flex flex-col flex-1 gap-y-2">
        <li class="flex-1 overflow-x-hidden">
            <ul role="list" class="flex flex-col h-full space-y-1">
                @if (isSubscribed() || !isCloud())
                
                    <!-- ========================================== -->
                    <!-- MENU PARA CLIENTES (Simplificado)          -->
                    <!-- ========================================== -->
                    @if(!isEasytiAdmin())
                    
                        <!-- 🏠 Início -->
                        <li>
                            <a title="Início" href="/" {{ wireNavigate() }}
                                class="{{ request()->is('/') ? 'menu-item-active menu-item' : 'menu-item' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Início
                            </a>
                        </li>

                        <!-- 🚀 Minhas Aplicações -->
                        <li>
                            <a title="Minhas Aplicações" {{ wireNavigate() }}
                                class="{{ request()->is('project/*') || request()->is('projects') ? 'menu-item menu-item-active' : 'menu-item' }}"
                                href="/projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline>
                                    <polyline points="7.5 19.79 7.5 14.6 3 12"></polyline>
                                    <polyline points="21 12 16.5 14.6 16.5 19.79"></polyline>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                Minhas Aplicações
                            </a>
                        </li>

                        <!-- ➕ BOTÃO DESTACADO: Nova Aplicação -->
                        <li class="pt-2 pb-2">
                            <a title="Nova Aplicação" {{ wireNavigate() }}
                                class="flex items-center gap-3 px-3 py-3 text-white font-semibold rounded-lg transition-all
                                       bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0]
                                       shadow-lg hover:shadow-xl transform hover:scale-[1.02]"
                                href="/projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Nova Aplicação
                            </a>
                        </li>

                        <li class="pt-4">
                            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                                Configurações
                            </span>
                        </li>

                        <!-- 🔐 Variáveis de Ambiente -->
                        <li>
                            <a title="Variáveis de Ambiente" {{ wireNavigate() }}
                                class="{{ request()->is('shared-variables*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('shared-variables.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Variáveis de Ambiente
                            </a>
                        </li>

                        <!-- 🔔 Notificações -->
                        <li>
                            <a title="Notificações" {{ wireNavigate() }}
                                class="{{ request()->is('notifications*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('notifications.email') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                Notificações
                            </a>
                        </li>

                        <!-- 🔑 Chaves de Acesso -->
                        <li>
                            <a title="Chaves de Acesso" {{ wireNavigate() }}
                                class="{{ request()->is('security*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('security.private-key.index') }}">
                                <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                </svg>
                                Chaves de Acesso
                            </a>
                        </li>

                        <div class="flex-1"></div>

                        <li class="pt-4">
                            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                                Conta
                            </span>
                        </li>

                        <!-- 👤 Minha Conta -->
                        <li>
                            <a title="Minha Conta" {{ wireNavigate() }}
                                class="{{ request()->is('profile*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('profile') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Minha Conta
                            </a>
                        </li>

                        <!-- 👥 Minha Equipe -->
                        <li>
                            <a title="Minha Equipe" {{ wireNavigate() }}
                                class="{{ request()->is('team*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('team.index') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Minha Equipe
                            </a>
                        </li>

                        <!-- 📞 Ajuda -->
                        <li>
                            <a title="Ajuda e Suporte" class="menu-item" href="https://easyti.cloud/suporte" target="_blank">
                                <svg class="icon text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                Ajuda e Suporte
                            </a>
                        </li>

                        <!-- 🚪 Sair -->
                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button title="Sair" type="submit" class="gap-2 mb-6 menu-item w-full text-left">
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </li>

                    <!-- ========================================== -->
                    <!-- MENU PARA ADMIN (Completo)                 -->
                    <!-- ========================================== -->
                    @else

                        <li class="pb-2">
                            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-[#4DC4E0]">
                                🔧 Modo Administrador
                            </span>
                        </li>

                        <li>
                            <a title="Dashboard" href="/" {{ wireNavigate() }}
                                class="{{ request()->is('/') ? 'menu-item-active menu-item' : 'menu-item' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a title="Projetos" {{ wireNavigate() }}
                                class="{{ request()->is('project/*') || request()->is('projects') ? 'menu-item menu-item-active' : 'menu-item' }}"
                                href="/projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                                    <path d="M4 12l8 4l8 -4" />
                                    <path d="M4 16l8 4l8 -4" />
                                </svg>
                                Projetos
                            </a>
                        </li>
                        <li>
                            <a title="Servidores" {{ wireNavigate() }}
                                class="{{ request()->is('server/*') || request()->is('servers') ? 'menu-item menu-item-active' : 'menu-item' }}"
                                href="/servers">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                    <path d="M15 20h-9a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h12" />
                                    <path d="M7 8v.01" />
                                    <path d="M7 16v.01" />
                                    <path d="M20 15l-2 3h3l-2 3" />
                                </svg>
                                Servidores
                            </a>
                        </li>
                        <li>
                            <a title="Fontes Git" {{ wireNavigate() }}
                                class="{{ request()->is('source*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('source.all') }}">
                                <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <line x1="1.05" y1="12" x2="7" y2="12"></line>
                                    <line x1="17.01" y1="12" x2="22.96" y2="12"></line>
                                </svg>
                                Fontes Git
                            </a>
                        </li>
                        <li>
                            <a title="Destinos" {{ wireNavigate() }}
                                class="{{ request()->is('destination*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('destination.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                                    <line x1="12" y1="22" x2="12" y2="15.5"></line>
                                    <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                                    <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="8.5"></line>
                                </svg>
                                Destinos Docker
                            </a>
                        </li>
                        <li>
                            <a title="S3 Storages" {{ wireNavigate() }}
                                class="{{ request()->is('storages*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('storage.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                                Storage S3
                            </a>
                        </li>
                        <li>
                            <a title="Variáveis Compartilhadas" {{ wireNavigate() }}
                                class="{{ request()->is('shared-variables*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('shared-variables.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="4 17 10 11 4 5"></polyline>
                                    <line x1="12" y1="19" x2="20" y2="19"></line>
                                </svg>
                                Variáveis
                            </a>
                        </li>
                        <li>
                            <a title="Notificações" {{ wireNavigate() }}
                                class="{{ request()->is('notifications*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('notifications.email') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                                Notificações
                            </a>
                        </li>
                        <li>
                            <a title="Chaves & Tokens" {{ wireNavigate() }}
                                class="{{ request()->is('security*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('security.private-key.index') }}">
                                <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                </svg>
                                Chaves & Tokens
                            </a>
                        </li>
                        <li>
                            <a title="Tags" {{ wireNavigate() }}
                                class="{{ request()->is('tags*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('tags.show') }}">
                                <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                Tags
                            </a>
                        </li>
                        @can('canAccessTerminal')
                            <li>
                                <a title="Terminal"
                                    class="{{ request()->is('terminal*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                    href="{{ route('terminal') }}">
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="4 17 10 11 4 5"></polyline>
                                        <line x1="12" y1="19" x2="20" y2="19"></line>
                                    </svg>
                                    Terminal SSH
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a title="Perfil" {{ wireNavigate() }}
                                class="{{ request()->is('profile*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('profile') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Perfil
                            </a>
                        </li>
                        <li>
                            <a title="Times/Clientes" {{ wireNavigate() }}
                                class="{{ request()->is('team*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                href="{{ route('team.index') }}">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Times/Clientes
                            </a>
                        </li>
                        @if (isInstanceAdmin())
                            <li>
                                <a title="Configurações" {{ wireNavigate() }}
                                    class="{{ request()->is('settings*') ? 'menu-item-active menu-item' : 'menu-item' }}"
                                    href="/settings">
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                    </svg>
                                    Configurações
                                </a>
                            </li>
                        @endif

                        <div class="flex-1"></div>

                        @if (isInstanceAdmin() && !isCloud())
                            @persist('upgrade')
                                <li>
                                    <livewire:upgrade />
                                </li>
                            @endpersist
                        @endif

                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button title="Sair" type="submit" class="gap-2 mb-6 menu-item w-full text-left">
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </li>

                    @endif
                @endif
            </ul>
        </li>
    </ul>
</nav>
