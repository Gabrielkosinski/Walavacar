<x-app-layout>
    <!-- Container principal com padding responsivo -->
    <div class="min-h-screen bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <!-- 🔔 Notificações -->
            @if(isset($notificacoes) && count($notificacoes) > 0)
            <div class="mb-6 space-y-3">
                @foreach($notificacoes as $notificacao)
                    <div class="p-4 rounded-xl border-l-4 shadow-lg animate__animated animate__slideInLeft
                        @if($notificacao['tipo'] === 'warning')
                            bg-yellow-50 border-yellow-400 text-yellow-800
                        @elseif($notificacao['tipo'] === 'info')
                            bg-blue-50 border-blue-400 text-blue-800
                        @elseif($notificacao['tipo'] === 'success')
                            bg-green-50 border-green-400 text-green-800
                        @else
                            bg-gray-50 border-gray-400 text-gray-800
                        @endif
                    ">
                        <div class="flex items-center">
                            <iconify-icon icon="{{ $notificacao['icon'] ?? 'lucide:bell' }}" class="text-xl mr-3 flex-shrink-0"></iconify-icon>
                            <div>
                                <h4 class="font-semibold text-sm">{{ $notificacao['titulo'] }}</h4>
                                <p class="text-xs opacity-90 mt-1">{{ $notificacao['mensagem'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- 🎯 Botão Principal - NOVO ATENDIMENTO -->
            <div class="mb-8">
                <a href="{{ route('atendimentos.create') }}"
                   class="block w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-6 px-8 rounded-2xl text-center shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] group">
                    <div class="flex flex-col items-center">
                        <iconify-icon icon="lucide:plus-circle" class="text-6xl mb-3 group-hover:animate-bounce"></iconify-icon>
                        <div class="text-2xl font-bold tracking-wide">NOVO ATENDIMENTO</div>
                        <div class="text-sm opacity-90 font-medium mt-1">Adicionar cliente à fila de espera</div>
                    </div>
                </a>
            </div>

            <!-- PRÓXIMO DA FILA -->
            @if($proximoDaFila)
            <div id="fila-espera-section" class="bg-gray-800 border-2 border-red-500 rounded-2xl p-6 mb-8 shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="bg-red-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                <iconify-icon icon="lucide:target" class="text-2xl text-white animate-pulse"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Próximo da Fila</h3>
                                <p class="text-sm text-gray-400">Próximo atendimento a ser chamado</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-700 rounded-xl p-4 border border-red-500/30">
                            <div class="font-bold text-xl text-white mb-2">{{ $proximoDaFila->cliente->nome }}</div>
                            <div class="space-y-2 text-gray-300">
                                <div class="flex items-center">
                                    <iconify-icon icon="lucide:car" class="mr-2 text-red-400"></iconify-icon>
                                    {{ $proximoDaFila->carro->marca }} {{ $proximoDaFila->carro->modelo }}
                                </div>
                                <div class="flex items-center">
                                    <iconify-icon icon="lucide:credit-card" class="mr-2 text-red-400"></iconify-icon>
                                    <span class="font-mono font-bold">{{ $proximoDaFila->carro->placa }}</span>
                                </div>
                                <div class="flex items-center">
                                    <iconify-icon icon="lucide:car-wash" class="mr-2 text-red-400"></iconify-icon>
                                    {{ $proximoDaFila->servico->nome ?? 'Serviço não definido' }} - 
                                    <span class="text-green-400 font-bold ml-2">R$ {{ number_format($proximoDaFila->valor, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @if($proximoDaFila->observacoes)
                                <div class="mt-3 p-3 bg-gray-600 rounded-lg">
                                    <div class="text-yellow-400 text-sm font-semibold mb-2 flex items-center">
                                        <iconify-icon icon="lucide:file-text" class="mr-2"></iconify-icon>
                                        Serviços solicitados:
                                    </div>
                                    <div class="text-gray-200 text-sm leading-relaxed">{{ $proximoDaFila->observacoes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                        <div class="flex justify-center">
                            <button onclick="chamarProximo()"
                                    class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                                <div class="flex flex-col items-center">
                                    <iconify-icon icon="lucide:rocket" class="text-3xl mb-2"></iconify-icon>
                                    <span class="text-lg">CHAMAR</span>
                                    <span class="text-sm opacity-75">PRÓXIMO</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- EM ANDAMENTO -->
            @if($emAndamento->count() > 0)
            <div id="em-andamento-section" class="bg-gray-800 border border-orange-500/50 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-orange-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <iconify-icon icon="lucide:clock" class="text-2xl text-white animate-spin"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Em Andamento</h3>
                        <p class="text-sm text-gray-400">{{ $emAndamento->count() }} atendimento(s) sendo processado(s)</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($emAndamento as $atendimento)
                        <div class="bg-gray-700 rounded-xl p-4 border border-orange-500/30 hover:border-orange-400 transition-all duration-300 hover:shadow-lg relative overflow-hidden">
                            <!-- Barra de progresso -->
                            <div class="absolute top-0 left-0 w-full h-1 bg-orange-900/50">
                                <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 animate-pulse"></div>
                            </div>
                            
                            <div class="pt-2 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="font-bold text-white text-lg mb-3">{{ $atendimento->cliente->nome }}</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <div class="flex items-center text-gray-300">
                                            <iconify-icon icon="lucide:car" class="mr-2 text-orange-400"></iconify-icon>
                                            {{ $atendimento->carro->marca ?? 'N/A' }} {{ $atendimento->carro->modelo ?? '' }}
                                        </div>
                                        <div class="flex items-center text-gray-300">
                                            <iconify-icon icon="lucide:credit-card" class="mr-2 text-orange-400"></iconify-icon>
                                            <span class="font-mono font-semibold">{{ $atendimento->carro->placa ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-orange-400">
                                            <iconify-icon icon="lucide:clock" class="mr-2"></iconify-icon>
                                            Iniciado: {{ $atendimento->data_agendamento ? $atendimento->data_agendamento->format('H:i') : $atendimento->created_at->format('H:i') }}
                                        </div>
                                        <div class="flex items-center text-green-400 font-bold">
                                            <iconify-icon icon="lucide:dollar-sign" class="mr-2"></iconify-icon>
                                            R$ {{ number_format($atendimento->valor, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @if($atendimento->observacoes)
                                        <div class="mt-3 p-3 bg-gradient-to-r from-yellow-900/30 to-yellow-800/20 border border-yellow-500/30 rounded-lg">
                                            <div class="text-yellow-400 text-sm font-semibold mb-2 flex items-center">
                                                <iconify-icon icon="lucide:info" class="mr-2"></iconify-icon>
                                                Informações importantes:
                                            </div>
                                            <div class="text-gray-200 text-sm leading-relaxed bg-gray-700/50 p-2 rounded border-l-4 border-yellow-400">{{ $atendimento->observacoes }}</div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-shrink-0">
                                    <button onclick="finalizarAtendimento({{ $atendimento->id }}, {{ $atendimento->valor }})"
                                            class="w-full lg:w-auto bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                                        <div class="flex items-center justify-center">
                                            <iconify-icon icon="lucide:check-circle" class="mr-2 text-lg"></iconify-icon>
                                            <span>FINALIZAR</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- FILA DE ESPERA -->
            @if($filaEspera->count() > 0)
            <div class="bg-gray-800 border border-red-500/50 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-red-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <iconify-icon icon="lucide:clock" class="text-2xl text-white animate-pulse"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Fila de Espera</h3>
                        <p class="text-sm text-gray-400">{{ $filaEspera->count() }} cliente(s) aguardando atendimento</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($filaEspera as $index => $atendimento)
                        <div class="bg-gray-700 rounded-xl p-4 border {{ $index === 0 ? 'border-red-500 bg-gradient-to-r from-red-900/20 to-gray-700' : 'border-gray-600' }} hover:border-red-400 transition-all duration-300 hover:shadow-lg relative">
                            <!-- Badge de posição para o primeiro -->
                            @if($index === 0)
                                <div class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold shadow-lg animate-pulse">
                                    <iconify-icon icon="lucide:crown"></iconify-icon>
                                </div>
                            @endif
                            
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Número da posição -->
                                    <div class="bg-red-600 rounded-full w-10 h-10 flex items-center justify-center font-bold text-white shadow-lg flex-shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    <!-- Informações do cliente -->
                                    <div class="flex-1">
                                        <div class="font-bold text-white text-lg mb-1">{{ $atendimento->cliente->nome }}</div>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 text-sm">
                                            <div class="flex items-center text-gray-300">
                                                <iconify-icon icon="lucide:car" class="mr-2 text-red-400"></iconify-icon>
                                                {{ $atendimento->carro->marca }} {{ $atendimento->carro->modelo }}
                                            </div>
                                            <div class="flex items-center text-gray-300">
                                                <iconify-icon icon="lucide:credit-card" class="mr-2 text-red-400"></iconify-icon>
                                                <span class="font-mono font-semibold">{{ $atendimento->carro->placa }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informações do serviço -->
                                <div class="text-right sm:text-left lg:text-right">
                                    <div class="text-red-400 font-bold text-lg mb-1">
                                        {{ $atendimento->servico->nome ?? 'Serviço não definido' }}
                                    </div>
                                    <div class="text-gray-400 text-sm mb-2">
                                        <iconify-icon icon="lucide:clock" class="mr-1"></iconify-icon>
                                        {{ $atendimento->data_entrada_fila ? $atendimento->data_entrada_fila->format('H:i') : $atendimento->created_at->format('H:i') }}
                                    </div>
                                    <div class="text-green-400 font-bold text-lg">
                                        <iconify-icon icon="lucide:dollar-sign" class="mr-1"></iconify-icon>
                                        R$ {{ number_format($atendimento->valor, 0, ',', '.') }}
                                    </div>
                                    @if($atendimento->observacoes)
                                        <div class="mt-2 p-2 bg-gray-600 rounded-lg text-left">
                                            <div class="text-yellow-400 text-xs font-semibold mb-1 flex items-center">
                                                <iconify-icon icon="lucide:file-text" class="mr-1"></iconify-icon>
                                                Serviços:
                                            </div>
                                            <div class="text-gray-200 text-xs leading-relaxed">{{ $atendimento->observacoes }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- PRONTOS PARA BUSCAR -->
            @if($prontos->count() > 0)
            <div id="prontos-section" class="bg-gray-800 border border-green-500/50 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="bg-green-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                        <iconify-icon icon="lucide:check-circle-outline" class="text-2xl text-white animate-bounce"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Prontos para Buscar</h3>
                        <p class="text-sm text-gray-400">{{ $prontos->count() }} veículo(s) finalizado(s)</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($prontos as $atendimento)
                        <div class="bg-gray-700 rounded-xl p-4 border border-green-500/30 hover:border-green-400 transition-all duration-300 hover:shadow-lg relative overflow-hidden">
                            <!-- Indicador de sucesso -->
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-500"></div>
                            
                            <div class="pt-2 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="font-bold text-white text-lg mb-3 flex items-center">
                                        <iconify-icon icon="lucide:user" class="mr-2 text-green-400"></iconify-icon>
                                        {{ $atendimento->cliente->nome }}
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-4">
                                        <div class="flex items-center text-gray-300">
                                            <iconify-icon icon="lucide:car" class="mr-2 text-green-400"></iconify-icon>
                                            {{ $atendimento->carro->marca ?? 'N/A' }} {{ $atendimento->carro->modelo ?? '' }}
                                        </div>
                                        <div class="flex items-center text-gray-300">
                                            <iconify-icon icon="lucide:credit-card" class="mr-2 text-green-400"></iconify-icon>
                                            <span class="font-mono font-semibold">{{ $atendimento->carro->placa ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-green-400">
                                            <iconify-icon icon="lucide:clock" class="mr-2"></iconify-icon>
                                            Finalizado: {{ $atendimento->updated_at ? $atendimento->updated_at->format('H:i') : $atendimento->created_at->format('H:i') }}
                                        </div>
                                        <div class="flex items-center text-green-400 font-bold text-lg">
                                            <iconify-icon icon="lucide:dollar-sign" class="mr-2"></iconify-icon>
                                            R$ {{ number_format($atendimento->valor, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @if($atendimento->observacoes)
                                        <div class="mt-3 p-3 bg-gradient-to-r from-green-900/30 to-green-800/20 border border-green-500/30 rounded-lg">
                                            <div class="text-green-400 text-sm font-semibold mb-2 flex items-center">
                                                <iconify-icon icon="lucide:info" class="mr-2"></iconify-icon>
                                                Informações importantes:
                                            </div>
                                            <div class="text-gray-200 text-sm leading-relaxed bg-gray-700/50 p-2 rounded border-l-4 border-green-400">{{ $atendimento->observacoes }}</div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-shrink-0">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button onclick="registrarPagamento({{ $atendimento->id }}, {{ $atendimento->valor }})"
                                                class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                                            <div class="flex items-center justify-center">
                                                <iconify-icon icon="lucide:calculator" class="mr-2 text-lg"></iconify-icon>
                                                <span class="hidden sm:inline">REGISTRAR </span>PAGAMENTO
                                            </div>
                                        </button>
                                        
                                        @if($atendimento->cliente->whatsapp)
                                            @php
                                                $dados = [
                                                    'cliente' => $atendimento->cliente->nome,
                                                    'marca' => $atendimento->carro->marca ?? 'N/A',
                                                    'modelo' => $atendimento->carro->modelo ?? '',
                                                    'placa' => $atendimento->carro->placa ?? 'N/A',
                                                    'servico' => $atendimento->servico->nome ?? 'Serviço padrão',
                                                    'valor' => number_format($atendimento->valor, 2, ',', '.'),
                                                    'filial' => 'Matriz'
                                                ];
                                                $urlWhatsApp = \App\Services\WhatsAppService::gerarUrlWhatsApp($atendimento->cliente->whatsapp, 'carro_pronto', $dados);
                                            @endphp
                                            <a href="{{ $urlWhatsApp }}"
                                               target="_blank"
                                               class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                                <iconify-icon icon="lucide:message-circle" class="mr-2 text-lg"></iconify-icon>
                                                <span class="hidden sm:inline">CHAMAR NO </span>WHATSAPP
                                            </a>
                                        @else
                                            <div class="bg-gray-600 text-gray-400 py-3 px-4 rounded-xl text-center">
                                                <iconify-icon icon="lucide:x" class="mr-2"></iconify-icon>
                                                Sem WhatsApp
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Resumo do Dia -->
            <div class="bg-gray-800 border border-blue-500/50 rounded-2xl p-6 shadow-lg">
                <div class="text-center mb-6">
                    <div class="bg-blue-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <iconify-icon icon="lucide:calendar" class="text-3xl text-white"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-bold text-white">Resumo de Hoje</h3>
                    <p class="text-sm text-gray-400">Estatísticas do dia atual</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-700 rounded-xl p-4 text-center hover:scale-105 transition-all duration-300 border border-blue-500/30 relative">
                        <div class="text-3xl font-bold text-blue-400 mb-2">{{ $estatisticas['atendimentos_hoje'] ?? 0 }}</div>
                        <div class="text-sm text-gray-300 font-medium">Total de Atendimentos</div>
                        <iconify-icon icon="lucide:car" class="absolute top-3 right-3 text-blue-400 opacity-30 text-xl"></iconify-icon>
                    </div>
                    
                    <div onclick="scrollToEmAndamento()" class="bg-gray-700 rounded-xl p-4 text-center hover:scale-105 transition-all duration-300 border border-orange-500/30 relative cursor-pointer hover:border-orange-400 hover:shadow-lg hover:shadow-orange-500/20">
                        <div class="text-3xl font-bold text-orange-400 mb-2">{{ $emAndamento->count() }}</div>
                        <div class="text-sm text-gray-300 font-medium">Em Processamento</div>
                        <iconify-icon icon="lucide:clock" class="absolute top-3 right-3 text-orange-400 opacity-30 text-xl"></iconify-icon>
                        @if($emAndamento->count() > 0)
                        <!-- Indicador de clicável -->
                        <div class="absolute bottom-2 right-2">
                            <iconify-icon icon="lucide:arrow-down" class="text-orange-400 text-sm animate-bounce"></iconify-icon>
                        </div>
                        @endif
                    </div>
                    
                    <div onclick="scrollToProntos()" class="bg-gray-700 rounded-xl p-4 text-center hover:scale-105 transition-all duration-300 border border-green-500/30 relative cursor-pointer hover:border-green-400 hover:shadow-lg hover:shadow-green-500/20">
                        <div class="text-3xl font-bold text-green-400 mb-2">{{ $prontos->count() }}</div>
                        <div class="text-sm text-gray-300 font-medium">Prontos para Buscar</div>
                        <iconify-icon icon="lucide:check-circle" class="absolute top-3 right-3 text-green-400 opacity-30 text-xl"></iconify-icon>
                        @if($prontos->count() > 0)
                        <!-- Indicador de clicável -->
                        <div class="absolute bottom-2 right-2">
                            <iconify-icon icon="lucide:arrow-down" class="text-green-400 text-sm animate-bounce"></iconify-icon>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Novo Card: Fila de Espera (Clicável) -->
                    <div onclick="scrollToFilaEspera()" class="bg-gray-700 rounded-xl p-4 text-center hover:scale-105 transition-all duration-300 border border-purple-500/30 relative cursor-pointer hover:border-purple-400 hover:shadow-lg hover:shadow-purple-500/20">
                        <div class="text-3xl font-bold text-purple-400 mb-2">
                            @php
                                $filaEsperaCount = \App\Models\Atendimento::whereIn('status', ['em_fila', 'aguardando'])->count();
                            @endphp
                            {{ $filaEsperaCount }}
                        </div>
                        <div class="text-sm text-gray-300 font-medium">Fila de Espera</div>
                        <iconify-icon icon="lucide:users" class="absolute top-3 right-3 text-purple-400 opacity-30 text-xl"></iconify-icon>
                        @if($filaEsperaCount > 0)
                        <!-- Indicador de clicável -->
                        <div class="absolute bottom-2 right-2">
                            <iconify-icon icon="lucide:arrow-down" class="text-purple-400 text-sm animate-bounce"></iconify-icon>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- � Link para Relatórios Financeiros -->
    <div class="glass-card p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mr-4">
                    <iconify-icon icon="lucide:chart-bar" class="text-2xl text-white"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">� Relatórios Financeiros</h3>
                    <p class="text-sm text-gray-400">Acesse relatórios detalhados de faturamento</p>
                </div>
            </div>
            <a href="{{ route('relatorios.index') }}" 
               class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 flex items-center space-x-2 hover:scale-105">
                <iconify-icon icon="lucide:external-link" class="text-lg"></iconify-icon>
                <span>Ver Relatórios</span>
            </a>
        </div>
    </div>

    <!-- JavaScript para finalizar atendimento e controlar fila -->
    <script>
        // 🚀 Testes de carregamento do JavaScript
        console.log('📋 Dashboard JavaScript carregado');
        console.log('🔍 SweetAlert2 disponível:', typeof Swal !== 'undefined');
        console.log('🔍 Enhanced UI disponível:', typeof window.Toast !== 'undefined');
        
        // 🎯 Função para scroll suave até a seção da fila de espera
        function scrollToFilaEspera() {
            const filaSection = document.getElementById('fila-espera-section');
            if (filaSection) {
                filaSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Efeito visual de destaque
                filaSection.style.transition = 'all 0.3s ease';
                filaSection.style.transform = 'scale(1.02)';
                filaSection.style.boxShadow = '0 0 30px rgba(239, 68, 68, 0.4)';
                
                setTimeout(() => {
                    filaSection.style.transform = 'scale(1)';
                    filaSection.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
                }, 300);
            } else {
                // Se não há fila, mostrar mensagem
                if (typeof window.Toast !== 'undefined') {
                    window.Toast.info('Não há clientes na fila de espera no momento! 🎉');
                } else {
                    alert('Não há clientes na fila de espera no momento!');
                }
            }
        }

        // 🟠 Função para scroll suave até a seção em andamento
        function scrollToEmAndamento() {
            const emAndamentoSection = document.getElementById('em-andamento-section');
            if (emAndamentoSection) {
                emAndamentoSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Efeito visual de destaque laranja
                emAndamentoSection.style.transition = 'all 0.3s ease';
                emAndamentoSection.style.transform = 'scale(1.02)';
                emAndamentoSection.style.boxShadow = '0 0 30px rgba(251, 146, 60, 0.4)';
                
                setTimeout(() => {
                    emAndamentoSection.style.transform = 'scale(1)';
                    emAndamentoSection.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
                }, 300);
            } else {
                // Se não há atendimentos em andamento
                if (typeof window.Toast !== 'undefined') {
                    window.Toast.info('Não há atendimentos em processamento no momento! ⏰');
                } else {
                    alert('Não há atendimentos em processamento no momento!');
                }
            }
        }

        // 🟢 Função para scroll suave até a seção prontos
        function scrollToProntos() {
            const prontosSection = document.getElementById('prontos-section');
            if (prontosSection) {
                prontosSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Efeito visual de destaque verde
                prontosSection.style.transition = 'all 0.3s ease';
                prontosSection.style.transform = 'scale(1.02)';
                prontosSection.style.boxShadow = '0 0 30px rgba(34, 197, 94, 0.4)';
                
                setTimeout(() => {
                    prontosSection.style.transform = 'scale(1)';
                    prontosSection.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
                }, 300);
            } else {
                // Se não há atendimentos prontos
                if (typeof window.Toast !== 'undefined') {
                    window.Toast.info('Não há veículos prontos para buscar no momento! 🚗');
                } else {
                    alert('Não há veículos prontos para buscar no momento!');
                }
            }
        }
        
        // Adicionar animações aos elementos quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            // Animar entrada dos cards com delay escalonado
            const cards = document.querySelectorAll('.animate-slide-up');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        async function finalizarAtendimento(id, valorServico = 0) {
            console.log('🎯 Função finalizarAtendimento chamada para ID:', id, 'Valor:', valorServico);
            console.log('🔍 SweetAlert2 disponível:', typeof Swal !== 'undefined');
            
            // Teste rápido para verificar se a função está funcionando
            if (!id) {
                alert('❌ Erro: ID do atendimento não fornecido');
                return;
            }
            
            if (typeof Swal === 'undefined') {
                alert('❌ SweetAlert2 não está carregado! Usando alert básico.');
                const confirmacao = confirm(`Finalizar atendimento ${id}?\n\nEscolha OK para continuar.`);
                if (confirmacao) {
                    // Aqui você pode implementar uma versão simples
                    alert('✅ Atendimento seria finalizado (versão simplificada)');
                }
                return;
            }
            
            try {
                const { value: opcaoFinalizacao } = await Swal.fire({
                    title: '🏁 Finalizar Atendimento',
                    text: 'Como deseja finalizar este atendimento?',
                    icon: 'question',
                    input: 'select',
                    inputOptions: {
                        'pronto': '✅ Veículo Pronto para Retirada',
                        'pago_finalizado': '💳 Pago e Finalizado',
                        'cancelado': '❌ Cancelar Atendimento'
                    },
                    inputPlaceholder: 'Selecione uma opção...',
                    showCancelButton: true,
                    confirmButtonText: '✅ Confirmar',
                    cancelButtonText: '❌ Cancelar',
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#ef4444',
                    background: '#1a1a1a',
                    color: '#ffffff',
                    width: '500px',
                    customClass: {
                        popup: 'animate__animated animate__slideInDown',
                        confirmButton: 'btn-primary-pro',
                        cancelButton: 'btn-secondary-wa'
                    }
                });
                
                if (opcaoFinalizacao) {
                    console.log('📋 Opção selecionada:', opcaoFinalizacao);
                    await processarFinalizacao(id, opcaoFinalizacao, valorServico);
                }
                
            } catch (error) {
                console.error('💥 Erro no modal de finalização:', error);
                Toast.error('Erro ao abrir modal: ' + error.message, '❌ Erro');
            }
        }

        async function processarFinalizacao(id, opcao, valorServico = 0) {
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            
            // Loading UI usando nossa biblioteca se disponível
            if (typeof LoadingUI !== 'undefined') {
                LoadingUI.showButton(button, 'Processando...');
            } else {
                button.innerHTML = '<iconify-icon icon="lucide:loader-2" class="animate-spin mr-2"></iconify-icon>Processando...';
                button.disabled = true;
            }
            
            try {
                let endpoint = '';
                let metodo = 'PATCH';
                let dadosAdicionais = {};
                
                // Definir endpoint baseado na opção
                switch(opcao) {
                    case 'pronto':
                        endpoint = `/atendimentos/${id}/finalizar`;
                        dadosAdicionais = { status: 'pronto' };
                        break;
                    case 'pago_finalizado':
                        // Abrir modal de pagamento primeiro
                        const dadosPagamento = await modalPagamento(valorServico);
                        if (!dadosPagamento) {
                            if (typeof LoadingUI !== 'undefined') {
                                LoadingUI.restoreButton(button, originalContent);
                            } else {
                                button.innerHTML = originalContent;
                                button.disabled = false;
                            }
                            return;
                        }
                        endpoint = `/atendimentos/${id}/finalizar`;
                        dadosAdicionais = { 
                            status: 'finalizado',
                            forma_pagamento: dadosPagamento.forma,
                            valor_pago: dadosPagamento.valor
                        };
                        break;
                    case 'cancelado':
                        const confirmacao = await Swal.fire({
                            title: '⚠️ Confirmar Cancelamento',
                            text: 'Tem certeza que deseja cancelar este atendimento?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: '✅ Sim, cancelar',
                            cancelButtonText: '❌ Não',
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            background: '#1a1a1a',
                            color: '#ffffff'
                        });
                        
                        if (!confirmacao.isConfirmed) {
                            if (typeof LoadingUI !== 'undefined') {
                                LoadingUI.restoreButton(button, originalContent);
                            } else {
                                button.innerHTML = originalContent;
                                button.disabled = false;
                            }
                            return;
                        }
                        
                        endpoint = `/atendimentos/${id}/cancelar`;
                        dadosAdicionais = { motivo: 'cancelado_usuario' };
                        break;
                }
                
                console.log('📡 Enviando requisição para:', endpoint, 'com dados:', dadosAdicionais);
                
                const response = await fetch(endpoint, {
                    method: metodo,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.getCSRFToken ? window.getCSRFToken() : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(dadosAdicionais)
                });
                
                console.log('📊 Response status:', response.status);
                
                // Verificar se é erro de CSRF/sessão
                if (response.status === 419) {
                    console.log('🔄 Token CSRF expirado, tentando renovar...');
                    await refreshCSRFToken();
                    
                    // Tentar novamente com token renovado
                    const retryResponse = await fetch(endpoint, {
                        method: metodo,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.getCSRFToken(),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(dadosAdicionais)
                    });
                    
                    if (retryResponse.status === 419) {
                        // Se ainda falhar, redirecionar para login
                        alert('⚠️ Sua sessão expirou. Redirecionando para login...');
                        window.location.href = '/login';
                        return;
                    }
                    
                    const retryData = await retryResponse.json();
                    console.log('📋 Retry response data:', retryData);
                    
                    if (retryData.success) {
                        showSuccessMessage('✅ ' + (mensagens[opcao] || retryData.message));
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showErrorMessage('❌ ' + retryData.message);
                        if (typeof LoadingUI !== 'undefined') {
                            LoadingUI.restoreButton(button, originalContent);
                        } else {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }
                    }
                    return;
                }
                
                const data = await response.json();
                console.log('📋 Response data:', data);
                
                if (data.success) {
                    // Mensagens específicas por tipo
                    const mensagens = {
                        'pronto': '✅ Veículo marcado como pronto para retirada!',
                        'pago_finalizado': '💳 Atendimento pago e finalizado com sucesso!',
                        'cancelado': '❌ Atendimento cancelado'
                    };
                    
                    // Usar Toast se disponível, senão usar função própria
                    if (typeof Toast !== 'undefined') {
                        Toast.success(mensagens[opcao] || data.message, '🎉 Sucesso!');
                    } else {
                        showSuccessMessage(mensagens[opcao] || data.message);
                    }
                    
                    // Reload suave após animação
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.error(data.message || 'Erro ao processar solicitação', '❌ Erro');
                    } else {
                        showErrorMessage(data.message || 'Erro ao processar solicitação');
                    }
                    
                    if (typeof LoadingUI !== 'undefined') {
                        LoadingUI.restoreButton(button, originalContent);
                    } else {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }
                }
            } catch (error) {
                console.error('💥 Erro na requisição:', error);
                
                if (typeof Toast !== 'undefined') {
                    Toast.error('Erro de conexão: ' + error.message, '❌ Erro de Rede');
                } else {
                    showErrorMessage('Erro de conexão: ' + error.message);
                }
                
                if (typeof LoadingUI !== 'undefined') {
                    LoadingUI.restoreButton(button, originalContent);
                } else {
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            }
        }

        async function modalPagamento(valorServico = 0) {
            const { value: formData } = await Swal.fire({
                title: '💰 Dados do Pagamento',
                html: `
                    <style>
                        .modal-pagamento-select {
                            width: 100% !important;
                            padding: 12px !important;
                            background: #374151 !important;
                            border: 2px solid #4b5563 !important;
                            border-radius: 8px !important;
                            color: #ffffff !important;
                            font-size: 14px !important;
                            transition: all 0.3s ease !important;
                        }
                        .modal-pagamento-select:focus {
                            border-color: #dc2626 !important;
                            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
                            outline: none !important;
                        }
                        .modal-pagamento-select option {
                            background: #374151 !important;
                            color: #ffffff !important;
                            padding: 8px !important;
                        }
                        .modal-pagamento-input {
                            width: 100% !important;
                            padding: 12px !important;
                            background: #374151 !important;
                            border: 2px solid #4b5563 !important;
                            border-radius: 8px !important;
                            color: #ffffff !important;
                            font-size: 14px !important;
                            transition: all 0.3s ease !important;
                        }
                        .modal-pagamento-input:focus {
                            border-color: #dc2626 !important;
                            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
                            outline: none !important;
                        }
                    </style>
                    <div class="wa-form-section p-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Forma de Pagamento</label>
                            <select id="forma_pagamento" class="modal-pagamento-select">
                                <option value="">Selecione...</option>
                                <option value="dinheiro">💵 Dinheiro</option>
                                <option value="pix">📱 PIX</option>
                                <option value="cartao_debito">💳 Cartão de Débito</option>
                                <option value="cartao_credito">💳 Cartão de Crédito</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Valor Pago (R$)</label>
                            <input type="number" id="valor_pago" step="0.01" min="0" value="${valorServico}" 
                                   class="modal-pagamento-input" placeholder="Digite o valor...">
                        </div>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '✅ Confirmar Pagamento',
                cancelButtonText: '❌ Cancelar',
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#ef4444',
                background: '#1a1a1a',
                color: '#ffffff',
                width: '450px',
                preConfirm: () => {
                    const forma = document.getElementById('forma_pagamento').value;
                    const valor = document.getElementById('valor_pago').value;
                    
                    if (!forma) {
                        Swal.showValidationMessage('Selecione a forma de pagamento');
                        return false;
                    }
                    
                    if (!valor || parseFloat(valor) <= 0) {
                        Swal.showValidationMessage('Informe um valor válido');
                        return false;
                    }
                    
                    return {
                        forma: forma,
                        valor: parseFloat(valor)
                    };
                },
                customClass: {
                    popup: 'animate__animated animate__slideInDown'
                }
            });
            
            return formData;
        }

        /**
         * Registrar pagamento quando cliente busca o veículo
         */
        function registrarPagamento(id, valor = null) {
            // Modal de pagamento simplificado e com CSS corrigido
            const modalHtml = `
                <div class="wa-card p-6 max-w-md mx-auto">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                        <iconify-icon icon="lucide:calculator" class="mr-2 text-green-400"></iconify-icon>
                        Registrar Pagamento
                    </h3>
                    
                    <form id="formPagamento">
                        <div class="mb-4">
                            <label class="block text-white text-sm font-bold mb-2">Forma de Pagamento:</label>
                            <select id="formaPagamento" required style="
                                width: 100% !important;
                                padding: 12px !important;
                                background: #374151 !important;
                                border: 2px solid #4b5563 !important;
                                border-radius: 8px !important;
                                color: #ffffff !important;
                                font-size: 14px !important;
                            ">
                                <option value="" style="background: #374151 !important; color: #ffffff !important;">Selecione...</option>
                                <option value="dinheiro" style="background: #374151 !important; color: #ffffff !important;">💵 Dinheiro</option>
                                <option value="pix" style="background: #374151 !important; color: #ffffff !important;">� PIX</option>
                                <option value="cartao_credito" style="background: #374151 !important; color: #ffffff !important;">💳 Cartão Crédito</option>
                                <option value="cartao_debito" style="background: #374151 !important; color: #ffffff !important;">💳 Cartão Débito</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-white text-sm font-bold mb-2">Valor Pago (R$):</label>
                            <input type="number" id="valorPago" step="0.01" min="0" required 
                                   value="${valor || ''}"
                                   placeholder="0,00"
                                   style="
                                       width: 100% !important;
                                       padding: 12px !important;
                                       background: #374151 !important;
                                       border: 2px solid #4b5563 !important;
                                       border-radius: 8px !important;
                                       color: #ffffff !important;
                                       font-size: 14px !important;
                                   ">
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="fecharModalPagamento()" class="btn-secondary-wa flex-1 py-3">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-primary-pro flex-1 py-3">
                                <iconify-icon icon="lucide:check" class="mr-2"></iconify-icon>
                                Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            `;
            
            // Criar modal
            const modal = document.createElement('div');
            modal.id = 'modalPagamento';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.innerHTML = modalHtml;
            document.body.appendChild(modal);
            
            // Handler do formulário
            document.getElementById('formPagamento').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const dadosPagamento = {
                    forma_pagamento: document.getElementById('formaPagamento').value,
                    valor_pago: parseFloat(document.getElementById('valorPago').value)
                };
                
                // Validação
                if (!dadosPagamento.forma_pagamento || dadosPagamento.valor_pago <= 0) {
                    showErrorMessage('❌ Preencha todos os campos obrigatórios');
                    return;
                }
                
                // Enviar pagamento
                fetch(`/atendimentos/${id}/pagar`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.getCSRFToken ? window.getCSRFToken() : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(dadosPagamento)
                })
                .then(async response => {
                    if (response.status === 419) {
                        console.log('🔄 Token CSRF expirado, renovando...');
                        await refreshCSRFToken();
                        alert('⚠️ Sessão expirou. Por favor, tente novamente.');
                        return { success: false, message: 'Sessão expirada' };
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fecharModalPagamento();
                        showSuccessMessage('✅ ' + data.message);
                        
                        // Opção de abrir comprovante
                        if (data.comprovante_url) {
                            setTimeout(() => {
                                if (confirm('💰 Pagamento registrado!\n\nDeseja visualizar o comprovante?')) {
                                    window.open(data.comprovante_url, '_blank');
                                }
                                location.reload();
                            }, 1000);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        showErrorMessage('❌ ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showErrorMessage('❌ Erro ao registrar pagamento');
                });
            });
        }
        
        function fecharModalPagamento() {
            const modal = document.getElementById('modalPagamento');
            if (modal) {
                modal.remove();
            }
        }

        function showPaymentModal() {
            const options = [
                '💵 1 - Dinheiro',
                '💳 2 - PIX',
                '💳 3 - Cartão Crédito',
                '💳 4 - Cartão Débito'
            ];
            
            const message = '💰 Selecione a forma de pagamento:\n\n' + options.join('\n') + '\n\nDigite o número (1-4):';
            const input = prompt(message);
            
            const formas = {
                '1': 'dinheiro',
                '2': 'pix', 
                '3': 'credito',
                '4': 'debito'
            };
            
            return formas[input] || null;
        }

        function chamarProximo() {
            @if($proximoDaFila)
            // Modal bonito com SweetAlert2
            Swal.fire({
                title: '🎯 Chamar para Atendimento',
                html: `
                    <div class="text-left space-y-4 p-4">
                        <div class="bg-gray-100 rounded-lg p-4 space-y-3">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-user mr-3 text-blue-500"></i>
                                <span class="font-bold text-lg">{{ $proximoDaFila->cliente->nome }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-car mr-3 text-red-500"></i>
                                <span>{{ $proximoDaFila->carro->marca }} {{ $proximoDaFila->carro->modelo }}</span>
                                <span class="ml-2 font-mono font-bold bg-gray-200 px-2 py-1 rounded">{{ $proximoDaFila->carro->placa }}</span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tools mr-3 text-orange-500"></i>
                                <span>{{ $proximoDaFila->servico->nome ?? 'Serviço não definido' }}</span>
                            </div>
                            
                            <div class="flex items-center text-green-600 font-bold text-lg">
                                <i class="fas fa-dollar-sign mr-3"></i>
                                <span>R$ {{ number_format($proximoDaFila->valor, 2, ',', '.') }}</span>
                            </div>
                            
                            @if($proximoDaFila->observacoes)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                <div class="flex items-center text-yellow-800 font-semibold mb-2">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    <span>Serviços solicitados:</span>
                                </div>
                                <div class="text-yellow-700 text-sm">{{ $proximoDaFila->observacoes }}</div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                            <div class="text-blue-800 text-sm font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Este cliente será movido para "Em Atendimento"
                            </div>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-rocket mr-2"></i>CHAMAR AGORA',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                width: '500px',
                customClass: {
                    popup: 'animated fadeInDown',
                    confirmButton: 'btn-wa-primary',
                    cancelButton: 'btn-wa-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceder com a chamada
                    executarChamada();
                }
            });
            @else
            Swal.fire({
                title: 'Fila Vazia',
                text: 'Não há clientes na fila de espera.',
                icon: 'info',
                confirmButtonColor: '#ef4444'
            });
            @endif
        }
        
        function executarChamada() {
            // Loading durante a requisição
            Swal.fire({
                title: 'Chamando cliente...',
                html: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-3xl text-red-500 mr-3"></i><span>Processando atendimento...</span></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated pulse'
                }
            });
            
            fetch('/atendimentos/chamar-proximo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.getCSRFToken ? window.getCSRFToken() : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                if (response.status === 419) {
                    console.log('🔄 Token CSRF expirado, renovando...');
                    await refreshCSRFToken();
                    Swal.fire({
                        title: 'Sessão Expirou',
                        text: 'Por favor, tente novamente.',
                        icon: 'warning',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#ef4444',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao chamar próximo da fila',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            });
        }

        function showSuccessMessage(message) {
            const toast = createToast(message, 'success');
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function showErrorMessage(message) {
            const toast = createToast(message, 'error');
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function createToast(message, type) {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-all duration-300 font-semibold`;
            toast.innerHTML = message;
            
            // Animar entrada
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Animar saída
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
            }, 2500);
            
            return toast;
        }

        // 🔄 Auto-renovação de token CSRF para evitar "page expired"
        async function refreshCSRFToken() {
            try {
                const response = await fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    // Atualizar meta tag
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    console.log('🔄 Token CSRF renovado automaticamente');
                    return data.csrf_token;
                } else {
                    throw new Error('Falha ao renovar token CSRF');
                }
            } catch (error) {
                console.log('⚠️ Erro ao renovar CSRF token:', error);
                // Se falhar, tentar recarregar a página
                if (error.message.includes('419') || error.message.includes('expired')) {
                    alert('⚠️ Sua sessão expirou. A página será recarregada.');
                    window.location.reload();
                }
                return null;
            }
        }

        // Função para obter token CSRF atual
        window.getCSRFToken = function() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        };

        // Renovar token CSRF a cada 10 minutos
        setInterval(refreshCSRFToken, 10 * 60 * 1000);

        // Verificar conectividade e status da sessão
        function checkSessionStatus() {
            fetch('/dashboard', {
                method: 'HEAD',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .catch(error => {
                console.log('⚠️ Possível problema de conectividade:', error);
                // Mostrar aviso para o usuário
                if (typeof showErrorMessage === 'function') {
                    showErrorMessage('⚠️ Verifique sua conexão com a internet');
                }
            });
        }

        // Verificar status da sessão a cada 5 minutos
        setInterval(checkSessionStatus, 5 * 60 * 1000);

        // Adicionar efeitos de hover suaves aos cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('[class*="hover:scale-"]');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.02) translateY(-2px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) translateY(0)';
                });
            });

            // Renovar token CSRF logo na inicialização
            setTimeout(refreshCSRFToken, 2000);
        });
    </script>
</x-app-layout>
