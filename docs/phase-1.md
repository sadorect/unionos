Absolutely. Here’s a **single continuous agent-instruction file** containing **Phases 1–5**, clearly demarcated so you can save it as one master build script and still execute phase-by-phase.

Laravel version is set to **Laravel 12** throughout.
No fluff. Clean contracts. Ready for agents.

---

# 🧠 UnionOS MVP — Agent Build Instructions (Phases 1–5)

**Stack:** Laravel 12 • PostgreSQL • Inertia.js + Vue 3 • Tailwind CSS • Sanctum • Breeze
**Mode:** Phase-by-phase deterministic implementation
**Rule:** Do not implement features outside the active phase

---

# =============================

# 📦 PHASE 1 — FOUNDATION & TRUST LAYER

# =============================

You are a senior Laravel 12 engineer and security-focused product architect.

Your task is to implement PHASE 1 of the UnionOS MVP: Foundation & Trust Layer.

## Objectives

Establish the core identity and relationship container system that enables two users to form a private couple space.

## Scope

### 1. Authentication

* Email/password authentication
* Registration, login, logout, password reset
* Laravel Breeze for scaffolding
* Laravel Sanctum for session/API auth

### 2. Core Domain Models

Implement:

**User**

* id
* email
* password
* timestamps

**Couple**

* id
* status (pending | active | dissolved)
* created_at

**CoupleMembership**

* id
* couple_id
* user_id
* joined_at
* is_active

**CoupleInvite**

* id
* couple_id
* token (secure random)
* expires_at
* used_at (nullable)

### 3. Couple Linking Flow

* User A creates couple → status=pending
* System generates secure invite token
* User B joins via token
* Couple becomes active when both joined
* Max two members enforced at DB + logic level

### 4. Authorization Foundations

* Laravel Policies for User & Couple
* Users may only access their own couple
* Deny-by-default access control

### 5. UI (Minimal Functional)

* Auth screens
* Create couple screen
* Invite partner screen
* Accept invite screen
* Pending vs Active couple states

## Out of Scope

* Check-ins
* Conversations
* Dashboards
* Insights
* Notifications

## Deliverables

* Migrations
* Eloquent models
* Policies
* Controllers
* Routes
* Minimal Inertia pages
* Notes on privacy enforcement

## Rules

* No third-user support
* No social features
* No scope creep
* No clarifying questions

Begin implementation.

---


