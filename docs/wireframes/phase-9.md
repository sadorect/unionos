# UnionOS — Phase 9 UI/State Wireframes (Text/Spec Form)

**Phase:** 9 — Notifications & Nudge Preferences
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement notification preference screens and in-app nudge surfaces that remain optional, respectful, and emotionally low-pressure.

---

## 0. Product Intent (Non-Negotiable)

You are building the **visible preference layer** for reminders and nudges.

This phase must:

* let users control reminder behavior
* make notifications feel optional and respectful
* avoid emotional pressure
* keep reminder settings simple

This phase must NOT:

* guilt users into participation
* expose partner behavior
* create urgency loops
* become a marketing-notification center

The tone should be:
**“Helpful if wanted, silent if not.”**

---

## 1. Global UI Rules

### Tone

* calm
* optional
* lightweight
* respectful
* non-intrusive

### Layout

* `max-w-3xl` for settings pages
* clean sections
* short helper text
* one preference group per card

### Language rules

Allowed:

* `reminder`
* `optional`
* `preference`
* `pause`
* `quiet`
* `helpful`

Forbidden:

* `don’t fall behind`
* `you haven’t completed`
* `your partner is waiting`
* `urgent`
* `streak`
* `missed activity`

No manipulative productivity language.

---

## 2. Screen Map for Phase 9

Implement exactly these screens/components:

1. `Settings/Notifications`
2. `State/NotificationsPaused`
3. `Shared/NextStepNudgeCard`
4. `Shared/NotificationPreferenceCard`

This phase is intentionally lighter than earlier phases.

---

## 3. Screen Spec — Notification Settings

**File:** `resources/js/Pages/Settings/Notifications.vue`

### Route intent

Let a user manage personal notification preferences.

### Access rules

* authenticated
* self only

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Notification preferences`
Subtext: `Choose which reminders feel helpful for you.`

Optional helper line:
`These settings apply only to your account.`

---

### Section A — Weekly reminder preferences

Card title: `Weekly check-in reminders`

Controls:

* toggle: `Email reminders`
* optional frequency selector:

  * `Weekly`
  * `Off`

Optional timing selector if backend supports it:

* preferred time of day
* preferred weekday

Helper text:
`Receive a gentle reminder when your weekly check-in is available.`

---

### Section B — Monthly reminder preferences

Card title: `Monthly check-in reminders`

Controls:

* toggle: `Email reminders`
* optional timing selector

Helper text:
`Receive a reminder when the monthly check-in window opens.`

---

### Section C — Guided conversation nudges

Card title: `Conversation suggestions`

Controls:

* toggle: `Show suggestion emails`
* toggle or select: `In-app suggestions`

Helper text:
`Occasionally receive a calm suggestion when a guided conversation may be useful.`

---

### Section D — Quiet mode

Card title: `Quiet mode`

Controls:

* toggle: `Pause all reminders`

Helper text:
`Turn off reminders for now. You can change this any time.`

If enabled:

* show subtle info note:
  `All optional reminders are currently paused.`

---

### Footer actions

Primary button: `Save preferences`

Optional secondary:

* `Cancel`

Loading label:
`Saving...`

---

### UI constraints

* do not show partner preferences
* do not show reminder history
* do not show engagement metrics
* no “recommended settings” unless product explicitly adds safe defaults

---

## 4. Screen Spec — Notifications Paused State

**File:** `resources/js/Pages/State/NotificationsPaused.vue`

### Route intent

Optional state or inline card shown when all reminders are paused.

### Layout

Use `CoupleLayout` or render inline on settings page.

### Page structure

#### State card

Heading: `Reminders are paused`
Subtext: `You won’t receive optional reminders until you turn them back on.`

Actions:

* Primary: `Manage preferences`
* Secondary: `Return home`

### Use cases

* landing page after pausing everything
* inline acknowledgment in settings
* optional return state from notification email settings flow

### UI constraints

* no guilt
* no urgency
* no “you may miss progress” wording

---

## 5. Screen Spec — Next Step Nudge Card

**File:** shared component

### Route intent

Reusable safe in-app suggestion card for dashboards or home screens.

### Placement

Can appear in:

* couple dashboard
* personal dashboard
* home screen

### Variants

Examples:

* `Ready for this week’s check-in?`
* `Your monthly check-in is available.`
* `A guided conversation is available if you’d like one.`

### Structure

* title
* one-sentence supporting text
* one action button

### Rules

* show only when truly actionable
* must remain optional
* one nudge card at a time
* no stacking multiple suggestion banners

### Forbidden variants

* `Your partner is waiting`
* `You’re overdue`
* `Don’t miss this week`
* `Complete this now`

---

## 6. Screen Spec — Notification Preference Card

**File:** shared component

### Route intent

Reusable settings card for toggles/selectors.

### Structure

* title
* helper text
* control group
* optional inline note if disabled/suppressed

### Rules

* simple API
* accessibility-friendly labels
* no hidden side effects
* change confirmation should be explicit via save action unless using clearly indicated autosave

---

## 7. In-App Notification Surface Rules

Apply these rules anywhere reminder-like UI appears.

### Rule A — Never expose partner inactivity

Do not render:

* `Your partner hasn’t completed...`
* `Still waiting on them...`
* `They haven’t responded yet...`

### Rule B — Keep reminders action-based, not shame-based

Good:

* `Your weekly check-in is available.`

Bad:

* `You skipped last week.`

### Rule C — One suggestion at a time

If multiple next steps are available, pick the most relevant one.
Do not stack banners.

### Rule D — Respect quiet mode visually

If reminders are paused, do not display nag-like banners.
Subtle informational notes are acceptable.

---

## 8. Shared Component Additions (Phase 9)

Implement or extend:

* `PreferenceToggle`
* `PreferenceSelect`
* `PreferenceSectionCard`
* `QuietModeNotice`
* `NextStepNudgeCard`

Component rules:

* props-driven only
* no direct notification dispatch logic
* presentational only

---

## 9. Route Map

* `GET /settings/notifications` → `Settings/Notifications`

Optional:

* `GET /settings/notifications/paused` → `State/NotificationsPaused`

If separate paused route is unnecessary, keep it as inline state only.

---

## 10. Copy Guardrails

Allowed:

* `reminder`
* `optional`
* `available`
* `pause`
* `preference`
* `helpful`

Forbidden:

* `overdue`
* `lagging`
* `behind`
* `your partner is waiting`
* `don’t miss out`
* `relationship boost score`

---

## 11. Agent UI Compliance Note

```text
Use this Phase 9 text/spec wireframe as the authoritative UI contract for notification preferences and in-app nudges.

Do not use guilt, urgency, or partner-pressure language.
Do not surface partner inactivity through reminders or banner copy.
Keep settings user-specific, simple, and optional.
If uncertain, show fewer nudges, not more.
```

---

## 12. Completion Note

Phase 9 defines the visible contract for:

* notification preferences
* quiet mode
* safe in-app nudges
* lightweight reminder surfaces

Proceed to a minimal Phase 10 UI/state spec only for graceful error and operational fallback surfaces.

---
