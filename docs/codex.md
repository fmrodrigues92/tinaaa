# Codex Workflow

TINAAA uses Laravel Boost guidelines plus project-specific Codex skills.

## Main Guidelines

[AGENTS.md](../AGENTS.md) is the authoritative instruction source for Laravel Boost. It includes project-specific Laravel, Inertia, Fortify, Wayfinder, Pest, Pint, Sail, and frontend guidance.

Codex should follow `AGENTS.md` before making framework decisions. The project-specific skills in `skills/` add TINAAA-specific knowledge on top of those guidelines.

## Local Skills

### `laravel-backend-core`

Located at `skills/laravel-backend-core/SKILL.md`.

Use this skill when working on Laravel backend code, clean architecture boundaries, services, repositories, Form Requests, Controllers, Middleware, Models, queues, providers, or Pest backend tests.

### `inertia-react-bridge`

Located at `skills/inertia-react-bridge/SKILL.md`.

Use this skill when connecting Laravel backend behavior to Inertia React pages, typed props, Wayfinder route helpers, forms, navigation, page contracts, or frontend state driven by server data.

### `rag-specialist`

Located at `skills/rag-specialist/SKILL.md`.

Use this skill when working on semantic search, embeddings, chunking, vector storage, retrieval, prompt assembly, grounded generation, citations, hallucination controls, or answer quality evaluation.

## Collaboration Pattern

For product work, start from the user-visible behavior and identify the relevant domain. Then choose the smallest implementation path that keeps Laravel conventions intact.

For backend work, define the use case before adding infrastructure. For Inertia work, define the page contract before building UI. For RAG work, define the evidence source and retrieval expectations before choosing prompts.

## Verification Expectations

Documentation-only changes should be reviewed for clarity and consistency.

Code changes should follow `AGENTS.md`, use the relevant local skill, and run the smallest useful verification command. Backend changes usually require Pest. Frontend changes usually require TypeScript and lint checks. PHP formatting should use Pint when PHP files are modified.
