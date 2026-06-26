# UnionOS — Deployment Readiness Report

**Date:** 2026-06-26  
**Local test environment:** PHP 8.2.12 · Node 20.19.6 · PostgreSQL 16 (Docker)  
**Test results:** 64 passed, 0 failed (384 assertions)

---

## Local Dev Smoke Test Summary

| Check | Result |
|---|---|
| PHP artisan serve | ✓ Running |
| PostgreSQL connection | ✓ Connected |
| All migrations | ✓ Ran (9 migrations) |
| npm install + build | ✓ Clean (0 vulnerabilities after `npm audit fix`) |
| Security headers on every response | ✓ CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy |
| Session cookies on HTTP (local) | ✓ Set correctly with `samesite=lax`, no `secure` flag (local .env override) |
| Health endpoint without token | ✓ Returns 503 (blocked) |
| Health endpoint with wrong token | ✓ Returns 401 (rejected) |
| robots.txt | ✓ `Disallow: /` |
| Registration (with captcha) | ✓ Creates user, lands on `Couple/Create` |
| Login (with captcha) | ✓ Authenticates, session established |
| Post-login routing | ✓ Redirects new users to couple setup |
| Full test suite | ✓ 64/64 passed |

---

## Actions Still Required Before Going Live

### 1. Set Production `.env` Values

Copy `.env.example` → `.env` on the VPS, then set:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=           # generate: php artisan key:generate

# Database
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=unionos
DB_USERNAME=unionos
DB_PASSWORD=<strong-password>

# Session — production values (different from local .env)
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# System endpoint protection — generate: openssl rand -hex 32
UNIONOS_SYSTEM_TOKEN=<generated-token>

# Mail — configure your SMTP provider
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### 2. Nginx / Caddy Configuration

Point the web root to `public/`. Example Caddy block:

```
yourdomain.com {
    root * /var/www/unionos/public
    php_fastcgi unix//run/php/php8.2-fpm.sock
    file_server
    encode gzip
}
```

For Nginx, use a standard Laravel vhost (document root = `public/`, try_files → `index.php`).

### 3. Run These Commands on First Deploy

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate          # if APP_KEY is blank
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install && npm run build      # build assets
```

### 4. Queue Worker

The app uses `QUEUE_CONNECTION=database` for notifications. Start a persistent worker:

```bash
php artisan queue:work --queue=default --tries=3 --sleep=3
```

Use Supervisor to keep it alive:

```ini
[program:unionos-worker]
command=php /var/www/unionos/artisan queue:work --queue=default --tries=3
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/unionos-worker.log
```

### 5. Scheduler

Add a cron entry to run the Laravel scheduler (advances system state, dispatches notifications):

```cron
* * * * * www-data cd /var/www/unionos && php artisan schedule:run >> /dev/null 2>&1
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. File Permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 8. Add `UNIONOS_SYSTEM_TOKEN` to Your Monitoring Tool

If you use UptimeRobot, Grafana, or any uptime checker, configure it to send the token:

```
Authorization: Bearer <your-UNIONOS_SYSTEM_TOKEN>
```

Endpoint: `https://yourdomain.com/health/unionos`

Expected response: `{"status":"ok","checks":{...}}`

---

## Local Dev Notes

The local `.env` intentionally overrides secure-by-default settings to work over plain HTTP:

```dotenv
SESSION_ENCRYPT=false         # true in production
SESSION_SECURE_COOKIE=false   # true in production
SESSION_SAME_SITE=lax         # strict in production
APP_DEBUG=true                # false in production
```

These overrides must NOT be copied to the production `.env`.

---

## Remaining Items (Non-Blocking for Launch)

These do not block deployment but should be addressed post-launch:

| Item | Notes |
|---|---|
| Favicon (`/favicon.svg`, `/apple-touch-icon.png`) | Linked in HTML but files not yet created in `public/` |
| Privacy policy page | Referenced in assessment; required for GDPR compliance |
| Data retention policy | Define archive/purge schedules for check-ins and conversations |
| WCAG 2.1 AA audit | Modal, FlashBanner, ScaleSelector need ARIA review |
| Notification timezone expansion | Currently limited to 6 hardcoded timezones |
| Nonce-based CSP | Upgrade from `unsafe-inline` once Ziggy supports nonce injection |

---

## npm Vulnerabilities Fixed

`npm audit fix` resolved all 11 vulnerabilities found in the initial install:

- `axios` — 10 advisories fixed (SSRF, prototype pollution, header injection)
- `esbuild` — arbitrary file read on Windows dev server (fixed)
- `follow-redirects` — auth header leak on cross-domain redirect (fixed)
- `form-data` — CRLF injection (fixed)
- `lodash-es` — code injection via template (fixed)
- `picomatch` — ReDoS (fixed)
- `postcss` — XSS via CSS stringify (fixed)
- `qs` — DoS via null entries (fixed)
- `shell-quote` (via `concurrently`) — newline injection (fixed)
- `vite` — path traversal + `server.fs.deny` bypass (fixed)

**Result: 0 vulnerabilities remaining.**
