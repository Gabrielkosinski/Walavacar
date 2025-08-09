# 🚀 Variáveis do Railway - WaLavacar

## 📋 Variáveis Obrigatórias no Railway:

### 🔐 Aplicação
```
APP_NAME=WaLavacar
APP_ENV=production
APP_KEY=base64:sua_chave_aqui
APP_DEBUG=false
APP_URL=https://seu-projeto.railway.app
```

### 🌍 Localização
```
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR
```

### 🗄️ Banco de Dados (PostgreSQL Railway)
```
DB_CONNECTION=pgsql
DB_HOST=postgres.railway.internal
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=${{PostgreSQL.DATABASE_URL}}
```

### 📝 Logs e Cache
```
LOG_CHANNEL=single
LOG_LEVEL=error
SESSION_DRIVER=database
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 📱 WhatsApp Business
```
WHATSAPP_BUSINESS_NUMBER=5541996875650
```

### 🔧 Railway Específicas
```
PORT=80
RAILWAY_ENVIRONMENT=production
```

## ⚡ Como configurar no Railway:

1. **Via Railway CLI:**
```bash
railway variables set APP_NAME=WaLavacar
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_LOCALE=pt_BR
railway variables set APP_FALLBACK_LOCALE=pt_BR
railway variables set LOG_LEVEL=error
railway variables set SESSION_DRIVER=database
railway variables set WHATSAPP_BUSINESS_NUMBER=5541996875650
```

2. **APP_KEY (gerar automaticamente):**
```bash
railway run php artisan key:generate --show
# Copie a chave e configure:
railway variables set APP_KEY=base64:sua_chave_copiada
```

3. **Banco PostgreSQL:**
- O Railway conecta automaticamente via `${{PostgreSQL.DATABASE_URL}}`
- Configure: `DB_PASSWORD=${{PostgreSQL.DATABASE_URL}}`

## 🛡️ Configurações de Segurança:
- ✅ `APP_DEBUG=false` (não vazar informações)
- ✅ `LOG_LEVEL=error` (logs mínimos)
- ✅ `SESSION_DRIVER=database` (sessões persistentes)
- ✅ Chave APP_KEY forte gerada automaticamente

## 🔍 Verificação:
Após configurar, teste com:
```bash
railway run php artisan config:show
railway run php artisan migrate:status
```
