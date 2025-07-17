<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Atendimento;
use App\Observers\AtendimentoObserver;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ⏱️ Registrar Observer para cronometragem automática
        Atendimento::observe(AtendimentoObserver::class);
        
        // 🇧🇷 Configurar localização brasileira
        Carbon::setLocale('pt_BR');
        setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
    }
}
