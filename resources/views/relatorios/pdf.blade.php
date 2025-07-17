<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Atendimentos - WaLavacar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 11px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        
        .status-agendado { color: #f59e0b; }
        .status-em_andamento { color: #3b82f6; }
        .status-concluido { color: #10b981; }
        .status-cancelado { color: #ef4444; }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .status-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 WaLavacar - Sistema de Gestão</h1>
        <p><strong>Relatório de Atendimentos</strong></p>
        <p>Gerado em: {{ date('d/m/Y H:i:s') }}</p>
        @if($request->filled('data_inicio') || $request->filled('data_fim'))
            <p>Período: 
                @if($request->filled('data_inicio'))
                    {{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }}
                @else
                    Início
                @endif
                até
                @if($request->filled('data_fim'))
                    {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }}
                @else
                    Fim
                @endif
            </p>
        @endif
        @if($request->filled('status'))
            <p>Status: {{ ucfirst(str_replace('_', ' ', $request->status)) }}</p>
        @endif
    </div>

    <!-- Estatísticas Gerais -->
    <div class="section">
        <div class="section-title">📊 Estatísticas Gerais</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($estatisticas['total_atendimentos'] ?? 0) }}</div>
                <div class="stat-label">Total de Atendimentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">R$ {{ number_format($estatisticas['faturamento_total'] ?? 0, 2, ',', '.') }}</div>
                <div class="stat-label">Faturamento Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">R$ {{ number_format($estatisticas['ticket_medio'] ?? 0, 2, ',', '.') }}</div>
                <div class="stat-label">Ticket Médio</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($estatisticas['clientes_unicos'] ?? 0) }}</div>
                <div class="stat-label">Clientes Únicos</div>
            </div>
        </div>
    </div>

    <!-- Distribuição por Status -->
    <div class="section">
        <div class="section-title">📈 Distribuição por Status</div>
        <div class="status-grid">
            @foreach($atendimentos_por_status as $status => $quantidade)
                <div class="status-item">
                    <div class="stat-value status-{{ $status }}">{{ number_format($quantidade) }}</div>
                    <div class="stat-label">
                        @switch($status)
                            @case('agendado')
                                Agendado
                                @break
                            @case('em_andamento')
                                Em Andamento
                                @break
                            @case('concluido')
                                Concluído
                                @break
                            @case('cancelado')
                                Cancelado
                                @break
                            @default
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                        @endswitch
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Serviços Mais Populares -->
    @if(isset($servicos_populares) && $servicos_populares->count() > 0)
    <div class="section">
        <div class="section-title">🏆 Serviços Mais Populares</div>
        <table>
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Serviço</th>
                    <th>Total de Atendimentos</th>
                    <th>Faturamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicos_populares as $index => $servico)
                    <tr>
                        <td>{{ $index + 1 }}º</td>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ number_format($servico->total_atendimentos) }}</td>
                        <td>R$ {{ number_format($servico->total_faturamento, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($atendimentos->count() > 0)
    <!-- Lista de Atendimentos -->
    <div class="section page-break">
        <div class="section-title">📋 Lista de Atendimentos</div>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Veículo</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($atendimentos as $atendimento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($atendimento->data_agendamento)->format('d/m/Y H:i') }}</td>
                        <td>{{ $atendimento->cliente->nome ?? 'N/A' }}</td>
                        <td>
                            @if($atendimento->carro)
                                {{ $atendimento->carro->marca }} {{ $atendimento->carro->modelo }}<br>
                                <small>{{ $atendimento->carro->placa }}</small>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $atendimento->servico->nome ?? 'N/A' }}</td>
                        <td class="status-{{ $atendimento->status }}">
                            @switch($atendimento->status)
                                @case('agendado')
                                    Agendado
                                    @break
                                @case('em_andamento')
                                    Em Andamento
                                    @break
                                @case('concluido')
                                    Concluído
                                    @break
                                @case('cancelado')
                                    Cancelado
                                    @break
                                @default
                                    {{ ucfirst(str_replace('_', ' ', $atendimento->status)) }}
                            @endswitch
                        </td>
                        <td>R$ {{ number_format($atendimento->valor, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>WaLavacar - Sistema de Gestão de Lava Car Multi-filiais</p>
        <p>Relatório gerado automaticamente pelo sistema</p>
    </div>
</body>
</html>
