
# UnionOS — Phase 10 UI/State Wireframes (Text/Spec Form)

**Phase:** 10 — Observability, Failure Handling & Operational Fallbacks
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement minimal user-facing failure and recovery states for operational issues without exposing internal system details or sensitive telemetry.

---

## 0. Product Intent (Non-Negotiable)

You are building the **visible edge of operational resilience**.

This phase must:

* handle failures gracefully
* protect users from raw system errors
* preserve calm and trust during outages or failed actions
* offer recovery paths where possible

This phase must NOT:

* expose logs, stack traces, or internal error IDs unnecessarily
* leak sensitive system behavior
* make users feel blamed for technical problems
* over-explain infrastructure issues

The app should fail like an adult: calmly, clearly, and with dignity.

---

## 1. Global UI Rules

### Tone

* calm
* brief
* apologetic without being dramatic
* practical

### Layout

* `max-w-2xl` or `max-w-3xl`
* centered fallback cards
* one or two recovery actions max

### Language rules

Allowed:

* `something went wrong`
* `please try again`
* `not available right now`
* `we couldn’t complete that action`
* `return home`

Forbidden:

* `server exception`
* `database failure`
* `queue timeout`
* `policy trace`
* `stack trace`
* `fatal error`

No backend jargon.

---

## 2. Screen Map for Phase 10

Implement exactly these screens/components:

1. `State/ActionFailed`
2. `State/TemporarilyUnavailable`
3. `State/SaveInterrupted`
4. `State/LoadFailed`
5. `Shared/RetryActionBar`

These are minimal by design.

---

## 3. Screen Spec — Action Failed

**File:** `resources/js/Pages/State/ActionFailed.vue`

### Route intent

Render after a user-initiated action fails and cannot be completed inline.

### Layout

Use `CoupleLayout` if authenticated; otherwise minimal shell.

### Page structure

#### State card

Heading: `We couldn’t complete that action`
Subtext: `Please try again in a moment.`

Actions:

* Primary: `Try again`
* Secondary: `Return home`

Optional tiny note:
`Your existing saved information is still protected.`

### Use cases

* failed invite acceptance
* failed session start
* failed save with nonrecoverable response
* action aborted due to backend issue

### UI constraints

* no internal reason text
* no technical codes unless hidden under support tooling later

---

## 4. Screen Spec — Temporarily Unavailable

**File:** `resources/js/Pages/State/TemporarilyUnavailable.vue`

### Route intent

Render when a feature or page cannot currently load due to operational issues.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This feature isn’t available right now`
Subtext: `Please try again a little later.`

Actions:

* Primary: `Return home`
* Secondary: `Try again`

### Use cases

* service outage
* background dependency unavailable
* partial maintenance window
* essential query failed

### UI constraints

* no mention of maintenance unless explicitly true and intended
* keep this broad and stable

---

## 5. Screen Spec — Save Interrupted

**File:** `resources/js/Pages/State/SaveInterrupted.vue`

### Route intent

Render or show inline when user input may not have been fully saved.

### Layout

Usually inline banner or modal; full page only if necessary.

### Page structure

#### Banner/card

Heading: `Your changes may not have been saved`
Subtext: `Please review and try again.`

Actions:

* Primary: `Try again`
* Secondary: `Back`

### Use cases

* network interruption during form submit
* timeout during check-in submission
* intermittent save failure

### UI constraints

* do not falsely confirm save
* do not discard user-entered text without warning where recoverable

---

## 6. Screen Spec — Load Failed

**File:** `resources/js/Pages/State/LoadFailed.vue`

### Route intent

Render when a page or critical data set cannot be loaded safely.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `We couldn’t load this page`
Subtext: `Please try again.`

Actions:

* Primary: `Reload`
* Secondary: `Return home`

### Use cases

* dashboard query failure
* conversation session load failure
* monthly insight retrieval failure

### UI constraints

* no partially broken layouts if core data is absent
* fail whole section/page cleanly

---

## 7. Screen Spec — Retry Action Bar

**File:** shared component

### Route intent

Reusable inline retry affordance for recoverable actions.

### Structure

* short status line
* optional icon
* retry button

Example text:
`That didn’t go through. Please try again.`

### Use cases

* small recoverable save failures
* inline preference update failures
* non-critical refresh operations

### UI constraints

* keep compact
* do not overuse
* only for recoverable, user-safe retries

---

## 8. Failure Rendering Rules

### Rule A — No silent failure on writes

If save/create/update fails, the UI must clearly indicate that completion is uncertain.

### Rule B — Prefer preserving user input

If a form submission fails and data can be retained locally in component state, keep it visible.

### Rule C — No raw framework error pages

Catch and route to safe fallbacks or inline states where practical.

### Rule D — Do not invent certainty

Do not show `Saved` unless backend confirmation was received.

---

## 9. Shared Component Additions (Phase 10)

Implement or extend:

* `FailureStateCard`
* `RetryActionBar`
* `InlineFailureNotice`
* `SafeReloadButton`

Component rules:

* generic
* presentational
* reusable across forms and pages
* no direct telemetry logic in UI layer

---

## 10. Route/Rendering Guidance

These states do not always need dedicated routes.
Use whichever is more appropriate:

### Full-page states

Use for:

* load failure
* feature unavailable
* unrecoverable action failure

### Inline states

Use for:

* save interrupted
* retry action bars
* recoverable control update failures

Stay consistent within a feature family.

---

## 11. Copy Guardrails

Allowed:

* `try again`
* `not available right now`
* `couldn’t load`
* `couldn’t complete`
* `return home`

Forbidden:

* `system exception`
* `trace id`
* `database lock`
* `job failed`
* `contact admin immediately`

---

## 12. Agent UI Compliance Note

```text
Use this Phase 10 text/spec wireframe as the authoritative UI contract for user-facing failure and fallback states.

Do not expose technical error details.
Do not falsely confirm success after failed actions.
Prefer calm, clear recovery paths and preserve user-entered data where possible.
If uncertain, fail safely and simply.
```

---

