{{-- EASYTI: Componente de Onboarding Tour para novos usuários --}}
@if(!isEasytiAdmin())
<div x-data="onboardingTour()" x-show="showTour" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white dark:bg-coolgray-100 rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <!-- Header com Logo -->
        <div class="bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] px-8 py-6 text-white text-center">
            <h2 class="text-2xl font-bold mb-1">Bem-vindo ao EasyTI Cloud! 🎉</h2>
            <p class="text-white/80">Vamos te ajudar a começar</p>
        </div>

        <!-- Conteúdo dos Steps -->
        <div class="px-8 py-6">
            
            <!-- Step 1: Boas vindas -->
            <div x-show="currentStep === 1" x-transition>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-[#4DC4E0]/10 rounded-full mb-4">
                        <svg class="w-10 h-10 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">Sua Plataforma de Deploy</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        O EasyTI Cloud simplifica o deploy de suas aplicações. 
                        Em poucos passos, você terá sua aplicação online!
                    </p>
                </div>
            </div>

            <!-- Step 2: Criar Projeto -->
            <div x-show="currentStep === 2" x-transition>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500/10 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">1. Crie um Projeto</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Projetos organizam suas aplicações. Comece criando seu primeiro projeto 
                        clicando em <span class="font-semibold text-[#4DC4E0]">"Nova Aplicação"</span>.
                    </p>
                </div>
            </div>

            <!-- Step 3: Conectar Repositório -->
            <div x-show="currentStep === 3" x-transition>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-500/10 rounded-full mb-4">
                        <svg class="w-10 h-10 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">2. Conecte seu Código</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Conecte seu repositório do <span class="font-semibold">GitHub</span>, 
                        <span class="font-semibold">GitLab</span> ou <span class="font-semibold">Bitbucket</span>.
                        Suportamos Next.js, NestJS, Node.js e muito mais!
                    </p>
                </div>
            </div>

            <!-- Step 4: Deploy -->
            <div x-show="currentStep === 4" x-transition>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-500/10 rounded-full mb-4">
                        <svg class="w-10 h-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">3. Faça o Deploy!</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Clique em <span class="font-semibold text-[#4DC4E0]">"Deploy"</span> e pronto! 
                        Sua aplicação estará online em minutos com SSL automático.
                    </p>
                </div>
            </div>

            <!-- Step 5: Pronto! -->
            <div x-show="currentStep === 5" x-transition>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-[#4DC4E0]/10 rounded-full mb-4">
                        <svg class="w-10 h-10 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">Você está pronto!</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Qualquer dúvida, estamos aqui para ajudar. 
                        Clique em <span class="font-semibold text-[#4DC4E0]">"Ajuda e Suporte"</span> no menu.
                    </p>
                </div>
            </div>

            <!-- Indicadores de Step -->
            <div class="flex justify-center gap-2 mb-6">
                <template x-for="step in totalSteps" :key="step">
                    <div class="w-2 h-2 rounded-full transition-all duration-300"
                         :class="currentStep === step ? 'bg-[#4DC4E0] w-6' : 'bg-neutral-300 dark:bg-coolgray-300'"></div>
                </template>
            </div>

            <!-- Botões -->
            <div class="flex gap-3">
                <button x-show="currentStep > 1" @click="prevStep()"
                        class="flex-1 px-4 py-3 border border-neutral-300 dark:border-coolgray-300 rounded-lg font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-coolgray-200 transition-colors">
                    ← Anterior
                </button>
                <button x-show="currentStep < totalSteps" @click="nextStep()"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0] text-white rounded-lg font-medium transition-all">
                    Próximo →
                </button>
                <button x-show="currentStep === totalSteps" @click="finishTour()"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-400 hover:to-green-500 text-white rounded-lg font-medium transition-all">
                    🚀 Começar!
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-neutral-50 dark:bg-coolgray-200 border-t border-neutral-200 dark:border-coolgray-300">
            <button @click="skipTour()" class="text-sm text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
                Pular tutorial
            </button>
        </div>
    </div>
</div>

<script>
    function onboardingTour() {
        return {
            showTour: false,
            currentStep: 1,
            totalSteps: 5,
            
            init() {
                // Verifica se é a primeira vez do usuário
                const hasSeenTour = localStorage.getItem('easyti_onboarding_completed');
                if (!hasSeenTour && window.location.pathname === '/') {
                    // Delay para não aparecer imediatamente
                    setTimeout(() => {
                        this.showTour = true;
                    }, 1000);
                }
            },
            
            nextStep() {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                }
            },
            
            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                }
            },
            
            finishTour() {
                localStorage.setItem('easyti_onboarding_completed', 'true');
                this.showTour = false;
            },
            
            skipTour() {
                localStorage.setItem('easyti_onboarding_completed', 'true');
                this.showTour = false;
            }
        }
    }
</script>
@endif

