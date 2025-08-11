#!/bin/bash

echo "🔍 DEBUG LOGIN ADMIN - WaLavacar"
echo "================================"
echo ""

echo "📋 COMANDOS PARA EXECUTAR NO RAILWAY:"
echo ""

echo "1️⃣ VERIFICAR SE ADMIN EXISTE:"
echo "railway run php artisan tinker --execute=\"echo 'Admin existe: ' . (\\DB::table('users')->where('email', 'admin@admin.com')->exists() ? 'SIM' : 'NÃO');\""
echo ""

echo "2️⃣ CONTAR TOTAL DE USUÁRIOS:"
echo "railway run php artisan tinker --execute=\"echo 'Total usuários: ' . \\DB::table('users')->count();\""
echo ""

echo "3️⃣ LISTAR TODOS OS USUÁRIOS:"
echo "railway run php artisan tinker --execute=\"\\DB::table('users')->select('id', 'name', 'email', 'tipo')->get();\""
echo ""

echo "4️⃣ FORÇAR CRIAÇÃO DO ADMIN:"
echo "railway run php artisan admin:create --force"
echo ""

echo "5️⃣ CRIAR ADMIN DIRETAMENTE NO TINKER:"
echo "railway run php artisan tinker"
echo "# Dentro do tinker, execute:"
echo "\\DB::table('users')->insertOrIgnore(['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => \\Hash::make('admin123'), 'tipo' => 'admin', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()]);"
echo ""

echo "6️⃣ TESTAR AUTENTICAÇÃO:"
echo "railway run php artisan tinker --execute=\"\\Auth::attempt(['email' => 'admin@admin.com', 'password' => 'admin123']) ? 'LOGIN OK' : 'LOGIN FALHOU';\""
echo ""

echo "7️⃣ EXECUTAR MIGRAÇÕES:"
echo "railway run php artisan migrate --force"
echo ""

echo "8️⃣ VERIFICAR ESTRUTURA DA TABELA USERS:"
echo "railway run php artisan tinker --execute=\"\\Schema::getColumnListing('users');\""
echo ""

echo "🚨 POSSÍVEIS CAUSAS DO PROBLEMA:"
echo "• Admin não foi criado nas migrações"
echo "• Campo 'tipo' não existe na tabela"
echo "• Hash da senha incorreto"
echo "• Guard de autenticação errado"
echo "• Tabela users não existe"
echo ""

echo "🎯 SOLUÇÃO MAIS PROVÁVEL:"
echo "Execute os comandos 1, 2, 3 para diagnosticar"
echo "Depois execute 4 ou 5 para criar o admin"
echo "Finalmente teste com comando 6"
