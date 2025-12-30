#!/bin/bash
## ==============================================================================
## EASYTI CLOUD - Script de Atualização
## Baseado no Coolify original, modificado para EasyTI Cloud
## Repositório: https://github.com/vico068/easyti-cloud-deploy
## ==============================================================================

# ================================
# CONFIGURAÇÕES EASYTI CLOUD
# ================================
EASYTI_REPO="https://github.com/vico068/easyti-cloud-deploy"
PRODUCT_NAME="EasyTI Cloud"
DATA_DIR="/data/easyti"

LATEST_IMAGE=${1:-latest}
LATEST_HELPER_VERSION=${2:-latest}
REGISTRY_URL=${3:-ghcr.io}
SKIP_BACKUP=${4:-false}
ENV_FILE="${DATA_DIR}/source/.env"
STATUS_FILE="${DATA_DIR}/source/.upgrade-status"

DATE=$(date +%Y-%m-%d-%H-%M-%S)
LOGFILE="${DATA_DIR}/source/upgrade-${DATE}.log"

# Helper function para log com timestamp
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >>"$LOGFILE"
}

# Helper function para headers de seção
log_section() {
    echo "" >>"$LOGFILE"
    echo "============================================================" >>"$LOGFILE"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >>"$LOGFILE"
    echo "============================================================" >>"$LOGFILE"
}

# Helper function para status da atualização
write_status() {
    local step="$1"
    local message="$2"
    echo "${step}|${message}|$(date -Iseconds)" > "$STATUS_FILE"
}

echo ""
echo -e "\033[0;36m"
echo "=========================================="
echo "   ${PRODUCT_NAME} - Atualização - ${DATE}"
echo "=========================================="
echo -e "\033[0m"
echo ""

# Inicializar arquivo de log
echo "============================================================" >>"$LOGFILE"
echo "${PRODUCT_NAME} Upgrade Log" >>"$LOGFILE"
echo "Iniciado: $(date '+%Y-%m-%d %H:%M:%S')" >>"$LOGFILE"
echo "Versão Alvo: ${LATEST_IMAGE}" >>"$LOGFILE"
echo "Helper Version: ${LATEST_HELPER_VERSION}" >>"$LOGFILE"
echo "Registry URL: ${REGISTRY_URL}" >>"$LOGFILE"
echo "============================================================" >>"$LOGFILE"

log_section "Passo 1/6: Atualizando repositório"
write_status "1" "Atualizando repositório Git"
echo "1/6 Atualizando repositório Git..."

cd ${DATA_DIR}/source

# Backup antes de atualizar
if [ "$SKIP_BACKUP" != "true" ]; then
    if [ -f "$ENV_FILE" ]; then
        echo "     Criando backup do arquivo .env..."
        log "Criando backup do .env para .env-$DATE"
        cp "$ENV_FILE" "$ENV_FILE-$DATE"
        log "Backup criado: ${ENV_FILE}-${DATE}"
    fi
fi

# Atualizar repositório
log "Atualizando repositório de ${EASYTI_REPO}"
git fetch origin >>"$LOGFILE" 2>&1
git reset --hard origin/main >>"$LOGFILE" 2>&1
log "Repositório atualizado com sucesso"
echo "     Concluído."

log_section "Passo 2/6: Atualizando configuração"
write_status "2" "Atualizando configuração"
echo ""
echo "2/6 Atualizando configuração de ambiente..."

# Merge .env.production se existir
if [ -f "${DATA_DIR}/source/.env.production" ]; then
    log "Mesclando valores do .env.production"
    awk -F '=' '!seen[$1]++' "$ENV_FILE" ${DATA_DIR}/source/.env.production > "$ENV_FILE.tmp" && mv "$ENV_FILE.tmp" "$ENV_FILE"
    log "Arquivo de ambiente mesclado com sucesso"
fi

update_env_var() {
    local key="$1"
    local value="$2"

    if grep -q "^${key}=$" "$ENV_FILE"; then
        sed -i "s|^${key}=$|${key}=${value}|" "$ENV_FILE"
        log "Atualizado ${key} (estava vazio)"
    elif ! grep -q "^${key}=" "$ENV_FILE"; then
        printf '%s=%s\n' "$key" "$value" >>"$ENV_FILE"
        log "Adicionado ${key} (estava faltando)"
    fi
}

log "Verificando variáveis de ambiente..."
update_env_var "PUSHER_APP_ID" "$(openssl rand -hex 32)"
update_env_var "PUSHER_APP_KEY" "$(openssl rand -hex 32)"
update_env_var "PUSHER_APP_SECRET" "$(openssl rand -hex 32)"
log "Verificação de variáveis concluída"
echo "     Concluído."

# Criar rede easyti se não existir
log "Verificando rede Docker 'easyti'..."
if ! docker network inspect easyti >/dev/null 2>&1; then
    log "Rede 'easyti' não existe, criando..."
    if ! docker network create --attachable --ipv6 easyti 2>/dev/null; then
        log "Falha ao criar rede com IPv6, tentando sem IPv6..."
        docker network create --attachable easyti 2>/dev/null
        log "Rede 'easyti' criada sem IPv6"
    else
        log "Rede 'easyti' criada com suporte IPv6"
    fi
else
    log "Rede 'easyti' já existe"
fi

log_section "Passo 3/6: Atualizando configuração de paths"
write_status "3" "Atualizando configuração"
echo ""
echo "3/6 Atualizando configuração de paths..."

cd ${DATA_DIR}/source

# EASYTI: Ajustar paths nos arquivos docker-compose.prod.yml para /data/easyti
sed -i 's|/data/coolify|/data/easyti|g' ${DATA_DIR}/source/docker-compose.prod.yml 2>/dev/null || true

log "Configuração atualizada"
echo "     Concluído."

log_section "Passo 4/6: Parando containers"
write_status "4" "Parando containers"
echo ""
echo "4/6 Parando containers existentes..."

docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml down --remove-orphans >>"$LOGFILE" 2>&1 || true

log "Containers parados"
echo "     Concluído."

log_section "Passo 5/6: Reconstruindo e iniciando containers"
write_status "5" "Buildando e iniciando containers"
echo ""
echo "5/6 Reconstruindo imagens EasyTI Cloud..."
echo "     Isso pode demorar alguns minutos."

# Rebuild das imagens com código atualizado
echo " - Buildando imagem do painel..."
docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml build --no-cache coolify >>"$LOGFILE" 2>&1

echo " - Buildando imagem do Realtime..."
docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml build --no-cache soketi >>"$LOGFILE" 2>&1

echo " - Iniciando containers..."
docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml up -d --remove-orphans >>"$LOGFILE" 2>&1

log "Containers iniciados"
echo "     Concluído."

log_section "Passo 6/6: Verificando atualização"
write_status "6" "Verificando atualização"
echo ""
echo "6/6 Verificando atualização..."

# Aguardar containers
sleep 5

# Verificar se container principal está rodando
if docker ps --format '{{.Names}}' | grep -q "easyti\|coolify"; then
    echo " - Atualização concluída com sucesso!"
    log "Atualização concluída com sucesso"
else
    echo " - AVISO: Verifique os logs dos containers."
    log "AVISO: Container principal não encontrado"
fi

# Limpar arquivo de status após delay
sleep 10
rm -f "$STATUS_FILE"
log "Arquivo de status limpo"

echo ""
echo -e "\033[0;32m"
echo "=========================================="
echo "   ${PRODUCT_NAME} ATUALIZADO!"
echo "=========================================="
echo -e "\033[0m"
echo ""
echo "Log de atualização: ${LOGFILE}"
echo ""

log_section "Atualização Completa"
log "${PRODUCT_NAME} atualizado com sucesso"
log "Versão: ${LATEST_IMAGE}"

