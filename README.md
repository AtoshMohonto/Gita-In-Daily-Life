# Gita in Daily Life

A modular PHP platform presenting wisdom from multiple Gita texts (not only the
Bhagavad Gita) and connecting it to everyday life situations — stress, fear,
anger, discipline, grief, and more.

> **Phase 1 of this project is implemented.** It covers the full architecture,
> database, authentication, and a complete working vertical slice: browse
> Gitas → chapters → verses, explore topics, use "What Are You Facing Today?",
> read mantras, search site-wide, see Today's Wisdom, and manage all of it
> from an admin panel. See **What's not built yet** below for what's deferred
> to later phases.

## Requirements

- PHP 8.0+ (built and tested against XAMPP's PHP 8.0.30)
- MySQL 8 / MariaDB (via XAMPP)
- PDO, mysqli, mbstring, openssl extensions (all bundled with XAMPP)
- No Composer / npm dependency — the app is dependency-free by design

## Installation (XAMPP / Windows)

1. Project already lives at `C:\xampp\htdocs\Gita-In-Daily-Life`.
2. Start Apache and MySQL in the XAMPP control panel.
3. Import the database (already done for this local setup, but to repeat from scratch):
   ```
   "C:\xampp\mysql\bin\mysql.exe" -u root < database\schema.sql
   "C:\xampp\mysql\bin\mysql.exe" -u root < database\seed.sql
   ```
4. Copy `.env.example` to `.env` and adjust DB credentials if needed (defaults
   match a stock XAMPP install: `root` with no password).
5. Visit **http://localhost/Gita-In-Daily-Life/public/** (the root
   `index.php` redirects here for convenience).
6. Admin panel: **http://localhost/Gita-In-Daily-Life/public/admin/login.php**
   - Seeded Super Admin: `admin` / `GitaAdmin@2026` — **change this password
     after first login** (there is no self-service password change screen
     yet in Phase 1; update the `users.password_hash` column directly with
     `password_hash()`, or wait for the Users admin module in a later phase).

## Why the URL includes `/public`

The spec's reference architecture assumes an Apache vhost whose document
root points at `/public`, keeping `/app`, `/database`, `.env`, etc. outside
the web root entirely. This XAMPP instance has no vhost configured, so the
whole project folder is technically reachable over HTTP. To keep the same
security posture without a vhost:

- Every folder other than `/public` (`app/`, `database/`, `storage/`,
  `languages/`, `views/`, `modules/`) carries its own `.htaccess` with
  `Require all denied`.
- The project-root `.htaccess` blocks `.env*`, `*.sql`, `*.md`, and
  `composer.*` specifically.
- If you later deploy with a real vhost pointed at `/public`, all of this
  still works — it's just now enforced twice (filesystem *and* HTTP).

## Folder structure

```
/app            core framework: config, PDO wrapper, router, base Model/Controller,
                the generic AdminModuleController CRUD engine, models, AIService stub
/database       schema.sql (full schema), seed.sql (demo content)
/languages      en.php, bn.php — UI string tables for the __t() helper
/modules        frontend controllers + routes.php (the URL map)
/views/frontend layout + page templates, including the reusable Wisdom Card
/public         web root: index.php (frontend front controller), assets/, uploads/,
                admin/ (login, dashboard, one folder per admin module)
/storage        logs
```

## Adding a new Gita (the workflow the whole architecture is built around)

1. Admin → Gitas → **+ Add Gita**. Fill in name, description, introduction,
   philosophy, historical context (marked as editor-provided content, not
   asserted historical fact), set status to `published` when ready.
2. Admin → Chapters → **+ Add Chapter**, pick the Gita, set chapter number.
3. Admin → Verses → **+ Add Verse**, pick Gita + Chapter, fill in Sanskrit /
   transliteration / translations / key teaching, and attach Topics,
   Situations and Teachings directly from the same form (multi-select).
4. Optionally add matching Teachings (Admin → Teachings) and Mantras (Admin →
   Mantras) and cross-link them to the same Situations.
5. New content appears on the frontend immediately once its status is
   `published` — no code changes required. This is the core promise of the
   architecture: **new scriptures are data, not code.**

### How the generic admin CRUD engine works

Every admin module (`public/admin/modules/{name}/`) is just:
- `config.php` — declares the table, model class, list columns, search
  columns, and a `fields` array (type: text / textarea / richtext / number /
  date / checkbox / select / relation / multiselect).
- `index.php` — three lines: bootstrap, an `Auth::requireRole()` check, and
  `(new AdminModuleController($config))->handle();`

`AdminModuleController` (`app/core/AdminModuleController.php`) handles list
+ search + pagination, the create/edit form (rendering the right input per
field type, including relational `<select>`s and many-to-many
`<select multiple>`s backed by pivot tables), slug generation/uniqueness,
CSRF, and activity logging — uniformly, for every module. Adding an 10th
module means writing one `config.php`, not three hand-built pages.

## Security notes

- All queries go through PDO prepared statements (`app/core/Model.php`);
  the only place table/column names are ever concatenated into SQL is from
  developer-authored config arrays (never request input), and even those are
  validated against an identifier whitelist regex.
- CSRF tokens on every state-changing form (`app/core/Csrf.php`).
- Passwords hashed with `password_hash()` / verified with `password_verify()`.
- Login lockout after `LOGIN_MAX_ATTEMPTS` failed attempts (`.env`-configurable).
- Session cookies are `HttpOnly`, `SameSite=Lax`, and `Secure` when served
  over HTTPS.
- `.env` holds all secrets; nothing is hardcoded. `APP_DEBUG=false` suppresses
  error display and logs to `storage/logs/php-error.log` instead.
- Uploads are not yet implemented (see below), so there is no file-upload
  attack surface yet in Phase 1.

## What's not built yet (intentionally deferred)

These were scoped out of Phase 1 to keep the first pass reviewable and
testable end to end, per the project spec's own "implement phase by phase"
instruction (§57/§58):

- Public user registration/login, and everything that depends on it:
  bookmarks, favorites, the reflection/journal feature.
- A Users / Roles / Permissions admin UI (roles and one Super Admin exist in
  the database via `seed.sql`; there's no screen to manage them yet).
- Media library upload UI (the `media` table exists; no upload screen yet).
- Learning Paths admin UI (table exists, unused).
- A separate SEO-meta admin screen (`seo_meta` table exists; in Phase 1,
  meta title/description are edited directly on each content form instead).
- Dark mode, an analytics dashboard, an activity-log *viewer* (logging
  itself is wired and populates `activity_logs`), the `/install` wizard,
  two-factor auth, and a REST API.

All of the above have their tables already in `database/schema.sql`, so
building them is additive, not a schema migration.

## Content integrity

Per the spec's content-integrity rule, verse and mantra pages visually
separate **Original Text**, **Translation**, **Commentary**, **Practical
Application**, and **Reflection** as distinct labeled sections
(`views/frontend/verses/show.php`). Seed content for Ashtavakra Gita and
Avadhuta Gita is a single well-known illustrative verse each, explicitly
marked as sample/demo content pending fuller scholarly-reviewed sourcing —
see the comment at the top of `database/seed.sql`.
