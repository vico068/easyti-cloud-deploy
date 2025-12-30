<form class="flex flex-col w-full gap-4 rounded-sm" wire:submit='submit'>
    
    {{-- EASYTI: Formulário simplificado para clientes --}}
    @if(!isEasytiAdmin())
        <div class="text-center mb-4">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#4DC4E0]/10 rounded-full mb-3">
                <svg class="w-8 h-8 text-[#4DC4E0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold dark:text-white">Criar Novo Projeto</h3>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Projetos organizam suas aplicações
            </p>
        </div>
        
        <x-forms.input 
            placeholder="Ex: Minha Loja Online" 
            id="name" 
            label="Nome do Projeto" 
            required 
        />
        
        <x-forms.input 
            placeholder="Descrição opcional do projeto" 
            id="description" 
            label="Descrição (opcional)" 
        />
        
        <div class="bg-[#4DC4E0]/5 border border-[#4DC4E0]/20 rounded-lg p-3 text-sm">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-[#4DC4E0] shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <p class="text-neutral-600 dark:text-neutral-400">
                    Seu projeto será criado com o ambiente <span class="font-semibold text-[#4DC4E0]">produção</span> configurado automaticamente.
                </p>
            </div>
        </div>
        
        <x-forms.button type="submit" class="w-full py-3 bg-gradient-to-r from-[#4DC4E0] to-[#2E7D9A] hover:from-[#7DD8ED] hover:to-[#4DC4E0]">
            🚀 Criar Projeto e Continuar
        </x-forms.button>
    @else
        {{-- EASYTI: Formulário original para admin --}}
        <x-forms.input placeholder="Meu Projeto Incrível" id="name" label="Nome" required />
        <x-forms.input placeholder="Descrição do projeto" id="description" label="Descrição" />
        <div class="subtitle">Novo projeto terá um ambiente <span class="dark:text-warning font-bold">produção</span> padrão.</div>
        <x-forms.button type="submit">
            Continuar
        </x-forms.button>
    @endif
</form>
