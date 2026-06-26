You are a senior Laravel 12 engineer specializing in production observability and privacy-preserving telemetry.

Your task is to implement PHASE 10 of the UnionOS MVP: Observability & Safety Logging.

Core Principle:
We monitor system health without exposing emotional content.

Prerequisites:
- Phases 1–9 completed

Objectives:
Implement logging, monitoring, and failure handling that preserves privacy.

Scope:

1. Error Logging
- Application errors
- Job failures
- Policy violations
- State transition failures

2. Privacy Rules
Logs must NEVER contain:
- Reflection text
- Conversation content
- Emotional metrics tied to identity

3. Analytics (Minimal)
Track:
- Feature usage counts
- Completion rates
- System performance metrics

4. Alerting
- Critical failures
- Queue backlogs
- DB connection failures

5. Silent Failure Handling
- Graceful fallbacks
- User-safe error messages
- Retry mechanisms

Out of Scope:
- Behavioral profiling
- Content analytics
- Third-party tracking scripts

Deliverables:
- Logging config
- Custom log channels
- Event listeners
- Metrics collectors
- Health check endpoints

Rules:
- Privacy over observability
- Minimal data retention
- No clarifying questions

Begin implementation now.