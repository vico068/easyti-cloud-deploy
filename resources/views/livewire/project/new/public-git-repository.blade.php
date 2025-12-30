<div x-data="{ 
    step: 1,
    init() {
        $nextTick(() => { if ($refs.autofocusInput) $refs.autofocusInput.focus(); });
    }
}">
    {{-- ============================================== --}}
    {{-- FORMULÁRIO GUIADO PARA CLIENTES               --}}
    {{-- ============================================== --}}
    @if(!isEasytiAdmin())

        <!-- Cabeçalho -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold dark:text-white">🚀 Nova Aplicação</h1>
            <p class="text-neutral-500 dark:text-neutral-400">Conecte seu repositório e faça deploy em minutos</p>
        </div>

        <!-- Indicador de Passos -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all
                        {{ !$branchFound ? 'bg-[#4DC4E0] text-white' : 'bg-green-500 text-white' }}">
                        @if($branchFound)
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            1
                        @endif
                    </div>
                    <span class="text-sm font-medium {{ !$branchFound ? 'text-[#4DC4E0]' : 'text-green-500' }}">Repositório</span>
                </div>
                <div class="w-12 h-0.5 {{ $branchFound ? 'bg-green-500' : 'bg-neutral-300 dark:bg-coolgray-300' }}"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all
                        {{ $branchFound ? 'bg-[#4DC4E0] text-white' : 'bg-neutral-200 dark:bg-coolgray-300 text-neutral-500' }}">
                        2
                    </div>
                    <span class="text-sm {{ $branchFound ? 'text-[#4DC4E0] font-medium' : 'text-neutral-500' }}">Configurar</span>
                </div>
                <div class="w-12 h-0.5 bg-neutral-300 dark:bg-coolgray-300"></div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-neutral-200 dark:bg-coolgray-300 flex items-center justify-center text-neutral-500 font-bold text-sm">
                        3
                    </div>
                    <span class="text-sm text-neutral-500">Deploy</span>
                </div>
            </div>
        </div>

        <!-- Step 1: URL do Repositório -->
        @if(!$branchFound)
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-coolgray-100 rounded-2xl border border-neutral-200 dark:border-coolgray-200 p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-[#4DC4E0]/10 rounded-full mb-4">
                        <svg class="w-8 h-8 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold dark:text-white mb-2">Cole a URL do seu repositório</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        GitHub, GitLab, Bitbucket ou qualquer repositório Git público
                    </p>
                </div>

                <form wire:submit='loadBranch' class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            URL do Repositório
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-neutral-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   wire:model="repository_url"
                                   placeholder="https://github.com/seu-usuario/seu-projeto"
                                   x-ref="autofocusInput"
                                   class="w-full pl-12 pr-4 py-4 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200 text-lg focus:ring-2 focus:ring-[#4DC4E0] focus:border-transparent transition-all" />
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0] text-white rounded-xl font-semibold text-lg shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Verificar Repositório
                    </button>
                </form>

                <!-- Dicas -->
                <div class="mt-6 p-4 bg-neutral-50 dark:bg-coolgray-200 rounded-xl">
                    <h4 class="text-sm font-medium dark:text-white mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#4DC4E0]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dicas
                    </h4>
                    <ul class="text-sm text-neutral-600 dark:text-neutral-400 space-y-1">
                        <li>• Use a URL completa do repositório (com https://)</li>
                        <li>• O repositório precisa ser público</li>
                        <li>• Suportamos Next.js, NestJS, Node.js, Laravel, Python e mais!</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Configuração -->
        @if ($branchFound)
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-coolgray-100 rounded-2xl border border-neutral-200 dark:border-coolgray-200 p-8">
                
                <!-- Sucesso -->
                <div class="flex items-center gap-3 p-4 mb-6 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 rounded-xl">
                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-medium text-green-700 dark:text-green-400">Repositório encontrado!</p>
                        <p class="text-sm text-green-600 dark:text-green-500">{{ $repository_url }}</p>
                    </div>
                </div>

                @if ($rate_limit_remaining && $rate_limit_reset)
                    <div class="text-xs text-neutral-400 mb-4">
                        Rate limit: {{ $rate_limit_remaining }} requisições restantes
                    </div>
                @endif

                <form wire:submit='submit' class="space-y-6">
                    
                    <!-- Branch e Build Pack -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                📂 Branch
                            </label>
                            @if ($git_source === 'other')
                                <input type="text" wire:model="git_branch" 
                                       class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200 focus:ring-2 focus:ring-[#4DC4E0]" />
                            @else
                                <input type="text" wire:model="git_branch" disabled
                                       class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-neutral-100 dark:bg-coolgray-300 cursor-not-allowed" />
                            @endif
                            <p class="text-xs text-neutral-400 mt-1">Você pode mudar depois nas configurações</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                🔧 Tipo de Build
                            </label>
                            <select wire:model.live="build_pack" 
                                    class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200 focus:ring-2 focus:ring-[#4DC4E0]">
                                <option value="nixpacks">🚀 Automático (Nixpacks)</option>
                                <option value="static">📄 Site Estático</option>
                                <option value="dockerfile">🐳 Dockerfile</option>
                                <option value="dockercompose">🐳 Docker Compose</option>
                            </select>
                            <p class="text-xs text-neutral-400 mt-1">Recomendamos "Automático" para a maioria dos casos</p>
                        </div>
                    </div>

                    <!-- Configurações adicionais baseadas no tipo -->
                    @if ($isStatic)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                📁 Diretório de Publicação
                            </label>
                            <input type="text" wire:model="publish_directory" placeholder="Ex: dist, build, out"
                                   class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200 focus:ring-2 focus:ring-[#4DC4E0]" />
                            <p class="text-xs text-neutral-400 mt-1">Onde ficam os arquivos após o build (React: build, Next.js: out, Vue: dist)</p>
                        </div>
                    @endif

                    @if ($build_pack === 'dockercompose')
                        <div x-data="{
                            baseDir: '{{ $base_directory }}',
                            composeLocation: '{{ $docker_compose_location }}',
                            normalizePath(path) {
                                if (!path || path.trim() === '') return '/';
                                path = path.trim().replace(/\/+$/, '');
                                if (!path.startsWith('/')) path = '/' + path;
                                return path;
                            },
                            normalizeBaseDir() { this.baseDir = this.normalizePath(this.baseDir); },
                            normalizeComposeLocation() { this.composeLocation = this.normalizePath(this.composeLocation); }
                        }" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    📁 Diretório Base
                                </label>
                                <input type="text" wire:model.defer="base_directory" placeholder="/" x-model="baseDir" @blur="normalizeBaseDir()"
                                       class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    🐳 Localização do docker-compose.yaml
                                </label>
                                <input type="text" wire:model.defer="docker_compose_location" placeholder="/docker-compose.yaml" x-model="composeLocation" @blur="normalizeComposeLocation()"
                                       class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200" />
                            </div>
                            <div class="p-3 bg-[#4DC4E0]/10 rounded-lg text-sm">
                                <span class="text-neutral-600 dark:text-neutral-400">Arquivo: </span>
                                <span class="font-mono text-[#4DC4E0]" x-text='(baseDir === "/" ? "" : baseDir) + (composeLocation.startsWith("/") ? composeLocation : "/" + composeLocation)'></span>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                📁 Diretório Base
                            </label>
                            <input type="text" wire:model="base_directory" placeholder="/" 
                                   class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200" />
                            <p class="text-xs text-neutral-400 mt-1">Use "/" para a raiz ou especifique para monorepos (ex: /apps/web)</p>
                        </div>
                    @endif

                    @if ($show_is_static)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    🔌 Porta
                                </label>
                                <input type="number" wire:model="port" placeholder="3000"
                                       {{ ($isStatic || $build_pack === 'static') ? 'readonly' : '' }}
                                       class="w-full px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-xl bg-white dark:bg-coolgray-200" />
                                <p class="text-xs text-neutral-400 mt-1">Porta que sua aplicação usa (padrão: 3000)</p>
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="isStatic" 
                                           class="w-5 h-5 rounded border-neutral-300 text-[#4DC4E0] focus:ring-[#4DC4E0]" />
                                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        É um site estático?
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- Botão de Continuar -->
                    <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-400 hover:to-green-500 text-white rounded-xl font-semibold text-lg shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Criar Aplicação e Fazer Deploy
                    </button>

                    <!-- Link para voltar -->
                    <div class="text-center">
                        <button type="button" wire:click="$set('branchFound', false)" 
                                class="text-sm text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
                            ← Voltar e usar outro repositório
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    {{-- ============================================== --}}
    {{-- FORMULÁRIO ORIGINAL PARA ADMIN                 --}}
    {{-- ============================================== --}}
    @else
    
    <h1>Criar nova Aplicação</h1>
    <div class="pb-8">Deploy de repositórios Git públicos.</div>

    <!-- Repository URL Form -->
    <form class="flex flex-col gap-2" wire:submit='loadBranch'>
        <div class="flex flex-col gap-2">
            <div class="flex gap-2 items-end">
                <x-forms.input required id="repository_url" label="URL do Repositório (https://)"
                    helper="{!! __('repository.url') !!}" autofocus />
                <x-forms.button type="submit">
                    Verificar repositório
                </x-forms.button>
            </div>
            <div>
                Para exemplos de aplicações, confira <a class="underline dark:text-white"
                    href="https://github.com/coollabsio/coolify-examples/" target="_blank">Coolify Examples</a>.
            </div>
        </div>
    </form>

    @if ($branchFound)
        @if ($rate_limit_remaining && $rate_limit_reset)
            <div class="flex gap-2 py-2">
                <div>Rate Limit</div>
                <x-helper helper="Limite restante: {{ $rate_limit_remaining }}<br>Reset em: {{ $rate_limit_reset }} UTC" />
            </div>
        @endif

        <!-- Application Configuration Form -->
        <form class="flex flex-col gap-2 pt-4" wire:submit='submit'>
            <div class="flex flex-col gap-2 pb-6">
                <div class="flex gap-2">
                    @if ($git_source === 'other')
                        <x-forms.input id="git_branch" label="Branch"
                            helper="Você pode selecionar outras branches após a configuração." />
                    @else
                        <x-forms.input disabled id="git_branch" label="Branch"
                            helper="Você pode selecionar outras branches após a configuração." />
                    @endif
                    <x-forms.select wire:model.live="build_pack" label="Build Pack" required>
                        <option value="nixpacks">Nixpacks</option>
                        <option value="static">Estático</option>
                        <option value="dockerfile">Dockerfile</option>
                        <option value="dockercompose">Docker Compose</option>
                    </x-forms.select>
                    @if ($isStatic)
                        <x-forms.input id="publish_directory" label="Diretório de Publicação"
                            helper="Se houver build (Svelte, React, Next, etc..), especifique o diretório de saída." />
                    @endif
                </div>
                @if ($build_pack === 'dockercompose')
                    <div x-data="{
                        baseDir: '{{ $base_directory }}',
                        composeLocation: '{{ $docker_compose_location }}',
                        normalizePath(path) {
                            if (!path || path.trim() === '') return '/';
                            path = path.trim().replace(/\/+$/, '');
                            if (!path.startsWith('/')) path = '/' + path;
                            return path;
                        },
                        normalizeBaseDir() { this.baseDir = this.normalizePath(this.baseDir); },
                        normalizeComposeLocation() { this.composeLocation = this.normalizePath(this.composeLocation); }
                    }" class="gap-2 flex flex-col">
                        <x-forms.input placeholder="/" wire:model.defer="base_directory" label="Diretório Base"
                            helper="Diretório raiz. Útil para monorepos." x-model="baseDir" @blur="normalizeBaseDir()" />
                        <x-forms.input placeholder="/docker-compose.yaml" wire:model.defer="docker_compose_location"
                            label="Localização do Docker Compose" helper="Calculado junto com o Diretório Base."
                            x-model="composeLocation" @blur="normalizeComposeLocation()" />
                        <div class="pt-2">
                            <span>Localização no repositório: </span>
                            <span class='dark:text-warning' x-text='(baseDir === "/" ? "" : baseDir) + (composeLocation.startsWith("/") ? composeLocation : "/" + composeLocation)'></span>
                        </div>
                    </div>
                @else
                    <x-forms.input wire:model="base_directory" label="Diretório Base"
                        helper="Diretório raiz. Útil para monorepos." />
                @endif
                @if ($show_is_static)
                    <x-forms.input type="number" id="port" label="Porta" :readonly="$isStatic || $build_pack === 'static'"
                        helper="Porta que sua aplicação escuta." />
                    <div class="w-64">
                        <x-forms.checkbox instantSave id="isStatic" label="É um site estático?"
                            helper="Se sua aplicação é estática ou o build final deve ser servido como estático, ative esta opção." />
                    </div>
                @endif
            </div>
            <x-forms.button type="submit">
                Continuar
            </x-forms.button>
        </form>
    @endif
    
    @endif
</div>
