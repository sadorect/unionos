# UnionOS — Phase 4 Inertia Wireframes (Text/Spec Form)

**Phase:** 4 — Guided Conversations
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Audience:** UI Agent
**Purpose:** Implement structured, turn-based conversation screens that reduce interruption, lower emotional temperature, and keep the experience contained.

---

## 0. Product Intent (Non-Negotiable)

You are building a **contained conversation flow**, not chat.

This feature must:

* create structure
* make turn-taking explicit
* reduce emotional hijacking
* end with appreciation
* feel protected and intentional

This feature must NOT:

* feel like messaging
* allow interruptive back-and-forth
* expose live typing
* store or display unnecessary emotional detail
* become a free-form argument box

Structure is the feature.

---

## 1. Global UI Rules

### Tone

* calm
* steady
* respectful
* lightly guided
* never preachy

### Layout

* max width: `max-w-3xl`
* full attention layout
* minimal distractions
* one primary action per stage
* no sidebar
* suppress unrelated navigation during active conversation where practical

### Visual rules

* warm neutral palette
* generous spacing
* large readable type
* timer visible but not aggressive
* no alert colors unless truly necessary

### UX rules

* one speaker at a time
* one prompt at a time
* clear progress
* clear pause/resume path
* no background clutter

---

## 2. Screen Map for Phase 4

Implement exactly these screens:

1. `Conversation/Index`
2. `Conversation/GroundRules`
3. `Conversation/Turn`
4. `Conversation/Pause`
5. `Conversation/AppreciationClose`
6. `Conversation/Complete`

Optional:

* `Conversation/Locked` if needed for invalid/completed session access

---

## 3. Screen Spec — Conversation Index

**File:** `resources/js/Pages/Conversation/Index.vue`

### Route intent

Entry screen where couple chooses a guided conversation pack.

### Access rules

* authenticated
* active couple member only

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Guided conversations`
Subtext: `Choose a structured conversation to help you talk calmly and clearly.`

---

### Conversation pack list

Render fixed cards for:

1. `Weekly Reset`
2. `Feeling Unheard`
3. `Appreciation & Reconnection`

Each card includes:

* title
* short description
* estimated time
* primary action: `Start`

Example descriptions:

**Weekly Reset**
`A light weekly check-in to reconnect and reset.`

**Feeling Unheard**
`A structured way to explore moments of misunderstanding.`

**Appreciation & Reconnection**
`A gentle conversation focused on gratitude and connection.`

---

### Optional previous sessions section

If history exists, show a compact list:

* pack name
* completed date
* status badge

Do not show conversation content.

---

### UI constraints

* no custom prompt builder
* no AI pack generator
* no free-form “start chat”
* no heavy descriptions

---

## 4. Screen Spec — Ground Rules

**File:** `resources/js/Pages/Conversation/GroundRules.vue`

### Route intent

Prepare both partners before session begins.

### Access rules

* authenticated
* active couple member
* session created but not yet started

### Layout

Use simplified full-attention version of `CoupleLayout`.

---

### Page structure

#### Header

Heading: `Before you begin`
Subtext: `A few ground rules help this conversation stay clear and respectful.`

---

### Ground rules card

Render short bullet list:

* `One person speaks at a time.`
* `Listen to understand, not to respond.`
* `No interruptions.`
* `You can pause if emotions rise.`
* `The goal is connection, not winning.`

---

### Agreement section

Checkbox or confirmation control:

* `I understand and agree`

Primary button:

* `Begin conversation`

Secondary:

* `Cancel`

### Button logic

Primary disabled until agreement confirmed.

---

### UI constraints

* keep this brief
* do not turn into a long tutorial
* no moralizing

---

## 5. Screen Spec — Turn Screen

**File:** `resources/js/Pages/Conversation/Turn.vue`

### Route intent

Core turn-based conversation screen.

### Access rules

* authenticated
* active couple member
* active session
* valid current turn state

### Layout

Use focused conversation layout with reduced nav distractions.

---

### Page structure

#### Top progress bar

Display:

* pack title
* step counter, e.g. `Prompt 2 of 4`
* session status badge: `In progress`

Optional timer position:

* top right or directly beneath prompt header

---

### Speaker status block

Display clearly:

* `Your turn` OR `Your partner is speaking`

This must be visually unambiguous.

---

### Prompt card

Display:

* prompt text
* short instruction if needed

Examples:

* `One thing that went well for me this week...`
* `One moment I felt misunderstood recently...`

---

### Turn behavior: current speaker = user

Show:

* textarea input
* optional character guidance
* timer
* primary button: `Submit response`
* secondary button: `Pause`

Rules:

* once submitted, no editing
* disable submit while saving

---

### Turn behavior: partner is current speaker

Show:

* locked listening state
* message:
  `Your partner is responding right now. Take a moment to listen.`
* no input field
* secondary button only if allowed: `Pause`

Optional passive area:

* `Next, you’ll have your turn.`

Do not show live typing.

---

### Timer behavior

* visible, calm styling
* no flashing urgency
* soft expiration state
* allow short grace period if backend logic supports it

---

### UI constraints

* no chat bubbles
* no side-by-side answers
* no scrolling transcript
* no reaction buttons

---

## 6. Screen Spec — Pause Screen

**File:** `resources/js/Pages/Conversation/Pause.vue`

### Route intent

Pause state for a session that was intentionally interrupted.

### Access rules

* authenticated
* active couple member
* session status = paused

### Layout

Use focused layout.

---

### Page structure

#### Header

Heading: `Conversation paused`
Subtext: `You can return when you’re ready.`

---

### Status card

Display:

* conversation pack title
* last completed step
* paused timestamp

---

### Actions

Primary button: `Resume conversation`
Secondary button: `Return home`

Optional subtle helper text:
`You don’t need to finish immediately.`

---

### UI constraints

* no blame language
* no urgency
* no “unfinished task” pressure

---

## 7. Screen Spec — Appreciation Close

**File:** `resources/js/Pages/Conversation/AppreciationClose.vue`

### Route intent

Required closing step before session completion.

### Access rules

* authenticated
* active couple member
* all core prompts completed
* closing step still pending

### Layout

Use focused layout.

---

### Page structure

#### Header

Heading: `Close with appreciation`
Subtext: `End this conversation by naming one thing you appreciate right now.`

---

### Prompt card

Prompt:
`One thing I appreciate about you right now...`

---

### Input section

Textarea:

* short response
* required

Actions:

* primary: `Complete conversation`
* secondary: `Pause`

---

### UI constraints

* do not allow skipping
* do not overdesign this
* this is the emotional landing strip

---

## 8. Screen Spec — Completion Screen

**File:** `resources/js/Pages/Conversation/Complete.vue`

### Route intent

Session completion confirmation.

### Access rules

* authenticated
* active couple member
* session completed

### Layout

Use `CoupleLayout`.

---

### Page structure

#### Confirmation card

Heading: `Conversation complete`
Subtext: `Thank you for making space for this conversation.`

Display:

* pack name
* completion timestamp
* status badge: `Completed`

---

### Optional feedback card

Question:
`Did this conversation feel helpful?`

Options:

* `Yes`
* `Neutral`
* `Not really`

This is optional and lightweight.

---

### Actions

Primary button: `Return home`
Secondary button: `Start another conversation`

---

### UI constraints

* do not show transcript
* do not summarize responses
* do not score the session

---

## 9. Optional Screen Spec — Locked/Unavailable Session

**File:** `resources/js/Pages/Conversation/Locked.vue`

### Route intent

Handle invalid session access, completed sessions, or unauthorized state.

### Page structure

Heading: `This conversation isn’t available`
Subtext:

* `It may already be completed or no longer active.`

Actions:

* `Return to guided conversations`

Use only if necessary instead of raw error pages.

---

## 10. Shared Component Additions (Phase 4)

Implement or extend:

* `ConversationPackCard`
* `GroundRulesList`
* `ProgressHeader`
* `SpeakerStatusBanner`
* `ConversationTimer`
* `PromptCard`
* `ListeningStateCard`
* `PauseStateCard`
* `HelpfulToggle`

Component rules:

* props-driven only
* no domain logic hidden in UI components
* no fetching inside components
* state comes from page props

---

## 11. Route Map

* `GET /conversations` → `Conversation/Index`
* `GET /conversations/{session}/rules` → `Conversation/GroundRules`
* `GET /conversations/{session}` → `Conversation/Turn`
* `GET /conversations/{session}/pause` → `Conversation/Pause`
* `GET /conversations/{session}/close` → `Conversation/AppreciationClose`
* `GET /conversations/{session}/complete` → `Conversation/Complete`

---

## 12. Conditional Rendering Rules

When entering a session route:

* if no active couple → redirect away
* if session completed → completion or locked page
* if session paused → pause page
* if session awaiting ground rules confirmation → ground rules page
* if session in active prompt flow → turn page
* if core prompts finished but close step pending → appreciation close page

Do not let the UI infer session state loosely. Respect backend status.

---

## 13. Copy Guardrails

Allowed:

* `begin`
* `pause`
* `resume`
* `listen`
* `respond`
* `appreciate`
* `take your turn`

Forbidden:

* `resolve conflict now`
* `fix your relationship`
* `your partner said`
* `urgent`
* `critical`
* `success score`

---

## 14. Agent UI Compliance Note

```text
Use this Phase 4 text/spec wireframe as the authoritative UI contract for guided conversations.

Do not design this like chat.
Do not introduce live typing, chat bubbles, reactions, or free-form conversation UI.
Do not expose transcripts or summaries.
Keep turn-taking explicit and visually clear.
If uncertain, choose stronger structure over greater freedom.
```

---

Proceed to Phase 5 wireframes when instructed.
