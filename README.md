# Slim Fleet - Sistema de Gerenciamento de Frota

> **Projeto gerado com auxílio de IA e revisado por [Luiz Eduardo](https://github.com/letsmg).**

Sistema completo de gerenciamento de frotas com autenticação, CRUD de usuários, veículos, motoristas, mecânicos, viagens e manutenções programadas. Construído com **Slim Framework 4** (PHP) no backend e **Vue 3 + Tailwind CSS** no frontend, utilizando **Vite** como bundler para compilação dos assets.

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
| `vite` | Bundler e dev server com HMR |
| `tailwindcss` | Framework CSS utility-first v4 |
| `@tailwindcss/vite` | Plugin Tailwind para Vite |

## Estrutura do Projeto

```
├── app/
│   ├── controllers/        # Controladores REST (User, Product, Report, Vehicle, Driver, Mechanic, Trip, ScheduledMaintenance)
│   ├── models/             # Modelos Eloquent (User, Product, Vehicle, Driver, Mechanic, Trip, ScheduledMaintenance)
│   ├── repositories/       # Camada de acesso a dados
│   ├── services/           # Lógica de negócio
│   ├── requests/           # Validação de entrada (StoreUser, StoreProduct, StoreVehicle, StoreDriver)
│   ├── policies/           # Regras de autorização
│   ├── middlewares/        # Middlewares PSR-15
│   └── helpers.php         # Funções auxiliares (sanitização, hash, escape)
├── config/
│   ├── config.php          # Configurações (lê variáveis do .env)
│   ├── routes.php          # Definição de rotas API + SPA fallback
│   ├── seeders/            # Seeders do Phinx (Database, Users, Vehicles, Drivers, Mechanics, Trips, ScheduledMaintenances)
│   └── tests/              # Testes PHPUnit (SystemTest)
├── db/
│   └── migrations/         # Migrations do Phinx (create_users, create_vehicles, create_drivers, create_mechanics, create_trips, create_scheduled_maintenances)
├── public/
│   ├── index.php           # Entry point da aplicação
│   ├── index.html          # HTML servido para SPA
│   ├── css/                # Assets CSS compilados (minificados com hash)
│   ├── js/                 # Assets JS compilados (minificados com hash)
│   └── imgs/               # Atalho simbólico para storage/imgs
├── resources/
│   ├── index.html          # Entry HTML do Vite (dev)
│   ├── storage/            # Pasta legada (não utilizada)
│   ├── css/
│   │   └── app.css         # Estilos Tailwind (entry point)
│   └── js/
│       ├── app.js          # Entry point JS Vue + Router
│       ├── services/
│       │   └── api.js      # Instância Axios configurada
│       ├── router/
│       │   └── index.js    # Vue Router (lazy loading + guards)
│       ├── layouts/
│       │   ├── Header.vue  # Header público
│       │   ├── AuthHeader.vue  # Header autenticado com menu completo
│       │   └── Footer.vue  # Footer com links institucionais
│       └── pages/
│           ├── HomePage.vue            # Página inicial com banner e features
│           ├── DashboardPage.vue       # Dashboard do painel
│           ├── user/
│           │   └── UserIndex.vue       # CRUD Usuários
│           ├── product/
│           │   └── ProductIndex.vue    # CRUD Produtos
│           ├── report/
│           │   └── ReportIndex.vue     # Relatórios
│           ├── vehicle/
│           │   └── VehicleIndex.vue    # CRUD Veículos
│           ├── driver/
│           │   └── DriverIndex.vue     # CRUD Motoristas
│           ├── mechanic/
│           │   └── MechanicIndex.vue   # CRUD Mecânicos
│           ├── trip/
│           │   └── TripIndex.vue       # CRUD Viagens
│           └── maintenance/
│               └── ScheduledMaintenanceIndex.vue  # CRUD Manutenções Programadas
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
- Escape de saída com `htmlspecialchars()` (função `esc()`)
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

# 5. Criar link simbólico para imagens
npm run storage:link

# 6. Executar migrations e seeders
npm run migrate:fresh

# 7. Compilar assets para produção
npm run build

# 8. Iniciar servidor embutido do PHP
php -S localhost:8080 -t public/
```

## Comandos

| Comando | Descrição |
|---------|-----------|
| `npm run dev` | Inicia servidor de desenvolvimento Vite com HMR |
| `npm run build` | Compila JS/CSS para produção em `public/js/` e `public/css/` (minificado com hash) |
| `npm run prod` | Compila JS/CSS para produção (alias para build) |
| `npm run storage:link` | Cria atalho simbólico `public/imgs` → `storage/imgs` |
| `npm run migrate` | Executa as migrations pendentes |
| `npm run seed` | Executa os seeders |
| `npm run migrate:fresh` | Rollback total + migrate + seed |
| `npm run test` | Executa os testes PHPUnit |
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

### Veículos
```
GET    /api/vehicles       # Lista veículos
GET    /api/vehicles/{id}  # Busca veículo por ID
POST   /api/vehicles       # Cria novo veículo
PUT    /api/vehicles/{id}  # Atualiza veículo
DELETE /api/vehicles/{id}  # Remove veículo
```

### Motoristas
```
GET    /api/drivers        # Lista motoristas
GET    /api/drivers/{id}   # Busca motorista por ID
POST   /api/drivers        # Cria novo motorista
PUT    /api/drivers/{id}   # Atualiza motorista
DELETE /api/drivers/{id}   # Remove motorista
```

### Mecânicos
```
GET    /api/mechanics      # Lista mecânicos
GET    /api/mechanics/{id} # Busca mecânico por ID
POST   /api/mechanics      # Cria novo mecânico
PUT    /api/mechanics/{id} # Atualiza mecânico
DELETE /api/mechanics/{id} # Remove mecânico
```

### Viagens
```
GET    /api/trips          # Lista viagens
GET    /api/trips/{id}     # Busca viagem por ID
POST   /api/trips          # Cria nova viagem
PUT    /api/trips/{id}     # Atualiza viagem
DELETE /api/trips/{id}     # Remove viagem
```

### Manutenções Programadas
```
GET    /api/scheduled-maintenances       # Lista manutenções
GET    /api/scheduled-maintenances/{id}  # Busca manutenção por ID
POST   /api/scheduled-maintenances       # Cria nova manutenção
PUT    /api/scheduled-maintenances/{id}  # Atualiza manutenção
DELETE /api/scheduled-maintenances/{id}  # Remove manutenção
```

### Relatórios
```
GET  /api/reports          # Lista tipos de relatório disponíveis
GET  /api/reports/{type}   # Gera relatório (general, users, products)
```

## Licença

Projeto de estudos - uso livre. Desenvolvido com assistência de inteligência artificial e revisado por Luiz Eduardo.
