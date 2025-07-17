<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Carro;
use App\Models\Despesa;

class LimparDadosTeste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:limpar-dados-teste {--force : Força a limpeza sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todos os dados de teste (clientes, carros, atendimentos, despesas) mantendo configurações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 WaLavacar - Limpeza de Dados de Teste');
        $this->info('=====================================');

        // Contar registros antes da limpeza
        $atendimentos = Atendimento::count();
        $clientes = Cliente::count();
        $carros = Carro::count();
        $despesas = Despesa::count();

        $this->info("📊 Dados atuais:");
        $this->line("   - Atendimentos: {$atendimentos}");
        $this->line("   - Clientes: {$clientes}");
        $this->line("   - Carros: {$carros}");
        $this->line("   - Despesas: {$despesas}");
        $this->newLine();

        if ($atendimentos + $clientes + $carros + $despesas === 0) {
            $this->info('✨ Sistema já está limpo! Nenhum dado de teste encontrado.');
            return 0;
        }

        // Confirmação se não usar --force
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Deseja continuar com a limpeza? Esta ação é IRREVERSÍVEL!')) {
                $this->info('❌ Operação cancelada pelo usuário.');
                return 1;
            }
        }

        $this->info('🗑️ Iniciando limpeza...');

        try {
            // Barra de progresso
            $bar = $this->output->createProgressBar(4);
            $bar->start();

            // 1. Deletar atendimentos
            Atendimento::truncate();
            $bar->advance();
            $this->line("\n   ✅ Atendimentos deletados: {$atendimentos}");

            // 2. Deletar carros
            Carro::truncate();
            $bar->advance();
            $this->line("   ✅ Carros deletados: {$carros}");

            // 3. Deletar clientes
            Cliente::truncate();
            $bar->advance();
            $this->line("   ✅ Clientes deletados: {$clientes}");

            // 4. Deletar despesas
            Despesa::truncate();
            $bar->advance();
            $this->line("   ✅ Despesas deletadas: {$despesas}");

            $bar->finish();
            $this->newLine(2);

            $this->info('🎉 Limpeza concluída com sucesso!');
            $this->info('✅ Sistema pronto para produção');
            
            // Verificação final
            $this->newLine();
            $this->info('📊 Verificação final:');
            $this->line('   - Atendimentos: ' . Atendimento::count());
            $this->line('   - Clientes: ' . Cliente::count());
            $this->line('   - Carros: ' . Carro::count());
            $this->line('   - Despesas: ' . Despesa::count());

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Erro durante a limpeza: ' . $e->getMessage());
            return 1;
        }
    }
}
