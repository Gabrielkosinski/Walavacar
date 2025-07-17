<x-app-layout>
    <x-slot name="header">
        <div class="wa-page-header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="font-bold text-2xl leading-tight wa-brand-text text-center sm:text-left">
                    <iconify-icon icon="lucide:plus-circle" class="inline mr-2 text-red-500"></iconify-icon>
                    Novo Atendimento
                </h2>
                <a href="{{ route('dashboard') }}" 
                   class="btn-wa-secondary text-sm self-center sm:self-auto">
                    <iconify-icon icon="lucide:home" class="mr-2"></iconify-icon>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="wa-card rounded-2xl">
                <div class="p-4 sm:p-6">
                    <!-- Informações do Cliente - Design WA Premium -->
                    <div class="wa-form-section mb-6">
                        <h3 class="text-center mb-6">
                            <div class="inline-flex items-center bg-gray-700 border-2 border-red-500/30 rounded-xl px-4 sm:px-8 py-3 sm:py-4 shadow-lg">
                                <iconify-icon icon="lucide:user" class="mr-2 sm:mr-3 text-red-500 text-xl sm:text-2xl"></iconify-icon>
                                <span class="text-white font-bold uppercase tracking-wide text-base sm:text-lg">
                                    Dados do Cliente
                                </span>
                            </div>
                        </h3>
                        <form id="clienteForm">
                            <div class="space-y-4">
                                <div class="wa-form-group">
                                    <label class="wa-form-label">Nome do Cliente</label>
                                    <input type="text" id="cliente_nome" 
                                           class="wa-form-input" 
                                           placeholder="Digite o nome completo..." required>
                                </div>
                                <div class="wa-form-group">
                                    <label class="wa-form-label">WhatsApp</label>
                                    <input type="tel" id="cliente_whatsapp" 
                                           class="wa-form-input" 
                                           placeholder="(11) 99999-9999" required>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Dados do Veículo - Design WA Premium -->
                    <div class="wa-form-section mb-6">
                        <h3 class="text-center mb-6">
                            <div class="inline-flex items-center bg-gray-700 border-2 border-red-500/30 rounded-xl px-4 sm:px-8 py-3 sm:py-4 shadow-lg">
                                <iconify-icon icon="lucide:car" class="mr-2 sm:mr-3 text-red-500 text-xl sm:text-2xl"></iconify-icon>
                                <span class="text-white font-bold uppercase tracking-wide text-base sm:text-lg">
                                    Dados do Veículo
                                </span>
                            </div>
                        </h3>
                        <div class="space-y-4">
                            <div class="wa-form-group">
                                <label class="wa-form-label">Placa do Veículo</label>
                                <input type="text" id="carro_placa" 
                                       class="wa-form-input font-mono uppercase" 
                                       placeholder="ABC1234" maxlength="8" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="wa-form-group">
                                    <label class="wa-form-label">Marca</label>
                                    <input type="text" id="carro_marca" 
                                           class="wa-form-input" 
                                           placeholder="Ex: Toyota" required>
                                </div>
                                <div class="wa-form-group">
                                    <label class="wa-form-label">Modelo</label>
                                    <input type="text" id="carro_modelo" 
                                           class="wa-form-input" 
                                           placeholder="Ex: Corolla" required>
                                </div>
                            </div>
                            <div class="wa-form-group">
                                <label class="wa-form-label">Cor</label>
                                <input type="text" id="carro_cor" 
                                       class="wa-form-input" 
                                       placeholder="Ex: Prata" required>
                            </div>
                        </div>
                    </div>

                    <!-- Serviços - Design WA Premium -->
                    <div class="wa-form-section mb-6">
                        <h3 class="text-center mb-6">
                            <div class="inline-flex items-center bg-gray-700 border-2 border-red-500/30 rounded-xl px-4 sm:px-8 py-3 sm:py-4 shadow-lg">
                                <iconify-icon icon="lucide:droplets" class="mr-2 sm:mr-3 text-red-500 text-xl sm:text-2xl"></iconify-icon>
                                <span class="text-white font-bold uppercase tracking-wide text-base sm:text-lg">
                                    Serviços
                                </span>
                            </div>
                        </h3>
                        <div class="space-y-3" id="servicos-list">
                            @foreach($servicos as $servico)
                            <label class="group wa-service-card block p-4 rounded-xl border-2 border-gray-600 cursor-pointer hover:border-red-400 hover:shadow-lg transition-all duration-300 transform hover:scale-102 relative">
                                <!-- Layout Mobile Otimizado -->
                                <div class="flex items-start space-x-4">
                                    <input type="checkbox" name="servicos[]" value="{{ $servico->id }}" 
                                           data-preco="{{ $servico->preco }}" 
                                           class="mt-1 w-6 h-6 text-red-600 bg-gray-800 border-2 border-red-500 rounded focus:ring-2 focus:ring-red-200 transition-all duration-300 flex-shrink-0" 
                                           onchange="calcularTotal()">
                                    <div class="flex-1 min-w-0">
                                        <!-- Nome do Serviço -->
                                        <h4 class="font-bold text-white text-lg sm:text-xl mb-2 leading-tight">
                                            {{ $servico->nome }}
                                        </h4>
                                        
                                        <!-- Descrição -->
                                        @if($servico->descricao)
                                        <p class="text-sm sm:text-base text-gray-400 mb-3 leading-relaxed">
                                            {{ $servico->descricao }}
                                        </p>
                                        @endif
                                        
                                        <!-- Informações do Serviço -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <!-- Preço -->
                                            <div class="font-bold text-green-400 text-xl sm:text-2xl flex items-center">
                                                <iconify-icon icon="lucide:dollar-sign" class="mr-1 text-green-500"></iconify-icon>
                                                R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                            </div>
                                            
                                            <!-- Tempo -->
                                            <div class="text-yellow-400 font-semibold text-sm sm:text-base flex items-center">
                                                <iconify-icon icon="lucide:clock" class="mr-1"></iconify-icon>
                                                {{ $servico->tempo_estimado }}min
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Efeito visual selecionado -->
                                <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-green-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl pointer-events-none"></div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total - Design WA Premium -->
                    <div class="wa-form-section mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-white text-xl flex items-center">
                                <iconify-icon icon="lucide:dollar-sign" class="mr-2 text-green-400 text-2xl"></iconify-icon>
                                <span class="bg-gradient-to-r from-green-600 to-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide">
                                    Valor Total
                                </span>
                            </span>
                            <span id="valor-total" class="font-bold text-3xl text-green-400 animate-pulse">R$ 0,00</span>
                        </div>
                    </div>

                    <!-- Observações - Design WA Premium -->
                    <div class="wa-form-section mb-6">
                        <label class="wa-form-label">Observações (opcional)</label>
                        <textarea id="observacoes" rows="3" 
                                  class="wa-form-input resize-none" 
                                  placeholder="Observações especiais sobre o atendimento..."></textarea>
                    </div>

                    <!-- Botões - Design WA Premium -->
                    <div class="space-y-3">
                        <button type="button" onclick="salvarAtendimento(event)" 
                                class="btn-wa-primary w-full py-4 sm:py-5 px-4 sm:px-6 text-lg sm:text-xl font-bold">
                            <iconify-icon icon="lucide:clock" class="mr-2 sm:mr-3 text-xl sm:text-2xl"></iconify-icon>
                            <span>🚗 ADICIONAR À FILA</span>
                        </button>
                        <a href="{{ route('dashboard') }}" 
                           class="btn-wa-secondary block w-full py-3 sm:py-4 px-4 sm:px-6 text-center text-base sm:text-lg">
                            <iconify-icon icon="lucide:x" class="mr-2 sm:mr-3 text-lg sm:text-xl"></iconify-icon>
                            <span>❌ CANCELAR</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Consultar placa com feedback visual aprimorado
        async function consultarPlaca() {
            const placa = document.getElementById('carro_placa').value.trim();
            if (!placa) {
                showErrorToast('❌ Digite uma placa primeiro!');
                return;
            }

            const button = event.target;
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<div class="flex items-center"><span class="animate-spin mr-2">⏳</span>Consultando...</div>';

            try {
                const response = await fetch(`/api/carros/consultar-placa?placa=${placa}`);
                const data = await response.json();

                if (data.success) {
                    document.getElementById('carro_marca').value = data.data.marca || '';
                    document.getElementById('carro_modelo').value = data.data.modelo || '';
                    document.getElementById('carro_cor').value = data.data.cor || '';
                    
                    showSuccessToast('✅ Dados encontrados e preenchidos automaticamente!');
                    
                    // Adicionar efeito visual aos campos preenchidos
                    ['carro_marca', 'carro_modelo', 'carro_cor'].forEach(id => {
                        const field = document.getElementById(id);
                        if (field.value) {
                            field.style.borderColor = '#10b981';
                            field.style.backgroundColor = '#f0fdf4';
                            setTimeout(() => {
                                field.style.borderColor = '';
                                field.style.backgroundColor = '';
                            }, 2000);
                        }
                    });
                } else {
                    showErrorToast('⚠️ Não foi possível consultar a placa. Preencha manualmente.');
                }
            } catch (error) {
                console.error('Erro:', error);
                showErrorToast('❌ Erro ao consultar placa. Preencha manualmente.');
            } finally {
                button.disabled = false;
                button.innerHTML = originalContent;
            }
        }

        // Calcular total com animação
        function calcularTotal() {
            const checkboxes = document.querySelectorAll('input[name="servicos[]"]:checked');
            let total = 0;
            
            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.dataset.preco);
            });

            const valorElement = document.getElementById('valor-total');
            
            // Adicionar efeito de pulso ao atualizar o valor
            valorElement.style.transform = 'scale(1.1)';
            valorElement.style.color = '#f59e0b';
            
            setTimeout(() => {
                valorElement.textContent = 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                valorElement.style.transform = 'scale(1)';
                valorElement.style.color = '';
            }, 150);
        }

        // Salvar atendimento com feedback visual aprimorado
        async function salvarAtendimento(event) {
            const button = event ? event.target : document.querySelector('button[onclick="salvarAtendimento()"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<div class="flex items-center justify-center"><span class="animate-spin text-2xl">⏳</span><span class="ml-2">Salvando...</span></div>';
            }

            try {
                // Validar dados obrigatórios
                const clienteNome = document.getElementById('cliente_nome').value.trim();
                const clienteWhatsapp = document.getElementById('cliente_whatsapp').value.trim();
                const carroPlaca = document.getElementById('carro_placa').value.trim();
                const carroMarca = document.getElementById('carro_marca').value.trim();
                const carroModelo = document.getElementById('carro_modelo').value.trim();
                const carroCor = document.getElementById('carro_cor').value.trim();

                if (!clienteNome || !clienteWhatsapp || !carroPlaca || !carroMarca || !carroModelo || !carroCor) {
                    showErrorToast('❌ Preencha todos os campos obrigatórios!');
                    return;
                }

                // Verificar se pelo menos um serviço foi selecionado
                const servicosSelecionados = document.querySelectorAll('input[name="servicos[]"]:checked');
                if (servicosSelecionados.length === 0) {
                    showErrorToast('❌ Selecione pelo menos um serviço!');
                    return;
                }

                // Preparar dados
                const formData = {
                    cliente: {
                        nome: clienteNome,
                        whatsapp: clienteWhatsapp,
                        telefone: null
                    },
                    carro: {
                        placa: carroPlaca,
                        marca: carroMarca,
                        modelo: carroModelo,
                        cor: carroCor,
                        ano: null
                    },
                    servicos: Array.from(servicosSelecionados).map(checkbox => parseInt(checkbox.value)),
                    valor_total: calcularTotalNumerico(),
                    observacoes: document.getElementById('observacoes').value.trim() || null
                };

                console.log('📤 Enviando dados:', formData);

                // Enviar via AJAX
                const response = await fetch('/atendimentos/json', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                console.log('📥 Status da resposta:', response.status);
                const result = await response.json();
                console.log('📥 Resultado:', result);
                
                if (response.ok && result.success) {
                    showSuccessToast('✅ Cliente adicionado à fila de espera com sucesso!');
                    console.log('🎉 Atendimento criado com sucesso!', result.data);
                    // Redirecionar para o dashboard após um delay
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 1500);
                } else {
                    console.error('❌ Erro do servidor:', result);
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        showErrorToast('❌ Erros de validação:\n' + errorMessages);
                    } else {
                        showErrorToast('❌ ' + (result.message || 'Erro desconhecido'));
                    }
                }

            } catch (error) {
                console.error('💥 Erro de rede:', error);
                showErrorToast('❌ Erro de conexão. Verifique sua internet e tente novamente.');
            } finally {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<div class="relative z-10 flex items-center justify-center"><span class="mr-3 text-2xl group-hover:animate-bounce">⏳</span><span>ADICIONAR À FILA</span></div>';
                }
            }
        }
        
        // Funções de toast para feedback visual
        function showSuccessToast(message) {
            const toast = createToast(message, 'success');
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
        
        function showErrorToast(message) {
            const toast = createToast(message, 'error');
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
        
        function createToast(message, type) {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-all duration-300 font-semibold max-w-sm`;
            toast.innerHTML = message;
            
            // Animar entrada
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Animar saída
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
            }, type === 'success' ? 2500 : 3500);
            
            return toast;
        }

        // Função auxiliar para calcular total numérico
        function calcularTotalNumerico() {
            const checkboxes = document.querySelectorAll('input[name="servicos[]"]:checked');
            let total = 0;
            
            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.dataset.preco);
            });

            return total;
        }

        // Máscaras
        document.getElementById('cliente_whatsapp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                if (value.length < 14) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                }
            }
            e.target.value = value;
        });

        document.getElementById('carro_placa').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
        
        // Animações de entrada quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            // Animar entrada das seções com delay escalonado
            const sections = document.querySelectorAll('.mb-6');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    section.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 150);
            });
            
            // Adicionar efeitos de foco aprimorados aos inputs
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 0 0 4px rgba(59, 130, 246, 0.3)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</x-app-layout>
