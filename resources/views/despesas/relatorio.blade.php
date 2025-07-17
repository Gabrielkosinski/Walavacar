<x-app-layout>
    <style>
        /* PWA/Mobile optimizations for charts */
        @media (max-width: 768px) {
            .chart-container {
                position: relative;
                height: 250px !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow: hidden;
            }
            
            .chart-container canvas {
                max-width: 100% !important;
                height: auto !important;
            }
            
            /* Prevent chart overflow on small screens */
            .grid > div {
                min-width: 0;
                overflow: hidden;
            }
        }
        
        /* Ensure charts are responsive */
        .chart-container {
            position: relative;
            overflow: hidden;
        }
        
        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>

    <div class="min-h-screen bg-gray-100">
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6 lg:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Relatório de Despesas</h1>
            <a href="{{ route('despesas.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Filtros de Período -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('despesas.relatorio') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio', $dataInicio) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                    <input type="date" name="data_fim" value="{{ request('data_fim', $dataFim) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-blue-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-blue-600">Despesas Fixas</h3>
                    <p class="text-2xl font-bold text-blue-800">R$ {{ number_format($totalFixas, 2, ',', '.') }}</p>
                    <p class="text-sm text-blue-600">Período selecionado</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-green-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-green-600">Despesas Variáveis</h3>
                    <p class="text-2xl font-bold text-green-800">R$ {{ number_format($totalVariaveis, 2, ',', '.') }}</p>
                    <p class="text-sm text-green-600">Período selecionado</p>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calculator text-purple-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-purple-600">Total Geral</h3>
                    <p class="text-2xl font-bold text-purple-800">R$ {{ number_format($totalFixas + $totalVariaveis, 2, ',', '.') }}</p>
                    <p class="text-sm text-purple-600">Período selecionado</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Gráfico: Despesas Fixas vs Variáveis -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-3 lg:mb-4">Despesas Fixas vs Variáveis</h3>
            <div class="chart-container relative h-64 lg:h-72">
                <canvas id="graficoTipos"></canvas>
            </div>
        </div>
        
        <!-- Gráfico: Despesas por Categoria -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-3 lg:mb-4">Despesas por Categoria</h3>
            <div class="chart-container relative h-64 lg:h-72">
                <canvas id="graficoCategorias"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico: Evolução Mensal -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-3 lg:mb-4">Evolução Mensal das Despesas</h3>
        <div class="chart-container relative h-64 lg:h-80">
            <canvas id="graficoEvolucao"></canvas>
        </div>
    </div>

    <!-- Tabela Detalhada por Categoria -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Detalhamento por Categoria</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% do Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalGeral = $totalFixas + $totalVariaveis;
                    @endphp
                    @foreach($despesasPorCategoria as $categoria)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \App\Models\Despesa::categorias()[$categoria->categoria] ?? $categoria->categoria }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($categoria->total, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $totalGeral > 0 ? number_format(($categoria->total / $totalGeral) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                    @if($despesasPorCategoria->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Nenhuma despesa encontrada no período selecionado.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados para os gráficos
    const totalFixas = {{ $totalFixas }};
    const totalVariaveis = {{ $totalVariaveis }};
    
    const categorias = @json($despesasPorCategoria->pluck('categoria'));
    const valoresCategorias = @json($despesasPorCategoria->pluck('total'));
    
    const evolucaoMeses = @json($evolucaoMensal->map(function($item) { return $item->mes . '/' . $item->ano; })->reverse());
    const evolucaoValores = @json($evolucaoMensal->pluck('total')->reverse());

    // Configuração comum dos gráficos
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#374151';

    // Gráfico: Despesas Fixas vs Variáveis
    const ctxTipos = document.getElementById('graficoTipos').getContext('2d');
    new Chart(ctxTipos, {
        type: 'doughnut',
        data: {
            labels: ['Fixas', 'Variáveis'],
            datasets: [{
                data: [totalFixas, totalVariaveis],
                backgroundColor: [
                    '#3B82F6', // Azul para fixas
                    '#10B981'  // Verde para variáveis
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 768 ? 11 : 12
                        },
                        boxWidth: window.innerWidth < 768 ? 12 : 15
                    }
                },
                tooltip: {
                    enabled: true,
                    titleFont: {
                        size: window.innerWidth < 768 ? 12 : 14
                    },
                    bodyFont: {
                        size: window.innerWidth < 768 ? 11 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': R$ ' + context.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Gráfico: Despesas por Categoria
    const ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');
    new Chart(ctxCategorias, {
        type: 'pie',
        data: {
            labels: categorias.map(cat => {
                const cats = @json(\App\Models\Despesa::categorias());
                return cats[cat] || cat;
            }),
            datasets: [{
                data: valoresCategorias,
                backgroundColor: [
                    '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6',
                    '#F97316', '#06B6D4', '#84CC16', '#EC4899', '#6B7280'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 768 ? 11 : 12
                        },
                        boxWidth: window.innerWidth < 768 ? 12 : 15
                    }
                },
                tooltip: {
                    enabled: true,
                    titleFont: {
                        size: window.innerWidth < 768 ? 12 : 14
                    },
                    bodyFont: {
                        size: window.innerWidth < 768 ? 11 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            return context.label + ': R$ ' + context.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });

    // Gráfico: Evolução Mensal
    const ctxEvolucao = document.getElementById('graficoEvolucao').getContext('2d');
    new Chart(ctxEvolucao, {
        type: 'line',
        data: {
            labels: evolucaoMeses,
            datasets: [{
                label: 'Total de Despesas',
                data: evolucaoValores,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 5,
                    right: 5
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    titleFont: {
                        size: window.innerWidth < 768 ? 12 : 14
                    },
                    bodyFont: {
                        size: window.innerWidth < 768 ? 11 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        },
                        maxRotation: window.innerWidth < 768 ? 45 : 0
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        },
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                }
            }
        }
    });
});

// PWA/Mobile: Redimensionar gráficos em mudança de orientação
window.addEventListener('resize', function() {
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.resize();
    });
});

// PWA/Mobile: Redimensionar gráficos em mudança de orientação
window.addEventListener('orientationchange', function() {
    setTimeout(function() {
        Chart.helpers.each(Chart.instances, function(instance) {
            instance.resize();
        });
    }, 500);
});
</script>
    </div>
</x-app-layout>
