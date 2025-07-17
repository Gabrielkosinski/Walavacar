<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Veículo') }}
            </h2>
            <a href="{{ route('carros.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('carros.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Selecione um cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }} - {{ $cliente->telefone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="placa" class="block text-sm font-medium text-gray-700">Placa do Veículo</label>
                            <div class="relative">
                                <input type="text" name="placa" id="placa" value="{{ old('placa') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-24" 
                                       placeholder="ABC-1234 ou ABC1234" required maxlength="8">
                                <button type="button" id="btnConsultarPlaca" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium"
                                        style="background-color: #2563eb !important; color: white !important; margin-top: 2px;">
                                    🔍 Consultar
                                </button>
                            </div>
                            <div id="placaStatus" class="mt-2 text-sm"></div>
                            <div class="mt-1 text-xs text-gray-500">
                                💡 <strong>Sistema inteligente:</strong> Digite qualquer placa e o sistema tentará inferir marca, modelo, cor e ano baseado em padrões brasileiros.
                            </div>
                            @error('placa')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                            <input type="text" name="marca" id="marca" value="{{ old('marca') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('marca')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                            <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('modelo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ano" class="block text-sm font-medium text-gray-700">Ano</label>
                            <input type="number" name="ano" id="ano" value="{{ old('ano') }}" 
                                   min="1900" max="{{ date('Y') + 1 }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('ano')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="cor" class="block text-sm font-medium text-gray-700">Cor</label>
                            <input type="text" name="cor" id="cor" value="{{ old('cor') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('cor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea name="observacoes" id="observacoes" rows="3" 
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}
                                       class="wa-checkbox">
                                <label for="ativo" class="wa-checkbox-label">
                                    <span class="checkmark-emoji">🚗</span>
                                    Veículo ativo
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('carros.index') }}" 
                               style="background-color: #6b7280 !important; color: white !important; padding: 8px 16px !important; border-radius: 6px !important; text-decoration: none !important; display: inline-block !important; font-weight: 600 !important;"
                               onmouseover="this.style.backgroundColor='#4b5563'" 
                               onmouseout="this.style.backgroundColor='#6b7280'">
                                ← Cancelar
                            </a>
                            <button type="submit" 
                                    style="background-color: #2563eb !important; color: white !important; padding: 8px 24px !important; border-radius: 6px !important; border: none !important; font-weight: 600 !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important;"
                                    onmouseover="this.style.backgroundColor='#1d4ed8'" 
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                🚗 Cadastrar Veículo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const placaInput = document.getElementById('placa');
            const btnConsultar = document.getElementById('btnConsultarPlaca');
            const placaStatus = document.getElementById('placaStatus');
            const marcaInput = document.getElementById('marca');
            const modeloInput = document.getElementById('modelo');
            const corInput = document.getElementById('cor');
            const anoInput = document.getElementById('ano');

            // Função para formatar a placa
            function formatarPlaca(valor) {
                // Remove caracteres não alfanuméricos
                valor = valor.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
                
                // Limita a 7 caracteres (3 letras + 4 números)
                if (valor.length > 7) {
                    valor = valor.substring(0, 7);
                }
                
                // Adiciona o hífen se necessário (formato ABC1234 -> ABC-1234)
                if (valor.length > 3) {
                    valor = valor.substring(0, 3) + '-' + valor.substring(3);
                }
                
                return valor;
            }

            // Formatação automática da placa enquanto digita
            placaInput.addEventListener('input', function() {
                const cursorPos = this.selectionStart;
                const valorOriginal = this.value;
                const valorFormatado = formatarPlaca(valorOriginal);
                
                this.value = valorFormatado;
                
                // Ajusta a posição do cursor
                if (valorFormatado.length !== valorOriginal.length) {
                    this.setSelectionRange(cursorPos, cursorPos);
                }
            });

            // Consulta automática quando sair do campo (blur) ou pressionar Enter
            placaInput.addEventListener('blur', function() {
                if (this.value.length >= 7) {
                    consultarPlaca();
                }
            });

            placaInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    consultarPlaca();
                }
            });

            // Botão de consulta manual
            btnConsultar.addEventListener('click', function() {
                consultarPlaca();
            });

            function consultarPlaca() {
                const placa = placaInput.value.trim();
                
                if (!placa || placa.length < 7) {
                    mostrarStatus('Por favor, insira uma placa válida.', 'error');
                    return;
                }

                mostrarStatus('🔍 Consultando dados do veículo...', 'loading');
                btnConsultar.disabled = true;
                btnConsultar.textContent = '⏳ Consultando...';

                // Faz a requisição para nossa API
                fetch(`{{ route('carros.consultar-placa') }}?placa=${encodeURIComponent(placa)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Preenche os campos automaticamente
                            marcaInput.value = data.data.marca;
                            modeloInput.value = data.data.modelo;
                            corInput.value = data.data.cor;
                            anoInput.value = data.data.ano;
                            
                            // Mensagem baseada na fonte dos dados
                            let mensagem = `✅ Dados encontrados: ${data.data.marca} ${data.data.modelo} ${data.data.cor} (${data.data.ano})`;
                            if (data.fonte) {
                                mensagem += ` - ${data.fonte}`;
                            }
                            
                            mostrarStatus(mensagem, 'success');
                            
                            // Destaca os campos preenchidos
                            [marcaInput, modeloInput, corInput, anoInput].forEach(input => {
                                input.style.backgroundColor = '#f0fdf4';
                                input.style.borderColor = '#22c55e';
                                setTimeout(() => {
                                    input.style.backgroundColor = '';
                                    input.style.borderColor = '';
                                }, 3000);
                            });
                        } else {
                            mostrarStatus(`⚠️ ${data.message || 'Não foi possível encontrar dados para esta placa.'}`, 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        mostrarStatus('❌ Erro ao consultar dados do veículo. Tente novamente.', 'error');
                    })
                    .finally(() => {
                        btnConsultar.disabled = false;
                        btnConsultar.innerHTML = '🔍 Consultar';
                    });
            }

            function mostrarStatus(mensagem, tipo) {
                placaStatus.textContent = mensagem;
                placaStatus.className = 'mt-2 text-sm ';
                
                switch(tipo) {
                    case 'success':
                        placaStatus.className += 'text-green-600 font-medium';
                        break;
                    case 'error':
                        placaStatus.className += 'text-red-600 font-medium';
                        break;
                    case 'warning':
                        placaStatus.className += 'text-yellow-600 font-medium';
                        break;
                    case 'loading':
                        placaStatus.className += 'text-blue-600 font-medium';
                        break;
                    default:
                        placaStatus.className += 'text-gray-600';
                }
                
                // Remove a mensagem após 5 segundos (exceto loading)
                if (tipo !== 'loading') {
                    setTimeout(() => {
                        if (placaStatus.textContent === mensagem) {
                            placaStatus.textContent = '';
                        }
                    }, 5000);
                }
            }

            // Dica inicial
            setTimeout(() => {
                mostrarStatus('� Digite qualquer placa brasileira (ex: ABC1234) e o sistema tentará inferir os dados do veículo automaticamente.', 'info');
            }, 1000);
        });
    </script>
</x-app-layout>
