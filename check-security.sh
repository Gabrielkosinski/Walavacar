#!/bin/bash

# 🔒 SCRIPT DE VERIFICAÇÃO DE SEGURANÇA - WALAVACAR
# Este script verifica configurações de segurança básicas

echo "🔒 VERIFICAÇÃO DE SEGURANÇA DO WALAVACAR"
echo "========================================"
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para verificar configuração
check_config() {
    local config_name=$1
    local expected_value=$2
    local current_value=$(grep "^$config_name=" .env 2>/dev/null | cut -d '=' -f2)
    
    if [ "$current_value" = "$expected_value" ]; then
        echo -e "✅ ${GREEN}$config_name está configurado corretamente${NC}"
        return 0
    else
        echo -e "❌ ${RED}$config_name não está seguro. Atual: '$current_value', Esperado: '$expected_value'${NC}"
        return 1
    fi
}

# Verificar se existe arquivo .env
if [ ! -f ".env" ]; then
    echo -e "❌ ${RED}Arquivo .env não encontrado!${NC}"
    exit 1
fi

echo "📋 VERIFICANDO CONFIGURAÇÕES BÁSICAS..."
echo ""

# Verificar configurações críticas
issues=0

# Debug mode
if check_config "APP_DEBUG" "false"; then
    :
else
    ((issues++))
fi

# Environment
if check_config "APP_ENV" "production"; then
    :
else
    ((issues++))
fi

# Session encryption
if check_config "SESSION_ENCRYPT" "true"; then
    :
else
    ((issues++))
fi

echo ""
echo "🔍 VERIFICANDO ARQUIVOS DE RISCO..."

# Verificar rota de auto-login ativa (não dentro de comentários)
# Usar awk para detectar se a rota está dentro de um bloco /* */
if awk '
    /\/\*/ { in_comment = 1 }
    /\*\// { in_comment = 0; next }
    !in_comment && /Route::get\(.*auto-login/ { found = 1; exit }
    END { exit !found }
' routes/web.php; then
    echo -e "❌ ${RED}Rota de auto-login ATIVA - VULNERABILIDADE CRÍTICA!${NC}"
    ((issues++))
else
    echo -e "✅ ${GREEN}Rota de auto-login removida/desabilitada${NC}"
fi

# Verificar se existe arquivo de backup do .env
if [ -f ".env.backup" ] || [ -f ".env.bak" ]; then
    echo -e "⚠️ ${YELLOW}Arquivo de backup do .env encontrado - verificar se não está exposto${NC}"
fi

echo ""
echo "🛡️ VERIFICANDO PERMISSÕES..."

# Verificar permissões do .env
env_perms=$(stat -f "%A" .env 2>/dev/null || stat -c "%a" .env 2>/dev/null)
if [ "$env_perms" = "600" ] || [ "$env_perms" = "644" ]; then
    echo -e "✅ ${GREEN}Permissões do .env estão adequadas ($env_perms)${NC}"
else
    echo -e "⚠️ ${YELLOW}Permissões do .env podem estar muito abertas ($env_perms)${NC}"
fi

# Verificar se storage é gravável
if [ -w "storage/logs" ]; then
    echo -e "✅ ${GREEN}Diretório de logs é gravável${NC}"
else
    echo -e "❌ ${RED}Diretório de logs não é gravável${NC}"
    ((issues++))
fi

echo ""
echo "📦 VERIFICANDO DEPENDÊNCIAS..."

# Verificar se composer.lock existe
if [ -f "composer.lock" ]; then
    echo -e "✅ ${GREEN}composer.lock presente - dependências travadas${NC}"
else
    echo -e "⚠️ ${YELLOW}composer.lock não encontrado - versões de dependências podem variar${NC}"
fi

# Verificar vulnerabilidades conhecidas (se composer audit está disponível)
if command -v composer &> /dev/null; then
    echo "🔍 Verificando vulnerabilidades conhecidas..."
    if composer audit --no-dev 2>/dev/null; then
        echo -e "✅ ${GREEN}Nenhuma vulnerabilidade conhecida encontrada${NC}"
    else
        echo -e "⚠️ ${YELLOW}Execute 'composer audit' para verificar vulnerabilidades${NC}"
    fi
fi

echo ""
echo "📊 RESUMO DA VERIFICAÇÃO"
echo "========================"

if [ $issues -eq 0 ]; then
    echo -e "🎉 ${GREEN}Parabéns! Nenhum problema crítico de segurança encontrado.${NC}"
    echo -e "🔒 ${GREEN}Sistema parece estar configurado de forma segura.${NC}"
else
    echo -e "⚠️ ${RED}$issues problema(s) de segurança encontrado(s).${NC}"
    echo -e "🚨 ${RED}AÇÃO NECESSÁRIA: Corrija os problemas listados acima.${NC}"
fi

echo ""
echo "💡 DICAS ADICIONAIS:"
echo "- Configure HTTPS no servidor"
echo "- Implemente backup automático"
echo "- Configure monitoramento de logs"
echo "- Atualize dependências regularmente"
echo "- Use autenticação de dois fatores"

echo ""
echo "📝 Para mais detalhes, consulte: RELATORIO_SEGURANCA.md"

exit $issues
