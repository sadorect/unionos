# UnionOS — Security, Design & Compliance Assessment

**Date:** 2026-06-26  
**Stack:** Laravel 12 · Vue 3 · Inertia.js · Tailwind CSS · PostgreSQL  
**Scope:** Full-stack application audit covering security posture, design best practices, and enhancement recommendations.

---

## Table of Contents

1. [Security Assessment](#1-security-assessment)
   - 1.1 Critical Issues
   - 1.2 Medium Issues
   - 1.3 Low / Informational
   - 1.4 What Is Already Well-Secured
2. [Design & Architecture Assessment](#2-design--architecture-assessment)
   - 2.1 Backend Architecture
   - 2.2 Frontend Architecture
   - 2.3 Routing & Middleware
3. [Compliance Considerations](#3-compliance-considerations)
4. [Enhancement Recommendations](#4-enhancement-recommendations)
5. [Severity Summary Table](#5-severity-summary-table)

---

## 1. Security Assessment

### 1.1 Critical Issues

#### CRIT-01 — Health and Metrics Endpoints Are Publicly Unauthenticated

**File:** [`routes/web.php:30-31`](../routes/web.php)

```php
Route::get('/health/unionos', [SystemHealthController::class, 'show']);
Route::get('/metrics/unionos', [SystemMetricsController::class, 'show']);
```

These routes sit outside the `auth` middleware group. Depending on what they expose (DB connection status, queue depth, service versions, error counts), they can reveal infrastructure details useful to an attacker for reconnaissance.

**Recommendation:** Gate these routes with a shared secret token in the `Authorization` header, an IP allowlist middleware, or move them behind the `auth` guard with an admin-role check. At minimum, add a middleware that validates a secret key from an environment variable.

---

#### CRIT-02 — `SESSION_SECURE_COOKIE` Has No Default — Cookies May Be Sent Over HTTP

**File:** [`config/session.php:172`](../config/session.php)

```php
'secure' => env('SESSION_SECURE_COOKIE'),
```

When `SESSION_SECURE_COOKIE` is not set in `.env`, this resolves to `null`, which PHP/Laravel treats as falsy. The session cookie is then transmitted over plain HTTP, leaving it vulnerable to interception on any non-HTTPS connection.

**Recommendation:**

```php
'secure' => env('SESSION_SECURE_COOKIE', true),
```

Only override to `false` explicitly in local development `.env`. Never leave this unset in production.

---

#### CRIT-03 — No Content Security Policy (CSP) Headers

**File:** [`resources/views/app.blade.php`](../resources/views/app.blade.php)

The root Blade template has no CSP meta tag or middleware that sets `Content-Security-Policy` headers. Without CSP, the browser permits inline scripts, arbitrary third-party script injection, and cross-origin data exfiltration — all attack vectors relevant to an intimate relationship app holding sensitive personal data.

**Recommendation:** Add a CSP header via a dedicated middleware (e.g., `AddSecurityHeaders`), registered in the `web` middleware group. A baseline policy for this app:

```
Content-Security-Policy:
  default-src 'self';
  script-src 'self' 'nonce-{NONCE}';
  style-src 'self' https://fonts.bunny.net 'unsafe-inline';
  font-src 'self' https://fonts.bunny.net;
  img-src 'self' data:;
  connect-src 'self';
  frame-ancestors 'none';
  base-uri 'self';
  form-action 'self';
```

Inertia uses inline scripts for initial page props, so a nonce-based approach or `unsafe-inline` for scripts is needed — prefer nonce injection via the middleware.

---

### 1.2 Medium Issues

#### MED-01 — Session Data Is Not Encrypted at Rest

**File:** [`config/session.php:50`](../config/session.php)

```php
'encrypt' => env('SESSION_ENCRYPT', false),
```

Session data is stored unencrypted in the `sessions` PostgreSQL table. While the session ID cookie itself is signed, anyone with database read access can inspect raw session contents, which may include user state, flash messages, and any data shared via Inertia.

**Recommendation:** Set `SESSION_ENCRYPT=true` in production. Laravel handles encryption/decryption transparently using `APP_KEY`.

---

#### MED-02 — Rate Limiting Applies Only to IP, Not User Identity

**File:** [`app/Providers/AppServiceProvider.php:28-31`](../app/Providers/AppServiceProvider.php)

```php
RateLimiter::for('auth-sensitive', function (Request $request) {
    return Limit::perMinute((int) config('unionos.rate_limits.auth_sensitive'))
        ->by($request->ip());
});
```

Rate limiting by IP alone is bypassable via IP rotation or proxy networks. For authentication routes (login, password reset), limiting by `email|ip` combination is significantly more robust.

**Recommendation:**

```php
return Limit::perMinute(5)
    ->by($request->input('email') . '|' . $request->ip());
```

Also verify this limiter is actually applied to `auth.php` routes — currently it's defined but its application to specific auth routes is not visible in the scanned route files.

---

#### MED-03 — Ziggy Exposes Full Route Map to the Client

**File:** [`resources/views/app.blade.php:14`](../resources/views/app.blade.php)

```blade
@routes
```

The `@routes` Ziggy directive serialises every named route — including internal state and system routes — into the HTML for every page load. While routes are not secrets, exposing the complete map (including `/health/unionos`, `/metrics/unionos`, and all state routes) reduces friction for attackers mapping the application.

**Recommendation:** Use Ziggy's filter feature to expose only the routes the frontend actually needs to call:

```php
// config/ziggy.php or AppServiceProvider
'only' => [
    'home', 'login', 'logout', 'register',
    'couple.*', 'checkins.*', 'conversations.*',
    'dashboard.*', 'settings.*', 'invite.*',
],
```

---

#### MED-04 — IDOR Risk on `/users/{user}/progress`

**File:** [`routes/web.php:54`](../routes/web.php)

```php
Route::get('/users/{user}/progress', [DashboardController::class, 'personalForUser'])
    ->name('dashboard.personal.user');
```

This route accepts an arbitrary `{user}` model binding inside the `auth` middleware. Unless `DashboardController::personalForUser` explicitly checks that the authenticated user is either the target user or their couple partner, any authenticated user could view another user's personal progress data.

**Recommendation:** Verify the controller applies a policy check such as:

```php
$this->authorize('view', $user); // via UserPolicy
```

If not, add a `UserPolicy::view` gate that permits only `$currentUser->id === $user->id` or `$currentUser->couple->hasMember($user)`.

---

#### MED-05 — Missing Security Headers Beyond CSP

The application does not appear to set standard security response headers. These are low-effort, high-value additions.

| Header | Recommended Value |
|---|---|
| `X-Frame-Options` | `DENY` |
| `X-Content-Type-Options` | `nosniff` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=()` |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` |

**Recommendation:** Add a single `AddSecurityHeaders` middleware registered globally in the `web` group, covering all of the above and CSP (from CRIT-03).

---

#### MED-06 — `APP_DEBUG` Risk in Production

**File:** [`config/app.php:42`](../config/app.php)

The default resolves to `false` from config, which is correct. However, if `.env` contains `APP_DEBUG=true` (common during active development), deploying without changing this will expose full stack traces, SQL queries, and environment details on any unhandled error.

**Recommendation:** Add a boot-time assertion in `AppServiceProvider` that aborts if `APP_DEBUG=true` and `APP_ENV=production`:

```php
if (app()->isProduction() && config('app.debug')) {
    throw new \RuntimeException('APP_DEBUG must be false in production.');
}
```

---

### 1.3 Low / Informational

#### LOW-01 — Session `same_site` Is Set to `lax`, Not `strict`

**File:** [`config/session.php:202`](../config/session.php)

`lax` allows the session cookie to be sent on top-level cross-site navigations (e.g., links from external sites). `strict` prevents this entirely. Since UnionOS is a private relationship app with no need for cross-site entry flows, `strict` is the better default.

**Recommendation:** `SESSION_SAME_SITE=strict` in production `.env`.

---

#### LOW-02 — External Font Dependency in Critical Render Path

**File:** [`resources/views/app.blade.php:10-11`](../resources/views/app.blade.php)

```html
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
```

Fonts.bunny.net is a GDPR-friendly alternative to Google Fonts (good choice), but it still introduces a third-party dependency in the render-critical path. If the CDN is slow or unavailable, the page stalls on font loading.

**Recommendation:** Self-host the Figtree font using `fontsource`:

```bash
npm install @fontsource/figtree
```

```js
// resources/js/app.js
import '@fontsource/figtree/400.css';
import '@fontsource/figtree/500.css';
import '@fontsource/figtree/600.css';
```

This eliminates the external dependency, removes the `preconnect`, and speeds up initial render.

---

#### LOW-03 — Public Invite Accept Page Has No Rate Limiting

**File:** [`routes/web.php:21`](../routes/web.php)

```php
Route::get('/invite/{token}', [CoupleInviteController::class, 'show'])
    ->name('invite.accept.show');
```

The GET route for invite display and the POST route for invite acceptance are publicly accessible without rate limiting. While the token provides obscurity, an attacker could enumerate token patterns or brute-force short tokens.

**Recommendation:** Apply the `throttle` middleware, or the `auth-sensitive` rate limiter, to both the show and accept invite routes.

---

#### LOW-04 — `expire_on_close` Is Disabled

**File:** [`config/session.php:37`](../config/session.php)

Sessions persist for 120 minutes regardless of browser close. For a sensitive relationship app, sessions surviving browser close is a minor privacy concern (shared devices).

**Recommendation:** Consider setting `SESSION_EXPIRE_ON_CLOSE=true` or offering it as a user preference ("Stay logged in") on the login form.

---

### 1.4 What Is Already Well-Secured

- **CSRF protection** is active via Laravel's built-in `VerifyCsrfToken` middleware on all `web` routes.
- **Authorization policies** cover every primary resource: `CouplePolicy`, `CoupleInvitePolicy`, `WeeklyCheckInPolicy`, `MonthlyCoupleCheckInPolicy`, `ConversationSessionPolicy`, `ConversationTurnPolicy`. This is thorough.
- **Bcrypt at 12 rounds** is a strong password hashing configuration.
- **`couple.active` middleware** correctly gates all emotionally sensitive features behind verified relationship status.
- **Invite TTL** of 72 hours (configurable) prevents indefinitely valid invite links.
- **Inertia shared data** is minimal and intentional — only `id`, `name`, and `email` are exposed, not the full user model.
- **AES-256-CBC encryption** is configured for the application key cipher.
- **Fonts.bunny.net** over Google Fonts is a privacy-conscious font CDN choice.
- **Database session driver** prevents session data from living in writable filesystem directories.

---

## 2. Design & Architecture Assessment

### 2.1 Backend Architecture

**Strengths:**

- **Domain-Driven Design (DDD)** is well-applied. Splitting into `Couple`, `CheckIn`, `Conversation`, `Dashboard`, `Notification`, and `System` domains with their own models, services, and policies is clean and scalable.
- **Action classes** (`app/Actions/`) correctly separate complex business operations from controller logic. Controllers stay thin.
- **Custom config files** (`unionos.php`, `unionos_content.php`) isolate domain-specific configuration cleanly instead of burying it in `.env` or inline code.
- **Event-driven architecture** (Events + Listeners) is present, suggesting good separation for side effects like notifications.
- **Phase-oriented migration structure** (phase timestamps) is unconventional but gives traceability to development milestones.

**Concerns:**

- The `System` domain controllers (`/health`, `/metrics`) appear to live inside the web application rather than as a separate internal service. In a production deployment, these should be firewalled or proxied differently from user-facing routes.
- The `NotificationEligibilityService` and `NextStepNudgeService` being in a `Notification` domain that also owns `NotificationPreference` is slightly wide. Consider separating nudge logic into its own domain if it grows.

---

### 2.2 Frontend Architecture

**Strengths:**

- **Inertia.js** is the right choice here — it avoids a separate API layer while preserving Vue's component model, which is appropriate for a server-authoritative relationship app.
- **State pages** (22 dedicated `State/*.vue` components) show deliberate UX design. Separating error states from feature states prevents 404/500 from bleeding into the user experience.
- **Component hierarchy** is logical: `Layouts/` → `Pages/` → `Components/` with domain-grouped components under `Components/`.
- **Vite prefetch** (`Vite::prefetch(concurrency: 3)`) improves perceived load performance for subsequent navigations.
- **Progress bar** colour matches the brand (`#0f766e` — teal) — small but considered UX detail.

**Concerns:**

- The root Blade template (`app.blade.php`) has **no `<meta name="description">`**, **no Open Graph tags**, and **no favicon**. Even for a private app, these are important for browser tab identity and link sharing.
- **No `lang` attribute** fallback is visible beyond the dynamic one in `<html lang="{{ ... }}">` — this is fine but deserves verification that locale is always set.
- No **loading state components** are visible at the layout level. Inertia's progress bar covers navigation, but individual form submissions may need spinner/disabled state patterns standardized across forms.

---

### 2.3 Routing & Middleware

**Strengths:**

- Three-layer middleware nesting is clean: global → `auth` → `couple.active`.
- Route names are consistent and predictable (`couple.*`, `checkins.*`, `conversations.*`).
- RESTful conventions are followed correctly (`GET /resource`, `POST /resource`, `DELETE /resource`).

**Concerns:**

- `GET /state/*` routes serve purely informational pages but some are inside `auth` and some are outside — this is intentional, but means unauthenticated users hitting auth-gated state routes get redirected to login rather than seeing a meaningful error. Consider whether state routes like `state.action-failed` should always be publicly accessible with minimal information.
- `GET /conversations` and `GET /conversations/packs` both map to `ConversationController@index` (duplicate route, lines 83-84). The `/conversations` alias could be removed to avoid ambiguity.

---

## 3. Compliance Considerations

UnionOS handles **intimate relationship data** — emotional check-ins, conversation responses, alignment scores, personal progress tracking. This places it in a sensitive personal data category under most privacy frameworks.

### GDPR / Privacy

| Requirement | Current State | Gap |
|---|---|---|
| Data minimisation | Inertia shares minimal user data (id, name, email) | ✓ Good |
| Right to erasure | Couple dissolution exists | Unclear if it purges all associated data |
| Data retention policy | No visible retention limits on check-ins or conversations | ✗ Missing |
| Privacy notice | No visible privacy policy link in templates | ✗ Missing |
| Consent for notifications | Preference system exists (NotificationPreference) | ✓ Good |
| Notification hours | Restricted to 8am–9pm with cooldowns | ✓ Good |
| Third-party data processors | fonts.bunny.net; SMTP provider | Needs privacy policy reference |

**Recommendation:** Add a privacy policy page and link it from the auth layout footer. Implement a data retention schedule for check-in and conversation data (e.g., archive after 12 months, purge after 36 months, configurable via `unionos.php`).

### Cookie Compliance

The session cookie is the only cookie set. It is `HttpOnly` (good) and `SameSite=lax`. If the `Secure` flag is properly enforced in production (see CRIT-02), the cookie posture will be compliant. No analytics, tracking, or advertising cookies are present — this is excellent for a private emotional health app.

---

## 4. Enhancement Recommendations

These are prioritised by design intent alignment — improvements that extend what the app is already trying to do.

### ENH-01 — Add Relationship Lifecycle Progress Indicator

The app tracks weekly and monthly check-ins and conversations, but there's no persistent visual indicator of "relationship health trajectory" across sessions. Given the `SparklineChart` and `TrendCard` components already exist, a summary widget on the home page showing streak/momentum would reinforce the core engagement loop.

**Suggested:** A `RelationshipMomentumCard` on `ActiveHome.vue` driven by existing dashboard data, showing: current streak, last alignment score, next suggested action.

---

### ENH-02 — Couple Invitation UX — QR Code Support

The invite flow uses token-based links. Adding a QR code render for the invite URL would significantly lower friction for in-person setup (both partners present). Libraries like `qrcode` (npm) can generate this client-side without a server dependency.

---

### ENH-03 — Offline / Poor Connection Resilience

Conversation sessions and check-in forms are critical paths where a dropped connection mid-submission causes data loss. The `SaveInterrupted` state page exists, but prevention is better.

**Suggested:**
- `localStorage` draft persistence for check-in forms using a Vue composable (`useDraftPersistence`).
- Optimistic UI with retry on the conversation turn submission.
- The `RetryActionBar` component already exists — wire it to local draft recovery.

---

### ENH-04 — Accessibility Audit (WCAG 2.1 AA)

No explicit ARIA roles, landmark regions, or focus management are visible in the scanned component files. Given that the app handles emotionally sensitive tasks (check-ins, conversations), accessibility is important.

**Minimum actions:**
- Add `role="main"` to page content wrappers in layouts.
- Ensure `Modal.vue` traps focus and has `aria-modal="true"` with a labelled `aria-labelledby`.
- Add `aria-live="polite"` to `FlashBanner.vue` so screen readers announce flash messages.
- Verify `ScaleSelector.vue` (used for check-in ratings) is keyboard-navigable with proper `aria-valuemin/max/now`.

---

### ENH-05 — Stale Conversation Detection UX

`UNIONOS_CONVERSATION_STALE_HOURS` (default: 24) is configured but there's no visible user-facing prompt when a session is approaching staleness. Users may return to a paused conversation unaware it's about to expire.

**Suggested:** A `ConversationTimer` warning state (the component already exists) that activates at 80% of stale window and shows a gentle notice: "This session will close in X hours if not resumed."

---

### ENH-06 — Notification Timezone Coverage

`unionos.php` lists 6 hardcoded timezones for notifications. This will miss users in Europe/continental zones, Asia-Pacific, and Latin America.

**Recommendation:** Replace the static list with PHP's `DateTimeZone::listIdentifiers(DateTimeZone::ALL)` filtered to major region groups, or use the `jenssegers/date` / Carbon timezone list. Present them grouped by region in the settings UI.

---

### ENH-07 — Meta Tags and Favicons in Root Template

**File:** [`resources/views/app.blade.php`](../resources/views/app.blade.php)

Add to `<head>`:

```html
<meta name="description" content="UnionOS — A private space for couples to grow together.">
<meta name="theme-color" content="#0f766e">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
```

Also consider Open Graph tags for clean link previews when sharing the app URL:

```html
<meta property="og:title" content="UnionOS">
<meta property="og:description" content="A private space for couples to grow together.">
<meta property="og:type" content="website">
```

---

### ENH-08 — Add a `robots.txt` and Structured `sitemap.xml`

No `robots.txt` is present in `public/`. For a private app, you want:

```
# public/robots.txt
User-agent: *
Disallow: /
```

This prevents all crawlers from indexing the application, protecting user privacy.

---

## 5. Severity Summary Table

| ID | Title | Severity | Effort to Fix |
|---|---|---|---|
| CRIT-01 | Health/metrics endpoints unauthenticated | Critical | Low |
| CRIT-02 | Session cookie `secure` flag has no default | Critical | Trivial |
| CRIT-03 | No Content Security Policy headers | Critical | Medium |
| MED-01 | Session data unencrypted at rest | Medium | Trivial |
| MED-02 | Rate limiting IP-only on auth routes | Medium | Low |
| MED-03 | Ziggy exposes full route map | Medium | Low |
| MED-04 | Potential IDOR on `/users/{user}/progress` | Medium | Low |
| MED-05 | Missing standard security headers | Medium | Low |
| MED-06 | `APP_DEBUG` production guard missing | Medium | Trivial |
| LOW-01 | `same_site` should be `strict` | Low | Trivial |
| LOW-02 | External font CDN in critical render path | Low | Low |
| LOW-03 | No rate limiting on invite routes | Low | Low |
| LOW-04 | Sessions don't expire on browser close | Low | Trivial |
| ENH-01 | Relationship momentum home widget | Enhancement | Medium |
| ENH-02 | QR code for couple invitations | Enhancement | Low |
| ENH-03 | Offline/draft persistence for forms | Enhancement | Medium |
| ENH-04 | WCAG 2.1 AA accessibility pass | Enhancement | High |
| ENH-05 | Stale conversation expiry warning | Enhancement | Low |
| ENH-06 | Expand notification timezone coverage | Enhancement | Low |
| ENH-07 | Meta tags and favicon in root template | Enhancement | Trivial |
| ENH-08 | `robots.txt` blocking all crawlers | Enhancement | Trivial |

---

*Assessment performed on codebase state as of 2026-06-26. Re-run after implementing fixes to verify posture.*
