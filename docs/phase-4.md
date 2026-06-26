
# =============================

# 📦 PHASE 4 — GUIDED CONVERSATIONS

# =============================

You are a senior Laravel 12 engineer building structured turn-based interaction systems.

## Prerequisite

Phases 1–3 complete.

## Objectives

Enable couples to engage in safe, structured conversations using guided prompts.

## Scope

### 1. Conversation Packs (Fixed)

* Weekly Reset
* Feeling Unheard
* Appreciation & Reconnection

### 2. Conversation Session Model

**ConversationSession**

* id
* couple_id
* pack_type
* status (active | paused | completed | abandoned)
* current_turn_user_id
* started_at
* completed_at

**ConversationTurn**

* id
* session_id
* user_id
* prompt_id
* response_text
* created_at

### 3. Flow Rules

* One speaker at a time
* Turn-based responses
* No editing after submission
* Timer per turn (UI-controlled)
* Session locks on completion

### 4. Privacy

* Only couple members can access
* No live typing indicators
* No audio/video recording
* No transcript export

### 5. UI Flow

* Ground rules screen
* Turn screen (Partner A → B)
* Reflection pause
* Closing appreciation
* Completion screen

## Out of Scope

* Free chat
* AI prompts
* Sentiment analysis
* Notifications

## Deliverables

* Models
* Prompt seeders
* Services
* Policies
* Controllers
* Inertia pages
* Notes on conflict containment

## Rules

* Structure reduces escalation
* No improvisation
* No clarifying questions

Begin implementation.
