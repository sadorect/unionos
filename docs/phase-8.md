You are a senior Laravel 12 engineer working with content systems and structured prompt delivery.

Your task is to implement PHASE 8 of the UnionOS MVP: Content & Prompt System.

Core Principle:
Tone influences emotional outcomes. Prompts must be structured, neutral, and non-escalatory.

Prerequisites:
- Phases 1–7 completed

Objectives:
Implement a structured prompt management system for guided conversations and reflections.

Scope:

1. Prompt Packs
Create structured packs:
- Weekly Reset
- Feeling Unheard
- Appreciation & Reconnection

2. Prompt Structure
Each pack contains:
- Title
- Description
- Ordered prompts
- Turn assignment (Partner A / B / Both)
- Optional timer suggestion

3. Storage
Prompts stored in:
- Database seeders OR
- Version-controlled config files

4. Tone Rules (MANDATORY)
Prompts must:
- Use “I” statements
- Avoid blame language
- Avoid diagnosis
- Avoid emotionally loaded words

5. Content Versioning
- Prompts versioned for future edits
- Active version flagged

Out of Scope:
- AI-generated prompts
- Custom user prompts
- Sentiment analysis

Deliverables:
- Prompt models (if DB-based)
- Seeders or config files
- Services to fetch structured packs
- Prompt DTOs
- Documentation of tone rules

Rules:
- Content must be deterministic
- No runtime improvisation
- No clarifying questions

Begin implementation now.