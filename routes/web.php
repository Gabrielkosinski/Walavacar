<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\DespesaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// 🔄 Rota para renovar token CSRF
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->middleware('web');

// ⚠️ ROTA REMOVIDA POR SEGURANÇA - Era uma vulnerabilidade crítica
// Esta rota permitia login automático sem credenciais - DESABILITADA
/*
Route::get('/auto-login', function() {
    $user = App\Models\User::first();
    if ($user) {
        Auth::login($user);
        session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Login automático realizado!');
    }
    return redirect('/')->with('error', 'Nenhum usuário encontrado');
});
*/

// 🧪 ROTA DE TESTE DESABILITADA POR SEGURANÇA
// Esta rota também permitia login automático - DESABILITADA
/*
Route::get('/dashboard-test', function() {
    $user = App\Models\User::first();
    Auth::login($user);
    
    // Dados simples para teste
    $estatisticas = [
        'total_atendimentos' => App\Models\Atendimento::count(),
        'atendimentos_hoje' => 5,
        'faturamento_mes' => 1500.00,
        'clientes_ativos' => App\Models\Cliente::count()
    ];
    
    $emAndamento = App\Models\Atendimento::with(['cliente', 'carro', 'servico'])
        ->where('status', 'em_andamento')
        ->take(5)
        ->get();
        
    $prontos = App\Models\Atendimento::with(['cliente', 'carro', 'servico'])
        ->where('status', 'pronto')
        ->take(5)
        ->get();
        
    $aguardandoPagamento = collect(); // Vazio por enquanto
    $filaEspera = collect(); // Vazio por enquanto
    $proximoDaFila = null;
    $proximosAtendimentos = collect();
    $notificacoes = [];
    $atendimentos = collect();
    
    return view('dashboard', compact(
        'estatisticas', 'atendimentos', 'emAndamento', 'prontos', 
        'aguardandoPagamento', 'proximosAtendimentos', 'notificacoes', 
        'filaEspera', 'proximoDaFila'
    ));
})->name('dashboard.test');
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Atendimentos
    Route::resource('atendimentos', AtendimentoController::class);
    Route::post('atendimentos/json', [AtendimentoController::class, 'storeJson'])->name('atendimentos.store-json');
    // Clientes
    Route::resource('clientes', ClienteController::class);
    // Carros
    Route::resource('carros', CarroController::class);
    // Serviços
    Route::resource('servicos', ServicoController::class);
    Route::patch('servicos/{servico}/toggle-status', [ServicoController::class, 'toggleStatus'])->name('servicos.toggle-status');
    // Relatórios
    Route::resource('relatorios', RelatorioController::class);
    Route::get('/relatorios/exportar/pdf', [RelatorioController::class, 'exportarPdf'])->name('relatorios.exportar-pdf');
    // Despesas
    Route::resource('despesas', DespesaController::class);
    Route::get('/despesas/relatorio/index', [DespesaController::class, 'relatorio'])->name('despesas.relatorio');
    Route::patch('/despesas/{despesa}/marcar-paga', [DespesaController::class, 'marcarComoPaga'])->name('despesas.marcar-paga');
    // Consulta de placa
    Route::get('api/carros/consultar-placa', [CarroController::class, 'consultarPlaca'])->name('carros.consultar-placa');
    // Fila
    Route::post('/atendimentos/chamar-proximo', [AtendimentoController::class, 'chamarProximo'])->name('atendimentos.chamar-proximo');
    Route::get('/atendimentos/fila', [AtendimentoController::class, 'obterFila'])->name('atendimentos.fila');
    // Finalização e pagamento
    Route::patch('/atendimentos/{atendimento}/finalizar', [AtendimentoController::class, 'finalizar'])->name('atendimentos.finalizar');
    Route::patch('/atendimentos/{atendimento}/cancelar', [AtendimentoController::class, 'cancelar'])->name('atendimentos.cancelar');
    Route::patch('/atendimentos/{atendimento}/pagar', [AtendimentoController::class, 'registrarPagamento'])->name('atendimentos.pagar');
    Route::get('/atendimentos/{atendimento}/comprovante', [AtendimentoController::class, 'gerarComprovante'])->name('atendimentos.comprovante');
    // WhatsApp templates
    Route::get('/whatsapp/templates', function() {
        return view('whatsapp.templates');
    })->middleware(['auth', 'verified'])->name('whatsapp.templates');
});

require __DIR__.'/auth.php';

// ROTA TEMPORÁRIA PARA DEBUG DO LOG
Route::get('/debug-log', function () {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        return response()->file($logPath);
    }
    return 'Log não encontrado!';
});
