# IMPAS-IN — Project Context

## Ringkasan
**IMPAS-IN** adalah platform barter bantuan berbasis poin ("hutang budi"). User bisa:
- Mempublish tugas yang butuh bantuan (dengan reward poin + effort level 1–5)
- Menawarkan bantuan ke tugas orang lain (dengan syarat + extra fee opsional)
- Menerima tawaran bantuan untuk tugas sendiri
- Menyelesaikan tugas → poin transfer & reputasi naik (berdasarkan effort level)
- Cancel tugas yang belum accepted

**Bahasa:** 100% Bahasa Indonesia (message, error, log, UI).

---

## ⚠️ Temuan Penting (baca dulu sebelum kerja)

1. **Tailwind Vite setengah jadi** — `vite.config.js`, `@tailwindcss/vite`, Tailwind 4 di `package.json`, dan `resources/css/app.css` (`@import 'tailwindcss'`) sudah disiapkan, tapi **semua view masih pakai CDN** (`<script src="https://cdn.tailwindcss.com">`) dan tidak ada directive `@vite`. Migrasi ke Vite belum selesai.
3. **Dua konfigurasi DB** — default dev: SQLite (`config/database.php`, `DB_CONNECTION=sqlite`); production/Docker: MySQL 8.0 (`docker-compose.yml` → database `impasin`). Pastikan `.env` konsisten.
4. **Error handling tidak 100% try-catch** — `AuthController@me` (API) dan `Web\AuthController@logout` tanpa try-catch. Sisanya sudah.
5. **Blade `$transactions` pakai `?? []`** — controller selalu kirim `$transactions`, tapi view sudah defensif.

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 12 (PHP ^8.2) |
| Auth API | Laravel Sanctum (token `auth_token`, expire 1440 menit / 24 jam) |
| Auth Web | Session (`config/session.php`, driver database) |
| Database | SQLite (dev default) / MySQL 8.0 (Docker) |
| Frontend | Blade + Tailwind CSS 4 **via Vite build** (`@vite(['resources/css/app.css','resources/js/app.js'])`) — CDN sudah dihapus |
| JS | Minimal (0,51 KB): IntersectionObserver animasi scroll di `app.js` — tanpa dependency eksternal |
| Tools | Docker + nginx (port host 8001), Composer, Vite |
| Testing | PHPUnit (skeleton default, belum ada test beneran) |

---

## Struktur Proyek

```
app/
├── Http/Controllers/
│   ├── Api/
│   │   ├── AuthController.php          # register, login, logout, me
│   │   ├── TaskController.php          # index, show, store, complete, cancel
│   │   ├── OfferController.php         # store, accept
│   │   └── LeaderboardController.php   # index (top reputation & balance)
│   └── Web/
│       ├── LandingController.php       # index → statistik real + kasus aktif + log kebaikan buat welcome
│       ├── AuthController.php          # showLogin, showRegister, register, login, logout
│       └── DashboardController.php     # index, storeTask, sendOffer, acceptOffer, completeTask, cancelTask
├── Models/
│   ├── User.php                        # tasks(), offers(), isAdult(); casts age/balance/reputation → int
│   ├── Task.php                        # REPUTATION_REWARD=10, reputationForEffort(); acceptedOffer()
│   ├── Offer.php                       # conditions → array, extra_fee → float
│   └── Transaction.php                 # points → int; sender(), receiver()
routes/
├── api.php      # ✅ TERDAFTAR di bootstrap/app.php (prefix /api, sebagian pakai auth:sanctum)
├── web.php      # welcome + guest auth + dashboard (auth)
└── console.php
bootstrap/app.php                      # web + api + commands + health (API aktif ✅)
database/
├── migrations/  # users, tasks, offers, transactions, personal_access_tokens, cache, jobs
├── factories/   # UserFactory (age 14–60, balance 0–100, reputation 0–50, password 'password')
└── seeders/     # DatabaseSeeder (Kandar Lubis + Budi Helper + 1 task)
resources/views/
├── welcome.blade.php          # Landing page: hero + bento stats + kasus aktif + log kebaikan + cara kerja + footer (animasi scroll ringan, Plus Jakarta Sans, ikon Lucide + Phosphor solid)
├── auth/login.blade.php       # Login split-screen (branding teal kiri + form kanan), error flash/validasi, autocomplete, ikon Lucide
├── auth/register.blade.php    # Register split-screen, per-field @error, autocomplete, novalidate, ikon Lucide
├── dashboard.blade.php        # Dashboard redesain: navbar teal, hero sapa, 4 stat cards, Minta Bantuan + @error, mutasi, feed + Bantu, tugas saya + badge status, aria-label, ikon Lucide + Phosphor solid
├── components/
│   └── icon.blade.php         # Component ikon inline SVG — <x-icon name="..." class="..."/> (22 ikon Lucide stroke currentColor + 3 ikon Phosphor fill viewBox 256 solid; aria-hidden + focusable=false, fallback handshake + Log::warning)
└── partials/
    └── head-assets.blade.php  # Asset head bersama: @vite + font Plus Jakarta Sans — @include('partials.head-assets') di 4 view (title/meta description tetap per-halaman)
config/
├── sanctum.php   # token expiration 1440 (24 jam)
└── app.php       # timezone Asia/Jakarta
infra/
├── docker-compose.yml  # app (php:8.2-fpm), webserver (nginx:alpine, 8001:80), db (mysql:8.0)
├── Dockerfile         # php:8.2-fpm + pdo_mysql, mbstring, bcmath, gd
└── nginx/conf.d/app.conf
```

---

## Alur Status

```
Task:   open → negotiation → accepted → completed
              ↘ cancelled (auto-reject semua offer pending)

Offer:  pending → accepted / rejected
```

---

## Rule Bisnis

| Rule | Implementasi |
|------|-------------|
| User tidak bisa offer tugas sendiri | `$task->creator_id === Auth::id()` → 403 / error flash |
| Hanya task owner bisa accept offer | `$task->creator_id !== Auth::id()` → 403 / redirect |
| Hanya task owner bisa complete task | guard owner + status `accepted` |
| Hanya task owner bisa cancel task | guard owner + status `open`/`negotiation` |
| Hanya task `open`/`negotiation` bisa dioffer/cancel | `in_array($task->status, ['open','negotiation'])` |
| Hanya offer `pending` bisa diaccept | `$offer->status !== 'pending'` → 400 |
| Balance harus cukup sebelum complete | `Auth::user()->balance < $points` → 400 |
| Helper tidak boleh null saat complete | `!$task->acceptedOffer?->helper` (null-safe) |
| Reputasi helper = effort_level × 10 | `Task::reputationForEffort($effortLevel)` |
| Accept offer → auto-reject offer lain | `Offer::where(task_id)->where(id, !=)->update(status: rejected)` |
| Cancel task → auto-reject offer pending | update massal di dalam `DB::transaction` |
| Offer pertama → task jadi `negotiation` | `$task->update(['status' => 'negotiation'])` |
| Transfer poin & reputasi via transaction | `DB::transaction` (task complete) |
| Registrasi | age min 14, `balance=0`, `reputation_score=0` |

---

## API Endpoints

> ✅ Semua endpoint **aktif** (prefix `/api`) — `routes/api.php` sudah didaftarkan di `bootstrap/app.php` via `withRouting(api: ...)`.

| Method | URI | Auth | Keterangan |
|--------|-----|------|------------|
| POST | `/api/register` | publik | `throttle:5,1`; body: name, email, password(min 8), age(min 14) → 201 + token |
| POST | `/api/login` | publik | `throttle:5,1`; body: email, password → token |
| GET | `/api/leaderboard` | publik | 2 paginator: `top_helpers` (reputasi), `top_creditors` (balance) |
| POST | `/api/logout` | sanctum | revoke **semua** token user |
| GET | `/api/user` | sanctum | profil user aktif |
| GET | `/api/tasks` | sanctum | list task `open`, filter `min_effort`/`max_effort`, paginate 20 |
| POST | `/api/tasks` | sanctum | body: title, description, effort_level(1–5), base_reward |
| GET | `/api/tasks/{id}` | sanctum | eager load creator, offers.helper, acceptedOffer.helper |
| POST | `/api/tasks/{id}/complete` | sanctum | owner-only; cek saldo + helper; transfer poin/reputasi |
| POST | `/api/tasks/{id}/cancel` | sanctum | owner-only; status open/negotiation; auto-reject offers |
| POST | `/api/tasks/{taskId}/offers` | sanctum | body: conditions(array max 3), extra_fee |
| POST | `/api/offers/{id}/accept` | sanctum | owner-only; auto-reject offer lain |

---

## Web Routes

| Method | URI | Middleware | Keterangan |
|--------|-----|------------|------------|
| GET | `/` | publik | Landing page (welcome) dengan statistik & kasus aktif real — LandingController@index |
| GET/POST | `/login` | guest | login session, name route `login` |
| GET/POST | `/register` | guest | register session (password_confirmation) |
| GET | `/dashboard` | auth | feed (open, bukan punya user), tugas saya, mutasi 10 terakhir |
| POST | `/tasks` | auth | publish tugas |
| POST | `/tasks/{taskId}/offer` | auth | kirim tawaran (condition required, extra_fee opsional) |
| POST | `/offers/{offerId}/accept` | auth | terima tawaran |
| POST | `/tasks/{taskId}/complete` | auth | konfirmasi selesai |
| POST | `/tasks/{taskId}/cancel` | auth | cancel tugas |
| POST | `/logout` | auth | logout + invalidate session |

Dashboard pagination: `feed_page` dan `mytasks_page` (nama halaman beda biar tidak bentrok).

---

## Skema Database (inti)

- **users**: name, email, password, age, balance (int, default 0), reputation_score (int, default 0), timestamps
- **tasks**: creator_id (FK users, cascade), title, description, effort_level (int, default 1), base_reward (int, default 0), status enum(`open`,`negotiation`,`accepted`,`completed`,`cancelled`) default open, deadline (nullable datetime)
- **offers**: task_id (FK, cascade), helper_id (FK users, cascade), conditions (json, nullable), extra_fee (decimal 10,2, default 0), status enum(`pending`,`accepted`,`rejected`) default pending
- **transactions**: from_user, to_user (FK users), points (int), type enum(`help`,`repayment`,`penalty`) — `repayment` & `penalty` **belum ada logikanya**
- **personal_access_tokens**: sanctum (expiration di config)

---

## Fitur yang Sudah Fix

- **Tailwind Vite selesai** — CDN `cdn.tailwindcss.com` dihapus dari 4 view, diganti `@vite(...)`; theme (warna primary teal, font Plus Jakarta Sans, shadow) dipindah ke `@theme` CSS di `app.css` (Tailwind 4 tidak support `tailwind.config` JS); semua CSS custom view pindah ke `app.css`; gradient pakai nama v4 `bg-linear-to-*`; `npm run build` sukses (CSS 45KB, manifest OK), terverifikasi render 4 halaman + teal #0f926e + font + 0 console error
- **Auth pages di-redesign** — login & register split-screen konsisten dengan landing (warm teal + Plus Jakarta Sans + AOS), per-field `@error`, `old()` preserve, `autocomplete`, AOS fallback `[data-aos]:not(.aos-init)` biar form tetap terlihat kalau CDN gagal
- **Fix bug validasi** — `catch (ValidationException) { throw $e; }` sebelum `catch (\Throwable)` di `register()`, `login()`, `storeTask()`, `sendOffer()` (sebelumnya ValidationException tertelan `\Throwable` → error validasi jadi flash generik)
- **Emoji → ikon Lucide inline** — semua emoji di 4 view (welcome/login/register/dashboard, total 56 pemakaian) diganti SVG stroke via component baru `resources/views/components/icon.blade.php` (`<x-icon name="..." class="..."/>`, 22 ikon dari lucide-static v1.30.0, `stroke=currentColor` ikut warna tema teal, `aria-hidden` + `focusable=false`, nama tak dikenal → fallback handshake + `Log::warning`); badge/stat/logo/step/alert/status semuanya pakai ikon; footer live indicator ganti bullet `●` jadi live-dot `animate-pulse`; 0 emoji tersisa di semua view (scan regex), `npm run build` sukses, render test 18/19 (satunya ambang jumlah SVG dashboard yang bervariasi ikut data — path ikon utama terverifikasi ter-render)
- **Ikon dekoratif → Phosphor fill (campuran, pengganti Flaticon)** — rencana Flaticon dibatalkan: website-nya diblokir di env ini (403 security filter) & free tier wajib atribusi penulis. Riset alternatif (CDN jsdelivr teruji langsung): Tabler/Heroicons/Phosphor/Iconoir/Bootstrap semuanya **MIT tanpa atribusi**; SVG Repo & Iconify API diblokir; Font Awesome free wajib atribusi (CC BY 4.0). Keputusan: **Lucide tetap untuk ikon kecil UI + Phosphor Icons (weight `fill`, MIT) untuk 3 ikon dekoratif besar** — `sprout-fill` (4 empty state: kasus aktif & log kebaikan di welcome, mutasi & feed di dashboard), `heart-fill` (CTA final landing), `clipboard-fill` (empty tugas saya, amber). Inline SVG di component `x-icon` (map `$iconsSolid`, viewBox 256, `fill=currentColor` ikut tema teal) — nol request eksternal tambahan; ikon kecil (footer heart, header clipboard-list) tetap Lucide; dibungkus badge lingkaran lembut (bg-primary-50 / bg-white/10 / bg-amber-50) biar kesan Flaticon-style. Lucide `sprout` entry dihapus dari map (dead code). Build OK, render welcome terverifikasi 3× svg viewBox-256, coverage 398/0 missing
- **Blok head → partial `head-assets`** — @vite + font Plus Jakarta Sans + AOS CSS diekstrak ke `partials/head-assets.blade.php`, di-@include 4 view (title/meta per-halaman tetap); juga dibersihkan `public/hot` stale yang bikin @vite render mode dev (sudah di .gitignore)
- **Bundle JS dipangkas** — axios (48,6 KB) dihapus dari `resources/js/bootstrap.js` (tidak dipakai view/manapun — verifikasi code search) & dari `package.json` (`npm uninstall`); bundle JS turun 48,616 → 1 byte; render 10/10 PASS
- **AOS CDN → animasi scroll ringan** — library AOS (CSS 14KB + JS 20KB dari unpkg) dihapus; pengganti: keyframes `aos-fade-up/down/left/right/zoom-in` di `app.css` + IntersectionObserver mini (~600 byte) di `app.js` (tambah class `aos-init` → `aos-animate` saat masuk viewport, set `animationDelay` dari `data-aos-delay`, threshold 0, unobserve setelah animate); anti-fail `[data-aos]:not(.aos-init)` tetap visible, `prefers-reduced-motion` dimatikan; 4 view: blok `<script aos.js>` + `AOS.init()` dihapus, `data-aos` attribute tetap; head-assets: link aos.css dihapus; bundle JS 0,51 KB, render 200 + coverage 395/0
- **nginx: kompresi + cache header** — `nginx/conf.d/app.conf`: gzip on (level 5, min 1024, types css/js/json/xml/svg, vary) untuk semua response; `location /build/` → `Cache-Control public, max-age=31536000, immutable` (asset Vite ber-hash) + `try_files =404`; location asset statis (gambar/font) → `expires 30d` + public; brotli disiapkan sebagai blok komentar (module ngx_brotli tidak built-in di nginx:alpine — perlu ganti image webserver untuk aktivasi); gzip_static dipertahankan
- **Audit CSS (46,9 KB raw / 8,6 KB gzip)** — breakdown: utilities Tailwind ~67% (395 class + variants: hover 1,9 KB, space-y 1,6 KB, backdrop-blur 1,2 KB), theme system ~25% (@layer theme 4,1 KB + @property 3,8 KB + :root 4,1 KB — wajar Tailwind 4), preflight base 3,6 KB, custom CSS ~2 KB. Temuan: class `dark:`/gray/blue berasal dari view pagination Laravel yang MEMANG dirender dashboard (`->links()`); `@source` pagination di-scope dari glob `*.blade.php` → `tailwind.blade.php` saja (bootstrap/semantic/simple views tidak pernah dirender; netral ukuran karena class Bootstrap bukan Tailwind utility — tetap lebih presisi). Kesimpulan: tidak ada sampah signifikan — CSS sudah ramping
- **Dashboard di-redesign** — konsisten dengan landing/auth: navbar putih teal, hero sapa, 4 stat cards (saldo/reputasi/feed/kasus saya), form Minta Bantuan + per-field @error, Riwayat Mutasi, Feed Bantuan (error condition tampil sekali), Tugas Saya + badge status berwarna, null-safe `acceptedOffer?->helper?->name`, aria-label di semua input, AOS fallback
- **Landing page baru** — `LandingController@index` + `welcome.blade.php` ditulis ulang: hero emosional, bento grid statistik real (member/poin/kasus/transaksi), kasus aktif + progress bar (offers_count), log kebaikan (feed transaksi), cara kerja 3 langkah, CTA, footer. Desain warm teal + Plus Jakarta Sans + AOS scroll animation. Data real dari DB, fallback aman kalau DB kosong/gagal. (Catatan: route `/` tidak lagi redirect ke dashboard saat sudah login)
- **API aktif** — `routes/api.php` terdaftar di `bootstrap/app.php` (`withRouting(api: ...)`, prefix `/api`), 12 endpoint tervalidasi via `php artisan route:list`
- CRUD task + complete + cancel (API & Web) dengan semua guard
- Offer flow (send + accept, auto-reject sisa offer, task → negotiation)
- Sanctum token 24 jam, rate limit login/register (5/menit), logout revoke semua token
- Register API & Web set balance=0 & reputation=0
- Backdoor `/login-test` sudah dihapus
- Eager loading di show/complete, null-safe `acceptedOffer?->helper`
- Reputasi multiplier effort level + filter `min_effort`/`max_effort` + badge Lv.X di UI
- Pagination di semua controller (custom page names)
- Try-catch di 6 dari 8 method berisiko (kecuali `me()` & web `logout`)
- Error/success flash banner di dashboard, `withInput()` preserve form
- Timezone Asia/Jakarta
- 100% Bahasa Indonesia

---

## Yang Belum Selesai (Prioritas)

| Prio | Item | Detail |
|------|------|--------|
| 🔥 | **Unit tests** | Belum ada test sama sekali (cuma skeleton PHPUnit) |
| 🟡 | Search task by title (`?search=`) | Belum |
| 🟡 | Filter reward range (`?min_reward=`/`?max_reward=`) | Belum |
| 🟡 | Repayment feature (`POST /api/transactions/repay`) | Enum ada, logic belum |
| 🟡 | Middleware `isAdult` | `User::isAdult()` ada, middleware belum |
| 🟢 | Email notification (Mailtrap) | Belum |
| 🟢 | Sort by effort_level (`?sort_by=&sort_dir=`) | Belum |

---

## Konvensi Kode

| Aspek | Konvensi |
|-------|----------|
| **Bahasa** | 100% Bahasa Indonesia — message, error, log, UI |
| **Response API** | JSON: `message`, `data`, `access_token` (auth), `meta` (paginated). Leaderboard pakai `status: success` |
| **Auth** | Sanctum token (API) + session (Web). API di bawah `auth:sanctum` |
| **Database** | SQLite default dev; MySQL via Docker. Migration kompatibel keduanya |
| **CSS** | Tailwind CDN saat ini → rencana Vite build |
| **PHP** | Min 8.2 (Laravel 12), null-safe `?->` |
| **Error handling** | `ModelNotFoundException` → re-throw (biar 404); `\Throwable` → `Log::error()` + pesan ramah (500 / flash) |
| **Log format** | `Log::error('Controller@method: ' . $e->getMessage())` |
| **Pagination** | `.paginate(20)` + custom page names utk multi-paginator |
| **Web flash** | `back()->with('success'|'error', ...)`, tambah `withInput()` di catch |
| **Transaksi DB** | `DB::transaction` untuk operasi yang ubah > 1 tabel |

---

## Cara Menjalankan (Ringkas)

```bash
composer install
cp .env.example .env   # (buat manual jika tidak ada; sesuaikan DB_CONNECTION)
php artisan key:generate
php artisan migrate --seed
npm install && npm run build   # WAJIB — views pakai @vite (manifest dibutuhkan saat serve)
php artisan serve             # atau: docker compose up -d (akses http://localhost:8001)
```

**Catatan:** kalau ubah class/view, jalankan `npm run build` lagi (atau `npm run dev` untuk hot reload).

Login test dari seeder: `kandar@test.com` / `password` atau `budi@test.com` / `password`.

---

*Last updated: August 2026 — ikon dekoratif besar pindah ke Phosphor Icons fill (MIT, campuran dgn Lucide; 6 titik: 4 empty state + CTA heart + empty tugas; badge lingkaran; 0 request tambahan); audit CSS: 46,9 KB raw (8,6 KB gzip) sudah efisien, `@source` pagination di-scope ke tailwind.blade.php; nginx app.conf: gzip on + Cache-Control immutable 1 tahun untuk /build/ + cache 30d asset statis (brotli siap via komentar); AOS CDN diganti animasi scroll ringan (keyframes CSS + IntersectionObserver 0,51 KB, nol dependency CDN); bundle JS dipangkas (axios 48,6 KB dihapus → 1 byte); blok head duplikat (vite + font) diekstrak ke partial `partials/head-assets.blade.php` (@include di 4 view; title/description tetap per-halaman); emoji di 4 view diganti ikon Lucide inline (component `x-icon`, 22 ikon, 0 emoji tersisa); Tailwind Vite migration SELESAI (CDN dihapus, theme ke @theme CSS); landing + auth + dashboard konsisten (warm teal, Plus Jakarta Sans); bug validasi ValidationException di-fix; `routes/api.php` aktif. Sisa: unit tests.*
