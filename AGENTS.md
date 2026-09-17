# AGENTS.md — mukespi

## Project

Hospital management system (RS Airlangga) — Quality indicators, PPI surveillance, document/accreditation management, e-office (surat). **CodeIgniter 3** on **XAMPP 7.4** (MySQL, PHP).

## Stack

- **Framework**: CodeIgniter 3 (standard MVC in `application/`)
- **DB**: MySQL database `mukespi` — see `database.sql` and `database_surat.sql` for schema/seeds
- **Auth**: Custom session-based (`application/controllers/Auth.php`). `is_logged_in()` helper in `application/core/` gates protected controllers
- **Internal UI**: SB Admin 2 theme (`assets/vendor/`, `assets/css/sb-admin-2.min.css`)
- **Login/Register views**: Self-contained HTML with **inline CSS** — does not use SB Admin 2

## Key commands

```bash
# Local dev server (XAMPP)
# Apache + MySQL must be running. Site root: http://localhost/mukespi/

# DB import (if needed)
mysql -u root mukespi < database.sql
mysql -u root mukespi < database_surat.sql
```

No test suite, no linter, no build step, no CI pipeline.

## Structure

| Path | Purpose |
|---|---|
| `application/controllers/` | One controller per module (e.g. `Mutu_indikator.php`, `Surat.php`) |
| `application/views/auth/` | Login, register, blocked (403) — standalone pages, no shared template |
| `application/views/template/` | Shared header/footer for internal pages (SB Admin 2) |
| `assets/img/bg-auth.jpg` | Background image for auth pages (unused by current inline-CSS login) |
| `uploads/` | User-uploaded files (gitignored) |
| `sertifikat/` | Certificate generation module |

## Routing

- Default controller: `Auth` (login page)
- `404_override` → `auth/blocked`
- After login, redirect is controlled by `Auth::$redirect_map` — keyed by `?tujuan=` param from portal cards
- Normal login → `portal` landing page

## Conventions

- Views in `application/views/<module>/` — one folder per module
- Internal pages use `template/header.php` + `template/footer.php` includes
- Auth views (`auth/login.php`, `auth/register.php`) are **fully standalone** — no shared template
- Flash messages via `$this->session->flashdata('message')` with raw HTML alert divs
- `form_error()` for field-level validation display

## Gotchas

- **No `base_url()` in auth views** — login/register are self-contained with Google Fonts CDN, no `assets/` CSS references
- `database.php` is gitignored — must be configured locally
- `assets/scss/` contains legacy SCSS (SB Admin 2 style) — **not compiled/used** by the app currently
- `assets/css/style.css` is unrelated to the main app (QR scanner styling)
