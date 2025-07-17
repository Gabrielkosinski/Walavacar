<x-app-layout>
    <x-slot name="header">
        <div class="wa-page-header">
            <h2 class="font-bold text-2xl leading-tight wa-brand-text">
                <iconify-icon icon="lucide:users" class="inline mr-2 text-red-500"></iconify-icon>
                Clientes
            </h2>
            <a href="{{ route('clientes.create') }}" 
               class="btn-primary-wa">
                <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon>
                Novo Cliente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="wa-card">
                <div class="p-6">
                    @if($clientes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="wa-table">
                                <thead>
                                    <tr>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:user" class="mr-2 text-red-400"></iconify-icon>
                                            Nome
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:phone" class="mr-2 text-red-400"></iconify-icon>
                                            Contato
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:bar-chart-3" class="mr-2 text-red-400"></iconify-icon>
                                            Status
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:settings" class="mr-2 text-red-400"></iconify-icon>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes as $cliente)
                                        <tr class="wa-tr">
                                            <td class="wa-td">
                                                <div class="text-sm font-medium text-white">
                                                    {{ $cliente->nome }}
                                                </div>
                                                @if($cliente->cpf)
                                                    <div class="text-sm text-gray-400">
                                                        CPF: {{ $cliente->cpf }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    📱 {{ $cliente->telefone }}
                                                </div>
                                                @if($cliente->email)
                                                    <div class="text-sm text-gray-500">
                                                        ✉️ {{ $cliente->email }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $cliente->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $cliente->ativo ? '✅ Ativo' : '❌ Inativo' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('clientes.show', $cliente) }}" 
                                                       class="text-blue-600 hover:text-blue-900 font-medium">👁️ Ver</a>
                                                    <a href="{{ route('clientes.edit', $cliente) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900 font-medium">✏️ Editar</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $clientes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg">Nenhum cliente encontrado.</div>
                            <div class="mt-4">
                                <a href="{{ route('clientes.create') }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Cadastrar Primeiro Cliente
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
