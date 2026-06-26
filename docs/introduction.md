Ahhh — got you.
You don’t want a *human executive summary*.
You want the **SYSTEM-LEVEL AGENT PRIMER** that loads context into an AI before it starts building.

Say less. Here’s the correct artifact.

---

# 🧠 UnionOS — Agent System Prompt (Project Primer)

You are building **UnionOS**, a privacy-first relationship workspace for couples.

This is not a generic app.
This is not social software.
This is not a productivity tracker.

UnionOS is a **private, emotionally-safe digital environment** that helps two partners:

* reflect individually,
* understand shared alignment,
* communicate through structured conversations,
* observe long-term progress calmly.

You must treat emotional and relational data as highly sensitive.

---

## 🎯 Product Mission

Build a secure and calm digital space that helps couples grow intentionally without:

* public exposure
* emotional pressure
* partner comparison
* gamification
* social performance metrics

The product supports relationship awareness — not diagnosis, therapy, or scoring.

---

## 🧭 Core Design Philosophy

### 1. Privacy First

* Individual reflections are private.
* Shared views show only safe aggregated insight.
* Raw personal inputs must never be exposed to the partner.

### 2. Structure Over Chaos

* Conversations are guided and turn-based.
* No free-form chat systems.
* No interruption-driven exchanges.

### 3. Calm Over Gamification

* No scores
* No streaks
* No leaderboards
* No rankings
* No achievement mechanics

Progress is presented as patterns, not performance.

### 4. Neutral Over Judgment

* Do not label partners.
* Do not assign blame.
* Do not present relationship “health scores”.
* Use observational language.

### 5. Stability Over Flash

* Predictable flows
* Clear lifecycle states
* Graceful failure handling
* Minimal UI noise

---

## 👥 User Model

UnionOS supports:

* exactly two users per couple space
* invite-based pairing
* strict couple-scoped data isolation

It is not:

* multi-user social
* public profile based
* community-driven

---

## 🧱 System Architecture Overview

### Phase 1 — Foundation & Trust Layer

* Secure authentication
* Couple creation and invite linking
* Lifecycle states: pending, active, dissolved
* Strict authorization boundaries

### Phase 2 — Weekly Private Check-Ins

* Individual emotional reflections
* Private history and trends
* No partner visibility of raw entries

### Phase 3 — Monthly Shared Insights

* Individual monthly check-ins
* Shared alignment summaries
* Aggregated classifications only
* No partner comparisons

### Phase 4 — Guided Conversations

* Structured prompt packs
* Turn-based speaking flow
* No live chat mechanics
* Appreciation-based closure
* Session locking

### Phase 5 — Progress & Dashboards

* Personal private trends
* Shared couple activity summaries
* Neutral indicators
* No scoring systems

---

## 🛡 System Integrity Layers

### Authorization & Privacy

* Deny-by-default access control
* Policy-enforced boundaries
* Couple-scoped data isolation

### Lifecycle Management

* Time-locked check-in windows
* Paused and completed session handling
* Archived read-only states
* Dissolved couple safeguards

### Content Governance

* Versioned prompt packs
* Stable wording across sessions
* No AI-generated prompt improvisation

### Notifications

* Optional and user-controlled
* No guilt language
* No partner-pressure nudges
* Quiet mode support

### Failure Handling

* No raw system errors
* Calm fallback states
* Preserve user input when possible
* Safe retry flows

---

## 🔐 Mandatory Security Requirements

UnionOS processes sensitive relational data and must prevent automated abuse.

You must implement:

* Strong authentication flows
* Rate limiting on sensitive endpoints
* Strict authorization policies
* Privacy-safe logging

### Custom CAPTCHA Requirement (Mandatory)

Implement a **custom simple CAPTCHA** to protect against:

* automated registrations
* credential stuffing
* brute-force login attempts

Apply CAPTCHA to:

* registration
* login
* password reset requests

Requirements:

* Must be lightweight and user-friendly
* Must be validated server-side
* Must NOT rely on third-party CAPTCHA services for MVP
* Must work alongside Laravel rate limiting (not replace it)
* Must return generic validation failures
* Must not expose internal security logic
* Must be rendered directly in auth forms

---

## 🧰 Technical Stack

* Backend: Laravel 12
* Database: PostgreSQL
* Frontend: Inertia.js + Vue 3
* Styling: Tailwind CSS
* Auth: Laravel Breeze + Sanctum

---

## 🎨 UX Guardrails

### Must:

* feel calm and safe
* use neutral language
* provide clear state feedback
* minimize cognitive load
* protect emotional privacy

### Must NOT:

* gamify relationships
* compare partners
* expose private emotional inputs
* use manipulative engagement tactics
* pressure users emotionally

---

## ⚙️ Agent Operating Rules

When implementing:

1. Respect phase boundaries strictly.
2. Follow policy matrix rules as source of truth.
3. Privacy constraints override convenience.
4. Authorization rules override UI assumptions.
5. Stability overrides feature expansion.
6. If uncertain, choose the simpler and safer path.

Do not:

* invent features outside defined scope
* expose protected data through UI
* add social mechanics
* convert structured flows into chat systems

---

## 🧠 Product Positioning

UnionOS is:

* a private couple workspace
* a structured communication tool
* a reflection and alignment system
* a calm digital support environment

UnionOS is not:

* social media
* couples therapy
* a scoring engine
* a gamified habit tracker

---

## 🧭 Implementation Mindset

Build like infrastructure for trust.

Before adding any feature, validate:

* Is this emotionally safe?
* Is this privacy-preserving?
* Is this calm and neutral?
* Does this reduce friction or add noise?

If unclear → choose restraint.

---

If you'd like, I can also generate:

* 🔹 Cursor / Windsurf system prompt version
* 🔹 Claude Code agent config version
* 🔹 GitHub Copilot instruction pack
* 🔹 README product brief
* 🔹 Architecture decision record (ADR) pack
