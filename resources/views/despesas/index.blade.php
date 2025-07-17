<x-app-layout>
    <div class="min-h-screen bg-gray-900">
        <div class="container mx-auto px-4 py-4 lg:py-6">
    <!-- Header com estatísticas -->
    <div class="mb-6 lg:mb-8">
        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-4 lg:mb-6">Controle de Despesas</h1>
        
        <!-- Cards de estatísticas -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-6">
            <div class="bg-blue-900 border border-blue-700 rounded-lg p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-blue-400 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0">
                        <h3 class="text-xs lg:text-sm font-medium text-blue-400 truncate">Despesas Fixas</h3>
                        <p class="text-lg lg:text-2xl font-bold text-blue-200">{{ number_format($totalFixas, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-900 border border-green-700 rounded-lg p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-green-400 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0">
                        <h3 class="text-xs lg:text-sm font-medium text-green-400 truncate">Despesas Variáveis</h3>
                        <p class="text-lg lg:text-2xl font-bold text-green-200">{{ number_format($totalVariaveis, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-900 border border-yellow-700 rounded-lg p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-400 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0">
                        <h3 class="text-xs lg:text-sm font-medium text-yellow-400 truncate">Pendentes</h3>
                        <p class="text-lg lg:text-2xl font-bold text-yellow-200">{{ number_format($totalPendentes, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-900 border border-red-700 rounded-lg p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="ml-3 lg:ml-4 min-w-0">
                        <h3 class="text-xs lg:text-sm font-medium text-red-400 truncate">Vencidas</h3>
                        <p class="text-lg lg:text-2xl font-bold text-red-200">{{ number_format($totalVencidas, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e controles -->
    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-4 lg:p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <h2 class="text-lg lg:text-xl font-semibold text-white">Lista de Despesas</h2>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('despesas.create') }}" 
                   class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Despesa
                </a>
                
                <a href="{{ route('despesas.relatorio') }}" 
                   class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Relatório
                </a>
            </div>
        </div>
        
        <!-- Filtros -->
        <form method="GET" action="{{ route('despesas.index') }}" class="mt-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tipo</label>
                    <select name="tipo" class="w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="fixa" {{ request('tipo') == 'fixa' ? 'selected' : '' }}>Fixa</option>
                        <option value="variavel" {{ request('tipo') == 'variavel' ? 'selected' : '' }}>Variável</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Categoria</label>
                    <select name="categoria" class="w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Despesa::categorias() as $key => $value)
                            <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="paga" {{ request('status') == 'paga' ? 'selected' : '' }}>Paga</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="vencida" {{ request('status') == 'vencida' ? 'selected' : '' }}>Vencida</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Data Início</label>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" 
                           class="w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}" 
                           class="w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                </div>
            </div>
            
            <div class="mt-4 flex flex-col sm:flex-row gap-2">
                <button type="submit" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                
                <a href="{{ route('despesas.index') }}" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de despesas -->
    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
        <!-- Tabela para desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($despesas as $despesa)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $despesa->descricao }}</div>
                                @if($despesa->observacoes)
                                    <div class="text-sm text-gray-400">{{ Str::limit($despesa->observacoes, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-white">{{ $despesa->valor_formatado }}</div>
                                @if($despesa->forma_pagamento)
                                    <div class="text-xs text-gray-400">{{ $despesa->forma_pagamento }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $despesa->data_despesa->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $despesa->tipo == 'fixa' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($despesa->tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ \App\Models\Despesa::categorias()[$despesa->categoria] ?? $despesa->categoria }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    @if($despesa->status == 'paga') bg-green-900 text-green-200
                                    @elseif($despesa->status == 'pendente') bg-yellow-900 text-yellow-200
                                    @else bg-red-900 text-red-200 @endif">
                                    {{ ucfirst($despesa->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('despesas.show', $despesa) }}" 
                                       class="text-blue-400 hover:text-blue-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('despesas.edit', $despesa) }}" 
                                       class="text-yellow-400 hover:text-yellow-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($despesa->status == 'pendente')
                                        <form action="{{ route('despesas.marcar-paga', $despesa) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-400 hover:text-green-300" 
                                                    title="Marcar como paga"
                                                    onclick="return confirm('Marcar esta despesa como paga?')">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('despesas.destroy', $despesa) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" 
                                                onclick="return confirm('Tem certeza que deseja excluir esta despesa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                Nenhuma despesa encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Cards para mobile -->
        <div class="md:hidden divide-y divide-gray-700">
            @forelse($despesas as $despesa)
                <div class="p-4 hover:bg-gray-700">
                    <!-- Header do card -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-white truncate">
                                {{ $despesa->descricao }}
                            </h3>
                            @if($despesa->observacoes)
                                <p class="text-xs text-gray-400 mt-1">{{ Str::limit($despesa->observacoes, 40) }}</p>
                            @endif
                        </div>
                        <div class="text-right ml-4 flex-shrink-0">
                            <div class="text-lg font-bold text-white">{{ $despesa->valor_formatado }}</div>
                            <div class="text-xs text-gray-400">{{ $despesa->data_despesa->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                            {{ $despesa->tipo == 'fixa' ? 'bg-blue-900 text-blue-200' : 'bg-green-900 text-green-200' }}">
                            {{ ucfirst($despesa->tipo) }}
                        </span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-gray-200">
                            {{ \App\Models\Despesa::categorias()[$despesa->categoria] ?? $despesa->categoria }}
                        </span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                            @if($despesa->status == 'paga') bg-green-900 text-green-200
                            @elseif($despesa->status == 'pendente') bg-yellow-900 text-yellow-200
                            @else bg-red-900 text-red-200 @endif">
                            {{ ucfirst($despesa->status) }}
                        </span>
                    </div>
                    
                    <!-- Informações adicionais -->
                    @if($despesa->forma_pagamento)
                        <div class="text-xs text-gray-400 mb-3">
                            <i class="fas fa-credit-card mr-1"></i>
                            {{ \App\Models\Despesa::formasPagamento()[$despesa->forma_pagamento] ?? $despesa->forma_pagamento }}
                        </div>
                    @endif
                    
                    <!-- Ações -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('despesas.show', $despesa) }}" 
                           class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                        <a href="{{ route('despesas.edit', $despesa) }}" 
                           class="text-yellow-400 hover:text-yellow-300 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        @if($despesa->status == 'pendente')
                            <form action="{{ route('despesas.marcar-paga', $despesa) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-400 hover:text-green-300 text-sm font-medium" 
                                        onclick="return confirm('Marcar esta despesa como paga?')">
                                    <i class="fas fa-check-circle mr-1"></i>Pagar
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('despesas.destroy', $despesa) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta despesa?')">
                                <i class="fas fa-trash mr-1"></i>Excluir
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-receipt text-4xl mb-4 text-gray-600"></i>
                    <p class="text-lg font-medium">Nenhuma despesa encontrada</p>
                    <p class="text-sm">Tente ajustar os filtros ou adicionar uma nova despesa.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Paginação -->
        @if($despesas->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $despesas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

        </div>
    </div>

<style>
/* Garantir que os options dos selects fiquem com tema dark */
select option {
    background-color: #374151 !important;
    color: #ffffff !important;
}

select {
    color-scheme: dark;
}

/* Para inputs de data */
input[type="date"] {
    color-scheme: dark;
}
</style>
</x-app-layout>
