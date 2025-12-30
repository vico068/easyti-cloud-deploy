# 🚀 EasyTI Cloud

> Plataforma de deploy simplificado para suas aplicações

[![Deploy](https://img.shields.io/badge/deploy-simplificado-4DC4E0?style=for-the-badge)](https://github.com/vico068/easyti-cloud-deploy)
[![Next.js](https://img.shields.io/badge/suporta-Next.js-black?style=for-the-badge)](https://nextjs.org/)
[![NestJS](https://img.shields.io/badge/suporta-NestJS-E0234E?style=for-the-badge)](https://nestjs.com/)
[![Docker](https://img.shields.io/badge/suporta-Docker-2496ED?style=for-the-badge)](https://docker.com/)

<p align="center">
  <img src="public/images/logo.png" alt="EasyTI Cloud" width="200"/>
</p>

## 📋 Sobre o Projeto

**EasyTI Cloud** é uma plataforma de deploy self-hosted, baseada no Coolify, customizada para oferecer uma experiência simplificada para desenvolvedores e empresas que desejam fazer deploy de aplicações web.

### ✨ Principais Funcionalidades

- 🎯 **Interface Simplificada** - Dashboard intuitivo para clientes
- 🚀 **Deploy em 3 Passos** - Wizard visual guiado
- 📦 **Templates Prontos** - Next.js, NestJS, Node.js, Docker
- 🔒 **SSL Automático** - Let's Encrypt integrado
- 🌐 **Domínios Customizados** - Configure facilmente
- 📊 **Monitoramento** - Status e logs em tempo real
- 🔄 **CI/CD** - Deploy automático via Git webhooks
- 🇧🇷 **Em Português** - Interface traduzida

---

## 🛠️ Instalação

### Requisitos Mínimos

- **Sistema Operacional:** Ubuntu 20.04+, Debian 11+, CentOS 8+, ou similar
- **RAM:** Mínimo 2GB (recomendado 4GB+)
- **Disco:** Mínimo 20GB disponíveis
- **Acesso:** Root ou sudo

### Instalação Rápida (1 Comando)

```bash
curl -fsSL https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main/scripts/easyti-install.sh | sudo bash
```

### Instalação Manual

1. **Clone o repositório:**

```bash
git clone https://github.com/vico068/easyti-cloud-deploy.git /data/easyti/source
cd /data/easyti/source
```

2. **Execute o script de instalação:**

```bash
sudo ./scripts/easyti-install.sh
```

3. **Acesse o painel:**

Após a instalação, acesse: `http://SEU_IP:8000`

---

## ⚙️ Configuração

### Variáveis de Ambiente

Você pode configurar a instalação usando variáveis de ambiente:

```bash
# Definir credenciais do usuário admin
export ROOT_USERNAME="admin"
export ROOT_USER_EMAIL="admin@seudominio.com"
export ROOT_USER_PASSWORD="sua_senha_segura"

# Executar instalação
curl -fsSL https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main/scripts/easyti-install.sh | sudo bash
```

### Arquivo .env

O arquivo de configuração principal está em: `/data/easyti/source/.env`

**Principais variáveis:**

| Variável | Descrição | Padrão |
|----------|-----------|--------|
| `APP_NAME` | Nome da aplicação | EasyTI Cloud |
| `APP_URL` | URL da aplicação | http://localhost |
| `DB_PASSWORD` | Senha do banco de dados | (gerada) |
| `REDIS_PASSWORD` | Senha do Redis | (gerada) |

---

## 🔄 Atualização

Para atualizar o EasyTI Cloud:

```bash
curl -fsSL https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main/scripts/easyti-upgrade.sh | sudo bash
```

Ou manualmente:

```bash
cd /data/easyti/source
git pull origin main
docker compose down
docker compose up -d --build
```

---

## 🏗️ Arquitetura

### Multi-Tenancy Simplificado

O EasyTI Cloud implementa um modelo de multi-tenancy onde:

- **Team Master (Admin)** - Gerencia servidores e clientes
- **Team Admin** - Administra projetos de seu time
- **Team Member** - Acessa e gerencia suas aplicações

```
┌──────────────────────────────────────────────────────┐
│                  Easy TI Solutions                    │
│                    (Team Master)                      │
│                        │                              │
│           ┌───────────┴───────────┐                  │
│           ▼                       ▼                  │
│    ┌──────────────┐       ┌──────────────┐          │
│    │  Cliente A   │       │  Cliente B   │          │
│    │   (Team)     │       │   (Team)     │          │
│    │              │       │              │          │
│    │ ┌──────────┐ │       │ ┌──────────┐ │          │
│    │ │  App 1   │ │       │ │  App 1   │ │          │
│    │ │  App 2   │ │       │ │  App 2   │ │          │
│    │ └──────────┘ │       │ └──────────┘ │          │
│    └──────────────┘       └──────────────┘          │
│                                                      │
│    ┌────────────────────────────────────────────┐   │
│    │            Servidor Master                  │   │
│    │  (Gerenciado pela Easy TI Solutions)        │   │
│    └────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────┘
```

### Isolamento de Recursos

Cada time possui:
- **Rede Docker isolada:** `easyti_team_{id}_network`
- **Containers nomeados:** `team_{id}_{app_uuid}`
- **Limites de recursos** configuráveis por plano

---

## 📦 Templates Suportados

### Next.js
```yaml
Porta: 3000
Build: npm run build
Start: npm start
```

### NestJS
```yaml
Porta: 3000
Build: npm run build
Start: npm run start:prod
```

### Node.js / Express
```yaml
Porta: 3000
Build: npm install
Start: npm start
```

### Docker Personalizado
```yaml
Use seu próprio Dockerfile ou docker-compose.yml
```

---

## 🔧 Comandos Úteis

```bash
# Ver logs dos containers
docker compose logs -f

# Reiniciar todos os serviços
docker compose restart

# Parar todos os serviços
docker compose down

# Iniciar serviços
docker compose up -d

# Ver status dos containers
docker compose ps

# Acessar container principal
docker exec -it coolify bash

# Executar migrations
docker exec coolify php artisan migrate
```

---

## 🐛 Solução de Problemas

### Container não inicia

```bash
# Verificar logs
docker compose logs coolify

# Verificar se portas estão em uso
netstat -tulpn | grep 8000
```

### Erro de conexão com banco

```bash
# Verificar se container do postgres está rodando
docker compose ps coolify-db

# Verificar logs do banco
docker compose logs coolify-db
```

### Resetar instalação

```bash
# Parar e remover containers
docker compose down -v

# Remover dados (CUIDADO!)
sudo rm -rf /data/easyti

# Reinstalar
curl -fsSL https://raw.githubusercontent.com/vico068/easyti-cloud-deploy/main/scripts/easyti-install.sh | sudo bash
```

---

## 📁 Estrutura de Diretórios

```
/data/easyti/
├── source/              # Código fonte
│   ├── .env            # Variáveis de ambiente
│   ├── docker-compose.yml
│   └── ...
├── applications/        # Dados das aplicações
├── databases/          # Dados dos bancos
├── backups/            # Backups automáticos
├── proxy/              # Configurações do proxy
├── ssh/                # Chaves SSH
│   ├── keys/
│   └── mux/
└── services/           # Serviços adicionais
```

---

## 🤝 Suporte

- **Documentação:** [GitHub Wiki](https://github.com/vico068/easyti-cloud-deploy/wiki)
- **Issues:** [GitHub Issues](https://github.com/vico068/easyti-cloud-deploy/issues)
- **Email:** suporte@easyti.cloud

---

## 📄 Licença

Este projeto é baseado no [Coolify](https://coolify.io) e segue a mesma licença open-source.

---

<p align="center">
  Feito com ❤️ por <a href="https://easyti.cloud">Easy TI Solutions</a>
</p>

