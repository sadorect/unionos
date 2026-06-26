# UnionOS — Phase 6 UI/State Wireframes (Text/Spec Form)

**Phase:** 6 — Authorization & Privacy States
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement visible privacy boundaries, access-denied states, and authorization-aware UI behavior without exposing sensitive system logic or confusing users.

---

## 0. Product Intent (Non-Negotiable)

You are building the **visible edge of the privacy system**.

This phase must:

* make access boundaries feel clear
* keep users from wandering into invalid states
* explain blocked actions calmly
* avoid leaking sensitive details through error messaging

This phase must NOT:

* reveal protected data through wording
* expose internal authorization logic
* create fear or suspicion
* produce generic broken-feeling “403” experiences

A denied action should feel intentional, not broken.

---

## 1. Global UI Rules

### Tone

* calm
* firm
* neutral
* privacy-respecting
* non-accusatory

### Layout

* max width: `max-w-3xl`
* centered state cards for blocked pages
* concise explanations
* one clear next action

### Language rules

Allowed:

* `not available`
* `not accessible`
* `not part of this space`
* `private`
* `protected`
* `return home`

Forbidden:

* `unauthorized access attempt`
* `security violation`
* `permission denied` as raw headline
* `forbidden`
* `you are not allowed to see your partner's...`

Do not teach users where the fences are in too much detail.

---

## 2. Screen Map for Phase 6

Implement exactly these reusable authorization-aware state pages/components:

1. `State/NotAvailable`
2. `State/PrivateContent`
3. `State/CoupleAccessRequired`
4. `State/ActiveCoupleRequired`
5. `State/InvalidResourceState`
6. `State/ExpiredInvite`
7. `State/AlreadyLinked`
8. `State/ProtectedHistory`

These may be full pages or shared state components rendered inside domain pages.

---

## 3. Screen Spec — Generic Not Available State

**File:** `resources/js/Pages/State/NotAvailable.vue`

### Route intent

Fallback screen for routes or resources that should not be accessible in current context.

### Layout

Use `CoupleLayout` if authenticated; otherwise minimal app shell.

### Page structure

#### State card

Heading: `This page isn’t available`
Subtext: `It may no longer be accessible in your current context.`

Actions:

* Primary: `Return home`
* Secondary: `Go back`

### Use cases

* invalid route state
* completed/archived resource no longer interactable
* safe fallback instead of raw framework error page

### UI constraints

* do not mention authorization explicitly
* do not mention resource existence if that is sensitive

---

## 4. Screen Spec — Private Content State

**File:** `resources/js/Pages/State/PrivateContent.vue`

### Route intent

Used when the user reaches an area that exists conceptually but is intentionally private to someone else.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This content is private`
Subtext: `Some parts of the app are only visible to the person who created them.`

Actions:

* Primary: `Return to my progress`
* Secondary: `Go home`

### Use cases

* partner attempts to access personal dashboard URL
* partner attempts to view another user’s weekly history route
* direct-link attempt to private personal content

### UI constraints

* do not say “your partner’s weekly history”
* do not confirm what exact resource exists
* keep wording broad and privacy-forward

---

## 5. Screen Spec — Couple Access Required

**File:** `resources/js/Pages/State/CoupleAccessRequired.vue`

### Route intent

User is authenticated but has no couple created yet.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `Create your couple space first`
Subtext: `This feature becomes available after you set up a couple space.`

Actions:

* Primary: `Create couple space`
* Secondary: `Return home`

### Use cases

* user tries to access couple-only routes before creating a couple
* dashboard/conversation/check-in routes accessed too early

### UI constraints

* do not mention partner yet if they have not entered the flow
* keep onboarding straightforward

---

## 6. Screen Spec — Active Couple Required

**File:** `resources/js/Pages/State/ActiveCoupleRequired.vue`

### Route intent

User has a couple record, but the couple is not active.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This feature isn’t available yet`
Subtext: `Your couple space needs to be active before you can use this feature.`

Optional helper line:
`Invite your partner to complete setup.`

Actions:

* Primary: `Go to couple setup`
* Secondary: `Return home`

### Use cases

* pending couple user tries to access weekly/monthly/conversation/dashboard routes
* dissolved couple user tries active-only features

### UI constraints

* do not scold
* do not frame as failure
* use this instead of cryptic redirects when clarity helps

---

## 7. Screen Spec — Invalid Resource State

**File:** `resources/js/Pages/State/InvalidResourceState.vue`

### Route intent

Resource exists but is no longer in a usable state for the attempted action.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This item can’t be used right now`
Subtext: `Its current state doesn’t allow this action.`

Actions:

* Primary: `Return home`
* Secondary: `Go back`

### Use cases

* trying to continue a completed conversation
* trying to edit a locked monthly check-in
* trying to reopen dissolved-couple activity

### UI constraints

* no internal status names like “403”, “invalid transition”, “state conflict”
* keep it user-readable

---

## 8. Screen Spec — Expired Invite

**File:** `resources/js/Pages/State/ExpiredInvite.vue`

### Route intent

Invite token is invalid, expired, or already used.

### Layout

Use guest-friendly layout if unauthenticated; otherwise standard app shell.

### Page structure

#### State card

Heading: `This invitation is no longer available`
Subtext: `It may have expired or already been used.`

Actions:

* Primary: `Return home`
* Secondary: `Ask for a new invitation`

### Use cases

* expired invite token
* consumed token
* malformed token route

### UI constraints

* do not reveal whether the couple still exists
* do not expose inviter data

---

## 9. Screen Spec — Already Linked

**File:** `resources/js/Pages/State/AlreadyLinked.vue`

### Route intent

User attempts to accept invite while already belonging to another couple state that prevents joining.

### Layout

Use `CoupleLayout` if authenticated.

### Page structure

#### State card

Heading: `You can’t join this invitation`
Subtext: `Your account is already linked to another couple space.`

Actions:

* Primary: `Return to my dashboard`

Optional secondary:

* `Go to couple settings`

### Use cases

* active couple user clicks another invite
* pending couple user attempts conflicting invite flow

### UI constraints

* no detailed cross-couple information
* no mention of other users

---

## 10. Screen Spec — Protected History

**File:** `resources/js/Pages/State/ProtectedHistory.vue`

### Route intent

Used when historical data exists but is intentionally not interactable or only partially accessible.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This history is protected`
Subtext: `Older records may remain visible in limited form, but new activity can’t be created here.`

Actions:

* Primary: `Return home`

### Use cases

* dissolved couple historical area
* archived read-only state
* protected legacy route

### UI constraints

* no mention of the partner’s data
* no promise of data export unless implemented

---

## 11. Inline Authorization UI Rules

Apply these across all phase pages.

### Hidden actions

Do not render action buttons the user cannot take.

Examples:

* hide `Start guided conversation` if couple not active
* hide `Edit check-in` if locked
* hide `Accept invite` if authenticated user is already linked elsewhere

### Disabled actions

Use disabled buttons only when the disabled state teaches something useful.

Examples:

* feature preview cards on active home before feature launch
* disabled “Begin conversation” until ground rules acknowledged

Do not rely on disabled buttons as your only protection.
Backend authorization still governs.

### Safe empty states

When content is filtered out due to privacy, do not imply missing content belongs to partner.

Bad:

* `Your partner's check-ins are hidden`

Good:

* `Only your own records appear here`

---

## 12. Route Fallback Behavior

When authorization fails, do not dump the user onto raw framework pages.

Preferred handling order:

1. Redirect to the safest valid page when obvious
2. Render a specific state page if user context benefits from explanation
3. Use generic `NotAvailable` as fallback

Examples:

* private personal dashboard access → `PrivateContent`
* pending couple using active-only feature → `ActiveCoupleRequired`
* invalid session transition → `InvalidResourceState`

---

## 13. Shared Component Additions (Phase 6)

Implement or extend:

* `StateCard`
* `StateIllustration` (minimal, optional)
* `ProtectedNotice`
* `InlinePrivacyHint`
* `UnavailableActionNote`

Component rules:

* generic and reusable
* no embedded domain logic
* do not hardcode sensitive nouns unless passed from page props

---

## 14. Copy Guardrails

Allowed:

* `private`
* `protected`
* `not available`
* `return`
* `set up`
* `active`

Forbidden:

* `forbidden`
* `blocked by security`
* `access violation`
* `your partner owns this`
* `you tried to access restricted data`

---

## 15. Agent UI Compliance Note

```text
Use this Phase 6 text/spec wireframe as the authoritative UI contract for privacy and authorization states.

Do not expose raw authorization logic to the user.
Do not reveal protected resource details through headings or subtext.
Do not rely on disabled buttons alone for access control.
Prefer calm explanation and safe redirection over raw error pages.
```

---

## 16. Completion Note

Phase 6 defines the visible layer of:

* privacy boundaries
* couple-state gating
* invalid state handling
* safe fallback rendering

Proceed to Phase 7 wireframes when instructed.
