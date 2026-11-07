# IMPAS-IN

> **Bantu Dibantu, Hutang Budi Lunas!** — Platform barter bantuan berbasis poin.

## Tentang Project

IMPAS-IN adalah aplikasi web (Laravel) untuk **barter bantuan antar user** dengan sistem poin & reputasi. User bisa mem-publish tugas yang butuh bantuan (dengan reward poin + effort level 1–5), menawarkan bantuan ke tugas orang lain (dengan syarat + extra fee opsional), lalu menyelesaikan tugas untuk mendapatkan poin dan reputasi.

## Fitur Utama

- **Auth dual**: API token (Sanctum) + Web session (register/login/logout)
- **Task**: publish, lihat feed, filter effort level, cancel (auto-reject semua tawaran)
- **Offer**: kirim tawaran bantuan (syarat + extra fee), terima tawaran (auto-reject yang lain)
- **Complete flow**: transfer poin & reputasi (effort level × 10) via database transaction
- **Leaderboard**: top helper (reputasi) & top creditor (balance), paginated
- **Dashboard**: saldo, reputasi, riwayat mutasi, feed, tugas saya — 100% Bahasa Indonesia

## Tech Stack

- **Backend**: Laravel 12 (PHP ^8.2) + Sanctum
- **Database**: SQLite (dev default) / MySQL 8.0 (Docker)
- **Frontend**: Blade + Tailwind CSS (CDN, Vite config siap)
- **Infra**: Docker + nginx (port host 8001)

## Instalasi

```bash
composer install
cp .env.example .env        # buat manual jika belum ada; sesuaikan DB_CONNECTION
php artisan key:generate
php artisan migrate --seed  # seeder: kandar@test.com / budi@test.com (password)
npm install && npm run build
php artisan serve
```

Atau via Docker:

```bash
docker compose up -d        # akses http://localhost:8001
```

## Akun Test (Seeder)

| Role | Email | Password |
|------|-------|----------|
| User | `kandar@test.com` | `password` |
| Helper | `budi@test.com` | `password` |

## Struktur Utama

```
app/Http/Controllers/
├── Api/     # Auth, Task, Offer, Leaderboard (JSON + Sanctum)
└── Web/     # Auth, Dashboard (session + Blade)
app/Models/  # User, Task, Offer, Transaction
routes/      # web.php + api.php (⚠️ api belum didaftarkan di bootstrap/app.php)
resources/views/  # welcome, auth/*, dashboard
```

> **Catatan**: `routes/api.php` belum terdaftar di `bootstrap/app.php` — semua endpoint `/api/*` akan 404 sampai ditambahkan `api:` di `withRouting()`.

## Dokumentasi

- **`TODO.md`** — progress tracker & rekomendasi prioritas

---

## English

**IMPAS-IN** is a Laravel web application for bartering help between users, with a point and reputation system. A user publishes a task with a point reward and an effort level from 1 to 5; another user offers help, optionally with conditions and an extra fee, and earns points and reputation once the task is done.

Authentication works through both API tokens (Sanctum) and web sessions.

---

## Lisensi

MIT
