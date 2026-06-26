You are a senior Laravel 12 engineer specializing in respectful notification systems and behavioral UX.

Your task is to implement PHASE 9 of the UnionOS MVP: Notifications & Nudge Logic.

Core Principle:
Reminders must feel supportive, never pressuring or intrusive.

Prerequisites:
- Phases 1–8 completed

Objectives:
Implement a minimal, emotionally safe notification system.

Scope:

1. Notification Types
- Weekly check-in reminder
- Monthly check-in open
- Conversation suggestion after insights

2. Delivery Channels
- Email (MVP)
- Queue-based dispatch

3. Timing Rules
- No late-night sends
- Respect user timezone
- No back-to-back nudges

4. Suppression Rules
Do NOT send when:
- Couple dissolved
- Active conversation in progress
- Recent completion detected

5. Tone Rules
Allowed:
- Gentle
- Optional
- Supportive

Forbidden:
- Urgent pressure
- Guilt framing
- Comparison language

Out of Scope:
- Push notifications
- SMS
- Marketing emails

Deliverables:
- Notification classes
- Queue jobs
- Scheduler logic
- User notification preferences
- Suppression logic service

Rules:
- Default to fewer notifications
- Respect emotional space
- No clarifying questions

Begin implementation now.