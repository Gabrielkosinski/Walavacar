# 🚨 TROUBLESHOOTING RAILWAY - Error 500

## 🔍 Problema Identificado
Erro 500 no Railway: `web-production-9622.up.railway.app`

## 🛠️ Soluções Implementadas

### 1. ✅ Script de Inicialização Melhorado
- Criado `start-railway.sh` com verificações robustas
- Verificação automática de APP_KEY
- Criação automática do banco SQLite
- Configuração de permissões

### 2. ✅ Arquivos Atualizados
- `Procfile` - Usar script robusto
- `railway.json` - Comando de start otimizado
- `.env.railway.example` - Variáveis corretas

### 3. 🎯 Variáveis Obrigatórias no Railway

**CRÍTICAS (devem estar configuradas):**
```env
APP_KEY=base64:g/jzaJfAz+lSLsb9qZWbN0MmfmyxlJgoawMCrQt9xJQ=
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
```

**COMPLETAS (recomendadas):**
```env
APP_NAME=WaLavacar
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:g/jzaJfAz+lSLsb9qZWbN0MmfmyxlJgoawMCrQt9xJQ=
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
DB_CONNECTION=sqlite
LOG_CHANNEL=errorlog
LOG_LEVEL=error
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
WHATSAPP_BUSINESS_NUMBER=5541996875650
BCRYPT_ROUNDS=12
```

## 🔄 Próximos Passos

### 1. Commit e Push das Correções
```bash
git add .
git commit -m "🐛 Fix: Railway 500 error - Robusto start script"
git push origin main
```

### 2. Verificar Variáveis no Railway
1. Acessar painel Railway
2. Ir em "Variables"
3. Adicionar/verificar todas as variáveis acima
4. **IMPORTANTE**: Verificar se `APP_KEY` está definida

### 3. Redeploy Automático
- Railway fará redeploy automático após push
- Aguardar 2-3 minutos
- Testar novamente

### 4. Verificação de Logs
No painel Railway:
1. Aba "Deployments"
2. Ver logs do build
3. Ver logs de runtime

## 🧪 Teste Local do Script
```bash
# Simular ambiente Railway
export PORT=3000
export APP_ENV=production
./start-railway.sh
```

## 📊 Possíveis Causas do Error 500

### ❌ Mais Prováveis:
1. **APP_KEY ausente** - Causa criptografia falhar
2. **Database SQLite não criado** - Erro nas migrations
3. **Permissões de arquivo** - Storage/cache não acessível
4. **Variáveis de ambiente** - Configuração incompleta

### ✅ Menos Prováveis:
- Código PHP (funciona local)
- Dependências (Composer OK)
- Assets frontend (Build OK)

## 🎯 Solução Garantida

1. **Fazer push das correções**
2. **Verificar APP_KEY no Railway**
3. **Aguardar redeploy automático**
4. **Testar em 2-3 minutos**

---

**💡 Se o erro persistir:** Verificar logs específicos no painel Railway!
