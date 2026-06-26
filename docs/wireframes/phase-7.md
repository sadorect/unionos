# UnionOS — Phase 7 UI/State Wireframes (Text/Spec Form)

**Phase:** 7 — System States & Edge-Case Behavior
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement clear, calm, state-aware UI for lifecycle transitions, locked periods, incomplete flows, and edge-case behaviors.

---

## 0. Product Intent (Non-Negotiable)

You are building the **visible behavior of lifecycle rules**.

This phase must:

* make unusual states understandable
* preserve trust when flows are interrupted
* prevent users from getting stranded
* reduce confusion around time-based locks and relationship-state changes

This phase must NOT:

* make interrupted flows feel like failure
* use punishing language
* imply blame
* allow stale UI to pretend an action is still available

The system must feel steady even when the user journey is messy.

---

## 1. Global UI Rules

### Tone

* calm
* clear
* stable
* non-judgmental
* non-dramatic

### Layout

* `max-w-3xl` for state pages
* card-first design
* one clear next action
* avoid dense explanation

### Language rules

Allowed:

* `paused`
* `closed`
* `no longer active`
* `available next period`
* `saved`
* `protected`

Forbidden:

* `failed`
* `abandoned by your partner`
* `expired because you didn’t...`
* `invalid transition`
* `state conflict`

Do not weaponize system state.

---

## 2. Screen Map for Phase 7

Implement exactly these state pages/components:

1. `State/CouplePending`
2. `State/CoupleDissolved`
3. `State/WeekClosed`
4. `State/MonthClosed`
5. `State/ConversationIncomplete`
6. `State/ConversationCompleted`
7. `State/SessionPaused`
8. `State/NoCurrentAction`
9. `State/ArchivedPeriod`

These may be full pages or domain-specific state screens where appropriate.

---

## 3. Screen Spec — Couple Pending State

**File:** `resources/js/Pages/State/CouplePending.vue`

### Route intent

Render when user belongs to a pending couple and tries to access shared features.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `Your couple space is still being set up`
Subtext: `Shared features will become available once your partner joins.`

Optional helper text:
`You can send or manage an invitation from your setup page.`

Actions:

* Primary: `Go to couple setup`
* Secondary: `Return home`

### UI constraints

* do not mention delay as user fault
* do not show countdowns or pressure

---

## 4. Screen Spec — Couple Dissolved State

**File:** `resources/js/Pages/State/CoupleDissolved.vue`

### Route intent

Render when user attempts to access active-only features after the couple has been dissolved.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This couple space is no longer active`
Subtext: `Shared features have been locked for this space.`

Optional helper line:
`You can create a new couple space if needed.`

Actions:

* Primary: `Create new couple space`
* Secondary: `Return home`

### UI constraints

* neutral wording only
* no consolation language
* no explanation of who triggered dissolution

---

## 5. Screen Spec — Week Closed

**File:** `resources/js/Pages/State/WeekClosed.vue`

### Route intent

Render when a weekly check-in window is closed.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This week’s check-in is closed`
Subtext: `Weekly check-ins are only available during the current check-in window.`

Display:

* week label
* status badge: `Closed`

Optional helper text:
`The next check-in will appear when the new week begins.`

Actions:

* Primary: `View my history`
* Secondary: `Return home`

### UI constraints

* no guilt language
* no “missed deadline” framing

---

## 6. Screen Spec — Month Closed

**File:** `resources/js/Pages/State/MonthClosed.vue`

### Route intent

Render when a monthly check-in period is closed and cannot be submitted or edited.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This month’s check-in is closed`
Subtext: `Monthly check-ins close automatically at the end of each period.`

Display:

* month label
* badge: `Closed`

Actions:

* Primary: `View latest insight` if safe and available
* Secondary: `Return home`

### UI constraints

* no “you missed it”
* no mention of partner behavior

---

## 7. Screen Spec — Conversation Incomplete

**File:** `resources/js/Pages/State/ConversationIncomplete.vue`

### Route intent

Render when a conversation session exists but was not completed and is no longer active.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This conversation wasn’t completed`
Subtext: `You can start a new guided conversation whenever you’re ready.`

Display:

* conversation pack name
* last known status badge: `Incomplete`

Actions:

* Primary: `Start a new conversation`
* Secondary: `Return to conversations`

### UI constraints

* do not frame as failure
* do not mention who left or stopped
* do not show transcript preview

---

## 8. Screen Spec — Conversation Completed

**File:** `resources/js/Pages/State/ConversationCompleted.vue`

### Route intent

Render when user tries to reopen or continue a completed conversation session in an active flow route.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This conversation is complete`
Subtext: `Completed sessions stay locked to preserve the conversation as it happened.`

Display:

* pack name
* completed date
* badge: `Completed`

Actions:

* Primary: `Return to conversations`
* Secondary: `Start another conversation`

### UI constraints

* no transcript access
* no reopen editing path

---

## 9. Screen Spec — Session Paused

**File:** `resources/js/Pages/State/SessionPaused.vue`

### Route intent

Render when a guided conversation session has been paused and can be resumed.

### Layout

Use focused `CoupleLayout`.

### Page structure

#### State card

Heading: `Conversation paused`
Subtext: `You can return and continue when you’re ready.`

Display:

* pack name
* paused timestamp
* badge: `Paused`

Actions:

* Primary: `Resume conversation`
* Secondary: `Return home`

Optional helper text:
`There’s no need to rush.`

### UI constraints

* no pressure to resume immediately
* no “unfinished” negative framing

---

## 10. Screen Spec — No Current Action

**File:** `resources/js/Pages/State/NoCurrentAction.vue`

### Route intent

Render when a user enters a feature route but there is currently nothing actionable there.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `Nothing to do here right now`
Subtext: `This area will update when a new action becomes available.`

Examples:

* no open weekly check-in
* no monthly insight yet
* no available next-step action

Actions:

* Primary: `Return home`

Optional secondary:

* `View history` if relevant

### UI constraints

* keep this generic
* avoid overexplaining availability logic

---

## 11. Screen Spec — Archived Period

**File:** `resources/js/Pages/State/ArchivedPeriod.vue`

### Route intent

Render when a user browses an old period that is preserved historically but is no longer editable.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This period is archived`
Subtext: `You can view preserved records here, but no new changes can be made.`

Display:

* week/month label
* badge: `Archived`

Actions:

* Primary: `View history`
* Secondary: `Return home`

### UI constraints

* no “expired” language if archive is normal
* preserve calm, record-like tone

---

## 12. Inline State Rules Across Existing Pages

Apply these to existing screens from Phases 1–5.

### Weekly Check-In page

If window closed:

* replace submit affordance with `WeekClosed` state or redirect there

### Monthly Insight page

If user submitted but month finalized without both responses:

* show `MonthClosed` or safe locked state
* do not imply what the partner did

### Conversation turn page

If session paused/completed/incomplete:

* do not render stale input controls
* redirect/render correct state page immediately

### Dashboard pages

If couple dissolved:

* hide active calls-to-action
* show read-only state or redirect per product rule

---

## 13. Shared Component Additions (Phase 7)

Implement or extend:

* `LifecycleStateCard`
* `PeriodBadge`
* `ResumeActionCard`
* `ArchivedNotice`
* `ClosedWindowNotice`

Component rules:

* generic
* props-driven
* usable across check-ins, conversations, and couple lifecycle states

---

## 14. Route/Rendering Behavior Guidance

Prefer one of these patterns consistently:

### Option A — dedicated state pages

Use for:

* paused sessions
* dissolved couple state
* invalid lifecycle state

### Option B — inline state cards inside domain pages

Use for:

* archived periods
* no current action
* closed week/month within familiar feature area

Do not mix patterns randomly.
Choose deliberately per feature family.

Recommended:

* full page for major lifecycle interruptions
* inline state for minor availability changes

---

## 15. Copy Guardrails

Allowed:

* `closed`
* `paused`
* `archived`
* `no longer active`
* `available later`
* `protected`

Forbidden:

* `timed out because you`
* `your partner abandoned`
* `you failed to complete`
* `broken session`
* `invalid state transition`

---

## 16. Agent UI Compliance Note

```text
Use this Phase 7 text/spec wireframe as the authoritative UI contract for lifecycle and edge-case states.

Do not let stale UI controls remain visible after a resource changes state.
Do not imply blame in any interrupted or closed-flow screen.
Do not expose partner-specific behavior through state messaging.
If uncertain, prefer calm preservation and safe redirection over complex explanation.
```

---

