# Desafio Backend Pleno - Soluct

Este projeto é uma API RESTful desenvolvida com Laravel 11 e PostgreSQL, utilizando autenticação via Laravel Sanctum.
A aplicação é dockerizada e segue uma estrutura modularizada, com separação por features e versionamento da API.

## 🧰 Tecnologias Utilizadas

- PHP 8.3+
- Laravel 11
- Laravel Sanctum
- PostgreSQL
- Docker + Docker Compose
- PHPUnit (testes)
- Postman (documentação e testes manuais)
- Estrutura modular por feature (`Auth`, `Task`, `TaskHistory`)
- Versionamento de API (v1)

## 🔧 Requisitos Funcionais

- ✅ Autenticação (Register, Login, Me, Logout)
- ✅ CRUD completo de Tarefas
- ✅ Histórico de alterações de Tarefas
- ✅ Filtros, paginação e ordenação de tarefas e históricos
- ✅ Cada usuário só acessa suas próprias tarefas e históricos
- ✅ Testes automatizados por feature
- ✅ Documentação Postman com variáveis de ambiente

## 📁 Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/Api/v1/
│   ├── Requests/Api/v1/
│   └── Resources/
├── Models/
├── Policies/
├── Services/
routes/
└── api.php
tests/
└── Feature/Api/v1/
    ├── Auth/
    └── Task/
```

## ▶️ Instruções para Executar

### 1. Clonar o Repositório

```bash
git clone https://github.com/seu-usuario/desafio-backend-soluct.git
cd desafio-backend-soluct
```

### 2. Rodar com Docker

```bash
cp .env.example .env
docker-compose up -d
docker exec -it soluct-app composer install
docker exec -it soluct-app php artisan key:generate
docker exec -it soluct-app php artisan migrate --seed
```

### 3. Testar API com Postman

Importe o arquivo `soluct-collection.json` no Postman.

**Ambiente:**

- Base URL: `http://localhost/api/v1`
- Variáveis de ambiente estão no próprio JSON da coleção.

### 4. Rodar Testes Automatizados

```bash
php artisan test
```

## 🧪 Cobertura de Testes

- ✅ AuthController (Register, Login, Logout, Me)
- ✅ TaskController (CRUD, filtros)
- ✅ TaskHistoryController (filtros, acesso)
- ✅ Policies (view, update, delete)
- ✅ Services (alterações com histórico)

## 🔐 Regras de Acesso

- Cada usuário só acessa e altera suas próprias tarefas e históricos.
- TaskPolicy controla as permissões.
- Toda rota protegida está sob `auth:sanctum`.

## 📌 Documentação e Coleção Postman

- Arquivo único: `soluct-collection.json`
- Contém requests e variáveis (`{{base_url}}`, `{{auth_token}}` etc.)

## 📌 Variáveis da API

| Nome          | Tipo   | Descrição                         |
|---------------|--------|-----------------------------------|
| `title`       | string | Título da tarefa                  |
| `description` | string | Descrição opcional                |
| `status`      | enum   | `pending`, `in_progress`, `completed`, `canceled` |
| `due_date`    | date   | Data de vencimento (opcional)     |

## 🔥 Exemplos de Filtros

### Tarefas (`GET /tasks`)
- `?status=completed`
- `?title=relatório`
- `?date_start=2024-01-01&date_end=2024-01-31`
- `?due_start=2024-01-01&due_end=2024-01-10`
- `?sort=title&direction=asc`

### Históricos (`GET /tasks/{task}/history`)
- `?field=status`
- `?date_start=2024-01-01`

---

## 🧑‍💻 Autor

**Lucas Felipe Freitas Lima**  

---