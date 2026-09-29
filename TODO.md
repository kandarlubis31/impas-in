# IMPAS-IN — Progress Tracker & Rekomendasi

> **Progress:** 21/28 selesai (75%) | Terakhir: June 2026

---

## ✅ SELESAI

### 🔴 Critical Bugs (6/6)
- [x] **#1** TaskController `show()` method — diimplementasi + eager load creator, offers, acceptedOffer
- [x] **#2** Offer `extra_fee` cast `decimal:2` → `float`
- [x] **#3** Web register set `balance=0` & `reputation_score=0`
- [x] **#4** Backdoor `/login-test` — DIHAPUS
- [x] **#5** Validasi balance cukup sebelum complete task (API & Web)
- [x] **#6** `welcome.blade.php` — landing page dibuat

### 🟡 Security (4/4)
- [x] **#7** Sanctum token expiration: 1440 menit (24 jam)
- [x] **#8** Rate limiting `throttle:5,1` di `/api/register` & `/api/login`
- [x] **#9** Logout revoke **semua** token (`tokens()->delete()`)
- [x] **#10** Validasi user tidak bisa offer tugas sendiri di Web

### 🔵 Code Quality (9/12)
- [x] **#11** → same as #1
- [x] **#12** Komentar kotor di Offer.php dibersihkan
- [x] **#13** `orWhere` grouping pakai closure di DashboardController
- [x] **#14** UserFactory: age, balance, reputation_score
- [x] **#15** Pagination `.paginate(20)` + custom page names + meta di semua controller
- [x] **#16** `effort_level` — reputation multiplier (`REPUTATION_REWARD × effort_level`), filter `min_effort`/`max_effort` di API, badge Lv.X di UI
- [x] **#17** Extra fee di Web flow — input `Fee (Rp)` di form offer, tampilan `+RpX` di daftar offer
- [x] **#18** Try-catch error handling di **semua** 6 controller (ModelNotFoundException re-throw, Throwable → Log + 500/halaman error)
- [x] **#19** Hardcoded `10` → `Task::reputationForEffort()` method

### 🟢 Enhancements (2/6)
- [x] **#20** Tailwind CDN → Vite build — BELUM
- [x] **#21** Inconsistent messages — **100% Bahasa Indonesia** sekarang
- [x] **#22** Filter params — effort_level filter done (min/max), search & reward range belum
- [x] **#23** **Cancel Task** — implementasi penuh (API + Web + UI + all offers auto-rejected)
- [x] **#24** Repayment feature — BELUM
- [x] **#25** Search task — BELUM
- [x] **#26** Email notification — BELUM
- [x] **#27** Middleware isAdult — BELUM
- [x] **#28** Unit tests — BELUM

### 🔍 Audit Tambahan (ditemukan & difiks)
- [x] Web `sendOffer` update task ke `negotiation` (sebelumnya missing)
- [x] Null-safe `acceptedOffer?->helper` + check BEFORE transaction
- [x] Offer accept validasi status `pending`
- [x] Send offer validasi task status `open`/`negotiation`
- [x] Transaction model: `points` cast `integer`
- [x] Unused imports dibersihkan
- [x] Timezone `Asia/Jakarta`
- [x] Dashboard error flash message banner
- [x] `withInput()` preserve form values di catch block

---

## ⬜ BELUM SELESAI — Rekomendasi Prioritas

### 🔥 HIGH PRIORITY (langsung impact)

| # | Item | Kenapa Penting |
|---|------|----------------|
| **28** | **Unit tests** | 0 test = production risk tinggi. Minimal test: register, login, create task, complete, cancel. Bisa pakai `tests/Feature/` yang sudah ada. |
| **20** | **Tailwind via Vite (bukan CDN)** | CDN lambat di production + gak bisa tree-shaking. `npm install -D tailwindcss @tailwindcss/vite` → config `vite.config.js` → import di `app.css`. |
| **22b** | **Search task by title** | User gak bisa cari task spesifik. Tambah `?search=` param di `TaskController@index` dengan `->where('title', 'like', "%{$search}%")`. |

### 🟡 MEDIUM PRIORITY (feature completeness)

| # | Item | Detail |
|---|------|--------|
| **22c** | **Filter reward range** | Tambah `?min_reward=` & `?max_reward=` di `TaskController@index` |
| **24** | **Repayment feature** | Enum `repayment` & `penalty` sudah di migration, logic belum. Buat endpoint `POST /api/transactions/repay` untuk transfer poin antar user. |
| **27** | **isAdult middleware** | `User::isAdult()` sudah ada, bikin middleware yang ngecek umur ≥ 14 untuk akses fitur tertentu. |

### 🟢 LOW PRIORITY (nice-to-have)

| # | Item | Detail |
|---|------|--------|
| **26** | **Email notification** | Setup Laravel Mail + Mailtrap. Trigger: offer baru, offer diterima/ditolak, task selesai. |
| **16b** | **effort_level sort** | Tambah `?sort_by=effort_level&sort_dir=desc` di `TaskController@index` |

---

## 📋 State Terkini per Komponen

| Komponen | State |
|----------|-------|
| **Auth** (API + Web) | Register, login, logout, token auth — SOLID ✅ |
| **Task CRUD** | Create, read (index/show), filter, cancel — SOLID ✅ |
| **Offer** | Create, accept (with guards) — SOLID ✅ |
| **Complete flow** | Balance check, helper null check, transaction, reputation multiplier — SOLID ✅ |
| **Cancel flow** | Owner-only, status guard, auto-reject pending offers — SOLID ✅ |
| **Leaderboard** | Paginated, dual page names — SOLID ✅ |
| **Error handling** | Try-catch all 6 controllers, 404 preserved, DB errors → friendly message — SOLID ✅ |
| **Pagination** | All queries paginated, custom page names di multi-paginator pages — SOLID ✅ |
| **UI (Blade)** | Welcome, login, register, dashboard with error/success banners, effort badges, extra_fee display, cancel button — SOLID ✅ |
| **Language** | 100% Bahasa Indonesia — SOLID ✅ |
| **Timezone** | Asia/Jakarta — SOLID ✅ |
| **Testing** | BELUM ADA ❌ |
| **CSS build** | Masih CDN ❌ |
| **Search** | Belum ❌ |
| **Repayment** | Belum ❌ |
| **Email** | Belum ❌ |

---

*Last updated: June 2026 — Batch 7 (language consistency fix)*
