# UTS KEPL 2026 — Agenda Kegiatan

Aplikasi pencatatan agenda kegiatan: **Laravel 12 (REST API + Sanctum)**, **Vue 3 + Axios**, **MySQL 8**, dijalankan dengan **Docker Compose**. Backend memakai **FrankenPHP** sebagai server aplikasi.

```
.
├── backend/                 # Laravel API (Dockerfile + FrankenPHP)
├── frontend/                # Vue 3 + Vite (Dockerfile multi-stage + Nginx)
├── postman/                 # Postman Collection
├── .github/workflows/ci.yml # CI (test + build) & CD (push image ke ghcr.io)
├── docker-compose.yml
└── .env.example
```

## Endpoint API

| Method | Endpoint | Token | Keterangan |
|---|---|---|---|
| POST | `/api/login` | – | Login, mengembalikan token |
| POST | `/api/logout` | ✔ | Hapus token aktif |
| GET | `/api/me` | ✔ | User yang login |
| GET | `/api/agendas` | ✔ | Tampil semua |
| POST | `/api/agendas` | ✔ | Tambah (201 / 422) |
| GET | `/api/agendas/{id}` | ✔ | Tampil satu |
| PUT | `/api/agendas/{id}` | ✔ | Ubah |
| DELETE | `/api/agendas/{id}` | ✔ | Hapus |

Tanpa token → **401**. Akun demo (dari seeder): `admin@agenda.test` / `password123`.

## Menjalankan dengan Docker

```bash
cp .env.example .env
# isi APP_KEY:  echo "base64:$(openssl rand -base64 32)"
docker compose up -d --build
```

- Frontend: http://localhost:8080
- API langsung: http://localhost:8000/api

## Alur data

Browser membuka Vue di container **frontend** (Nginx). Axios memanggil `/api/...`; Nginx meneruskannya ke `http://backend:8000` (nama service). Laravel di container **backend** (FrankenPHP) memvalidasi token Sanctum lalu membaca/menulis tabel `agendas` di container **db** (`DB_HOST=db`). Data MySQL disimpan di named volume `db_data`.

## Development tanpa Docker

```bash
cd backend && php artisan serve          # http://localhost:8000
cd frontend && cp .env.example .env && npm install && npm run dev
```
