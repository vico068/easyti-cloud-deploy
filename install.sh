#!/bin/bash
## ==============================================================================
## EASYTI CLOUD - Script de Instalação (Ponto de Entrada)
## 
## Uso:
##   curl -fsSL https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main/install.sh | sudo bash
##
## ==============================================================================

set -e

EASYTI_REPO="https://github.com/vico068/easyti-cloud-deploy"
EASYTI_RAW="https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main"

echo ""
echo -e "\033[0;36m"
echo "  ███████╗ █████╗ ███████╗██╗   ██╗████████╗██╗     ██████╗██╗      ██████╗ ██╗   ██╗██████╗"
echo "  ██╔════╝██╔══██╗██╔════╝╚██╗ ██╔╝╚══██╔══╝██║    ██╔════╝██║     ██╔═══██╗██║   ██║██╔══██╗"
echo "  █████╗  ███████║███████╗ ╚████╔╝    ██║   ██║    ██║     ██║     ██║   ██║██║   ██║██║  ██║"
echo "  ██╔══╝  ██╔══██║╚════██║  ╚██╔╝     ██║   ██║    ██║     ██║     ██║   ██║██║   ██║██║  ██║"
echo "  ███████╗██║  ██║███████║   ██║      ██║   ██║    ╚██████╗███████╗╚██████╔╝╚██████╔╝██████╔╝"
echo "  ╚══════╝╚═╝  ╚═╝╚══════╝   ╚═╝      ╚═╝   ╚═╝     ╚═════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═════╝"
echo -e "\033[0m"
echo ""
echo "  Deploy simplificado para sua aplicação"
echo ""
echo "  Repositório: ${EASYTI_REPO}"
echo ""

# Verificar se está sendo executado como root
if [ $EUID != 0 ]; then
    echo "Por favor, execute este script como root ou com sudo:"
    echo ""
    echo "  sudo bash -c \"\$(curl -fsSL ${EASYTI_RAW}/install.sh)\""
    echo ""
    exit 1
fi

# Verificar se curl está instalado
if ! command -v curl &> /dev/null; then
    echo "Instalando curl..."
    apt-get update -y && apt-get install -y curl || yum install -y curl || apk add curl
fi

echo "Baixando e executando script de instalação completo..."
echo ""

# Baixar e executar script principal
curl -fsSL ${EASYTI_RAW}/scripts/easyti-install.sh | bash

exit $?

