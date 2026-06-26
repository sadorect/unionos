
# UnionOS — Phase 1 Inertia Wireframes (Text/Spec Form)

**Phase:** 1 — Foundation & Trust Layer
**Stack Context:** Laravel 12 + Inertia.js + Vue 3 + Tailwind CSS
**Purpose:** Define the minimum screens, sections, states, actions, and UI rules for auth and couple linking.

## Global UI principles for Phase 1

These apply to every screen in this phase.

### Tone

* Calm
* Clean
* Private
* Reassuring
* No clinical language
* No overly romantic copy either; keep it neutral and warm

### Layout rules

* Max content width: `max-w-2xl` for forms, `max-w-4xl` for dashboards/states
* Plenty of whitespace
* Single primary action per card/section
* Avoid crowded multi-column forms in this phase
* Use cards/panels for distinct actions

### Navigation rules

Top nav should remain minimal:

* App name/logo: `UnionOS`
* Authenticated state: user menu with logout
* Guest state: login / register links
* No sidebar in Phase 1

### Component style rules

Use consistent design atoms:

* Primary button
* Secondary button
* Danger button only for destructive actions like dissolve/cancel
* Form input
* Form label
* Inline validation errors
* Neutral status badge

### State messaging rules

Every state must answer:

* Where am I?
* What can I do next?
* What can’t I do yet?

---

# Screen Map for Phase 1

Phase 1 should include exactly these screens/pages:

1. `Auth/Login`
2. `Auth/Register`
3. `Auth/ForgotPassword`
4. `Couple/Create`
5. `Couple/Pending`
6. `Couple/Invite`
7. `Couple/AcceptInvite`
8. `Couple/ActiveHome`
9. `Couple/Dissolved`

No extra screens unless Laravel auth scaffolding requires a tiny support screen like password reset confirmation.

---

# 1. Screen Spec — Login

**File:** `resources/js/Pages/Auth/Login.vue`

## Route intent

Guest entry point for existing users.

## Layout

Use `AuthLayout`.

## Page structure

### Header block

* App mark/logo
* Heading: `Welcome back`
* Subtext: `Sign in to your private couple space.`

### Form card

Fields:

* Email
* Password
* Remember me checkbox

Actions:

* Primary button: `Log in`
* Text link: `Forgot password?`
* Footer text link: `Create an account`

## Validation behavior

* Show field-level validation below inputs
* Show auth failure message at top of form card
* Preserve email on failed submit
* Never reveal whether an email exists separately from auth failure

## Empty/loading states

* Button disabled while submitting
* Loading label: `Signing in...`

## UI notes

* Keep this very standard
* No marketing copy
* No testimonials, hero banners, or decorative clutter

---

# 2. Screen Spec — Register

**File:** `resources/js/Pages/Auth/Register.vue`

## Route intent

Create a new user account.

## Layout

Use `AuthLayout`.

## Page structure

### Header block

* App mark/logo
* Heading: `Create your account`
* Subtext: `Start with your own secure account. You’ll link with your partner next.`

### Form card

Fields:

* Name or preferred display name
* Email
* Password
* Confirm password

Actions:

* Primary button: `Create account`
* Footer link: `Already have an account? Log in`

## Validation behavior

* Standard field validation
* Password confirmation mismatch shown inline
* Email uniqueness shown inline

## Loading state

* Button disabled on submit
* Label: `Creating account...`

## UI notes

* Mention linking comes later to reduce anxiety
* Do not ask for partner info here
* Do not combine account creation with couple creation on one screen

---

# 3. Screen Spec — Forgot Password

**File:** `resources/js/Pages/Auth/ForgotPassword.vue`

## Route intent

Password reset request screen.

## Layout

Use `AuthLayout`.

## Page structure

### Header block

* Heading: `Reset your password`
* Subtext: `Enter your email and we’ll send a reset link.`

### Form card

Fields:

* Email

Actions:

* Primary button: `Send reset link`
* Secondary text link: `Back to login`

## Success state

Show success alert inside same card:

* `If an account matches that email, a reset link has been sent.`

## UI notes

* Keep wording privacy-safe
* No “email not found” disclosure

---

# 4. Screen Spec — Create Couple

**File:** `resources/js/Pages/Couple/Create.vue`

## Route intent

First authenticated step after registration/login for users not yet in a couple.

## Layout

Use `CoupleLayout`.

## Access rules

* Only authenticated users
* Only users not in an active or pending couple

## Page structure

### Page header

* Heading: `Create your couple space`
* Subtext: `This is your shared private space. You can invite your partner after setup.`

### Main card

Content:

* Brief explanation of what happens next:

  1. Create your private couple space
  2. Invite your partner
  3. Unlock shared features once both of you are linked

Actions:

* Primary button: `Create couple space`

### Optional small print

* `Only two people can belong to a couple space.`
* `Nothing is shared until your partner joins.`

## Success behavior

On submit success:

* Redirect to `Couple/Pending`

## Loading state

* Button label: `Creating...`

## UI notes

* This screen should feel lightweight
* No advanced settings
* No partner email field here

---

# 5. Screen Spec — Pending Couple State

**File:** `resources/js/Pages/Couple/Pending.vue`

## Route intent

Show that the user has created a couple but the second partner has not joined yet.

## Layout

Use `CoupleLayout`.

## Access rules

* Authenticated
* User belongs to a `pending` couple

## Page structure

### Header

* Heading: `Your couple space is waiting`
* Subtext: `Invite your partner to activate shared features.`

### Primary status card

Display:

* Status badge: `Pending`
* Short explanation:
  `Your couple space has been created, but it won’t become active until your partner accepts the invite.`

Primary action:

* `Invite partner`

Secondary action:

* `Enter invite code` or `Accept invite` only if product wants same user to also join through a received code flow; otherwise omit

### Feature lock card

Title: `Available after linking`
List:

* Weekly private check-ins
* Shared monthly insights
* Guided conversations
* Progress dashboard

Use muted/disabled styling, not clickable.

### Invite summary card

If invite exists:

* Show masked or plain invited email depending on your final privacy choice
* Show invite status badge: `Sent` / `Expired`
* Show actions:

  * `Regenerate invite`
  * `Cancel invite`

If invite does not exist:

* Show prompt:
  `No active invite yet.`
  Button: `Send invite`

## UI notes

* This page should reduce “did I do it right?” confusion
* Don’t guilt the user because partner hasn’t joined
* No countdown timers

---

# 6. Screen Spec — Invite Partner

**File:** `resources/js/Pages/Couple/Invite.vue`

## Route intent

Allow a pending couple member to send or regenerate an invite.

## Layout

Use `CoupleLayout`.

## Access rules

* Authenticated
* Member of pending couple

## Page structure

### Header

* Heading: `Invite your partner`
* Subtext: `Send a secure invitation to join your private couple space.`

### Invite form card

Fields:

* Partner email

Actions:

* Primary button: `Send invite`

### Helper copy

* `The invitation link is single-use and expires automatically.`
* `Your partner will create or sign in to an account before joining.`

### Existing invite card

If active invite already exists:
Display:

* Current invite state
* Expiry date/time
* Optional copy link button
* Buttons:

  * `Regenerate invite`
  * `Cancel invite`

## Validation rules

* Cannot invite own email
* Cannot invite if couple already full
* Cannot create multiple concurrent active invites unless your backend allows replacement flow

## Success state

Show flash message:

* `Invitation sent.`
  Then redirect back to pending state or remain on page with updated existing invite card.

## UI notes

* Avoid exposing too much token detail
* Never show raw token string in plain UI unless it’s in a copy-link action
* If showing link, put it inside a copy field, not as naked text blob

---

# 7. Screen Spec — Accept Invite

**File:** `resources/js/Pages/Couple/AcceptInvite.vue`

## Route intent

Landing page for partner using secure invite token.

## Layout

Use guest-friendly layout if not authenticated; if authenticated, still render same page with appropriate action.

## Access rules

* Valid invite token required to reach meaningful state
* If token invalid or expired, render error state

## Page states

## A. Valid token + guest user

### Header

* Heading: `You’ve been invited`
* Subtext: `Join a private couple space on UnionOS.`

### Info card

Display:

* Invite valid status
* Optional inviter display name if you choose to include it
* Short explanation:
  `You’ll need an account to accept this invite.`

Actions:

* Primary: `Create account and continue`
* Secondary: `Log in to continue`

---

## B. Valid token + authenticated user not yet coupled

### Header

* Heading: `Accept invitation`
* Subtext: `You’re about to join a private couple space.`

### Summary card

Display:

* Invitation valid
* Simple warning:
  `A couple space can only have two people.`

Actions:

* Primary: `Accept invite`
* Secondary: `Cancel`

---

## C. Invalid or expired token

### Error card

Heading: `This invitation is no longer available`
Subtext:

* `It may have expired or already been used.`

Actions:

* Secondary link: `Return home`
* Optional note: `Ask your partner to send a new invitation.`

## D. Authenticated user already in another couple

### Error card

Heading: `You can’t join this invitation`
Subtext:

* `Your account is already linked to another couple space.`

Action:

* `Return to your dashboard`

## UI notes

* This page must be crystal clear
* No ambiguous token failure states
* Avoid scary wording

---

# 8. Screen Spec — Active Couple Home

**File:** `resources/js/Pages/Couple/ActiveHome.vue`

## Route intent

Very simple landing/home screen once couple becomes active in Phase 1.

## Layout

Use `CoupleLayout`.

## Access rules

* Authenticated
* Active couple member

## Page structure

### Header

* Heading: `Your couple space is active`
* Subtext: `You’re linked and ready to begin.`

### Status card

Display:

* Status badge: `Active`
* Partner linked confirmation
* Optional linked date

### Primary actions card

Buttons:

* `Weekly check-in` (disabled or “coming next” if Phase 2 not built yet)
* `Monthly insight` (disabled/coming next)
* `Guided conversations` (disabled/coming next)
* `Dashboard` (disabled/coming next)

If you are building strictly Phase 1 only:

* show these as disabled preview cards, not active buttons

### Relationship management card

Actions:

* `View invite history` if relevant
* `Dissolve couple space` as low-emphasis danger action

## UI notes

* This page exists to confirm success
* Don’t overbuild it yet
* It should transition nicely into future phases

---

# 9. Screen Spec — Dissolved Couple State

**File:** `resources/js/Pages/Couple/Dissolved.vue`

## Route intent

Inform user that the previous couple space is no longer active.

## Layout

Use `CoupleLayout` or simplified app layout.

## Access rules

* Authenticated
* User associated with dissolved couple context or post-dissolution redirect

## Page structure

### Header

* Heading: `This couple space is no longer active`
* Subtext: `Shared features have been locked.`

### Explanation card

Content:

* `No new check-ins, insights, or conversations can be created in this space.`
* `Historical data remains protected according to system rules.`

### Primary action

* `Create a new couple space`

### Secondary action

* `Return to home`

## UI notes

* Neutral tone only
* No moral framing
* No attempt at emotional consolation copy
* Keep it respectful and clean

---

# Shared Layout Spec — AuthLayout

**File:** `resources/js/Layouts/AuthLayout.vue`

## Purpose

Wrap guest auth screens.

## Structure

* Top center or left-aligned logo/app name
* Centered content container
* Minimal footer

## Required slots

* Header slot
* Main content slot

## Rules

* No sidebar
* No extra marketing content
* Focus entirely on auth task

---

# Shared Layout Spec — CoupleLayout

**File:** `resources/js/Layouts/CoupleLayout.vue`

## Purpose

Wrap authenticated Phase 1 couple screens.

## Structure

### Top nav

Left:

* App logo/name

Center or none:

* nothing for now

Right:

* authenticated user menu
* logout

### Main content

* Page header block
* Flash message region
* Content cards

## Optional shared context data

* Current couple state: none / pending / active / dissolved
* Partner linked boolean
* Invite active boolean

## Rules

* No left sidebar yet
* Keep it lightweight until dashboard phase expands navigation

---

# Shared Component Spec

These should exist before or during Phase 1 UI build.

## Components

* `AppLogo`
* `PageHeader`
* `CardPanel`
* `PrimaryButton`
* `SecondaryButton`
* `DangerButton`
* `StatusBadge`
* `TextInput`
* `InputLabel`
* `InputError`
* `FlashBanner`
* `EmptyState`

## Rules

* Keep component API simple
* No domain logic inside shared components
* Domain state comes from page props, not internal fetches

---

# Phase 1 Route-to-Page Map

Recommended route/page mapping:

* `GET /login` → `Auth/Login`
* `GET /register` → `Auth/Register`
* `GET /forgot-password` → `Auth/ForgotPassword`

Authenticated:

* `GET /couple/create` → `Couple/Create`
* `GET /couple/pending` → `Couple/Pending`
* `GET /couple/invite` → `Couple/Invite`
* `GET /invite/{token}` → `Couple/AcceptInvite`
* `GET /home` or `/couple` → conditional redirect based on state:

  * no couple → `Couple/Create`
  * pending → `Couple/Pending`
  * active → `Couple/ActiveHome`
  * dissolved → `Couple/Dissolved`

---

# Phase 1 Conditional Redirect Rules

This matters a lot. Don’t let the agent improvise.

## After login

Redirect according to state:

* No couple → create page
* Pending → pending page
* Active → active home
* Dissolved with no new couple → dissolved page or create page depending on product decision

## After registration

* Default to couple create page

## After creating couple

* Redirect to pending page

## After sending invite

* Redirect to pending page with success flash

## After accepting invite

* Redirect to active home

---

# Phase 1 Agent Handoff Note

This is Phase 1. The agent will be building the foundation for all future features. The UI must be minimal, clear, and strictly adhere to these screen definitions. The agent should not invent any new screens, states, or actions beyond what’s specified here. This is about building a solid base, not adding extra polish or features yet.
```text
Use the following Phase 1 text/spec wireframes as the source of truth for screen structure, layout hierarchy, user actions, and state rendering.

Do not invent new screens.
Do not merge screens.
Do not add sidebars, social features, or decorative marketing sections.
Respect the route-to-page mapping and conditional redirect rules.
If Laravel Breeze introduces minor auth scaffolding differences, adapt only at the implementation layer while preserving these screen intentions.
```

---


