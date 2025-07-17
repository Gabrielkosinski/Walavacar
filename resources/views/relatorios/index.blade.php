<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Filtros de Relatório</h3>
                    <form method="GET" action="{{ route('relatorios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="data_fim" class="block text-sm font-medium text-gray-700">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos os Status</option>
                                <option value="agendado" {{ request('status') == 'agendado' ? 'selected' : '' }}>📅 Agendado</option>
                                <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>🔄 Em Andamento</option>
                                <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>✅ Concluído</option>
                                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>❌ Cancelado</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex-1 transition duration-300">
                                🔍 Filtrar
                            </button>
                        </div>
                    </form>
                    
                    <!-- Botão de Exportar PDF -->
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('relatorios.exportar-pdf', request()->query()) }}" 
                           class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                            📄 Exportar PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-2xl text-blue-600">📊</div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-500">Total de Atendimentos</div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_atendimentos'] ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-2xl text-green-600">💰</div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-500">Faturamento Total</div>
                                <div class="text-2xl font-bold text-gray-900">R$ {{ number_format($estatisticas['faturamento_total'] ?? 0, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-2xl text-yellow-600">📈</div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-500">Ticket Médio</div>
                                <div class="text-2xl font-bold text-gray-900">R$ {{ number_format($estatisticas['ticket_medio'] ?? 0, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-2xl text-purple-600">👥</div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-500">Clientes Únicos</div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['clientes_unicos'] ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- � SEÇÃO FINANCEIRA DETALHADA -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">💰</span>
                        Controle Financeiro
                    </h2>
                    <p class="text-gray-600 mt-2">Acompanhamento detalhado de pagamentos e faturamento</p>
                </div>
                
                <div class="p-6">
                    <!-- Cards Financeiros do Período -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Recebido no Período -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 text-center hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-green-200 rounded-full -mr-10 -mt-10 opacity-30"></div>
                            <div class="relative">
                                <div class="text-3xl font-bold text-green-700 mb-2">
                                    R$ {{ number_format($estatisticasPagamento['total_recebido_periodo'] ?? 0, 2, ',', '.') }}
                                </div>
                                <div class="text-sm text-green-600 font-medium">Total Recebido no Período</div>
                                <iconify-icon icon="lucide:trending-up" class="absolute top-2 right-2 text-green-500 opacity-40 text-xl"></iconify-icon>
                            </div>
                        </div>
                        
                        <!-- Pagamentos Realizados -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 text-center hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200 rounded-full -mr-10 -mt-10 opacity-30"></div>
                            <div class="relative">
                                <div class="text-3xl font-bold text-blue-700 mb-2">{{ $estatisticasPagamento['atendimentos_pagos_periodo'] ?? 0 }}</div>
                                <div class="text-sm text-blue-600 font-medium">Pagamentos Realizados</div>
                                <iconify-icon icon="lucide:check-circle" class="absolute top-2 right-2 text-blue-500 opacity-40 text-xl"></iconify-icon>
                            </div>
                        </div>
                        
                        <!-- Pendentes de Pagamento -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 text-center hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-200 rounded-full -mr-10 -mt-10 opacity-30"></div>
                            <div class="relative">
                                <div class="text-3xl font-bold text-yellow-700 mb-2">{{ $estatisticasPagamento['pendentes_pagamento'] ?? 0 }}</div>
                                <div class="text-sm text-yellow-600 font-medium">Pendentes de Pagamento</div>
                                <iconify-icon icon="lucide:clock" class="absolute top-2 right-2 text-yellow-500 opacity-40 text-xl"></iconify-icon>
                            </div>
                        </div>
                        
                        <!-- Ticket Médio -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 text-center hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-purple-200 rounded-full -mr-10 -mt-10 opacity-30"></div>
                            <div class="relative">
                                <div class="text-3xl font-bold text-purple-700 mb-2">
                                    R$ {{ number_format($estatisticas['ticket_medio'] ?? 0, 2, ',', '.') }}
                                </div>
                                <div class="text-sm text-purple-600 font-medium">Ticket Médio</div>
                                <iconify-icon icon="lucide:bar-chart-3" class="absolute top-2 right-2 text-purple-500 opacity-40 text-xl"></iconify-icon>
                            </div>
                        </div>
                    </div>

                    <!-- Formas de Pagamento Detalhadas -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <iconify-icon icon="lucide:credit-card" class="text-xl mr-2 text-gray-600"></iconify-icon>
                            Formas de Pagamento
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                            @if(isset($estatisticasPagamento['pagamentos_por_forma']) && $estatisticasPagamento['pagamentos_por_forma']->count() > 0)
                                @foreach($estatisticasPagamento['pagamentos_por_forma'] as $forma => $dados)
                                    <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-300">
                                        <div class="text-center">
                                            <div class="text-2xl mb-2">
                                                @switch($forma)
                                                    @case('dinheiro')
                                                        💵
                                                        @break
                                                    @case('pix')
                                                        📱
                                                        @break
                                                    @case('credito')
                                                    @case('cartao_credito')
                                                        💳
                                                        @break
                                                    @case('debito')
                                                        💳
                                                        @break
                                                    @default
                                                        📄
                                                @endswitch
                                            </div>
                                            <div class="text-lg font-bold text-gray-900">{{ $dados->quantidade }}</div>
                                            <div class="text-sm text-gray-600 font-medium">
                                                @switch($forma)
                                                    @case('dinheiro')
                                                        Dinheiro
                                                        @break
                                                    @case('pix')
                                                        PIX
                                                        @break
                                                    @case('credito')
                                                    @case('cartao_credito')
                                                        Cartão Crédito
                                                        @break
                                                    @case('debito')
                                                        Cartão Débito
                                                        @break
                                                    @default
                                                        {{ ucfirst($forma) }}
                                                @endswitch
                                            </div>
                                            @if(isset($dados->valor_total))
                                                <div class="text-xs text-green-600 font-medium mt-1">
                                                    R$ {{ number_format($dados->valor_total, 2, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-full text-center py-8">
                                    <iconify-icon icon="lucide:inbox" class="text-4xl text-gray-400 mb-2"></iconify-icon>
                                    <p class="text-gray-500">Nenhum pagamento encontrado no período selecionado</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <span class="text-2xl mr-3">📊</span>
                            Dashboard Analítico
                        </h2>
                        <div class="flex space-x-3">
                            <button onclick="downloadAllCharts()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                <i class="fas fa-download mr-2"></i>
                                Baixar Gráficos
                            </button>
                            <button onclick="refreshDashboard()" class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Atualizar
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2">Análise visual completa do desempenho do seu lava-car</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Gráfico de Faturamento Mensal -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                            <span class="text-xl mr-2">📈</span>
                                            Faturamento Mensal
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Evolução do faturamento nos últimos 6 meses</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Total</div>
                                        @php
                                            $totalFaturamento = array_sum($faturamento_mensal['data']);
                                        @endphp
                                        <div class="text-lg font-bold text-green-600">
                                            R$ {{ number_format($totalFaturamento, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="h-80">
                                    <canvas id="faturamentoChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Atendimentos por Status -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                            <span class="text-xl mr-2">🎯</span>
                                            Distribuição por Status
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Status dos atendimentos no período</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Total</div>
                                        @php
                                            $totalStatus = array_sum($atendimentos_por_status);
                                        @endphp
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($totalStatus) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="h-80">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Atendimentos por Dia da Semana -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                            <span class="text-xl mr-2">📅</span>
                                            Atendimentos por Dia
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Distribuição semanal dos atendimentos</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $melhorDia = collect($atendimentos_semana['data'])->max();
                                            $indiceMelhorDia = array_search($melhorDia, $atendimentos_semana['data']);
                                            $nomeMelhorDia = $atendimentos_semana['labels'][$indiceMelhorDia] ?? '-';
                                        @endphp
                                        <div class="text-sm text-gray-500">Melhor dia</div>
                                        <div class="text-lg font-bold text-purple-600">
                                            {{ $nomeMelhorDia }}
                                        </div>
                                    </div>
                                </div>
                                <div class="h-80">
                                    <canvas id="diaSemanaChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Horários de Pico -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                            <span class="text-xl mr-2">⏰</span>
                                            Horários de Pico
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Concentração de atendimentos por horário</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $picoAtendimentos = collect($horarios_pico['data'])->max();
                                            $indicePico = array_search($picoAtendimentos, $horarios_pico['data']);
                                            $horarioPico = $horarios_pico['labels'][$indicePico] ?? '-';
                                        @endphp
                                        <div class="text-sm text-gray-500">Pico</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ $horarioPico }}
                                        </div>
                                    </div>
                                </div>
                                <div class="h-80">
                                    <canvas id="horarioChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🎯 Cards de Comparação com Período Anterior - Melhorados -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Card de Faturamento -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg border border-green-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-green-500 rounded-full p-3 mr-4">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Evolução do Faturamento</h3>
                                    <p class="text-sm text-gray-600">Comparação com período anterior</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-xs text-gray-500 mb-1">Período Atual</div>
                                <div class="text-xl font-bold text-gray-900">
                                    R$ {{ number_format($comparacao_periodo['faturamento']['atual'] ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-xs text-gray-500 mb-1">Período Anterior</div>
                                <div class="text-xl font-bold text-gray-600">
                                    R$ {{ number_format($comparacao_periodo['faturamento']['anterior'] ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $variacao = $comparacao_periodo['faturamento']['variacao'] ?? 0;
                            $isPositivo = $variacao >= 0;
                        @endphp
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-2xl mr-2">{{ $isPositivo ? '📈' : '📉' }}</div>
                                <div>
                                    <div class="text-lg font-bold {{ $isPositivo ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isPositivo ? '+' : '' }}{{ number_format($variacao, 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $isPositivo ? 'Crescimento' : 'Redução' }}
                                    </div>
                                </div>
                            </div>
                            <div class="w-20 bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $isPositivo ? 'green' : 'red' }}-500 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, abs($variacao * 2)) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Atendimentos -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-full p-3 mr-4">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Volume de Atendimentos</h3>
                                    <p class="text-sm text-gray-600">Comparação com período anterior</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-xs text-gray-500 mb-1">Período Atual</div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($comparacao_periodo['atendimentos']['atual'] ?? 0) }}
                                </div>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-xs text-gray-500 mb-1">Período Anterior</div>
                                <div class="text-xl font-bold text-gray-600">
                                    {{ number_format($comparacao_periodo['atendimentos']['anterior'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $variacao = $comparacao_periodo['atendimentos']['variacao'] ?? 0;
                            $isPositivo = $variacao >= 0;
                        @endphp
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="text-2xl mr-2">{{ $isPositivo ? '📈' : '📉' }}</div>
                                <div>
                                    <div class="text-lg font-bold {{ $isPositivo ? 'text-blue-600' : 'text-red-600' }}">
                                        {{ $isPositivo ? '+' : '' }}{{ number_format($variacao, 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $isPositivo ? 'Crescimento' : 'Redução' }}
                                    </div>
                                </div>
                            </div>
                            <div class="w-20 bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $isPositivo ? 'blue' : 'red' }}-500 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, abs($variacao * 2)) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ⏱️ SEÇÃO DE MÉTRICAS DE TEMPO E PRODUTIVIDADE -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">⏱️</span>
                        Cronometragem e Produtividade
                    </h2>
                    <p class="text-gray-600 mt-1">Análise de tempo de execução e eficiência dos serviços</p>
                </div>

                <!-- Cards de Métricas de Tempo -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $metricas_tempo['tempo_medio_geral'] ?? 0 }}min
                            </div>
                            <div class="text-sm text-blue-700 font-medium">Tempo Médio</div>
                            <div class="text-xs text-gray-600">Geral dos serviços</div>
                        </div>

                        <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $metricas_tempo['tempo_minimo'] ?? 0 }}min
                            </div>
                            <div class="text-sm text-green-700 font-medium">Mais Rápido</div>
                            <div class="text-xs text-gray-600">Menor tempo registrado</div>
                        </div>

                        <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $metricas_tempo['tempo_maximo'] ?? 0 }}min
                            </div>
                            <div class="text-sm text-yellow-700 font-medium">Mais Lento</div>
                            <div class="text-xs text-gray-600">Maior tempo registrado</div>
                        </div>

                        <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $metricas_tempo['eficiencia_media'] ?? 0 }}%
                            </div>
                            <div class="text-sm text-purple-700 font-medium">Eficiência</div>
                            <div class="text-xs text-gray-600">Média geral</div>
                        </div>

                        <div class="text-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-2xl font-bold text-gray-600">
                                {{ $metricas_tempo['total_cronometrados'] ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-700 font-medium">Cronometrados</div>
                            <div class="text-xs text-gray-600">Total de registros</div>
                        </div>
                    </div>

                    <!-- Gráficos de Eficiência -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Ranking de Eficiência por Serviço -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center mb-6">
                                    <span class="text-xl mr-2">🏆</span>
                                    Ranking de Eficiência
                                </h3>
                                
                                @if($eficiencia_servicos && $eficiencia_servicos->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($eficiencia_servicos->take(8) as $index => $servico)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $servico['servico'] }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $servico['tempo_medio'] }}min (est: {{ $servico['tempo_estimado'] }}min)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-lg font-bold {{ $servico['eficiencia'] >= 100 ? 'text-green-600' : ($servico['eficiencia'] >= 80 ? 'text-yellow-600' : 'text-red-600') }}">
                                                        {{ $servico['eficiencia'] }}%
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $servico['total_atendimentos'] }} atendimentos</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-8">
                                        <i class="fas fa-clock text-4xl mb-4"></i>
                                        <p>Nenhum atendimento cronometrado ainda</p>
                                        <p class="text-sm">Os tempos aparecerão quando os serviços forem executados</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Produtividade por Funcionário -->
                        <div class="bg-gray-50 rounded-lg p-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center mb-6">
                                    <span class="text-xl mr-2">👥</span>
                                    Produtividade por Funcionário
                                </h3>
                                
                                @if($produtividade_funcionarios && $produtividade_funcionarios->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($produtividade_funcionarios->take(8) as $index => $funcionario)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $funcionario['funcionario'] }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $funcionario['total_atendimentos'] }} atendimentos • {{ $funcionario['tempo_medio'] }}min médio
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-lg font-bold text-blue-600">
                                                        {{ $funcionario['produtividade_score'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        @if($funcionario['nota_media'])
                                                            ⭐ {{ $funcionario['nota_media'] }}/10
                                                        @else
                                                            Score
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-8">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p>Nenhum funcionário registrado ainda</p>
                                        <p class="text-sm">Os dados aparecerão quando os atendimentos tiverem funcionários atribuídos</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🏆 Card de Metas e KPIs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <span class="text-xl mr-2">🏆</span>
                        Indicadores de Performance (KPIs)
                    </h3>
                    <p class="text-gray-600 mt-1">Principais métricas do seu negócio</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Taxa de Conversão -->
                        <div class="text-center">
                            @php
                                $totalStatus = array_sum($atendimentos_por_status);
                                $taxaConversao = $totalStatus > 0 ? ($atendimentos_por_status['concluido'] / $totalStatus) * 100 : 0;
                            @endphp
                            <div class="relative inline-flex items-center justify-center w-20 h-20 mb-3">
                                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-300" stroke="currentColor" stroke-width="3" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-green-500" stroke="currentColor" stroke-width="3" fill="none"
                                          stroke-dasharray="{{ $taxaConversao }}, 100"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="absolute text-sm font-bold text-gray-700">
                                    {{ number_format($taxaConversao, 1) }}%
                                </span>
                            </div>
                            <div class="text-sm font-medium text-gray-900">Taxa de Conclusão</div>
                            <div class="text-xs text-gray-500">Atendimentos concluídos</div>
                        </div>

                        <!-- Ticket Médio -->
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-dollar-sign text-2xl text-blue-600"></i>
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                R$ {{ number_format($estatisticas['ticket_medio'] ?? 0, 2, ',', '.') }}
                            </div>
                            <div class="text-sm font-medium text-gray-700">Ticket Médio</div>
                            <div class="text-xs text-gray-500">Valor médio por atendimento</div>
                        </div>

                        <!-- Cliente Recorrência -->
                        <div class="text-center">
                            @php
                                $recorrencia = ($estatisticas['total_atendimentos'] > 0 && $estatisticas['clientes_unicos'] > 0) 
                                    ? $estatisticas['total_atendimentos'] / $estatisticas['clientes_unicos'] : 0;
                            @endphp
                            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-sync-alt text-2xl text-purple-600"></i>
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($recorrencia, 1) }}x
                            </div>
                            <div class="text-sm font-medium text-gray-700">Recorrência</div>
                            <div class="text-xs text-gray-500">Média de visitas por cliente</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Atendimentos por Status (Grid Original - Mantido para compatibilidade) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <span class="text-xl mr-2">📈</span>
                        Distribuição por Status
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($atendimentos_por_status as $status => $quantidade)
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($quantidade) }}</div>
                                <div class="text-sm font-medium text-gray-600">
                                    @switch($status)
                                        @case('agendado')
                                            📅 Agendado
                                            @break
                                        @case('em_andamento')
                                            🔄 Em Andamento
                                            @break
                                        @case('concluido')
                                            ✅ Concluído
                                            @break
                                        @case('cancelado')
                                            ❌ Cancelado
                                            @break
                                        @default
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Serviços mais Populares -->
            @if(isset($servicos_populares) && $servicos_populares->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <span class="text-xl mr-2">🏆</span>
                        Serviços Mais Populares
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="flex items-center">
                                            🛠️ Serviço
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="flex items-center">
                                            📊 Quantidade
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span class="flex items-center">
                                            💰 Faturamento
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($servicos_populares as $index => $servico)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-semibold text-blue-600">{{ $index + 1 }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $servico->nome }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ number_format($servico->total_atendimentos) }}</div>
                                            <div class="text-xs text-gray-500">atendimentos</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-green-600">R$ {{ number_format($servico->total_faturamento, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-center">
                    <div class="text-gray-500 mb-2">
                        <span class="text-4xl">📈</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum serviço encontrado</h3>
                    <p class="text-gray-600">Ajuste os filtros para ver os serviços mais populares.</p>
                </div>
            </div>
            @endif

            <!-- Atendimentos Detalhados -->
            @if(isset($atendimentos) && $atendimentos->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <span class="text-xl mr-2">📋</span>
                        Atendimentos Detalhados
                        <span class="ml-2 text-sm text-gray-500">({{ $atendimentos->total() }} registros)</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">📅 Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">👤 Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">🚗 Veículo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">🛠️ Serviço</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">📊 Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">💰 Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">💳 Pagamento</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($atendimentos as $atendimento)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $atendimento->data_agendamento->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $atendimento->data_agendamento->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $atendimento->cliente->nome }}</div>
                                            <div class="text-xs text-gray-500">{{ $atendimento->cliente->telefone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $atendimento->carro->marca }} {{ $atendimento->carro->modelo }}</div>
                                            <div class="text-xs text-gray-500">{{ $atendimento->carro->placa }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $atendimento->servico->nome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($atendimento->status === 'concluido') bg-green-100 text-green-800
                                                @elseif($atendimento->status === 'em_andamento') bg-yellow-100 text-yellow-800
                                                @elseif($atendimento->status === 'agendado') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                @switch($atendimento->status)
                                                    @case('agendado')
                                                        📅 Agendado
                                                        @break
                                                    @case('em_andamento')
                                                        🔄 Em Andamento
                                                        @break
                                                    @case('concluido')
                                                        ✅ Concluído
                                                        @break
                                                    @case('cancelado')
                                                        ❌ Cancelado
                                                        @break
                                                    @default
                                                        {{ ucfirst(str_replace('_', ' ', $atendimento->status)) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                            R$ {{ number_format($atendimento->valor, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($atendimento->forma_pagamento)
                                                <div class="flex items-center">
                                                    @switch($atendimento->forma_pagamento)
                                                        @case('dinheiro')
                                                            <span class="text-green-600">💵</span>
                                                            <span class="ml-1">Dinheiro</span>
                                                            @break
                                                        @case('pix')
                                                            <span class="text-purple-600">📱</span>
                                                            <span class="ml-1">PIX</span>
                                                            @break
                                                        @case('credito')
                                                            <span class="text-blue-600">💳</span>
                                                            <span class="ml-1">Crédito</span>
                                                            @break
                                                        @case('debito')
                                                            <span class="text-orange-600">💳</span>
                                                            <span class="ml-1">Débito</span>
                                                            @break
                                                        @default
                                                            <span class="text-gray-500">📄</span>
                                                            <span class="ml-1">{{ ucfirst($atendimento->forma_pagamento) }}</span>
                                                    @endswitch
                                                </div>
                                                @if($atendimento->data_pagamento)
                                                    <div class="text-xs text-gray-500">
                                                        Pago em {{ $atendimento->data_pagamento->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                            @else
                                                @if($atendimento->status === 'finalizado' || $atendimento->status === 'concluido')
                                                    <span class="text-red-500 text-sm">❌ Não informado</span>
                                                @else
                                                    <span class="text-gray-400 text-sm">⏳ Pendente</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $atendimentos->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="text-gray-500 mb-4">
                        <span class="text-6xl">📊</span>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Nenhum atendimento encontrado</h3>
                    <p class="text-gray-600 mb-4">Não foram encontrados atendimentos com os filtros aplicados.</p>
                    <a href="{{ route('relatorios.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                        Limpar Filtros
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- 📊 JavaScript para Gráficos Interativos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração padrão para todos os gráficos
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#6B7280';
            Chart.defaults.elements.bar.borderRadius = 6;
            Chart.defaults.elements.bar.borderSkipped = false;

            // Cores modernas para os gráficos
            const colors = {
                primary: '#3B82F6',
                success: '#10B981', 
                warning: '#F59E0B',
                danger: '#EF4444',
                info: '#06B6D4',
                purple: '#8B5CF6',
                pink: '#EC4899'
            };

            // 1. Gráfico de Faturamento Mensal (Linha)
            const faturamentoCtx = document.getElementById('faturamentoChart').getContext('2d');
            new Chart(faturamentoCtx, {
                type: 'line',
                data: {
                    labels: @json($faturamento_mensal['labels']),
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: @json($faturamento_mensal['data']),
                        borderColor: colors.primary,
                        backgroundColor: colors.primary + '10',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'R$ ' + new Intl.NumberFormat('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }).format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + new Intl.NumberFormat('pt-BR').format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Gráfico de Status (Donut)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = @json($atendimentos_por_status);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['📅 Agendado', '🔄 Em Andamento', '✅ Concluído', '❌ Cancelado'],
                    datasets: [{
                        data: [
                            statusData.agendado || 0,
                            statusData.em_andamento || 0, 
                            statusData.concluido || 0,
                            statusData.cancelado || 0
                        ],
                        backgroundColor: [
                            colors.info,
                            colors.warning,
                            colors.success,
                            colors.danger
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed * 100) / total).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // 3. Gráfico de Atendimentos por Dia da Semana (Barra)
            const diaSemanaCtx = document.getElementById('diaSemanaChart').getContext('2d');
            new Chart(diaSemanaCtx, {
                type: 'bar',
                data: {
                    labels: @json($atendimentos_semana['labels']),
                    datasets: [{
                        label: 'Atendimentos',
                        data: @json($atendimentos_semana['data']),
                        backgroundColor: [
                            colors.purple + '80',
                            colors.primary + '80',
                            colors.success + '80',
                            colors.warning + '80',
                            colors.info + '80',
                            colors.pink + '80',
                            colors.danger + '80'
                        ],
                        borderColor: [
                            colors.purple,
                            colors.primary,
                            colors.success,
                            colors.warning,
                            colors.info,
                            colors.pink,
                            colors.danger
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 4. Gráfico de Horários de Pico (Área)
            const horarioCtx = document.getElementById('horarioChart').getContext('2d');
            new Chart(horarioCtx, {
                type: 'line',
                data: {
                    labels: @json($horarios_pico['labels']),
                    datasets: [{
                        label: 'Atendimentos',
                        data: @json($horarios_pico['data']),
                        borderColor: colors.success,
                        backgroundColor: colors.success + '20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: colors.success,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                title: function(context) {
                                    return 'Horário: ' + context[0].label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Animação de entrada dos gráficos
            setTimeout(() => {
                document.querySelectorAll('canvas').forEach(canvas => {
                    canvas.style.opacity = '1';
                    canvas.style.transform = 'scale(1)';
                });
            }, 100);
        });

        // Efeito de loading nos gráficos
        document.querySelectorAll('canvas').forEach(canvas => {
            canvas.style.opacity = '0';
            canvas.style.transform = 'scale(0.95)';
            canvas.style.transition = 'all 0.3s ease-in-out';
        });

        // 🔧 Funcionalidades extras para o dashboard
        
        // Função para baixar todos os gráficos
        function downloadAllCharts() {
            const charts = ['faturamentoChart', 'statusChart', 'diaSemanaChart', 'horarioChart'];
            const chartNames = ['Faturamento_Mensal', 'Status_Atendimentos', 'Atendimentos_Semana', 'Horarios_Pico'];
            
            charts.forEach((chartId, index) => {
                setTimeout(() => {
                    const canvas = document.getElementById(chartId);
                    if (canvas) {
                        const link = document.createElement('a');
                        link.download = `WaLavacar_${chartNames[index]}_${new Date().toISOString().split('T')[0]}.png`;
                        link.href = canvas.toDataURL('image/png', 1.0);
                        link.click();
                    }
                }, index * 500); // Delay para não sobrecarregar
            });
            
            // Feedback visual
            Swal.fire({
                icon: 'success',
                title: '📊 Gráficos Exportados!',
                text: 'Os gráficos foram baixados com sucesso.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        
        // Função para atualizar dashboard
        function refreshDashboard() {
            // Mostra loading
            Swal.fire({
                title: '🔄 Atualizando Dashboard...',
                text: 'Buscando dados mais recentes',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simula atualização e recarrega a página
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        // 📱 Responsividade dinâmica para gráficos
        function adjustChartsForMobile() {
            const isMobile = window.innerWidth < 768;
            const charts = document.querySelectorAll('canvas');
            
            charts.forEach(canvas => {
                const chart = Chart.getChart(canvas);
                if (chart) {
                    chart.options.plugins.legend.position = isMobile ? 'bottom' : 'top';
                    chart.options.plugins.legend.labels.padding = isMobile ? 10 : 20;
                    chart.update();
                }
            });
        }
        
        // Event listener para mudanças de tela
        window.addEventListener('resize', adjustChartsForMobile);
        
        // 💡 Insights automáticos baseados nos dados
        function showInsights() {
            const faturamentoData = @json($faturamento_mensal['data']);
            const statusData = @json($atendimentos_por_status);
            const comparacao = @json($comparacao_periodo);
            
            let insights = [];
            
            // Análise de crescimento
            if (comparacao.faturamento.variacao > 10) {
                insights.push(`🚀 Excelente! Seu faturamento cresceu ${comparacao.faturamento.variacao.toFixed(1)}% em relação ao período anterior.`);
            } else if (comparacao.faturamento.variacao < -10) {
                insights.push(`⚠️ Atenção: Houve uma queda de ${Math.abs(comparacao.faturamento.variacao).toFixed(1)}% no faturamento.`);
            }
            
            // Análise de eficiência
            const taxaConclusao = statusData.concluido / (statusData.agendado + statusData.em_andamento + statusData.concluido + statusData.cancelado) * 100;
            if (taxaConclusao > 80) {
                insights.push(`✅ Ótima taxa de conclusão: ${taxaConclusao.toFixed(1)}% dos atendimentos foram concluídos.`);
            }
            
            // Mostra insights se houver
            if (insights.length > 0) {
                setTimeout(() => {
                    Swal.fire({
                        icon: 'info',
                        title: '💡 Insights do seu Negócio',
                        html: insights.join('<br><br>'),
                        confirmButtonText: 'Entendi!',
                        confirmButtonColor: '#3B82F6'
                    });
                }, 3000);
            }
        }
        
        // Inicia insights após carregamento completo
        setTimeout(showInsights, 4000);
    </script>
</x-app-layout>
