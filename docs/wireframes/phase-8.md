# UnionOS — Phase 8 UI/Content Wireframes (Text/Spec Form)

**Phase:** 8 — Content & Prompt System
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement the visible presentation of prompt packs, prompt sequencing, and content rendering rules so that guided content remains structured, calm, and version-safe.

---

## 0. Product Intent (Non-Negotiable)

You are building the **content delivery layer**, not a creative writing interface.

This phase must:

* present prompts clearly
* keep wording stable and intentional
* support versioned prompt packs without UI confusion
* maintain calm, safe tone across all guided content

This phase must NOT:

* improvise prompts in the UI
* allow user-authored prompt packs in MVP
* decorate prompts so heavily that they become theatrical
* turn prompt delivery into chat or coaching

Content should feel deliberate, not generated on the fly.

---

## 1. Global UI Rules

### Tone

* calm
* clear
* minimal
* lightly guided
* non-diagnostic

### Layout

* `max-w-3xl` for prompt-driven pages
* one prompt focus area at a time
* strong hierarchy between:

  * pack title
  * section intro
  * current prompt
  * action controls

### Content rendering rules

* prompt text must be visually primary
* support text must be visually secondary
* do not add interpretive labels unless provided by backend/content config
* prompt order must always be explicit

### Versioning rule

If prompt content changes across versions, the UI must present the active version only and must not mix content from different versions in a single live session.

---

## 2. Screen Map for Phase 8

Implement exactly these content-focused screens/components:

1. `Conversation/PackOverview`
2. `Conversation/PackPreview`
3. `Conversation/PromptStep`
4. `Conversation/PackUnavailable`
5. `Conversation/VersionLockedNotice`

These may overlap with Phase 4 pages, but in this phase the focus is the **content rendering contract**.

---

## 3. Screen Spec — Pack Overview

**File:** `resources/js/Pages/Conversation/PackOverview.vue`

### Route intent

Display all available fixed prompt packs with structured metadata.

### Access rules

* authenticated
* active couple member only

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Conversation packs`
Subtext: `Choose a structured conversation with a clear tone and purpose.`

---

### Pack grid/list

Render one card per available pack.

Each card must include:

* pack title
* short description
* estimated duration
* prompt count
* tone tag or purpose tag if supplied by backend
* action: `Preview` or `Start`

Example metadata layout:

**Weekly Reset**

* Description: `A light conversation to reconnect and reset the week.`
* Duration: `10–15 min`
* Prompts: `4 prompts`

**Feeling Unheard**

* Description: `A structured way to explore moments of misunderstanding.`
* Duration: `12–15 min`
* Prompts: `4 prompts`

**Appreciation & Reconnection**

* Description: `A guided conversation centered on gratitude and connection.`
* Duration: `8–12 min`
* Prompts: `4 prompts`

---

### UI constraints

* no user ratings
* no popularity badges
* no recommended-by-AI labels
* no custom creation controls

---

## 4. Screen Spec — Pack Preview

**File:** `resources/js/Pages/Conversation/PackPreview.vue`

### Route intent

Let users inspect the structure of a pack before starting it.

### Access rules

* authenticated
* active couple member only
* pack exists and is active

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: pack title
Subtext: pack description

---

### Metadata row

Display:

* estimated duration
* number of prompts
* content version identifier only if useful internally or subtly shown
* purpose tag if supplied

Do not overemphasize version labels to end users.

---

### Prompt preview card/list

Render ordered preview items:

1. prompt title or short label
2. short prompt text preview
3. turn assignment:

   * `Partner A`
   * `Partner B`
   * `Both`

If full prompt preview is shown, ensure it is read-only and clearly a preview.

---

### Footer actions

Primary: `Start conversation`
Secondary: `Back to packs`

---

### UI constraints

* no editing
* no reordering
* no user customization
* do not show hidden/internal content fields

---

## 5. Screen Spec — Prompt Step

**File:** `resources/js/Pages/Conversation/PromptStep.vue`

### Route intent

Render one active prompt within a live guided session.

### Access rules

* authenticated
* active couple member
* session active
* prompt step valid for current session version

### Layout

Use focused conversation layout.

---

### Page structure

#### Progress header

Display:

* pack title
* step count, e.g. `Step 2 of 4`
* current prompt group label if provided

---

### Prompt content card

Display, in order:

1. prompt label/title if provided
2. primary prompt text
3. optional support instruction text
4. turn assignment banner

Example turn assignment banner:

* `Your turn`
* `Your partner’s turn`
* `Respond together`

---

### Content formatting rules

* prompt text should have strongest visual emphasis
* support instruction should appear below in smaller muted text
* no decorative quote styling
* no emoji injection
* preserve exact backend/content wording

---

### Footer action area

Actions vary by state:

* current speaker: input + submit
* non-current speaker: waiting/listening state
* always safe secondary action: `Pause`

---

### UI constraints

* do not paraphrase prompt text in UI
* do not auto-summarize
* do not inject examples unless provided by content system
* exact prompt wording is authoritative

---

## 6. Screen Spec — Pack Unavailable

**File:** `resources/js/Pages/Conversation/PackUnavailable.vue`

### Route intent

Render when a pack exists in concept but is unavailable due to version, state, or deactivation.

### Layout

Use `CoupleLayout`.

### Page structure

#### State card

Heading: `This conversation pack isn’t available right now`
Subtext: `It may no longer be active or supported in the current session.`

Actions:

* Primary: `Return to conversation packs`

---

### Use cases

* deprecated pack
* missing active version
* invalid direct URL to inactive pack
* pack removed between preview and start

### UI constraints

* do not expose backend pack IDs or version conflicts
* keep wording simple

---

## 7. Screen Spec — Version Locked Notice

**File:** shared component or page partial

### Route intent

Inform users that an in-progress session is using a fixed content version.

### Placement

Can appear as a subtle info note in:

* live session header
* session overview
* resumed conversation flow

### Text

Heading or inline label:
`This session uses a fixed conversation version`

Subtext:
`Prompt wording stays consistent for the duration of this conversation.`

### Use cases

* session resumed after prompt pack updates
* backend detects pack version drift but preserves live session

### UI constraints

* keep this subtle
* informational only
* do not create concern

---

## 8. Content Rendering Rules (Cross-Cutting)

Apply these rules everywhere guided content appears.

### Rule A — Exact wording

Render prompt text exactly as supplied by the active content source.
Do not:

* paraphrase
* abbreviate
* embellish
* auto-correct meaning

### Rule B — Safe formatting

Prompts may contain:

* title
* body
* support note
* turn assignment

Prompt UI must support those fields gracefully without assuming all exist.

### Rule C — Stable ordering

Always render prompts in backend-defined order.
Do not sort alphabetically or by any UI heuristic.

### Rule D — No free-form generation

The UI must never create or improvise new prompts.
It only renders provided content.

---

## 9. Shared Component Additions (Phase 8)

Implement or extend:

* `ConversationPackMeta`
* `PromptPreviewList`
* `PromptContentCard`
* `TurnAssignmentBanner`
* `SupportNote`
* `VersionNotice`

Component rules:

* fully props-driven
* no content generation logic
* no fallback fake content
* if content missing, fail to safe unavailable state

---

## 10. Route Map

* `GET /conversations/packs` → `Conversation/PackOverview`
* `GET /conversations/packs/{pack}` → `Conversation/PackPreview`
* live session routes continue from Phase 4, but must use content rendering rules from this phase

If a separate preview route is not desired, integrate preview into index cards via modal or inline expansion.
If so, preserve the same information architecture.

---

## 11. Copy Guardrails

Allowed:

* `pack`
* `prompt`
* `guided`
* `structured`
* `preview`
* `version`
* `consistent`

Forbidden:

* `AI-generated`
* `smart prompt`
* `dynamic therapy`
* `custom coaching`
* `recommended by the system`
* `optimized for your relationship`

---

## 12. Agent UI Compliance Note

```text
Use this Phase 8 text/spec wireframe as the authoritative UI contract for prompt pack and content rendering.

Do not invent, paraphrase, or generate prompts in the UI.
Do not add customization, pack editing, or AI prompt creation features.
Render exact backend-provided prompt content in stable order.
If content is missing or inactive, fail to a calm unavailable state rather than guessing.
```

---

## 13. Completion Note

Phase 8 defines the visible contract for:

* pack browsing
* pack preview
* prompt rendering
* content version consistency
* unavailable/deprecated pack states

Proceed to a lighter Phase 9 UI/state spec only if notification preferences and in-app nudge surfaces need explicit frontend constraints.
