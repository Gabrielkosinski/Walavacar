<x-app-layout>
    <x-slot name="                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-green-500">
                            <pre class="text-gray-200 whitespace-pre-wrap font-mono text-sm">{{ App\Services\WhatsAppService::gerarExemploMensagem('carro_pronto') }}</pre><div class="flex justify-between items-center backdrop-blur-md bg-black/20 p-4 rounded-lg border border-white/10">
            <div class="flex items-center space-x-3">
                <iconify-icon icon="lucide:message-circle" class="text-2xl text-green-500"></iconify-icon>
                <h2 class="font-bold text-xl text-white leading-tight">
                    Templates Auto Premier - WhatsApp
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden shadow-2xl">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">📱 Visualizar Templates de WhatsApp</h3>
                        <p class="text-gray-400">Visualize como ficam as mensagens que serão enviadas aos clientes.</p>
                    </div>

                    <!-- Template: Carro Pronto -->
                    <div class="mb-8 p-6 bg-gray-700 rounded-xl border border-green-500/30">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                <iconify-icon icon="lucide:check-circle" class="text-2xl text-white"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">✅ Veículo Pronto</h4>
                                <p class="text-gray-400">Mensagem quando o veículo está pronto para retirada</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-green-500">
                            <pre class="text-gray-200 whitespace-pre-wrap font-mono text-sm">🚗 *Auto Premier Detalhes Automotivos WA* — Veículo Pronto para Retirada!

Olá Jose, tudo certo?

Seu carro já está pronto para ser retirado:

� Toyota Corolla
🔹 Placa: ABC123
🔹 Serviço: Serviço padrão
� Valor: R$ 15,00

📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210
� Atendimento: Segunda a Sábado, das 8h às 18h

Aguardamos você!
Obrigado por escolher a Auto Premier.</pre>
                        </div>
                        
                        <div class="mt-4">
                            @php
                                $dadosExemplo = [
                                    'cliente' => 'Jose',
                                    'marca' => 'Toyota',
                                    'modelo' => 'Corolla',
                                    'placa' => 'ABC123',
                                    'servico' => 'Serviço padrão',
                                    'valor' => '15,00',
                                    'filial' => 'Matriz'
                                ];
                                $urlExemplo = \App\Services\WhatsAppService::gerarUrlWhatsApp('5541996875650', 'carro_pronto', $dadosExemplo);
                            @endphp
                            <a href="{{ $urlExemplo }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <iconify-icon icon="lucide:external-link" class="mr-2"></iconify-icon>
                                Testar no WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Template: Cliente Chamado -->
                    <div class="mb-8 p-6 bg-gray-700 rounded-xl border border-red-500/30">
                        <div class="flex items-center mb-4">
                            <div class="bg-red-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                <iconify-icon icon="lucide:target" class="text-2xl text-white"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">🎯 Cliente Chamado</h4>
                                <p class="text-gray-400">Mensagem quando é a vez do cliente</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-red-500">
                            <pre class="text-gray-200 whitespace-pre-wrap font-mono text-sm">{{ App\Services\WhatsAppService::gerarExemploMensagem('cliente_chamado') }}</pre>
                        </div>
                        
                        <div class="mt-4">
                            @php
                                $dadosExemplo2 = [
                                    'cliente' => 'Maria Santos',
                                    'marca' => 'Toyota',
                                    'modelo' => 'Corolla',
                                    'placa' => 'XYZ-5678',
                                    'servico' => 'Lavagem Simples',
                                    'valor' => '15,00',
                                    'tempo_estimado' => '30 minutos'
                                ];
                                $urlExemplo2 = \App\Services\WhatsAppService::gerarUrlWhatsApp('5541996875650', 'cliente_chamado', $dadosExemplo2);
                            @endphp
                                    'tempo_estimado' => '30-45 minutos',
                                    'filial' => 'Matriz'
                                ];
                                $urlExemplo2 = \App\Services\WhatsAppService::gerarUrlWhatsApp('5541996875650', 'cliente_chamado', $dadosExemplo2);
                            @endphp
                            <a href="{{ $urlExemplo2 }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <iconify-icon icon="lucide:external-link" class="mr-2"></iconify-icon>
                                Testar no WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Template: Agendamento Confirmado -->
                    <div class="mb-8 p-6 bg-gray-700 rounded-xl border border-blue-500/30">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                <iconify-icon icon="lucide:calendar-check" class="text-2xl text-white"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">📅 Agendamento Confirmado</h4>
                                <p class="text-gray-400">Mensagem de confirmação de agendamento</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500">
                            <pre class="text-gray-200 whitespace-pre-wrap font-mono text-sm">{{ App\Services\WhatsAppService::gerarExemploMensagem('agendamento_confirmado') }}</pre>
                        </div>
                        
                        <div class="mt-4">
                            @php
                                $dadosExemplo3 = [
                                    'cliente' => 'Carlos Oliveira',
                                    'marca' => 'Ford',
                                    'modelo' => 'Ka',
                                    'placa' => 'LMN-9012',
                                    'servico' => 'Lavagem Completa + Enceramento',
                                    'data' => '15/07/2025',
                                    'horario' => '14:00',
                                    'valor' => '40,00',
                                    'filial' => 'Matriz'
                                ];
                                $urlExemplo3 = \App\Services\WhatsAppService::gerarUrlWhatsApp('5541996875650', 'agendamento_confirmado', $dadosExemplo3);
                            @endphp
                            <a href="{{ $urlExemplo3 }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <iconify-icon icon="lucide:external-link" class="mr-2"></iconify-icon>
                                Testar no WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Informações de Configuração -->
                    <div class="p-6 bg-gray-700 rounded-xl border border-yellow-500/30">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-600 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                <iconify-icon icon="lucide:settings" class="text-2xl text-white"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">⚙️ Configurações Recomendadas</h4>
                                <p class="text-gray-400">Sugestões para melhorar a experiência do WhatsApp</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <h5 class="font-bold text-white mb-3">📞 Informações de Contato</h5>
                                <ul class="space-y-2 text-gray-300">
                                    <li>• Empresa: Auto Premier Detalhes Automotivos WA</li>
                                    <li>• Telefone: (41) 99687-5650</li>
                                    <li>• WhatsApp Business: 5541996875650</li>
                                    <li>• Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR</li>
                                    <li>• Horário: Segunda a Sábado, das 8h às 18h</li>
                                </ul>
                            </div>
                            
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <h5 class="font-bold text-white mb-3">🚀 Próximas Melhorias</h5>
                                <ul class="space-y-2 text-gray-300">
                                    <li>• Lembretes automáticos</li>
                                    <li>• Mensagens de promoção</li>
                                    <li>• Status de atendimento</li>
                                    <li>• Pesquisa de satisfação</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
