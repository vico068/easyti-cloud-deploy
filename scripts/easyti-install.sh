#!/bin/bash
## ==============================================================================
## EASYTI CLOUD - Script de Instalação
## Baseado no Coolify original, modificado para EasyTI Cloud
## Repositório: https://github.com/vico068/easyti-cloud-deploy
## ==============================================================================

## Variáveis de ambiente que podem ser configuradas:
## ROOT_USERNAME - Nome de usuário root predefinido
## ROOT_USER_EMAIL - Email do usuário root
## ROOT_USER_PASSWORD - Senha do usuário root
## DOCKER_ADDRESS_POOL_BASE - Base do pool de endereços Docker (padrão: 10.0.0.0/8)
## DOCKER_ADDRESS_POOL_SIZE - Tamanho do pool Docker (padrão: 24)
## DOCKER_POOL_FORCE_OVERRIDE - Forçar override do pool Docker (padrão: false)
## AUTOUPDATE - Definir como "false" para desativar atualizações automáticas
## REGISTRY_URL - URL customizada do registry Docker (padrão: ghcr.io)

set -e
set -o pipefail

# ================================
# CONFIGURAÇÕES EASYTI CLOUD
# ================================
EASYTI_REPO="https://github.com/vico068/easyti-cloud-deploy"
EASYTI_RAW_URL="https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main"
PRODUCT_NAME="EasyTI Cloud"
DATA_DIR="/data/easyti"

# CDN/Download URL - usa o raw do GitHub
CDN="${EASYTI_RAW_URL}/scripts"

DATE=$(date +"%Y%m%d-%H%M%S")

OS_TYPE=$(grep -w "ID" /etc/os-release | cut -d "=" -f 2 | tr -d '"')
ENV_FILE="${DATA_DIR}/source/.env"
DOCKER_VERSION="27.0"
CURRENT_USER=$USER

# EASYTI: Verificar se está rodando como root
# Se Docker Desktop estiver em uso, pode rodar sem root
RUNNING_AS_ROOT=false
if [ $EUID = 0 ]; then
    RUNNING_AS_ROOT=true
fi

# Verificar se Docker está acessível (para suportar Docker Desktop)
if docker info >/dev/null 2>&1; then
    echo "Docker detectado e acessível."
    DOCKER_ACCESSIBLE=true
else
    DOCKER_ACCESSIBLE=false
    if [ "$RUNNING_AS_ROOT" = false ]; then
        echo ""
        echo "============================================================"
        echo " AVISO: Docker não está acessível como usuário normal."
        echo " Tentando executar com sudo..."
        echo "============================================================"
        echo ""
        # Re-executar script como root
        exec sudo bash "$0" "$@"
    fi
fi

echo ""
echo -e "\033[0;36m"
echo "  ███████╗ █████╗ ███████╗██╗   ██╗████████╗██╗"
echo "  ██╔════╝██╔══██╗██╔════╝╚██╗ ██╔╝╚══██╔══╝██║"
echo "  █████╗  ███████║███████╗ ╚████╔╝    ██║   ██║"
echo "  ██╔══╝  ██╔══██║╚════██║  ╚██╔╝     ██║   ██║"
echo "  ███████╗██║  ██║███████║   ██║      ██║   ██║"
echo "  ╚══════╝╚═╝  ╚═╝╚══════╝   ╚═╝      ╚═╝   ╚═╝"
echo "                   ██████╗██╗      ██████╗ ██╗   ██╗██████╗"
echo "                  ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗"
echo "                  ██║     ██║     ██║   ██║██║   ██║██║  ██║"
echo "                  ██║     ██║     ██║   ██║██║   ██║██║  ██║"
echo "                  ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝"
echo "                   ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝"
echo -e "\033[0m"
echo ""
echo "=========================================="
echo "   ${PRODUCT_NAME} - Instalação - ${DATE}"
echo "=========================================="
echo ""
echo "Bem-vindo ao instalador do ${PRODUCT_NAME}!"
echo "Este script instalará tudo para você. Relaxe e aguarde."
echo "Código fonte: ${EASYTI_REPO}"
echo ""

# Credenciais do usuário root predefinidas
ROOT_USERNAME=${ROOT_USERNAME:-}
ROOT_USER_EMAIL=${ROOT_USER_EMAIL:-}
ROOT_USER_PASSWORD=${ROOT_USER_PASSWORD:-}

# Registry URL
if [ -n "${REGISTRY_URL+x}" ]; then
    echo "Usando Registry URL da variável de ambiente: $REGISTRY_URL"
else
    if [ -f "$ENV_FILE" ] && grep -q "^REGISTRY_URL=" "$ENV_FILE"; then
        REGISTRY_URL=$(grep "^REGISTRY_URL=" "$ENV_FILE" | cut -d '=' -f2)
        echo "Usando Registry URL do .env: $REGISTRY_URL"
    else
        REGISTRY_URL="ghcr.io"
        echo "Usando Registry URL padrão: $REGISTRY_URL"
    fi
fi

# Configuração do pool de endereços Docker
DOCKER_ADDRESS_POOL_BASE_DEFAULT="10.0.0.0/8"
DOCKER_ADDRESS_POOL_SIZE_DEFAULT=24

DOCKER_POOL_BASE_PROVIDED=false
DOCKER_POOL_SIZE_PROVIDED=false
DOCKER_POOL_FORCE_OVERRIDE=${DOCKER_POOL_FORCE_OVERRIDE:-false}

if [ -n "${DOCKER_ADDRESS_POOL_BASE+x}" ]; then
    DOCKER_POOL_BASE_PROVIDED=true
fi

if [ -n "${DOCKER_ADDRESS_POOL_SIZE+x}" ]; then
    DOCKER_POOL_SIZE_PROVIDED=true
fi

restart_docker_service() {
    if command -v systemctl >/dev/null 2>&1; then
        systemctl restart docker
        if [ $? -eq 0 ]; then
            echo " - Daemon Docker reiniciado com sucesso"
        else
            echo " - Falha ao reiniciar daemon Docker"
            return 1
        fi
    elif command -v service >/dev/null 2>&1; then
        service docker restart
        if [ $? -eq 0 ]; then
            echo " - Daemon Docker reiniciado com sucesso"
        else
            echo " - Falha ao reiniciar daemon Docker"
            return 1
        fi
    else
        echo " - Erro: Nenhum sistema de gerenciamento de serviços encontrado"
        return 1
    fi
}

# Pool de endereços Docker
DOCKER_ADDRESS_POOL_BASE=${DOCKER_ADDRESS_POOL_BASE:-"$DOCKER_ADDRESS_POOL_BASE_DEFAULT"}
DOCKER_ADDRESS_POOL_SIZE=${DOCKER_ADDRESS_POOL_SIZE:-$DOCKER_ADDRESS_POOL_SIZE_DEFAULT}

# Carregar configuração do pool do .env se existir
if [ -f "${DATA_DIR}/source/.env" ] && [ "$DOCKER_POOL_BASE_PROVIDED" = false ] && [ "$DOCKER_POOL_SIZE_PROVIDED" = false ]; then
    ENV_DOCKER_ADDRESS_POOL_BASE=$(grep -E "^DOCKER_ADDRESS_POOL_BASE=" "${DATA_DIR}/source/.env" | cut -d '=' -f2 || true)
    ENV_DOCKER_ADDRESS_POOL_SIZE=$(grep -E "^DOCKER_ADDRESS_POOL_SIZE=" "${DATA_DIR}/source/.env" | cut -d '=' -f2 || true)

    if [ -n "$ENV_DOCKER_ADDRESS_POOL_BASE" ]; then
        DOCKER_ADDRESS_POOL_BASE="$ENV_DOCKER_ADDRESS_POOL_BASE"
    fi

    if [ -n "$ENV_DOCKER_ADDRESS_POOL_SIZE" ]; then
        DOCKER_ADDRESS_POOL_SIZE="$ENV_DOCKER_ADDRESS_POOL_SIZE"
    fi
fi

# Verificar configuração existente do daemon.json
EXISTING_POOL_CONFIGURED=false
if [ -f /etc/docker/daemon.json ]; then
    if jq -e '.["default-address-pools"]' /etc/docker/daemon.json >/dev/null 2>&1; then
        EXISTING_POOL_BASE=$(jq -r '.["default-address-pools"][0].base' /etc/docker/daemon.json 2>/dev/null || true)
        EXISTING_POOL_SIZE=$(jq -r '.["default-address-pools"][0].size' /etc/docker/daemon.json 2>/dev/null || true)

        if [ -n "$EXISTING_POOL_BASE" ] && [ -n "$EXISTING_POOL_SIZE" ] && [ "$EXISTING_POOL_BASE" != "null" ] && [ "$EXISTING_POOL_SIZE" != "null" ]; then
            echo "Pool de rede Docker existente encontrado: $EXISTING_POOL_BASE/$EXISTING_POOL_SIZE"
            EXISTING_POOL_CONFIGURED=true

            if [ "$DOCKER_POOL_BASE_PROVIDED" = false ] && [ "$DOCKER_POOL_SIZE_PROVIDED" = false ]; then
                DOCKER_ADDRESS_POOL_BASE="$EXISTING_POOL_BASE"
                DOCKER_ADDRESS_POOL_SIZE="$EXISTING_POOL_SIZE"
            fi
        fi
    fi
fi

# Validar configuração do pool
if ! [[ $DOCKER_ADDRESS_POOL_BASE =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/[0-9]+$ ]]; then
    echo "Aviso: Formato inválido do pool de rede: $DOCKER_ADDRESS_POOL_BASE"
    DOCKER_ADDRESS_POOL_BASE="$DOCKER_ADDRESS_POOL_BASE_DEFAULT"
fi

if ! [[ $DOCKER_ADDRESS_POOL_SIZE =~ ^[0-9]+$ ]] || [ "$DOCKER_ADDRESS_POOL_SIZE" -lt 16 ] || [ "$DOCKER_ADDRESS_POOL_SIZE" -gt 28 ]; then
    echo "Aviso: Tamanho inválido do pool: $DOCKER_ADDRESS_POOL_SIZE (deve ser 16-28)"
    DOCKER_ADDRESS_POOL_SIZE=$DOCKER_ADDRESS_POOL_SIZE_DEFAULT
fi

# Verificar espaço em disco
TOTAL_SPACE=$(df -BG / | awk 'NR==2 {print $2}' | sed 's/G//')
AVAILABLE_SPACE=$(df -BG / | awk 'NR==2 {print $4}' | sed 's/G//')
REQUIRED_TOTAL_SPACE=30
REQUIRED_AVAILABLE_SPACE=20
WARNING_SPACE=false

if [ "$TOTAL_SPACE" -lt "$REQUIRED_TOTAL_SPACE" ]; then
    WARNING_SPACE=true
    cat <<EOF
AVISO: Espaço em disco total insuficiente!

Espaço total:     ${TOTAL_SPACE}GB
Espaço necessário:  ${REQUIRED_TOTAL_SPACE}GB

==================
EOF
fi

if [ "$AVAILABLE_SPACE" -lt "$REQUIRED_AVAILABLE_SPACE" ]; then
    cat <<EOF
AVISO: Espaço em disco disponível insuficiente!

Espaço disponível:   ${AVAILABLE_SPACE}GB
Espaço necessário: ${REQUIRED_AVAILABLE_SPACE}GB

==================
EOF
    WARNING_SPACE=true
fi

if [ "$WARNING_SPACE" = true ]; then
    echo "Aguardando 5 segundos..."
    sleep 5
fi

# Criar diretórios
echo " - Criando diretórios de dados..."
if [ "$RUNNING_AS_ROOT" = true ]; then
    mkdir -p ${DATA_DIR}/{source,ssh,applications,databases,backups,services,proxy,sentinel}
    mkdir -p ${DATA_DIR}/ssh/{keys,mux}
    mkdir -p ${DATA_DIR}/proxy/dynamic
    chown -R 9999:root ${DATA_DIR}
    chmod -R 700 ${DATA_DIR}
else
    # Sem root, usar sudo apenas para criar diretórios
    sudo mkdir -p ${DATA_DIR}/{source,ssh,applications,databases,backups,services,proxy,sentinel}
    sudo mkdir -p ${DATA_DIR}/ssh/{keys,mux}
    sudo mkdir -p ${DATA_DIR}/proxy/dynamic
    sudo chown -R $(id -u):$(id -g) ${DATA_DIR}
    chmod -R 755 ${DATA_DIR}
fi

INSTALLATION_LOG_WITH_DATE="${DATA_DIR}/source/installation-${DATE}.log"

exec > >(tee -a $INSTALLATION_LOG_WITH_DATE) 2>&1

# Helper functions
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

log_section() {
    echo ""
    echo "============================================================"
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "============================================================"
}

all_packages_installed() {
    for pkg in curl wget git jq openssl; do
        if ! command -v "$pkg" >/dev/null 2>&1; then
            return 1
        fi
    done
    return 0
}

# Normalizar OS type
if [ "$OS_TYPE" = "manjaro" ] || [ "$OS_TYPE" = "manjaro-arm" ]; then
    OS_TYPE="arch"
fi

if [ "$OS_TYPE" = "endeavouros" ]; then
    OS_TYPE="arch"
fi

if [ "$OS_TYPE" = "cachyos" ]; then
    OS_TYPE="arch"
fi

if [ "$OS_TYPE" = "fedora-asahi-remix" ]; then
    OS_TYPE="fedora"
fi

if [ "$OS_TYPE" = "pop" ]; then
    OS_TYPE="ubuntu"
fi

if [ "$OS_TYPE" = "linuxmint" ]; then
    OS_TYPE="ubuntu"
fi

if [ "$OS_TYPE" = "zorin" ]; then
    OS_TYPE="ubuntu"
fi

if [ "$OS_TYPE" = "arch" ] || [ "$OS_TYPE" = "archarm" ]; then
    OS_VERSION="rolling"
else
    OS_VERSION=$(grep -w "VERSION_ID" /etc/os-release | cut -d "=" -f 2 | tr -d '"')
fi

if [ "$OS_TYPE" = 'amzn' ]; then
    dnf install -y findutils >/dev/null
fi

# EASYTI: Versão para instalação - usar "latest" para a versão estável mais recente
LATEST_VERSION="latest"
LATEST_HELPER_VERSION="latest"
LATEST_REALTIME_VERSION="latest"

case "$OS_TYPE" in
arch | ubuntu | debian | raspbian | centos | fedora | rhel | ol | rocky | sles | opensuse-leap | opensuse-tumbleweed | almalinux | amzn | alpine) ;;
*)
    echo "Este script só suporta sistemas operacionais baseados em Debian, Redhat, Arch Linux, Alpine Linux ou SLES."
    exit 1
    ;;
esac

echo "---------------------------------------------"
echo "| Sistema Operacional | $OS_TYPE $OS_VERSION"
echo "| Docker              | $DOCKER_VERSION"
echo "| ${PRODUCT_NAME}       | $LATEST_VERSION"
echo "| Helper              | $LATEST_HELPER_VERSION"
echo "| Realtime            | $LATEST_REALTIME_VERSION"
echo "| Pool Docker         | $DOCKER_ADDRESS_POOL_BASE (tamanho $DOCKER_ADDRESS_POOL_SIZE)"
echo "| Registry URL        | $REGISTRY_URL"
echo "---------------------------------------------"
echo ""

log_section "Passo 1/9: Instalando pacotes necessários"
echo "1/9 Instalando pacotes necessários (curl, wget, git, jq, openssl)..."

APT_UPDATED=false

if all_packages_installed; then
    log "Todos os pacotes necessários já estão instalados"
    echo " - Todos os pacotes já instalados."
else
    case "$OS_TYPE" in
    arch)
        pacman -Sy --noconfirm --needed curl wget git jq openssl >/dev/null || true
        ;;
    alpine)
        sed -i '/^#.*\/community/s/^#//' /etc/apk/repositories
        apk update >/dev/null
        apk add curl wget git jq openssl >/dev/null
        ;;
    ubuntu | debian | raspbian)
        apt-get update -y >/dev/null
        APT_UPDATED=true
        apt-get install -y curl wget git jq openssl >/dev/null
        ;;
    centos | fedora | rhel | ol | rocky | almalinux | amzn)
        if [ "$OS_TYPE" = "amzn" ]; then
            dnf install -y wget git jq openssl >/dev/null
        else
            if ! command -v dnf >/dev/null; then
                yum install -y dnf >/dev/null
            fi
            if ! command -v curl >/dev/null; then
                dnf install -y curl >/dev/null
            fi
            dnf install -y wget git jq openssl >/dev/null
        fi
        ;;
    sles | opensuse-leap | opensuse-tumbleweed)
        zypper refresh >/dev/null
        zypper install -y curl wget git jq openssl >/dev/null
        ;;
    *)
        echo "Sistema operacional não suportado."
        exit 1
        ;;
    esac
    log "Pacotes instalados com sucesso"
fi
echo "     Concluído."

log_section "Passo 2/9: Verificando servidor OpenSSH"
echo "2/9 Verificando configuração do servidor OpenSSH..."

SSH_DETECTED=false
if [ -x "$(command -v systemctl)" ]; then
    if systemctl status sshd >/dev/null 2>&1; then
        echo " - Servidor OpenSSH instalado."
        SSH_DETECTED=true
    elif systemctl status ssh >/dev/null 2>&1; then
        echo " - Servidor OpenSSH instalado."
        SSH_DETECTED=true
    fi
elif [ -x "$(command -v service)" ]; then
    if service sshd status >/dev/null 2>&1; then
        echo " - Servidor OpenSSH instalado."
        SSH_DETECTED=true
    elif service ssh status >/dev/null 2>&1; then
        echo " - Servidor OpenSSH instalado."
        SSH_DETECTED=true
    fi
fi

if [ "$SSH_DETECTED" = "false" ]; then
    echo " - Servidor OpenSSH não detectado. Instalando..."
    case "$OS_TYPE" in
    arch)
        pacman -Sy --noconfirm openssh >/dev/null
        systemctl enable sshd >/dev/null 2>&1
        systemctl start sshd >/dev/null 2>&1
        ;;
    alpine)
        apk add openssh >/dev/null
        rc-update add sshd default >/dev/null 2>&1
        service sshd start >/dev/null 2>&1
        ;;
    ubuntu | debian | raspbian)
        if [ "$APT_UPDATED" = false ]; then
            apt-get update -y >/dev/null
            APT_UPDATED=true
        fi
        apt-get install -y openssh-server >/dev/null
        systemctl enable ssh >/dev/null 2>&1
        systemctl start ssh >/dev/null 2>&1
        ;;
    centos | fedora | rhel | ol | rocky | almalinux | amzn)
        dnf install -y openssh-server >/dev/null
        systemctl enable sshd >/dev/null 2>&1
        systemctl start sshd >/dev/null 2>&1
        ;;
    sles | opensuse-leap | opensuse-tumbleweed)
        zypper install -y openssh >/dev/null
        systemctl enable sshd >/dev/null 2>&1
        systemctl start sshd >/dev/null 2>&1
        ;;
    *)
        echo "###############################################################################"
        echo "AVISO: Não foi possível detectar e instalar o servidor OpenSSH."
        echo "Por favor, certifique-se de que está instalado e rodando."
        echo "###############################################################################"
        exit 1
        ;;
    esac
    echo " - Servidor OpenSSH instalado com sucesso."
    SSH_DETECTED=true
fi

# Verificar se Docker está instalado via snap
if [ -x "$(command -v snap)" ]; then
    SNAP_DOCKER_INSTALLED=$(snap list docker >/dev/null 2>&1 && echo "true" || echo "false")
    if [ "$SNAP_DOCKER_INSTALLED" = "true" ]; then
        echo "Docker está instalado via snap."
        echo "   ${PRODUCT_NAME} não suporta Docker instalado via snap."
        echo "   Por favor, remova o Docker com snap (snap remove docker) e execute este script novamente."
        exit 1
    fi
fi

install_docker() {
    set +e
    curl -s https://releases.rancher.com/install-docker/${DOCKER_VERSION}.sh | sh 2>&1 || true
    if ! [ -x "$(command -v docker)" ]; then
        curl -s https://get.docker.com | sh -s -- --version ${DOCKER_VERSION} 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo "Instalação automática do Docker falhou. Tentando instalação manual."
            install_docker_manually
        fi
    fi
    set -e
}

install_docker_manually() {
    case "$OS_TYPE" in
    "ubuntu" | "debian" | "raspbian")
        if [ "$APT_UPDATED" = false ]; then
            apt-get update
            APT_UPDATED=true
        fi
        apt-get install -y ca-certificates curl
        install -m 0755 -d /etc/apt/keyrings
        curl -fsSL https://download.docker.com/linux/$OS_TYPE/gpg -o /etc/apt/keyrings/docker.asc
        chmod a+r /etc/apt/keyrings/docker.asc

        echo \
            "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/$OS_TYPE \
                  $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}") stable" |
            tee /etc/apt/sources.list.d/docker.list
        apt-get update
        apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
        ;;
    *)
        exit 1
        ;;
    esac
    if ! [ -x "$(command -v docker)" ]; then
        echo "Instalação do Docker falhou."
        echo "   Por favor, visite https://docs.docker.com/engine/install/ e instale manualmente."
        exit 1
    else
        echo "Docker instalado com sucesso."
    fi
}

log_section "Passo 3/9: Verificando instalação do Docker"
echo "3/9 Verificando instalação do Docker..."
if ! [ -x "$(command -v docker)" ]; then
    echo " - Docker não instalado. Instalando Docker. Pode demorar um pouco."
    case "$OS_TYPE" in
    "almalinux")
        dnf config-manager --add-repo=https://download.docker.com/linux/centos/docker-ce.repo >/dev/null 2>&1
        dnf install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin >/dev/null 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo " - Instalação automática do Docker falhou. Por favor, instale manualmente."
            exit 1
        fi
        systemctl start docker >/dev/null 2>&1
        systemctl enable docker >/dev/null 2>&1
        ;;
    "alpine")
        apk add docker docker-cli-compose >/dev/null 2>&1
        rc-update add docker default >/dev/null 2>&1
        service docker start >/dev/null 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo " - Falha ao instalar Docker. Tente instalar manualmente."
            exit 1
        fi
        ;;
    "arch")
        pacman -Sy docker docker-compose --noconfirm >/dev/null 2>&1
        systemctl enable docker.service >/dev/null 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo " - Falha ao instalar Docker. Tente instalar manualmente."
            exit 1
        fi
        ;;
    "amzn")
        dnf install docker -y >/dev/null 2>&1
        DOCKER_CONFIG=${DOCKER_CONFIG:-/usr/local/lib/docker}
        mkdir -p $DOCKER_CONFIG/cli-plugins >/dev/null 2>&1
        curl -sL "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o $DOCKER_CONFIG/cli-plugins/docker-compose >/dev/null 2>&1
        chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose >/dev/null 2>&1
        systemctl start docker >/dev/null 2>&1
        systemctl enable docker >/dev/null 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo " - Falha ao instalar Docker."
            exit 1
        fi
        ;;
    "centos" | "fedora" | "rhel")
        if [ -x "$(command -v dnf5)" ]; then
            dnf config-manager addrepo --from-repofile=https://download.docker.com/linux/$OS_TYPE/docker-ce.repo --overwrite >/dev/null 2>&1
        else
            dnf config-manager --add-repo=https://download.docker.com/linux/$OS_TYPE/docker-ce.repo >/dev/null 2>&1
        fi
        dnf install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin >/dev/null 2>&1
        if ! [ -x "$(command -v docker)" ]; then
            echo " - Instalação do Docker falhou."
            exit 1
        fi
        systemctl start docker >/dev/null 2>&1
        systemctl enable docker >/dev/null 2>&1
        ;;
    "ubuntu" | "debian" | "raspbian")
        install_docker
        if ! [ -x "$(command -v docker)" ]; then
            install_docker_manually
        fi
        ;;
    *)
        install_docker
        if ! [ -x "$(command -v docker)" ]; then
            install_docker_manually
        fi
        ;;
    esac
    echo " - Docker instalado com sucesso."
else
    echo " - Docker está instalado."
fi

# EASYTI: Garantir que Docker está rodando
echo " - Verificando se Docker está rodando..."

# Verificar se Docker Desktop está sendo usado
USING_DOCKER_DESKTOP=false
if docker info 2>/dev/null | grep -q "Docker Desktop"; then
    USING_DOCKER_DESKTOP=true
    echo " - Docker Desktop detectado."
fi

start_docker() {
    # Se Docker Desktop, não tenta iniciar via systemctl
    if [ "$USING_DOCKER_DESKTOP" = true ]; then
        return 0
    fi
    
    if command -v systemctl >/dev/null 2>&1; then
        systemctl start docker 2>/dev/null || true
        systemctl enable docker 2>/dev/null || true
    elif command -v service >/dev/null 2>&1; then
        service docker start 2>/dev/null || true
    elif command -v rc-service >/dev/null 2>&1; then
        rc-service docker start 2>/dev/null || true
    fi
}

# Se Docker já está acessível, não precisa iniciar
if [ "$DOCKER_ACCESSIBLE" = true ]; then
    echo " - Docker já está rodando e acessível."
else
    # Tentar iniciar Docker
    start_docker
    sleep 3

    # Verificar se Docker está respondendo
    DOCKER_RETRIES=0
    MAX_DOCKER_RETRIES=3

    while [ $DOCKER_RETRIES -lt $MAX_DOCKER_RETRIES ]; do
        if docker info >/dev/null 2>&1; then
            echo " - Docker está rodando."
            DOCKER_ACCESSIBLE=true
            break
        else
            DOCKER_RETRIES=$((DOCKER_RETRIES + 1))
            echo " - Docker não está respondendo. Tentativa $DOCKER_RETRIES de $MAX_DOCKER_RETRIES..."
            
            # Tentar reiniciar (apenas se não for Docker Desktop)
            if [ "$USING_DOCKER_DESKTOP" = false ]; then
                if command -v systemctl >/dev/null 2>&1; then
                    systemctl restart docker 2>/dev/null || true
                elif command -v service >/dev/null 2>&1; then
                    service docker restart 2>/dev/null || true
                fi
            fi
            sleep 5
        fi
    done
fi

if ! docker info >/dev/null 2>&1; then
    echo ""
    echo "============================================================"
    echo " ERRO: Docker não está funcionando!"
    echo "============================================================"
    echo ""
    echo " Possíveis soluções:"
    echo ""
    echo " 1. Se você usa Docker Desktop:"
    echo "    - Abra o Docker Desktop e aguarde iniciar"
    echo "    - Execute o script SEM sudo: bash $0"
    echo ""
    echo " 2. Se você está em uma máquina local (não servidor):"
    echo "    - Inicie o Docker Desktop ou Docker daemon manualmente"
    echo "    - No Ubuntu Desktop: sudo systemctl start docker"
    echo ""
    echo " 3. Se você está em um servidor:"
    echo "    - Verifique os logs: journalctl -u docker"
    echo "    - Reinicie: sudo systemctl restart docker"
    echo ""
    echo " 4. Se Docker não está instalado:"
    echo "    - Execute: curl -fsSL https://get.docker.com | sh"
    echo ""
    echo " Após resolver, execute o script novamente."
    echo "============================================================"
    exit 1
fi

log_section "Passo 4/9: Configurando Docker"
echo "4/9 Configurando Docker..."

# EASYTI: Docker Desktop gerencia sua própria configuração, pular
if [ "$USING_DOCKER_DESKTOP" = true ]; then
    echo " - Docker Desktop detectado - usando configuração do Docker Desktop."
    echo " - Pulando configuração do daemon.json."
    echo "     Concluído."
else
    echo " - Configuração do pool de rede: ${DOCKER_ADDRESS_POOL_BASE}/${DOCKER_ADDRESS_POOL_SIZE}"

    # Usar sudo se não estiver como root
    if [ "$RUNNING_AS_ROOT" = true ]; then
        SUDO_CMD=""
    else
        SUDO_CMD="sudo"
    fi

    $SUDO_CMD mkdir -p /etc/docker

    if [ -f /etc/docker/daemon.json ]; then
        $SUDO_CMD cp /etc/docker/daemon.json /etc/docker/daemon.json.original-"$DATE" 2>/dev/null || true
    fi

    NEED_MERGE=false
    if [ "$DOCKER_POOL_FORCE_OVERRIDE" = true ] || [ "$EXISTING_POOL_CONFIGURED" = false ]; then
        if [ ! -f /etc/docker/daemon.json ]; then
            echo " - Criando nova configuração Docker..."
            $SUDO_CMD tee /etc/docker/daemon.json > /dev/null <<EOL
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  },
  "default-address-pools": [
    {"base":"${DOCKER_ADDRESS_POOL_BASE}","size":${DOCKER_ADDRESS_POOL_SIZE}}
  ]
}
EOL
            NEED_MERGE=true
        fi
    fi

    if [ ! -f /etc/docker/daemon.json ]; then
        $SUDO_CMD tee /etc/docker/daemon.json > /dev/null <<EOL
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  },
  "default-address-pools": [
    {"base":"${DOCKER_ADDRESS_POOL_BASE}","size":${DOCKER_ADDRESS_POOL_SIZE}}
  ]
}
EOL
    fi

    if [ "$NEED_MERGE" = true ]; then
        echo " - Reiniciando daemon Docker..."
        restart_docker_service
    fi
    echo "     Concluído."
fi

log_section "Passo 5/9: Clonando repositório ${PRODUCT_NAME}"
echo "5/9 Clonando repositório ${PRODUCT_NAME}..."

# EASYTI: Configurar safe.directory para evitar erro de ownership
git config --global --add safe.directory ${DATA_DIR}/source 2>/dev/null || true

# Clonar ou atualizar repositório
if [ -d "${DATA_DIR}/source/.git" ]; then
    echo " - Repositório existente encontrado, atualizando..."
    cd ${DATA_DIR}/source
    git config --global --add safe.directory ${DATA_DIR}/source 2>/dev/null || true
    git fetch origin 2>/dev/null || true
    git reset --hard origin/main 2>/dev/null || git checkout main 2>/dev/null || true
else
    echo " - Clonando repositório..."
    rm -rf ${DATA_DIR}/source
    git clone ${EASYTI_REPO}.git ${DATA_DIR}/source
fi

echo "     Concluído."

log_section "Passo 6/9: Configurando variáveis de ambiente"
echo "6/9 Configurando variáveis de ambiente..."

# Copiar .env.example para .env se não existir
if [ -f "${DATA_DIR}/source/.env.example" ] && [ ! -f "$ENV_FILE" ]; then
    cp "${DATA_DIR}/source/.env.example" "$ENV_FILE"
    echo " - Arquivo .env criado a partir do .env.example"
elif [ -f "${DATA_DIR}/source/.env.production" ] && [ ! -f "$ENV_FILE" ]; then
    cp "${DATA_DIR}/source/.env.production" "$ENV_FILE"
    echo " - Arquivo .env criado a partir do .env.production"
fi

update_env_var() {
    local key="$1"
    local value="$2"

    if grep -q "^${key}=$" "$ENV_FILE"; then
        sed -i "s|^${key}=$|${key}=${value}|" "$ENV_FILE"
        echo " - Atualizado ${key}"
    elif ! grep -q "^${key}=" "$ENV_FILE"; then
        printf '%s=%s\n' "$key" "$value" >>"$ENV_FILE"
        echo " - Adicionado ${key}"
    fi
}

# Gerar chaves seguras
update_env_var "APP_ID" "$(openssl rand -hex 16)"
update_env_var "APP_KEY" "base64:$(openssl rand -base64 32)"
update_env_var "APP_NAME" "${PRODUCT_NAME}"
update_env_var "DB_PASSWORD" "$(openssl rand -base64 32)"
update_env_var "REDIS_PASSWORD" "$(openssl rand -base64 32)"
update_env_var "PUSHER_APP_ID" "$(openssl rand -hex 32)"
update_env_var "PUSHER_APP_KEY" "$(openssl rand -hex 32)"
update_env_var "PUSHER_APP_SECRET" "$(openssl rand -hex 32)"

# Credenciais predefinidas
if [ -n "$ROOT_USERNAME" ] && [ -n "$ROOT_USER_EMAIL" ] && [ -n "$ROOT_USER_PASSWORD" ]; then
    echo " - Configurando credenciais do usuário root"
    update_env_var "ROOT_USERNAME" "$ROOT_USERNAME"
    update_env_var "ROOT_USER_EMAIL" "$ROOT_USER_EMAIL"
    update_env_var "ROOT_USER_PASSWORD" "$ROOT_USER_PASSWORD"
fi

if [ -n "${REGISTRY_URL+x}" ]; then
    update_env_var "REGISTRY_URL" "$REGISTRY_URL"
fi

if [ "$AUTOUPDATE" = "false" ]; then
    update_env_var "AUTOUPDATE" "false"
fi

echo "     Concluído."

log_section "Passo 7/9: Configurando chave SSH"
echo "7/9 Configurando chave SSH para acesso localhost..."

if [ ! -f ~/.ssh/authorized_keys ]; then
    mkdir -p ~/.ssh
    chmod 700 ~/.ssh
    touch ~/.ssh/authorized_keys
    chmod 600 ~/.ssh/authorized_keys
fi

set +e
IS_DB_VOLUME_EXISTS=$(docker volume ls | grep coolify-db | wc -l)
set -e

if [ "$IS_DB_VOLUME_EXISTS" -eq 0 ]; then
    echo " - Gerando chave SSH..."
    SSH_KEY_FILE="${DATA_DIR}/ssh/keys/id.${CURRENT_USER}@host.docker.internal"
    
    test -f "${SSH_KEY_FILE}" && rm -f "${SSH_KEY_FILE}"
    test -f "${SSH_KEY_FILE}.pub" && rm -f "${SSH_KEY_FILE}.pub"
    
    ssh-keygen -t ed25519 -a 100 -f "${SSH_KEY_FILE}" -q -N "" -C easyti
    
    if [ "$RUNNING_AS_ROOT" = true ]; then
        chown 9999 "${SSH_KEY_FILE}"
    fi
    
    # Adicionar à authorized_keys
    if [ -f ~/.ssh/authorized_keys ]; then
        sed -i "/easyti/d" ~/.ssh/authorized_keys 2>/dev/null || true
    fi
    cat "${SSH_KEY_FILE}.pub" >> ~/.ssh/authorized_keys 2>/dev/null || true
    rm -f "${SSH_KEY_FILE}.pub"
fi

if [ "$RUNNING_AS_ROOT" = true ]; then
    chown -R 9999:root ${DATA_DIR}
    chmod -R 700 ${DATA_DIR}
else
    # Para Docker Desktop, manter permissões do usuário
    sudo chown -R $(id -u):$(id -g) ${DATA_DIR} 2>/dev/null || true
fi
echo "     Concluído."

log_section "Passo 8/9: Construindo e iniciando containers"
echo "8/9 Construindo e iniciando containers Docker..."
echo " - Isso pode demorar alguns minutos na primeira execução..."

cd ${DATA_DIR}/source

# EASYTI: Usar docker-compose.easyti.yml para BUILDAR as imagens customizadas
if [ -f "${DATA_DIR}/source/docker-compose.easyti.yml" ]; then
    echo " - Usando docker-compose.easyti.yml para build das imagens..."
    
    # Criar rede coolify se não existir
    echo " - Criando rede Docker..."
    docker network create --attachable coolify 2>/dev/null || true
    
    # Parar containers existentes
    echo " - Parando containers existentes..."
    docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml down --remove-orphans 2>/dev/null || true
    
    # Verificar se .env tem as variáveis necessárias
    if ! grep -q "^DB_USERNAME=" "$ENV_FILE"; then
        echo "DB_USERNAME=coolify" >> "$ENV_FILE"
    fi
    if ! grep -q "^DB_DATABASE=" "$ENV_FILE"; then
        echo "DB_DATABASE=coolify" >> "$ENV_FILE"
    fi
    if ! grep -q "^APP_NAME=" "$ENV_FILE"; then
        echo "APP_NAME=EasyTI Cloud" >> "$ENV_FILE"
    fi
    
    # BUILDAR e subir containers
    echo ""
    echo " =============================================="
    echo "  BUILDANDO IMAGENS DO EASYTI CLOUD"
    echo "  Isso pode demorar 5-15 minutos na primeira vez..."
    echo " =============================================="
    echo ""
    
    # Build das imagens
    echo " - [1/3] Buildando imagem do painel EasyTI Cloud..."
    docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml build coolify
    
    echo " - [2/3] Buildando imagem do Realtime (WebSocket)..."
    docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml build soketi
    
    echo " - [3/3] Iniciando todos os containers..."
    docker compose -f ${DATA_DIR}/source/docker-compose.easyti.yml up -d --remove-orphans
    
    echo ""
    echo " - Build e deploy concluídos!"
    
else
    echo " - AVISO: docker-compose.easyti.yml não encontrado."
    echo " - Verificando arquivos disponíveis:"
    ls -la ${DATA_DIR}/source/*.yml 2>/dev/null || echo " - Nenhum arquivo .yml encontrado"
    
    # Fallback: tentar com docker-compose padrão
    if [ -f "${DATA_DIR}/source/docker-compose.yml" ]; then
        echo " - Tentando com docker-compose.yml padrão..."
        docker network create --attachable coolify 2>/dev/null || true
        export LATEST_IMAGE="latest"
        docker compose -f ${DATA_DIR}/source/docker-compose.yml -f ${DATA_DIR}/source/docker-compose.prod.yml up -d --remove-orphans 2>/dev/null || true
    fi
fi

echo "     Concluído."

log_section "Passo 9/9: Verificando instalação"
echo "9/9 Verificando instalação..."

# Aguardar containers ficarem saudáveis
echo " - Aguardando containers ficarem prontos..."
sleep 15

cd ${DATA_DIR}/source

# Verificar status dos containers
echo " - Status dos containers:"
docker compose -f docker-compose.yml -f docker-compose.prod.yml ps 2>/dev/null || docker ps

# Verificar se container principal está rodando
if docker ps --format '{{.Names}}' | grep -q "coolify"; then
    echo " - Container principal (coolify) está rodando!"
    
    # Aguardar healthcheck
    echo " - Aguardando aplicação ficar saudável..."
    HEALTH_WAIT=0
    MAX_HEALTH_WAIT=120
    while [ $HEALTH_WAIT -lt $MAX_HEALTH_WAIT ]; do
        HEALTH=$(docker inspect --format='{{.State.Health.Status}}' coolify 2>/dev/null || echo "starting")
        if [ "$HEALTH" = "healthy" ]; then
            echo " - Aplicação está saudável!"
            break
        fi
        sleep 5
        HEALTH_WAIT=$((HEALTH_WAIT + 5))
        echo " - Aguardando... ($HEALTH_WAIT/$MAX_HEALTH_WAIT segundos)"
    done
    
    if [ "$HEALTH" != "healthy" ]; then
        echo " - AVISO: Aplicação ainda não está saudável. Status: $HEALTH"
        echo " - Verifique os logs: docker logs coolify"
    fi
else
    echo " - AVISO: Container principal não encontrado."
    echo " - Verificando logs..."
    docker compose -f docker-compose.yml -f docker-compose.prod.yml logs --tail=30 2>/dev/null || true
fi

echo ""
echo -e "\033[0;32m"
echo "=============================================="
echo "   ${PRODUCT_NAME} INSTALADO COM SUCESSO!"
echo "=============================================="
echo -e "\033[0m"

# Obter IPs
IPV4_PUBLIC_IP=$(curl -4s --max-time 5 https://ifconfig.io 2>/dev/null || true)
IPV6_PUBLIC_IP=$(curl -6s --max-time 5 https://ifconfig.io 2>/dev/null || true)

echo ""
echo "Sua instância está pronta!"
echo ""
if [ -n "$IPV4_PUBLIC_IP" ]; then
    echo -e "Acesse pelo IP Público IPv4: \033[1;36mhttp://$IPV4_PUBLIC_IP:8000\033[0m"
fi
if [ -n "$IPV6_PUBLIC_IP" ]; then
    echo -e "Acesse pelo IP Público IPv6: \033[1;36mhttp://[$IPV6_PUBLIC_IP]:8000\033[0m"
fi

set +e
DEFAULT_PRIVATE_IP=$(ip route get 1 | sed -n 's/^.*src \([0-9.]*\) .*$/\1/p')
PRIVATE_IPS=$(hostname -I 2>/dev/null || ip -o addr show scope global | awk '{print $4}' | cut -d/ -f1)
set -e

if [ -n "$PRIVATE_IPS" ]; then
    echo ""
    echo "IPs Privados disponíveis:"
    for IP in $PRIVATE_IPS; do
        if [ "$IP" != "$DEFAULT_PRIVATE_IP" ]; then
            echo "  http://$IP:8000"
        fi
    done
fi

echo ""
echo -e "\033[1;33mIMPORTANTE:\033[0m Faça backup do arquivo de variáveis de ambiente:"
echo "  ${ENV_FILE}"
echo ""
echo "Repositório: ${EASYTI_REPO}"
echo "Log de instalação: ${INSTALLATION_LOG_WITH_DATE}"
echo ""
log_section "Instalação Completa"
log "${PRODUCT_NAME} instalado com sucesso"
log "Versão: ${LATEST_VERSION}"

