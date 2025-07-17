<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center backdrop-blur-md bg-black/20 p-4 rounded-lg border border-white/10">
            <div class="flex items-center space-x-3">
                <iconify-icon icon="lucide:droplets" class="text-2xl text-red-500"></iconify-icon>
                <h2 class="font-bold text-xl text-white leading-tight">
                    {{ __('Atendimentos') }}
                </h2>
            </div>
            <a href="{{ route('atendimentos.create') }}" 
               class="btn-ultra-premium btn-size-md">
                <iconify-icon icon="lucide:plus"></iconify-icon>
                Novo Atendimento
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-2xl"
                <div class="p-6">
                    @if($atendimentos->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-white/10">
                                <thead class="bg-gradient-to-r from-black/40 to-red-900/20 backdrop-blur-sm">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:user" class="mr-2"></iconify-icon>
                                            Cliente
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:car" class="mr-2"></iconify-icon>
                                            Veículo
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:wrench" class="mr-2"></iconify-icon>
                                            Serviço
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:calendar" class="mr-2"></iconify-icon>
                                            Data
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:activity" class="mr-2"></iconify-icon>
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:dollar-sign" class="mr-2"></iconify-icon>
                                            Valor
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:credit-card" class="mr-2"></iconify-icon>
                                            Pagamento
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-red-400 uppercase tracking-wider">
                                            <iconify-icon icon="lucide:settings" class="mr-2"></iconify-icon>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($atendimentos as $atendimento)
                                        <tr class="hover:bg-white/5 transition-all duration-300 group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-white group-hover:text-red-300 transition-colors">
                                                    {{ $atendimento->cliente->nome }}
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    {{ $atendimento->cliente->telefone }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-white font-medium">
                                                    {{ $atendimento->carro->marca }} {{ $atendimento->carro->modelo }}
                                                </div>
                                                <div class="text-sm text-gray-400">
                                                    {{ $atendimento->carro->placa }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ $atendimento->servico->nome ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ $atendimento->data_agendamento->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full backdrop-blur-sm
                                                    @if($atendimento->status === 'concluido') bg-green-500/20 text-green-300 border border-green-500/30
                                                    @elseif($atendimento->status === 'em_andamento') bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                                                    @elseif($atendimento->status === 'agendado') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                                    @else bg-red-500/20 text-red-300 border border-red-500/30
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $atendimento->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-400">
                                                R$ {{ number_format($atendimento->valor, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                @if($atendimento->forma_pagamento)
                                                    <div class="flex items-center">
                                                        @switch($atendimento->forma_pagamento)
                                                            @case('dinheiro')
                                                                <span class="text-green-400">💵</span>
                                                                <span class="ml-1">Dinheiro</span>
                                                                @break
                                                            @case('pix')
                                                                <span class="text-purple-400">📱</span>
                                                                <span class="ml-1">PIX</span>
                                                                @break
                                                            @case('credito')
                                                                <span class="text-blue-400">💳</span>
                                                                <span class="ml-1">Crédito</span>
                                                                @break
                                                            @case('debito')
                                                                <span class="text-orange-400">💳</span>
                                                                <span class="ml-1">Débito</span>
                                                                @break
                                                            @default
                                                                <span class="text-gray-400">📄</span>
                                                                <span class="ml-1">{{ ucfirst($atendimento->forma_pagamento) }}</span>
                                                        @endswitch
                                                    </div>
                                                    @if($atendimento->data_pagamento)
                                                        <div class="text-xs text-gray-400">
                                                            {{ $atendimento->data_pagamento->format('d/m/Y H:i') }}
                                                        </div>
                                                    @endif
                                                @else
                                                    @if($atendimento->status === 'finalizado' || $atendimento->status === 'concluido')
                                                        <span class="text-red-400 text-sm">❌ Não informado</span>
                                                    @else
                                                        <span class="text-gray-500 text-sm">⏳ Pendente</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('atendimentos.show', $atendimento) }}" 
                                                       class="btn-premium-micro bg-blue-600/20 text-blue-300 hover:bg-blue-500/30 border-blue-500/30">
                                                        <iconify-icon icon="lucide:eye"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('atendimentos.edit', $atendimento) }}" 
                                                       class="btn-premium-micro bg-indigo-600/20 text-indigo-300 hover:bg-indigo-500/30 border-indigo-500/30">
                                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                                    </a>
                                                    <form method="POST" action="{{ route('atendimentos.destroy', $atendimento) }}" 
                                                          class="inline" 
                                                          onsubmit="return confirm('Tem certeza que deseja excluir este atendimento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-delete-trash btn-premium-micro bg-red-600/30 text-red-200 hover:bg-red-500/40 border-red-400/50 hover:scale-105" title="🗑️ Excluir atendimento">
                                                            <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $atendimentos->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg">Nenhum atendimento encontrado.</div>
                            <div class="mt-4">
                                <a href="{{ route('atendimentos.create') }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Criar Primeiro Atendimento
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
