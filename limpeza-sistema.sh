#!/bin/bash

echo "🧹 LIMPEZA DO SISTEMA WALAVACAR"
echo "================================"

# 1. Remover views desnecessárias
echo "📁 Removendo views desnecessárias..."
rm -f resources/views/dashboard-backup.blade.php
rm -f resources/views/dashboard-new.blade.php
rm -f resources/views/dashboard-ultra.blade.php
rm -f resources/views/dashboard-wa-clean.blade.php
rm -f resources/views/dashboard-wa.blade.php
rm -f resources/views/showcase-buttons.blade.php
rm -f resources/views/wa-showcase.blade.php

# 2. Remover arquivos de teste do public
echo "🧪 Removendo arquivos de teste..."
rm -f public/teste-*.html
rm -f public/debug-multiplos-servicos.html
rm -f public/performance-test.html
rm -f public/mobile-access.html
rm -f public/modo-facil.html
rm -f public/qr-mobile.html
rm -f public/qrcode.html

# 3. Remover documentação desnecessária
echo "📋 Removendo documentação desnecessária..."
rm -f ACESSO_MOBILE.md
rm -f ANALISE_SISTEMA.md
rm -f BOTOES_PREMIUM_README.md
rm -f ETAPAS_DO_SISTEMA.md
rm -f GUIA_CSRF_MOBILE.md
rm -f GUIA_TESTE_MOBILE.md
rm -f MANUAL_USO_SISTEMA.md
rm -f MOBILE_ATENDIMENTO_CONCLUIDO.md
rm -f RELATORIO_FINAL_SISTEMA.md
rm -f SISTEMA_COMPLETO.md
rm -f VISUAL_PRESERVADO.md
rm -f WA_THEME_IMPLEMENTATION.md
rm -f Untitled-1.css

# 4. Remover pastas desnecessárias
echo "📂 Removendo pastas de backup..."
rm -rf backup/
rm -rf lavacar-system/

# 5. Remover arquivos temporários
echo "🗂️ Removendo arquivos temporários..."
rm -f cliente-
rm -f data_inicio-
rm -f "nome}n\""
rm -f cookies.txt
rm -f status
rm -f fresh
rm -f comandos-uteis.sh
rm -f env-apis-veiculos.example

# 6. Limpar cache e otimizar
echo "⚡ Otimizando sistema..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "✅ LIMPEZA CONCLUÍDA!"
echo "🚀 Sistema otimizado para produção!"
echo ""
echo "📊 Espaço liberado:"
du -sh . 2>/dev/null || echo "Sistema limpo com sucesso!"
