
# =============================

# 📦 PHASE 3 — MONTHLY COUPLE INSIGHTS

# =============================

You are a senior Laravel 12 engineer building emotionally neutral shared insight systems.

## Prerequisite

Phases 1–2 complete.

## Objectives

Provide couples with shared monthly alignment insights without exposing individual vulnerability.

## Scope

### 1. Monthly Check-In Rules

* One submission per partner per month
* Reveal only after both submit
* No partial reveals

### 2. Data Model

**MonthlyCoupleCheckIn**

* id
* couple_id
* user_id
* month (YYYY-MM)
* felt_heard (1–5)
* felt_appreciated (1–5)
* conflict_safety (1–5)
* emotional_closeness (1–5)
* submitted_at

Unique: (user_id + month)

### 3. Insight Merge Logic

For each dimension:

* Calculate response difference
* Classify:

  * Aligned (≤1 diff)
  * Needs Conversation (≥2 diff)

### 4. Privacy Rules

* Do NOT expose individual scores
* Do NOT show side-by-side answers
* Only show merged classification

### 5. Shared Insight UI

* Reveal screen after both submit
* Alignment indicators
* CTA → “Start Guided Conversation”

## Out of Scope

* Advice
* AI analysis
* Notifications
* Historical comparisons

## Deliverables

* Migration
* Model
* Aggregation service
* Policy
* Controller
* Inertia pages
* Neutral insight wording
* Notes on non-blaming language

## Rules

* Insight ≠ judgment
* Neutral tone only
* No partner labeling
* No clarifying questions

Begin implementation.

---
