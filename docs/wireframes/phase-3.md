Understood. Continuing in **direct agent-instruction mode**.

---

# UnionOS — Phase 3 Inertia Wireframes (Text/Spec Form)

**Phase:** 3 — Monthly Couple Insights (Shared Alignment)
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement shared monthly alignment experience that surfaces insight without blame, comparison, or exposure of private inputs.

---

## 0. Product Intent (Non-Negotiable)

You are building a **shared awareness layer**, not a diagnostic tool.

This feature must:

* Help couples notice alignment
* Signal where a conversation may help
* Avoid exposing raw individual answers
* Avoid assigning blame
* Avoid emotional escalation

This feature must NOT:

* Compare partners side-by-side
* Show raw numeric answers per partner
* Use red/green judgment colors
* Imply fault, failure, or relationship health scores

Insight = orientation, not evaluation.

---

## 1. Global UI Rules

### Tone

* Neutral
* Calm
* Observational
* Respectful
* Non-clinical

### Layout

* Max width: `max-w-3xl`
* Use clear card segmentation
* One primary action per section
* Avoid dense dashboards

### Shared context

This is a **couple-visible feature**.
Everything shown here must be safe for both partners to see.

### Language rules

Allowed:

* “aligned”
* “difference”
* “worth discussing”
* “you may want to explore”

Forbidden:

* “problem”
* “issue”
* “unhealthy”
* “mismatch”
* “warning”
* “low score”
* “your partner feels”

---

## 2. Screen Map for Phase 3

Implement exactly these screens:

1. `CheckIn/MonthlyForm`
2. `CheckIn/MonthlyWaiting`
3. `CheckIn/MonthlyInsight`
4. `CheckIn/MonthlyLocked`

No extra screens unless required by routing.

---

## 3. Screen Spec — Monthly Check-In Form

**File:** `resources/js/Pages/CheckIn/MonthlyForm.vue`

### Route intent

Individual monthly relationship reflection that feeds shared insight.

### Access rules

* Authenticated
* Active couple member
* Current month submission not yet completed by this user

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Monthly check-in`
Subtext: `A shared reflection that helps you stay aligned.`

Optional privacy note:
`Your individual answers aren’t shown directly.`

---

### Context bar

Display:

* Month label (e.g., `March 2026`)
* Status badge: `Open`

Helper text:
`Takes about 2–3 minutes`

---

### Form card

#### Question 1

Label: `Feeling heard`
Prompt text: `I feel heard in our relationship.`
Control: 1–5 scale selector

#### Question 2

Label: `Feeling appreciated`
Prompt text: `I feel appreciated by my partner.`
Control: 1–5 scale selector

#### Question 3

Label: `Conflict safety`
Prompt text: `When we disagree, it feels emotionally safe.`
Control: 1–5 scale selector

#### Question 4

Label: `Emotional closeness`
Prompt text: `I feel emotionally close to my partner.`
Control: 1–5 scale selector

All fields required.

---

### Footer actions

Primary button: `Submit check-in`
Secondary: `Cancel`

Loading label: `Submitting...`

---

### Validation

* All fields required
* Prevent duplicate submission within same month

---

### Success behavior

Redirect to `MonthlyWaiting`.

---

### UI constraints

* No emojis
* No sentiment faces
* No free-text explanation fields
* No partner references in questions

---

## 4. Screen Spec — Monthly Waiting State

**File:** `resources/js/Pages/CheckIn/MonthlyWaiting.vue`

### Route intent

User has submitted monthly check-in; waiting for partner.

### Access rules

* Authenticated
* Active couple member
* User submitted; partner not yet submitted

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Check-in saved`
Subtext: `Shared insight will appear once both of you complete the check-in.`

---

### Status card

Display:

* Month label
* Status badge: `Waiting`

Neutral explanation:
`Nothing is shared until both submissions are complete.`

---

### Action section

Primary button: `Return home`

Optional secondary:
`View my history` (if history exists)

---

### UI constraints

* Do NOT display partner completion status
* Do NOT show countdowns
* Do NOT nudge partner
* No “Your partner hasn’t responded” language

Silence prevents pressure.

---

## 5. Screen Spec — Monthly Insight Reveal

**File:** `resources/js/Pages/CheckIn/MonthlyInsight.vue`

### Route intent

Show shared alignment insight after both partners submit.

### Access rules

* Authenticated
* Active couple member
* Both submissions exist for the month

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Monthly alignment`
Subtext: `A shared view of how you’re feeling this month.`

---

### Insight summary card

Short neutral intro:
`These insights highlight where you feel aligned and where a conversation may help.`

---

### Alignment grid

Render four alignment cards:

1. Feeling heard
2. Feeling appreciated
3. Conflict safety
4. Emotional closeness

Each card includes:

#### Title

Dimension label

#### Alignment badge

One of:

* `Aligned`
* `Needs conversation`

#### Neutral explanation text

Examples:

Aligned:
`You feel similarly in this area.`

Needs conversation:
`You may feel differently in this area.`

---

### Visual rules

* Use calm neutral palette
* Avoid red/green polarity
* No numeric scores
* No partner A vs partner B display
* No directionality (“higher/lower”)

---

### Call-to-action section

Primary card:
Heading: `Want to talk about this together?`
Subtext: `A guided conversation can help you explore these areas calmly.`

Primary button:
`Start guided conversation`

Secondary:
`Maybe later`

---

### Optional secondary section

Link:
`View past months` (if historical view exists)

---

### UI constraints

* No charts comparing partners
* No exposing raw answers
* No labeling “strong/weak”
* No diagnosis language

---

## 6. Screen Spec — Monthly Locked State

**File:** `resources/js/Pages/CheckIn/MonthlyLocked.vue`

### Route intent

Handle closed month windows or already-finalized period.

### Access rules

* Authenticated
* Active couple member
* Month closed OR locked state triggered

---

### Page structure

#### Header

Heading: `This month’s check-in is closed`
Subtext: `New entries can’t be added for this period.`

---

### Status card

Display:

* Month label
* Badge: `Locked`

Neutral explanation:
`Check-in periods close automatically.`

---

### Actions

Primary: `View latest insight` (if available)
Secondary: `Return home`

---

### UI constraints

* No blame language
* No “you missed it”
* No emotional framing

---

## 7. Shared Component Additions (Phase 3)

Implement or extend:

* `AlignmentCard`
* `AlignmentBadge`
* `ScaleSelector` (reuse Phase 2)
* `MonthLabel`
* `InsightIntroCard`

Component rules:

* Props-driven only
* No partner raw data
* All alignment classifications come from backend service

---

## 8. Route Map

* `GET /check-ins/monthly` → MonthlyForm or state redirect
* `POST /check-ins/monthly` → submit
* `GET /check-ins/monthly/waiting` → MonthlyWaiting
* `GET /check-ins/monthly/insight` → MonthlyInsight
* `GET /check-ins/monthly/locked` → MonthlyLocked

---

## 9. Conditional Rendering Rules

When user visits `/check-ins/monthly`:

If:

* No active couple → redirect to setup
* Month open & user not submitted → MonthlyForm
* User submitted & partner not → MonthlyWaiting
* Both submitted → MonthlyInsight
* Month closed without submissions → MonthlyLocked

---

## 10. Copy Guardrails

Allowed phrasing:

* “aligned”
* “feel similarly”
* “feel differently”
* “may help”
* “shared view”

Forbidden phrasing:

* “problem area”
* “relationship risk”
* “your partner reported”
* “low emotional closeness”
* “conflict score”

---

## 11. Agent UI Compliance Note

```text
Use this Phase 3 text/spec wireframe as the authoritative UI contract.

Do not expose raw individual answers.
Do not compare partners visually.
Do not introduce charts, scores, or evaluation metrics.
Do not add urgency, pressure, or blame framing.
If uncertain, choose the calmer and simpler presentation.
```

---

Proceed to Phase 4 wireframes when instructed.
