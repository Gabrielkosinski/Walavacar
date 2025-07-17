<x-app-layout>
    <div class="min-h-screen bg-gray-100">
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Editar Despesa</h1>
                <a href="{{ route('despesas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Formulário -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('despesas.update', $despesa) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Informações Básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição *
                        </label>
                        <input type="text" name="descricao" id="descricao" 
                               value="{{ old('descricao', $despesa->descricao) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descricao') border-red-300 @enderror"
                               placeholder="Ex: Conta de água, Salário funcionário, Compra de produtos..."
                               required>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor (R$) *
                        </label>
                        <input type="number" name="valor" id="valor" 
                               value="{{ old('valor', $despesa->valor) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('valor') border-red-300 @enderror"
                               step="0.01" min="0"
                               placeholder="0,00"
                               required>
                        @error('valor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="data_despesa" class="block text-sm font-medium text-gray-700 mb-2">
                            Data da Despesa *
                        </label>
                        <input type="date" name="data_despesa" id="data_despesa" 
                               value="{{ old('data_despesa', $despesa->data_despesa->format('Y-m-d')) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('data_despesa') border-red-300 @enderror"
                               required>
                        @error('data_despesa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Despesa *
                        </label>
                        <select name="tipo" id="tipo" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tipo') border-red-300 @enderror"
                                required>
                            <option value="">Selecione...</option>
                            <option value="fixa" {{ old('tipo', $despesa->tipo) == 'fixa' ? 'selected' : '' }}>Fixa</option>
                            <option value="variavel" {{ old('tipo', $despesa->tipo) == 'variavel' ? 'selected' : '' }}>Variável</option>
                        </select>
                        @error('tipo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <strong>Fixa:</strong> Valor fixo mensal (aluguel, salários)<br>
                            <strong>Variável:</strong> Valor variável conforme uso (água, produtos)
                        </p>
                    </div>
                    
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria *
                        </label>
                        <select name="categoria" id="categoria" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('categoria') border-red-300 @enderror"
                                required>
                            <option value="">Selecione...</option>
                            @foreach(\App\Models\Despesa::categorias() as $key => $value)
                                <option value="{{ $key }}" {{ old('categoria', $despesa->categoria) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status *
                        </label>
                        <select name="status" id="status" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror"
                                required>
                            <option value="paga" {{ old('status', $despesa->status) == 'paga' ? 'selected' : '' }}>Paga</option>
                            <option value="pendente" {{ old('status', $despesa->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="vencida" {{ old('status', $despesa->status) == 'vencida' ? 'selected' : '' }}>Vencida</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="forma_pagamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Forma de Pagamento
                        </label>
                        <select name="forma_pagamento" id="forma_pagamento" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('forma_pagamento') border-red-300 @enderror">
                            <option value="">Selecione...</option>
                            @foreach(\App\Models\Despesa::formasPagamento() as $key => $value)
                                <option value="{{ $key }}" {{ old('forma_pagamento', $despesa->forma_pagamento) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('forma_pagamento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Configurações para Despesas Recorrentes -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg" id="recorrencia-config">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="recorrente" id="recorrente" value="1" 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               {{ old('recorrente', $despesa->recorrente) ? 'checked' : '' }}>
                        <label for="recorrente" class="ml-2 text-sm font-medium text-gray-700">
                            Despesa Recorrente (repete todo mês)
                        </label>
                    </div>
                    
                    <div id="dia-vencimento-div" style="display: {{ old('recorrente', $despesa->recorrente) ? 'block' : 'none' }};">
                        <label for="dia_vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                            Dia do Vencimento (1-31)
                        </label>
                        <input type="number" name="dia_vencimento" id="dia_vencimento" 
                               value="{{ old('dia_vencimento', $despesa->dia_vencimento) }}"
                               class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               min="1" max="31"
                               placeholder="Ex: 15">
                        <p class="mt-1 text-xs text-gray-500">Para despesas que vencem no mesmo dia todo mês</p>
                    </div>
                </div>
                
                <!-- Observações e Comprovante -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                            Observações
                        </label>
                        <textarea name="observacoes" id="observacoes" rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('observacoes') border-red-300 @enderror"
                                  placeholder="Informações adicionais sobre a despesa...">{{ old('observacoes', $despesa->observacoes) }}</textarea>
                        @error('observacoes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="comprovante" class="block text-sm font-medium text-gray-700 mb-2">
                            Comprovante (opcional)
                        </label>
                        
                        @if($despesa->comprovante)
                            <div class="mb-3 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-600 mb-2">Comprovante atual:</p>
                                <a href="{{ Storage::url($despesa->comprovante) }}" target="_blank" 
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-file mr-2"></i>
                                    Ver comprovante
                                </a>
                            </div>
                        @endif
                        
                        <input type="file" name="comprovante" id="comprovante" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('comprovante') border-red-300 @enderror"
                               accept="image/*,.pdf">
                        @error('comprovante')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Formatos aceitos: JPG, PNG, PDF (máx. 2MB)
                            @if($despesa->comprovante)
                                <br>Selecione um novo arquivo para substituir o atual
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Botões -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('despesas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Atualizar Despesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recorrenteCheckbox = document.getElementById('recorrente');
    const diaVencimentoDiv = document.getElementById('dia-vencimento-div');
    
    recorrenteCheckbox.addEventListener('change', function() {
        if (this.checked) {
            diaVencimentoDiv.style.display = 'block';
        } else {
            diaVencimentoDiv.style.display = 'none';
            document.getElementById('dia_vencimento').value = '';
        }        });
    });
</script>
    </div>
</x-app-layout>
