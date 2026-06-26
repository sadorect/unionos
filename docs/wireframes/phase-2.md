# UnionOS — Phase 2 Inertia Wireframes (Text/Spec Form)

**Phase:** 2 — Weekly Private Check-Ins
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Purpose:** Define the exact screens, states, layout behavior, and UI rules for weekly private reflection.

## Phase 2 product intent

This feature must feel like:

* a calm weekly pause
* safe enough for honesty
* light enough to complete
* private enough to trust

This feature must **not** feel like:

* therapy homework
* performance scoring
* a social update
* something your partner can inspect later

---

# Global UI principles for Phase 2

## Tone

* Reflective
* Calm
* Non-judgmental
* Private
* Gentle, but not gushy

## Layout rules

* Max content width: `max-w-2xl` for form pages
* `max-w-4xl` for private history page
* One main task per screen
* No multi-step wizard unless absolutely needed
* Prefer a single, calm scroll flow for weekly check-in

## Privacy rules in UI

The UI must repeatedly reinforce:

* this is private
* your partner cannot see raw answers
* only safe trends may later be derived

Do this subtly. Not every paragraph needs a privacy sermon.

## Navigation rules

Authenticated top nav may now include:

* Home
* Weekly Check-In
* User menu/logout

Still no sidebar required yet.

---

# Screen Map for Phase 2

Phase 2 should include exactly these screens/pages:

1. `CheckIn/WeeklyForm`
2. `CheckIn/WeeklySubmitted`
3. `CheckIn/WeeklyLocked`
4. `CheckIn/WeeklyHistory`

Optional support partial/state:

* `CheckIn/NoActiveCoupleState` only if middleware doesn’t hard redirect

---

# 1. Screen Spec — Weekly Check-In Form

**File:** `resources/js/Pages/CheckIn/WeeklyForm.vue`

## Route intent

Primary weekly reflection entry screen for active couple members.

## Access rules

* Authenticated
* Active couple member only
* Current week check-in not yet submitted, or editable within same week if product supports editing

## Layout

Use `CoupleLayout`.

## Page structure

### Header block

* Heading: `Weekly check-in`
* Subtext: `A short private reflection for this week.`

Optional privacy microcopy beneath subtext:

* `Your written responses stay private.`

### Context bar / metadata row

Display:

* Current week label, e.g. `Week of Mar 4 – Mar 10`
* Status badge: `Open`

Optional helper text:

* `Takes about 3–5 minutes`

### Main form card

Use a single vertical flow with generous spacing.

---

## Section A — Scale reflections

### Field 1

Label: `Emotional availability`
Helper text: `This week, I felt emotionally present.`

Control:

* 1–5 segmented selector, slider, or radio tile row

Display anchors:

* 1 = `Very low`
* 3 = `Moderate`
* 5 = `Very high`

### Field 2

Label: `Communication effort`
Helper text: `This week, I tried to communicate clearly and kindly.`

Control:

* same 1–5 control pattern

### Field 3

Label: `Stress level`
Helper text: `This week felt stressful.`

Control:

* same 1–5 control pattern

### UI rule

All 3 numeric fields are required.

---

## Section B — Optional reflections

### Field 4

Label: `One thing I appreciated this week`
Type:

* textarea
* optional
* recommended max length: 280–500 chars

### Field 5

Label: `One thing I struggled with`
Type:

* textarea
* optional

### Field 6

Label: `One thing I want to do better next week`
Type:

* textarea
* optional

### UI rule

Optional fields should clearly say `Optional`, but not feel second-class.

---

## Section C — Footer actions

Primary button:

* `Save check-in`

Secondary action:

* `Cancel` or `Back`

### Submission behavior

* Disable submit while posting
* Loading label: `Saving...`

### Success behavior

* Redirect to `WeeklySubmitted`

---

## Validation behavior

* Inline field errors
* Preserve entered form values on failed validation
* If duplicate weekly submission is attempted, show top-form error:

  * `This week’s check-in has already been saved.`

---

## UI notes

* No emojis
* No mood faces
* No animated encouragement
* No partner references on this page
* This page is about self-reflection, not relationship negotiation

---

# 2. Screen Spec — Weekly Submitted Confirmation

**File:** `resources/js/Pages/CheckIn/WeeklySubmitted.vue`

## Route intent

Confirm successful check-in submission.

## Access rules

* Authenticated
* Active couple member
* Current week check-in exists

## Layout

Use `CoupleLayout`.

## Page structure

### Confirmation card

Icon:

* subtle checkmark or calm success icon

Heading:

* `Check-in saved`

Subtext:

* `Thanks for taking a moment to reflect. Your responses are private.`

### Summary block

Display:

* Week label
* Submission timestamp
* Status badge: `Saved`

Do not display actual content summary unless it is the user’s own and product wants it.
For MVP, keep it simple.

### Primary actions

* `View my history`
* `Return home`

Optional secondary:

* `Edit this check-in` only if edits are allowed during the same week

## UI notes

* This should feel complete, not celebratory
* No confetti nonsense; this isn’t a fintech dashboard after a stock trade

---

# 3. Screen Spec — Weekly Locked / Already Submitted

**File:** `resources/js/Pages/CheckIn/WeeklyLocked.vue`

## Route intent

Handle cases where the current week’s check-in is unavailable for editing or creation.

## Access rules

* Authenticated
* Active couple member
* Either:

  * current week already submitted and editing window closed
  * week not open
  * check-in period unavailable

## Layout

Use `CoupleLayout`.

## Page structure

### Status card

Heading examples depending on state:

* `This week’s check-in is already complete`
* `This check-in window has closed`

Subtext variants:

* `You’ve already submitted your check-in for this week.`
* `The current check-in period is no longer open.`

### Metadata

Display:

* Week label
* State badge:

  * `Complete`
  * `Locked`

### Primary actions

* `View my history`
* `Return home`

Optional:

* `Start next week’s check-in` only if next period is actually open

## UI notes

* This page prevents confusing blank states
* Never imply the user failed for missing a week
* No “you missed your chance” tone

---

# 4. Screen Spec — Weekly History (Private)

**File:** `resources/js/Pages/CheckIn/WeeklyHistory.vue`

## Route intent

Let a user view their own weekly check-in history and trend context.

## Access rules

* Authenticated
* Only the owner can view this page
* Partner must never access

## Layout

Use `CoupleLayout`.

## Page structure

### Header

* Heading: `My weekly history`
* Subtext: `A private record of your weekly reflections.`

Optional privacy note:

* `Only you can view this page.`

---

## Section A — Personal trend overview

Display 3 compact trend cards:

1. `Emotional availability`
2. `Communication effort`
3. `Stress level`

Each card shows:

* small sparkline or line chart
* recent trend label:

  * `Rising`
  * `Stable`
  * `Mixed`

### Rules

* These labels are for self-awareness only
* No score color drama
* Use calm neutral/soft accent styling

---

## Section B — History list

Render reverse chronological cards or rows for past weekly check-ins.

Each history item should show:

* Week label
* Status badge: `Saved`
* Numeric summary chips (optional)

  * Emotional availability
  * Communication effort
  * Stress
* Expand/collapse action: `View details`

### Expanded details

Show only the user’s own content:

* appreciation text
* struggle text
* intention text

Do not show any partner context.

### Pagination / loading

* Paginate if needed
* Or infinite scroll if simple and stable
* Prefer straightforward pagination for MVP

---

## Section C — Empty state

If no history exists:
Heading:

* `No check-ins yet`

Subtext:

* `Your weekly reflections will appear here after you complete your first check-in.`

Primary action:

* `Start this week’s check-in`

---

## UI notes

* This page should feel reflective, not analytical
* Keep charts simple
* Avoid over-explaining trends
* This is history, not diagnosis

---

# 5. Optional State Spec — No Active Couple

**File:** optional component or redirect state

## Route intent

Handle users trying to access check-ins without an active couple.

## Preferred behavior

Redirect back to couple setup state rather than rendering a dedicated screen.

If a screen is needed, use:

Heading:

* `Weekly check-ins aren’t available yet`

Subtext:

* `Activate your couple space first to unlock this feature.`

Primary action:

* `Go to couple setup`

## Rule

Do not allow any check-in entry while couple status is `pending` or `dissolved`.

---

# Shared Component Additions for Phase 2

Add or extend these reusable UI components:

* `ScaleSelector`
* `PrivacyNote`
* `TrendCard`
* `HistoryItemCard`
* `SparklineChart`
* `WeekLabel`
* `StatusPill`

## Component rules

* No domain fetching inside components
* Props-driven only
* Trend labels supplied by backend/service layer where possible
* Components remain reusable for later dashboard phase

---

# Phase 2 Route-to-Page Map

Recommended route/page mapping:

* `GET /check-ins/weekly` → `CheckIn/WeeklyForm` or redirect/state page based on current availability
* `POST /check-ins/weekly` → save check-in
* `GET /check-ins/weekly/submitted` → `CheckIn/WeeklySubmitted`
* `GET /check-ins/weekly/history` → `CheckIn/WeeklyHistory`

Conditional rendering route behavior:

* If already submitted and locked → `WeeklyLocked`
* If active week open and not submitted → `WeeklyForm`
* If no active couple → redirect to couple setup

---

# Phase 2 Conditional Rules for UI

## When user opens `/check-ins/weekly`

System decides:

* No active couple → redirect/setup state
* Current week open and no submission → show form
* Submission exists and editable window still open → show form prefilled or edit mode
* Submission exists and locked → show locked page or redirect to submitted page

## Edit mode behavior

If edit is allowed in the same week:

* Show small badge: `Editing this week’s check-in`
* Primary button label changes to: `Update check-in`


---

# Copy Guardrails for Phase 2

Allowed tone:

* `reflect`
* `private`
* `saved`
* `history`
* `this week`

Forbidden tone:

* `score`
* `performance`
* `healthy/unhealthy`
* `better than last week`
* `your partner will see`
* `how are you doing as a spouse`


---

# Phase 2 Agent Handoff Note

Phase 2 is about building a **private, supportive weekly check-in experience**. The UI must be calm, simple, and focused on self-reflection without any social or gamified elements. The user should feel safe and unpressured when engaging with this feature.

```text
Use the following Phase 2 text/spec wireframes as the source of truth for the weekly private check-in experience.

Do not invent social, gamified, or mood-tracking UI.
Do not expose partner visibility anywhere in the weekly check-in screens.
Do not add progress comparisons, badges, streak gimmicks, or celebratory animations.
Respect the route and state logic exactly.
If implementation requires choosing between a simpler private flow and a richer one, choose the simpler private flow.
```

---
 
# Phase 2: Private Check-In UI (Summary)
