# Architecture

TINAAA uses a pragmatic clean architecture approach inside Laravel. The architecture should protect domain decisions, keep RAG behavior testable, and still let Laravel do what it does well.

## Directory Layout

Future domain code should use this structure:

```text
app/src/<domain>/application
app/src/<domain>/domain
app/src/<domain>/infrastructure
app/src/<domain>/presentation
```

Use `App\Src\<Domain>` as the namespace root for code under `app/src/<domain>`.

## Layer Responsibilities

`application` contains use cases, application services, commands, queries, DTOs, and orchestration logic. This layer coordinates work without knowing presentation details.

`domain` contains entities, value objects, domain rules, repository contracts, and business language. This layer should be small, expressive, and independent from HTTP, Inertia, queues, and database details.

`infrastructure` contains Eloquent implementations, external API clients, queue jobs, embedding providers, vector-store adapters, and persistence details. This layer satisfies contracts from the domain and application layers.

`presentation` contains Laravel Controllers, Form Requests, Middleware integration, Inertia response builders, and view data mappers. This layer translates HTTP input into application calls and translates application output into responses.

## Laravel Boundaries

Laravel-native entry points remain first class:

- Controllers receive HTTP requests and return Laravel/Inertia responses.
- Form Requests validate input before application use cases run.
- Middleware handles request concerns such as authentication, authorization, rate limiting, and shared context.
- Eloquent Models may live in `app/Models` unless a future domain needs a clear reason to colocate persistence models.
- Service Providers bind interfaces to infrastructure implementations.

Do not move existing Fortify or starter-kit code into clean architecture folders just to make the tree look uniform. New product domains should use the domain layout; framework and authentication scaffolding can stay where Laravel expects it.

## Suggested Domains

`ProfessionalProfile` owns professional experiences, roles, projects, achievements, skills, technologies, and evidence quality.

`Opportunity` owns job descriptions, company context, requirements, recruiter questions, and application-specific notes.

`KnowledgeBase` owns source documents, chunking, embeddings, semantic indexing, and evidence retrieval.

`RagAssistant` owns retrieval orchestration, prompt assembly, grounded answer generation, gap analysis, citations, and answer review workflows.

## Design Rules

- Keep services focused on use cases, not generic utility behavior.
- Create repository interfaces only when the application needs a persistence boundary.
- Avoid generic base repositories until repeated behavior proves the abstraction useful.
- Avoid CQRS, event buses, and domain events by default.
- Prefer explicit DTOs or value objects when array shapes become unclear.
- Keep validation in Form Requests for HTTP input, and keep domain invariants in the domain layer.
- Use queues for slow tasks such as embedding generation, indexing, and answer generation.
- Write Pest tests around behavior and boundaries, not implementation ceremony.
