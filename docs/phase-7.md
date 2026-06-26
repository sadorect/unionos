You are a senior Laravel 12 engineer experienced in resilient system design and lifecycle state management.

Your task is to implement PHASE 7 of the UnionOS MVP: System States & Edge-Case Logic.

Core Principle:
Real users behave unpredictably. The system must remain safe and stable under incomplete, abandoned, or interrupted flows.

Prerequisites:
- Phases 1–6 completed

Objectives:
Implement lifecycle states and edge-case handling across the system.

Scope:

1. Couple Lifecycle States
Define and enforce:
- pending
- active
- dissolved

Rules:
- pending → limited features
- active → full access
- dissolved → read-only historical access, no new activity

2. User Departure Handling
If a user:
- Deletes account
- Becomes inactive long-term

System must:
- Soft-delete user
- Dissolve couple automatically
- Lock shared features
- Preserve historical data

3. Check-In Edge Cases
- Missed week → no penalty, no backfill
- Partial submission → discard at period end
- Duplicate submission → reject

4. Monthly Insight Edge Cases
- One partner submits → no reveal
- Month ends incomplete → archive silently

5. Conversation Edge Cases
- Mid-session disconnect → mark incomplete
- Completed session → permanent lock
- Pause/resume allowed
- No partial visibility

6. Re-Coupling Rules
- Dissolved couples cannot reactivate
- New pairing requires new Couple record
- Historical data remains tied to old couple_id

7. Time-Based Locks
- Weekly check-ins lock at week end
- Monthly insights lock at month end
- Conversations lock at completion

Out of Scope:
- Notifications
- UI redesign
- Analytics

Deliverables:
- State enums
- Model state logic
- Guard clauses in services/controllers
- Scheduled tasks for time-based locks
- Edge-case test scenarios

Rules:
- Fail safely
- Preserve data integrity
- No silent data loss
- No clarifying questions

Begin implementation now.