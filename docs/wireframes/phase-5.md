# UnionOS — Phase 5 Inertia Wireframes (Text/Spec Form)

**Phase:** 5 — Progress & Dashboard
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement personal and couple-level progress views that encourage reflection without pressure, comparison, or emotional overexposure.

---

## 0. Product Intent (Non-Negotiable)

You are building a **calm awareness dashboard**, not a performance dashboard.

This feature must:

* help users notice trends over time
* reinforce consistency and reflection
* support shared awareness without exposing private raw content
* keep the tone steady and non-competitive

This feature must NOT:

* gamify the relationship
* rank partners
* compare one partner against the other
* assign scores or grades
* create pressure through aggressive visuals

Trend > moment.
Awareness > judgment.

---

## 1. Global UI Rules

### Tone

* calm
* reflective
* lightly informative
* non-evaluative
* non-performative

### Layout

* `max-w-6xl` for dashboard pages
* card-based sections
* simple two-column layouts where helpful
* no cluttered analytics walls
* charts must be small and readable

### Visual rules

* no red/green “good vs bad”
* no score badges
* no progress bars that imply target completion
* no trophies, streak flames, or gamification artifacts

### Data exposure rules

* personal dashboard may show user’s own raw trend data
* couple dashboard may show only safe shared aggregates
* no partner raw text anywhere
* no partner raw slider values anywhere

---

## 2. Screen Map for Phase 5

Implement exactly these screens:

1. `Dashboard/Personal`
2. `Dashboard/Couple`

Optional:

* `Dashboard/EmptyState` if product prefers a dedicated page rather than inline empty cards

---

## 3. Screen Spec — Personal Dashboard

**File:** `resources/js/Pages/Dashboard/Personal.vue`

### Route intent

Private self-awareness dashboard for the logged-in user.

### Access rules

* authenticated
* owner only
* never visible to partner

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `My progress`
Subtext: `A private view of your reflection patterns over time.`

Optional privacy note:
`Only you can see this page.`

---

### Section A — Overview cards

Render compact summary cards:

1. `Weekly check-ins completed`
2. `Recent reflection consistency`
3. `Conversation participation`

Each card includes:

* label
* simple value or short phrase
* optional tiny sparkline

Examples of safe values:

* `6 completed`
* `Mostly consistent`
* `3 conversations completed`

Do not use achievement language.

---

### Section B — Trend cards

Render three trend cards:

1. `Emotional availability`
2. `Communication effort`
3. `Stress level`

Each card includes:

* small line or sparkline chart
* recent label:

  * `Rising`
  * `Stable`
  * `Mixed`
* short neutral helper text

Example helper text:
`Based on your recent weekly check-ins.`

Do not show moral interpretation.

---

### Section C — Reflection history preview

Show recent weekly history preview:

* week label
* saved status
* optional short metadata only

Actions:

* `View full weekly history`

Do not show full journal text inline on dashboard.

---

### Section D — Conversation activity

Render a compact activity list:

* pack name
* completion date
* status badge: `Completed` or `Paused`

No transcript links.
No content summaries.

---

### Section E — Empty state handling

If user has no weekly check-ins and no conversation history:

Heading:
`Nothing here yet`

Subtext:
`Your private reflection patterns will appear here as you begin using the app.`

Primary action:
`Start weekly check-in`

---

### UI constraints

* no “improving as a spouse”
* no “better than last month”
* no streak counters
* no comparative language

---

## 4. Screen Spec — Couple Dashboard

**File:** `resources/js/Pages/Dashboard/Couple.vue`

### Route intent

Shared, safe high-level view for both partners.

### Access rules

* authenticated
* active couple member
* same couple only

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Our progress`
Subtext: `A shared view of your recent patterns and connection points.`

---

### Section A — Shared overview cards

Render safe shared cards such as:

1. `Monthly check-ins completed`
2. `Guided conversations completed`
3. `Recent alignment status`

Examples:

* `3 monthly check-ins completed`
* `2 guided conversations completed`
* `Mostly stable`

These must remain shared aggregates only.

---

### Section B — Monthly alignment trend

Render a small historical view across recent months.

Each month row/card shows:

* month label
* general alignment status badge:

  * `Stable`
  * `Needs attention`
  * `Mixed`

Optional helper text:
`Based on your shared monthly check-ins.`

Rules:

* no drill-down into partner raw scores
* no side-by-side comparisons
* no “who changed”

---

### Section C — Guided conversation history

Show shared safe history list:

* conversation pack title
* completed date
* session status

No response content.
No session summary.

---

### Section D — Next step card

Render a simple next-step suggestion card.

Examples:

* `Ready for this month’s check-in?`
* `You have a guided conversation available.`
* `Take a quiet moment to reconnect this week.`

Tone must remain optional and calm.

Actions:

* `Go to monthly check-in`
* or `Start guided conversation`

Only show actions that are actually available.

---

### Section E — Empty state

If no shared data exists:

Heading:
`Your shared progress will appear here`

Subtext:
`Complete monthly check-ins or guided conversations to start building this view.`

Primary action:
`Go to monthly check-in`

---

### UI constraints

* no couple score
* no relationship rating
* no positive/negative arrows
* no partner-specific indicators

---

## 5. Shared Component Additions (Phase 5)

Implement or extend:

* `OverviewStatCard`
* `TrendCard`
* `MiniLineChart`
* `MonthlyAlignmentRow`
* `ConversationActivityList`
* `NextStepCard`
* `DashboardEmptyState`

Component rules:

* props-driven only
* no domain logic in components
* no fetching in child components
* all labels and classifications supplied by backend/page props

---

## 6. Route Map

* `GET /dashboard/personal` → `Dashboard/Personal`
* `GET /dashboard/couple` → `Dashboard/Couple`

Optional route:

* `GET /dashboard` → redirect based on preferred default

  * either personal dashboard
  * or couple dashboard

Choose one and remain consistent.

Recommended:

* default `/dashboard` → `Dashboard/Couple`
* provide clear nav link to `My progress`

---

## 7. Conditional Rendering Rules

### Personal dashboard

If no personal history:

* show empty state cards, not a blank page

### Couple dashboard

If couple active but shared activity is empty:

* show empty state
* keep page usable and non-awkward

If couple dissolved:

* either redirect away or show read-only safe state according to product rules
* do not show active next-step actions

---

## 8. Copy Guardrails

Allowed:

* `progress`
* `patterns`
* `shared view`
* `recent`
* `stable`
* `mixed`
* `needs attention`

Forbidden:

* `failing`
* `winning`
* `top score`
* `better partner`
* `relationship score`
* `health rating`
* `success rate`

---

## 9. Navigation Rules After Phase 5

At this point, authenticated top navigation may safely include:

* `Home`
* `Weekly Check-In`
* `Monthly Insight`
* `Conversations`
* `Our Progress`
* `My Progress`
* user menu/logout

Still avoid heavy sidebar unless explicitly added later.

---

## 10. Agent UI Compliance Note

```text
Use this Phase 5 text/spec wireframe as the authoritative UI contract for dashboards.

Do not introduce gamification, scores, ranks, streaks, trophies, or comparative partner visuals.
Do not surface raw partner data.
Do not convert this into an analytics dashboard.
If uncertain, choose the calmer and less evaluative presentation.
```

---

