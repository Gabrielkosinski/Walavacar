<x-app-layout>
    <div class="min-h-screen bg-gray-100">
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Detalhes da Despesa</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('despesas.edit', $despesa) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('despesas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <!-- Informações da Despesa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Status e Valor -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $despesa->descricao }}</h2>
                        <div class="mt-2 flex space-x-4">
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                {{ $despesa->tipo == 'fixa' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($despesa->tipo) }}
                            </span>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                @if($despesa->status == 'paga') bg-green-100 text-green-800
                                @elseif($despesa->status == 'pendente') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($despesa->status) }}
                            </span>
                            @if($despesa->recorrente)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-redo mr-1"></i>
                                    Recorrente
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900">{{ $despesa->valor_formatado }}</p>
                        <p class="text-sm text-gray-500">{{ $despesa->data_despesa->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Detalhes -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informações Básicas -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                            <p class="text-sm text-gray-900">
                                {{ \App\Models\Despesa::categorias()[$despesa->categoria] ?? $despesa->categoria }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data da Despesa</label>
                            <p class="text-sm text-gray-900">{{ $despesa->data_despesa->format('d/m/Y') }}</p>
                        </div>
                        
                        @if($despesa->forma_pagamento)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                                <p class="text-sm text-gray-900">
                                    {{ \App\Models\Despesa::formasPagamento()[$despesa->forma_pagamento] ?? $despesa->forma_pagamento }}
                                </p>
                            </div>
                        @endif
                        
                        @if($despesa->recorrente && $despesa->dia_vencimento)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dia do Vencimento</label>
                                <p class="text-sm text-gray-900">Todo dia {{ $despesa->dia_vencimento }}</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Informações Adicionais -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cadastrado por</label>
                            <p class="text-sm text-gray-900">{{ $despesa->user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Cadastro</label>
                            <p class="text-sm text-gray-900">{{ $despesa->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($despesa->updated_at != $despesa->created_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Última Atualização</label>
                                <p class="text-sm text-gray-900">{{ $despesa->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($despesa->is_vencida)
                            <div class="p-3 bg-red-50 border border-red-200 rounded-md">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-sm font-medium text-red-800">Esta despesa está vencida!</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Observações -->
                @if($despesa->observacoes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                        <div class="p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $despesa->observacoes }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Comprovante -->
                @if($despesa->comprovante)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comprovante</label>
                        <div class="flex items-center space-x-4">
                            <a href="{{ Storage::url($despesa->comprovante) }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-file mr-2"></i>
                                Ver Comprovante
                            </a>
                            <span class="text-sm text-gray-500">
                                Arquivo: {{ basename($despesa->comprovante) }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Ações -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    @if($despesa->status == 'pendente')
                        <form action="{{ route('despesas.marcar-paga', $despesa) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Marcar esta despesa como paga?')">
                                <i class="fas fa-check-circle mr-2"></i>
                                Marcar como Paga
                            </button>
                        </form>
                    @else
                        <div></div>
                    @endif
                    
                    <form action="{{ route('despesas.destroy', $despesa) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                onclick="return confirm('Tem certeza que deseja excluir esta despesa? Esta ação não pode ser desfeita.')">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Despesa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50" id="success-alert">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-alert').style.display = 'none';
        }, 3000);
    </script>
@endif
    </div>
</x-app-layout>
