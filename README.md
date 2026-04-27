# TINAAA

**TINAAA** means **This is not an AI Apply**.

This project is a Laravel study case for building a grounded job-application assistant. The goal is to register detailed professional experiences, index them for semantic search, and use those real experiences to answer job-description questions with accuracy, context, and traceability.

TINAAA is not intended to generate random, generic application text. It is designed to help a developer turn their own career history into well-structured answers that remain faithful to what they actually did.

## Stack

- Laravel 13
- Laravel Fortify authentication
- Inertia Laravel 3
- React 19
- Laravel Wayfinder
- Pest 4
- Laravel Boost
- Laravel Sail
- Postgres

Laravel Sail with Postgres is already part of the local development baseline. Postgres is the default application database, and future RAG/vector-search work should build from this foundation, with pgvector planned as the vector extension when semantic indexing is implemented.

## Project Intent

TINAAA will store professional evidence such as roles, projects, responsibilities, achievements, technical decisions, business impact, and lessons learned. When a job description or recruiter question is provided, the system should retrieve relevant evidence and help draft grounded answers.

The core product principle is simple: answers must be based on the developer's recorded reality. If the available evidence is weak, the system should expose that gap instead of inventing confidence.

## Architecture Direction

Future domain code should follow a pragmatic clean architecture style:

```text
app/src/<domain>/application
app/src/<domain>/domain
app/src/<domain>/infrastructure
app/src/<domain>/presentation
```

Laravel-native entry points remain first class. Controllers, Middleware, Form Requests, Models, Service Providers, queues, and tests should continue to use Laravel conventions. The clean architecture structure is a boundary tool, not a reason to fight the framework.

Suggested initial domains:

- `ProfessionalProfile`
- `Opportunity`
- `KnowledgeBase`
- `RagAssistant`

See [docs/architecture.md](docs/architecture.md) for the full architecture guide.

## Codex Workflow

The project keeps Laravel Boost guidelines in [AGENTS.md](AGENTS.md). Those guidelines remain authoritative for Laravel, Inertia, Wayfinder, Pest, Sail, and framework-specific work.

Additional project-specific Codex skills are versioned in `skills/`:

- `laravel-backend-core`
- `inertia-react-bridge`
- `rag-specialist`

See [docs/codex.md](docs/codex.md) for when to use each skill.

## Documentation

- [Architecture](docs/architecture.md)
- [Codex Workflow](docs/codex.md)
- [RAG Strategy](docs/rag.md)
- [Feature Review](docs/features/README.md)
