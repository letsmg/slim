# Slim Framework - Projeto de Estudos

Este é um projeto de estudos utilizando o **Slim Framework 4**, criado para explorar uma arquitetura de pastas organizada e funcional, com base na experiência do autor.

## Estrutura de Pastas

```
├── app/
│   ├── controllers/    # Controladores da aplicação
│   ├── models/         # Modelos de dados
│   ├── repositories/   # Camada de repositório (acesso a dados)
│   ├── services/       # Lógica de negócio
│   ├── policies/       # Regras de autorização
│   └── middlewares/     # Middlewares PSR-15
├── config/
│   ├── config.php      # Configurações (lê variáveis do .env)
│   └── routes.php      # Definição de rotas
├── public/
│   └── index.php       # Entry point da aplicação
├── resources/
│   ├── css/            # Estilos (Tailwind, etc.)
│   └── js/             # Frontend Vue.js
│       ├── components/ # Componentes Vue
│       ├── pages/      # Páginas Vue
│       ├── router/     # Vue Router
│       ├── store/      # Pinia stores
│       └── composables/ # Composables Vue
├── .env                # Variáveis de ambiente
└── composer.json       # Dependências PHP
```

## Libs Utilizadas

### PHP (Composer)

| Pacote | Versão | Descrição |
|--------|--------|-----------|
| `php` | >=8.3 | Versão mínima do PHP |
| `slim/slim` | ^4.0 | Micro-framework Slim |
| `slim/psr7` | ^1.8 | Implementação PSR-7 |
| `illuminate/database` | ^13.9 | Eloquent ORM |
| `phpunit/phpunit` | ^11.0 | Testes unitários (dev) |

### JavaScript (npm)

| Pacote | Descrição |
|--------|-----------|
| `vue` | Framework frontend Vue.js |
| `vue-router` | Roteamento SPA |
| `pinia` | Gerenciamento de estado |
| `typescript` | Superset tipado do JavaScript |
| `vite` | Bundler e dev server |
| `tailwind` | Framework CSS utility-first |
| `axios` | HTTP client |

## Como Rodar

```bash
# Instalar dependências PHP
composer install

# Instalar dependências frontend
npm install

# Iniciar servidor embutido do PHP
php -S localhost:8080 -t public/
```

## Licença

Projeto de estudos - uso livre.