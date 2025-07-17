<x-app-layout>
    <x-slot name="header">
        <div class="wa-page-header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="font-bold text-2xl leading-tight wa-brand-text text-center sm:text-left">
                    <iconify-icon icon="lucide:plus-circle" class="inline mr-2 text-red-500"></iconify-icon>
                    Novo Serviço
                </h2>
                <a href="{{ route('servicos.index') }}" 
                   class="btn-wa-secondary text-sm self-center sm:self-auto">
                    <iconify-icon icon="lucide:arrow-left" class="mr-2"></iconify-icon>
                    Voltar para Serviços
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="wa-card rounded-2xl">
                <div class="p-4 sm:p-6">
                    
                    @if ($errors->any())
                        <div class="bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg mb-6">
                            <strong class="font-semibold">❌ Erro:</strong>
                            <ul class="mt-2 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('servicos.store') }}">
                        @csrf

                        <!-- Informações do Serviço -->
                        <div class="wa-form-section mb-6">
                            <h3 class="text-center mb-6">
                                <div class="inline-flex items-center bg-gray-700 border-2 border-red-500/30 rounded-xl px-4 sm:px-8 py-3 sm:py-4 shadow-lg">
                                    <iconify-icon icon="lucide:droplets" class="mr-2 sm:mr-3 text-red-500 text-xl sm:text-2xl"></iconify-icon>
                                    <span class="text-white font-bold uppercase tracking-wide text-base sm:text-lg">
                                        Dados do Serviço
                                    </span>
                                </div>
                            </h3>

                            <div class="space-y-4">
                                <!-- Nome do Serviço -->
                                <div class="wa-form-group">
                                    <label class="wa-form-label" for="nome">Nome do Serviço *</label>
                                    <input type="text" 
                                           id="nome" 
                                           name="nome" 
                                           value="{{ old('nome') }}"
                                           class="wa-form-input" 
                                           placeholder="Ex: Lavagem Completa"
                                           required>
                                </div>

                                <!-- Descrição -->
                                <div class="wa-form-group">
                                    <label class="wa-form-label" for="descricao">Descrição</label>
                                    <textarea id="descricao" 
                                              name="descricao" 
                                              rows="3"
                                              class="wa-form-input" 
                                              placeholder="Descreva o que está incluso no serviço...">{{ old('descricao') }}</textarea>
                                </div>

                                <!-- Preço e Tempo -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Preço -->
                                    <div class="wa-form-group">
                                        <label class="wa-form-label" for="preco">Preço (R$) *</label>
                                        <input type="number" 
                                               id="preco" 
                                               name="preco" 
                                               value="{{ old('preco') }}"
                                               step="0.01"
                                               min="0"
                                               class="wa-form-input" 
                                               placeholder="25.00"
                                               required>
                                    </div>

                                    <!-- Tempo Estimado -->
                                    <div class="wa-form-group">
                                        <label class="wa-form-label" for="tempo_estimado">Tempo Estimado (min)</label>
                                        <input type="number" 
                                               id="tempo_estimado" 
                                               name="tempo_estimado" 
                                               value="{{ old('tempo_estimado', 30) }}"
                                               min="1"
                                               class="wa-form-input" 
                                               placeholder="30">
                                    </div>
                                </div>

                                <!-- Status Ativo -->
                                <div class="wa-form-group">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="ativo" 
                                               name="ativo" 
                                               value="1"
                                               {{ old('ativo', true) ? 'checked' : '' }}
                                               class="wa-checkbox">
                                        <label for="ativo" class="wa-checkbox-label">
                                            <span class="checkmark-emoji">✅</span>
                                            Serviço ativo (disponível para seleção)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                            <a href="{{ route('servicos.index') }}" 
                               class="btn-wa-secondary text-center order-2 sm:order-1">
                                <iconify-icon icon="lucide:x" class="mr-2"></iconify-icon>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="btn-wa-primary order-1 sm:order-2">
                                <iconify-icon icon="lucide:check" class="mr-2"></iconify-icon>
                                ✨ Criar Serviço
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
