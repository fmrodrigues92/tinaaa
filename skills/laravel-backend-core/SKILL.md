---
name: laravel-backend-core
description: Use when working on TINAAA Laravel backend code, clean architecture boundaries, application services, repositories, Form Requests, Controllers, Middleware, Models, queues, providers, or Pest backend tests.
---

# Laravel Backend Core

Use this skill for backend work in TINAAA.

## Project Baseline

- Follow `AGENTS.md` and Laravel Boost guidelines first.
- Laravel-native entry points remain first class.
- Use Sail/Postgres as the local development baseline.
- Use Pest for backend behavior tests.
- Keep the design pragmatic: clean boundaries without unnecessary ceremony.

## Architecture

Future product domains should use:

```text
app/src/<domain>/application
app/src/<domain>/domain
app/src/<domain>/infrastructure
app/src/<domain>/presentation
```

Layer intent:

- `application`: use cases, commands, queries, DTOs, application services.
- `domain`: entities, value objects, domain rules, repository contracts.
- `infrastructure`: Eloquent repositories, provider clients, queues, adapters.
- `presentation`: Controllers, Form Requests, Middleware integration, Inertia response builders.

## Backend Rules

- Keep Controllers thin: validate, authorize, call a use case, return a response.
- Put HTTP validation in Form Requests.
- Put business invariants in domain objects or focused services.
- Create repository interfaces only when a real persistence boundary is needed.
- Avoid generic base repositories until repeated behavior proves useful.
- Use Eloquent directly for simple CRUD that does not need a domain boundary.
- Bind interfaces to implementations in a Service Provider when needed.
- Prefer queues for slow embedding, indexing, and generation tasks.

## Testing

- Add or update Pest tests for behavioral changes.
- Prefer feature tests for HTTP flows.
- Use unit tests for domain rules, value objects, and focused services.
- Run the smallest useful test set before finishing.
