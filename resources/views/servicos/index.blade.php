<x-app-layout>
    <x-slot name="header">
        <div class="wa-page-header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="font-bold text-2xl leading-tight wa-brand-text text-center sm:text-left">
                    <iconify-icon icon="lucide:droplets" class="inline mr-2 text-red-500"></iconify-icon>
                    Serviços
                </h2>
                <a href="{{ route('servicos.create') }}" class="btn-wa-primary text-center sm:text-left">
                    <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon>
                    Novo Serviço
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="wa-card rounded-2xl">
                <div class="p-4 sm:p-6">
                    <!-- Filtros WA -->
                    <div class="wa-form-section mb-6">
                        <form method="GET" action="{{ route('servicos.index') }}" class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Buscar por nome ou descrição..."
                                       class="wa-form-input">
                            </div>
                            <div class="sm:w-48">
                                <select name="status" class="wa-form-input">
                                    <option value="">Todos os status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="btn-wa-primary flex-1 sm:flex-none">
                                    <iconify-icon icon="lucide:filter" class="mr-2"></iconify-icon>
                                    Filtrar
                                </button>
                                @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('servicos.index') }}" class="btn-wa-secondary flex-1 sm:flex-none">
                                        <iconify-icon icon="lucide:x" class="mr-2"></iconify-icon>
                                        Limpar
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Mensagens de Feedback -->
                    @if(session('success'))
                        <div class="wa-alert-success mb-4">
                            <iconify-icon icon="lucide:check-circle" class="mr-2"></iconify-icon>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="wa-alert-error mb-4">
                            <iconify-icon icon="lucide:alert-circle" class="mr-2"></iconify-icon>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Listagem de Serviços -->
                    @if($servicos->count() > 0)
                    <!-- Layout Mobile: Cards -->
                    <div class="block sm:hidden space-y-4">
                        @foreach($servicos as $servico)
                            <div class="wa-card-mobile rounded-xl border border-red-500/20 bg-gray-800/50 p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-white font-semibold text-lg">{{ $servico->nome }}</h3>
                                        @if($servico->descricao)
                                            <p class="text-gray-400 text-sm mt-1">{{ Str::limit($servico->descricao, 60) }}</p>
                                        @endif
                                    </div>
                                    <span class="wa-badge-{{ $servico->ativo ? 'green' : 'red' }} ml-3">
                                        <iconify-icon icon="lucide:{{ $servico->ativo ? 'check-circle' : 'x-circle' }}" class="mr-1"></iconify-icon>
                                        {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="text-center">
                                        <div class="text-gray-400 text-xs uppercase tracking-wide">Preço</div>
                                        <div class="text-green-400 font-bold text-lg">R$ {{ number_format($servico->preco, 2, ',', '.') }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-400 text-xs uppercase tracking-wide">Tempo</div>
                                        <div class="text-yellow-400 font-bold text-lg">{{ $servico->tempo_estimado }}min</div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <!-- Botão Toggle Status -->
                                    <button onclick="confirmarToggleStatus({{ $servico->id }}, '{{ $servico->nome }}', {{ $servico->ativo ? 'true' : 'false' }})" 
                                            class="btn-wa-{{ $servico->ativo ? 'secondary' : 'primary' }} flex-1 text-center text-sm">
                                        <iconify-icon icon="lucide:{{ $servico->ativo ? 'toggle-left' : 'toggle-right' }}" class="mr-1"></iconify-icon>
                                        {{ $servico->ativo ? 'Desativar' : 'Ativar' }}
                                    </button>
                                    
                                    <!-- Botão Excluir -->
                                    <button onclick="confirmarExclusao({{ $servico->id }}, '{{ $servico->nome }}')" 
                                            class="btn-trash-mobile"
                                            title="🗑️ Excluir serviço">
                                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Layout Desktop: Tabela -->
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto">
                            <table class="wa-table">
                                <thead>
                                    <tr>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:droplets" class="mr-2 text-red-400"></iconify-icon>
                                            Nome
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:dollar-sign" class="mr-2 text-red-400"></iconify-icon>
                                            Preço
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:clock" class="mr-2 text-red-400"></iconify-icon>
                                            Tempo
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:toggle-left" class="mr-2 text-red-400"></iconify-icon>
                                            Status
                                        </th>
                                        <th class="wa-th">
                                            <iconify-icon icon="lucide:settings" class="mr-2 text-red-400"></iconify-icon>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicos as $servico)
                                        <tr class="wa-tr">
                                            <td class="wa-td">
                                                <div class="text-sm font-medium text-white">{{ $servico->nome }}</div>
                                                @if($servico->descricao)
                                                    <div class="text-sm text-gray-400 mt-1">{{ Str::limit($servico->descricao, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="wa-td">
                                                <span class="text-green-400 font-semibold">
                                                    R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="wa-td">
                                                <span class="text-yellow-400 font-medium">
                                                    {{ $servico->tempo_estimado }}min
                                                </span>
                                            </td>
                                            <td class="wa-td">
                                                <span class="wa-badge-{{ $servico->ativo ? 'green' : 'red' }}">
                                                    <iconify-icon icon="lucide:{{ $servico->ativo ? 'check-circle' : 'x-circle' }}" class="mr-1"></iconify-icon>
                                                    {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td class="wa-td">
                                                <div class="flex space-x-2">
                                                    <!-- Botão Toggle Status -->
                                                    <button onclick="confirmarToggleStatus({{ $servico->id }}, '{{ $servico->nome }}', {{ $servico->ativo ? 'true' : 'false' }})" 
                                                            class="btn-premium-micro bg-{{ $servico->ativo ? 'yellow' : 'green' }}-600/20 text-{{ $servico->ativo ? 'yellow' : 'green' }}-300 hover:bg-{{ $servico->ativo ? 'yellow' : 'green' }}-500/30 border-{{ $servico->ativo ? 'yellow' : 'green' }}-500/30"
                                                            title="{{ $servico->ativo ? 'Desativar' : 'Ativar' }} serviço">
                                                        <iconify-icon icon="lucide:{{ $servico->ativo ? 'toggle-left' : 'toggle-right' }}"></iconify-icon>
                                                    </button>
                                                    
                                                    <!-- Botão Excluir -->
                                                    <button onclick="confirmarExclusao({{ $servico->id }}, '{{ $servico->nome }}')" 
                                                            class="btn-delete-trash btn-premium-micro bg-red-600/30 text-red-200 hover:bg-red-500/40 border-red-400/50 hover:scale-105"
                                                            title="🗑️ Excluir serviço">
                                                        <iconify-icon icon="lucide:trash-2" class="text-lg"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginação WA -->
                    <div class="mt-6">
                        <div class="wa-pagination">
                            {{ $servicos->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @else
                        <!-- Estado Vazio WA -->
                        <div class="text-center py-12">
                            <div class="mb-6">
                                <iconify-icon icon="lucide:droplets" class="text-6xl text-gray-600"></iconify-icon>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Nenhum serviço encontrado</h3>
                            <p class="text-gray-400 mb-6">
                                @if(request()->hasAny(['search', 'status']))
                                    Tente ajustar os filtros ou limpar a busca.
                                @else
                                    Comece cadastrando seu primeiro serviço.
                                @endif
                            </p>
                            <a href="{{ route('servicos.create') }}" class="btn-wa-primary">
                                <iconify-icon icon="lucide:plus" class="mr-2"></iconify-icon>
                                Cadastrar Primeiro Serviço
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmarExclusao(servicoId, servicoNome) {
            Swal.fire({
                title: '🗑️ Confirmar Exclusão',
                html: `Tem certeza que deseja excluir o serviço:<br><strong>"${servicoNome}"</strong><br><br>Esta ação não pode ser desfeita.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '🗑️ Sim, excluir!',
                cancelButtonText: '❌ Cancelar',
                background: '#1f2937',
                color: '#ffffff',
                customClass: {
                    popup: 'border border-red-500/30'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Excluindo...',
                        text: 'Aguarde um momento',
                        allowOutsideClick: false,
                        background: '#1f2937',
                        color: '#ffffff',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Criar e submeter formulário de exclusão
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/servicos/${servicoId}`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmarToggleStatus(servicoId, servicoNome, isAtivo) {
            const acao = isAtivo ? 'desativar' : 'ativar';
            const emoji = isAtivo ? '❌' : '✅';
            const cor = isAtivo ? '#dc2626' : '#16a34a';
            
            Swal.fire({
                title: `${emoji} ${acao.charAt(0).toUpperCase() + acao.slice(1)} Serviço`,
                html: `Deseja ${acao} o serviço:<br><strong>"${servicoNome}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: cor,
                cancelButtonColor: '#6b7280',
                confirmButtonText: `${emoji} Sim, ${acao}!`,
                cancelButtonText: '❌ Cancelar',
                background: '#1f2937',
                color: '#ffffff',
                customClass: {
                    popup: 'border border-red-500/30'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: `${acao.charAt(0).toUpperCase() + acao.slice(1)}ando...`,
                        text: 'Aguarde um momento',
                        allowOutsideClick: false,
                        background: '#1f2937',
                        color: '#ffffff',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Criar e submeter formulário de toggle
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/servicos/${servicoId}/toggle-status`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PATCH';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Animações de entrada para os cards
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.wa-tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.4s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 50);
            });

            // Animação para cards mobile
            const mobileCards = document.querySelectorAll('.wa-card-mobile');
            mobileCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</x-app-layout>
