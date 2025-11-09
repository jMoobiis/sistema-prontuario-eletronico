# Sistema de Prontuário Eletrônico

CRUD completo dividido em backend (Laravel) e frontend (Vue.js) para o gerenciamento de pacientes e atendimentos médicos em clínicas particulares. O sistema visa facilitar o acesso de profissionais de saúde aos prontuários e evoluções dos pacientes de forma prática e segura.

## Tecnologias Utilizadas

**Backend (Laravel):**

- PHP 8.2+
- Laravel 10
- MySQL
- API RESTful

**Frontend (Vue.js):**

- Vue.js 3 (Composition API)
- Axios
- Vue Router
- Vite

## Como Executar Localmente

### Backend --- Laravel

1. Clone o repositório:
```bash
git clone https://github.com/jMoobiis/Sistema-Prontuario-Eletronico-Backend.git
cd Sistema-Prontuario-Eletronico-Backend
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o arquivo .env:
```bash
cp .env.example .env
```

4. Gere a chave da aplicação:
```bash
php artisan key:generate
```

5. Execute as migrations:
```bash
php artisan migrate
```

6. Inicie o servidor:
```bash
php artisan serve
```
API disponível em http://localhost:8000/api

### 🖥️ Frontend --- Vue.js

1. Clone o repositório:
```bash
git clone https://github.com/jMoobiis/Sistema-Prontuario-Eletronico-Frontend.git
cd Sistema-Prontuario-Eletronico-Frontend
```

2. Instale as dependências:
```bash
npm install
```

3. Configure o arquivo .env com a URL da API:
```env
VITE_API_URL=http://localhost:8000/api
```

4. Inicie o servidor de desenvolvimento:
```bash
npm run dev
```
Aplicação disponível em http://localhost:5173

## Endpoints Principais

**Pacientes:**
- GET     /api/pacientes
- POST   /api/pacientes
- PUT    /api/pacientes/{id}
- DELETE /api/pacientes/{id}

**Atendimentos:**
- GET    /api/atendimentos
- POST   /api/atendimentos
- PUT    /api/atendimentos/{id}
- DELETE /api/atendimentos/{id}

## Planejamento no Azure DevOps

O planejamento do projeto foi realizado no **Azure DevOps Boards**, estruturando as tarefas em Features e Subtasks para o Frontend (Vue.js) e Backend (Laravel).

🔗 **Acesse o board completo:**  
[https://dev.azure.com/mobiglia/Sistema-Prontuario-Eletronico/_boards/board/t/Sistema-Prontuario-Eletronico%20Team/Epics](https://dev.azure.com/mobiglia/Sistema-Prontuario-Eletronico/_boards/board/t/Sistema-Prontuario-Eletronico%20Team/Epics)

## Autor

**João Victor Mobiglia Podenciano**

Ribeirão Preto - SP  
[linkedin.com/in/joao-mobiglia](https://linkedin.com/in/joao-mobiglia)  
joao.m.pode@hotmail.com
