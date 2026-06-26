
# =============================

# 📦 PHASE 2 — WEEKLY PRIVATE CHECK-INS

# =============================

You are a senior Laravel 12 engineer building privacy-first reflective systems.

## Prerequisite

Phase 1 complete.

## Objectives

Implement a weekly private emotional reflection system for individual users.

## Scope

### 1. Weekly Check-In Rules

* One check-in per user per week
* Week defined by ISO week
* Editable only within same week
* No backfilling past weeks

### 2. Data Model

**WeeklyCheckIn**

* id
* user_id
* couple_id
* week_start_date
* emotional_availability (1–5)
* communication_effort (1–5)
* stress_level (1–5)
* appreciation_text (nullable)
* struggle_text (nullable)
* intention_text (nullable)
* created_at

Unique: (user_id + week_start_date)

### 3. Privacy Rules

* User sees full own entries
* Partner cannot see raw entries
* No exposure of text fields
* No partner completion visibility

### 4. UI Flow

* Weekly check-in form (single page)
* Sliders + optional text inputs
* Private history view (user only)

### 5. Trend Preparation

Store time-series data for:

* Emotional availability
* Communication effort
* Stress

(No shared insights yet)

## Out of Scope

* Monthly aggregation
* Conversations
* Notifications
* Advice
* AI analysis

## Deliverables

* Migration
* Model
* Policy
* Controller
* Validation
* Routes
* Inertia pages
* Notes on privacy boundaries

## Rules

* Private by default
* No emotional scoring
* No partner comparisons
* No clarifying questions

Begin implementation.

---
