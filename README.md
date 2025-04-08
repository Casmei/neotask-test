# Neotask - Teste Técnico

Este repositório contém um projeto fullstack dividido em duas aplicações:

- **Backend:** Laravel (API RESTful)
- **Frontend:** Next.js (interface web)

Este projeto foi desenvolvido como parte de um **teste técnico da Neotask**.

---

## 🚀 Como rodar o projeto

### Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## 🔧 Backend (Laravel)

A API está containerizada com Docker e utiliza Laravel Sail para facilitar o setup local.

### Subindo a aplicação

```bash
cd backend
cp .env.example .env
docker compose up
```

---
## 🌐 Frontend (Next)

A interface está localizada na pasta frontend, utilizando Next.js com suporte a Server Actions.

### Subindo a aplicação

```bash
cd frontend
npm install --force
npm run dev

npx auth secret
```
---

### Acesso
Documentação da API: http://localhost:8080/docs/api
Frontend: http://localhost:3000

### 🧪 Tecnologias Utilizadas
- Backend
  - Laravel 12
  - PHPUnit
  - Postgres
  - Docker

- Frontend
  - Next.js 14+
  - Server Actions
  - React Hook Form + Zod
  - Material UI
  - Next Auth

### 🏁 Observações
Este projeto foi desenvolvido como parte de um desafio técnico da Neotask. O foco foi demonstrar organização de código, clareza arquitetural, e integração entre sistemas.

