# Slim App - Sistema de Gerenciamento

> **Projeto gerado com auxílio de IA e revisado por [Luiz Eduardo](https://github.com/letsmg).**

Sistema completo de gerenciamento com autenticação, CRUD de usuários, produtos e relatórios. Construído com **Slim Framework 4** (PHP) no backend e **Vue 3 + Tailwind CSS** no frontend, utilizando **Vite** como bundler para compilação dos assets.

## Stack Tecnológica

### Backend (PHP)

| Pacote | Versão | Descrição |
|--------|--------|-----------|
| `php` | >=8.3 | Versão mínima do PHP |
| `slim/slim` | ^4.0 | Micro-framework Slim (PSR-7/15) |
| `slim/psr7` | ^1.8 | Implementação PSR-7 |
| `illuminate/database` | ^13.9 | Eloquent ORM (Laravel) |
| `vlucas/phpdotenv` | ^5.6 | Gerenciamento de .env |
| `phpunit/phpunit` | ^11.0 | Testes unitários (dev) |

### Frontend (Node.js/npm)

| Pacote | Descrição |
|--------|-----------|
| `vue` | Framework frontend Vue 3 (Composition API) |
| `vue-router` | Roteamento SPA com lazy loading |
| `pinia` | Gerenciamento de estado |
| `vite` | Bundler e dev server com HMR |
| `tailwindcss` | Framework CSS utility-first v4 |
| `@tailwindcss/vite` | Plugin Tailwind para Vite |
| `axios` | HTTP client com interceptors CSRF |
| `sass` | Pré-processador SCSS |

## Estrutura do Projeto

```
├── app/
│   ├── controllers/        # Controladores REST (User, Product, Report)
│   ├── models/             # Modelos Eloquent (User, Product)
│   ├── repositories/       # Camada de acesso a dados (User, Product, Report)
│   ├── services/           # Lógica de negócio (User, Product, Report)
│   ├── requests/           # Validação de entrada (StoreUser, StoreProduct)
│   ├── policies/           # Regras de autorização
│   ├── middlewares/        # Middlewares PSR-15
│   └── helpers.php         # Funções auxiliares (sanitização, hash, escape)
├── config/
│   ├── config.php          # Configurações (lê variáveis do .env)
│   └── routes.php          # Definição de rotas API + SPA fallback
├── public/
│   ├── index.php           # Entry point da aplicação
│   ├── index.html          # HTML servido para SPA
│   ├── css/                # Assets CSS compilados (minificados com hash)
│   └── js/                 # Assets JS compilados (minificados com hash)
├── resources/
│   ├── index.html          # Entry HTML do Vite (dev)
│   ├── css/
│   │   └── app.css         # Estilos Tailwind (entry point)
│   └── js/
│       ├── app.js          # Entry point JS Vue + Pinia + Router
│       ├── services/
│       │   └── api.js      # Instância Axios configurada
│       ├── router/
│       │   └── index.js    # Vue Router (lazy loading + guards)
│       ├── components/
│       │   └── App.vue     # Componente raiz (Header + RouterView + Footer)
│       ├── layouts/
│       │   ├── Header.vue  # Header responsivo com menu mobile
│       │   └── Footer.vue  # Footer com links institucionais
│       └── pages/
│           ├── HomePage.vue            # Dashboard inicial
│           ├── user/
│           │   └── UserIndex.vue       # CRUD Usuários (tabela)
│           ├── product/
│           │   └── ProductIndex.vue    # CRUD Produtos (grid cards)
│           └── report/
│               └── ReportIndex.vue     # Relatórios (métricas + tabelas)
├── .env                    # Variáveis de ambiente
├── vite.config.js          # Configuração do Vite (build, aliases)
├── composer.json           # Dependências PHP
└── package.json            # Dependências Node.js
```

## Arquitetura

### Camadas

- **Controllers**: Recebem requisições HTTP, sanitizam entradas, chamam Services e retornam respostas JSON
- **Services**: Contém toda lógica de negócio, orquestra chamadas a Repositories e Requests
- **Repositories**: Abstraem consultas ao banco de dados via Eloquent ORM
- **Requests**: Validam e sanitizam dados de entrada (ISO 27001)
- **Models**: Representam tabelas do banco com mutators para sanitização automática

### Segurança (ISO 27001)

- Hash de senhas com **Argon2id** (memory_cost 64MB, time_cost 4, threads 3)
- Sanitização de entrada com `strip_tags()` + `trim()` em todas as entradas
- Escape de saída com `htmlspecialchars()` (função `e()`)
- Proteção contra Mass Assignment (Eloquent `$fillable`)
- Sessões configuradas com `httpOnly`, `SameSite=Lax`, `use_strict_mode`
- Token CSRF automático via meta tag
- Soft deletes nos models (proteção contra perda acidental de dados)

## Instalação

```bash
# 1. Clonar o repositório
git clone https://github.com/letsmg/slim.git
cd slim

# 2. Configurar variáveis de ambiente
cp .env.example .env
# Editar .env com dados do banco PostgreSQL

# 3. Instalar dependências PHP
composer install

# 4. Instalar dependências frontend
npm install

# 5. Compilar assets para produção
npm run build

# 6. Iniciar servidor embutido do PHP
php -S localhost:8080 -t public/
```

## Comandos

| Comando | Descrição |
|---------|-----------|
| `npm run dev` | Inicia servidor de desenvolvimento Vite com HMR |
| `npm run build` | Compila JS/CSS para produção em `public/js/` e `public/css/` (minificado com hash) |
| `npm run preview` | Preview local da build de produção |
| `composer dump-autoload` | Recarrega autoload PSR-4 |

## API Endpoints

### Health Check
```
GET  /api/ping            # Verifica se API está online
```

### Usuários
```
GET    /api/users          # Lista todos os usuários
GET    /api/users/{id}     # Busca usuário por ID
POST   /api/users          # Cria novo usuário
PUT    /api/users/{id}     # Atualiza usuário
DELETE /api/users/{id}     # Remove usuário (soft delete)
```

### Produtos
```
GET    /api/products       # Lista produtos (filtros: active, category, search)
GET    /api/products/stats # Estatísticas dos produtos
GET    /api/products/{id}  # Busca produto por ID
POST   /api/products       # Cria novo produto
PUT    /api/products/{id}  # Atualiza produto
DELETE /api/products/{id}  # Remove produto (soft delete)
```

### Relatórios
```
GET  /api/reports          # Lista tipos de relatório disponíveis
GET  /api/reports/{type}   # Gera relatório (general, users, products)
```

## Licença

Projeto de estudos - uso livre. Desenvolvido com assistência de inteligência artificial e revisado por Luiz Eduardo.