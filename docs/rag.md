# RAG Strategy

TINAAA's RAG system should produce answers grounded in the developer's real professional history.

The system should not invent experience. If evidence is missing, weak, outdated, or unrelated, the answer should communicate that limitation and suggest an honest framing.

## Storage Direction

The project already uses Laravel Sail with Postgres. Postgres is the default database for application data.

Future semantic search should use Postgres with pgvector so profile evidence, metadata, filters, and embeddings can stay close to the Laravel application model.

## Source Evidence

The first source of truth is the professional profile:

- Roles and companies
- Projects and responsibilities
- Technical decisions
- Business or product impact
- Technologies and practices used
- Measurable achievements
- Lessons learned
- Constraints, tradeoffs, and context

Every generated answer should be traceable to source evidence.

## Indexing Approach

Index evidence in meaningful chunks rather than arbitrary text slices. A chunk should preserve enough context to explain why it is relevant.

Good chunk candidates include:

- A role responsibility with its outcome
- A project summary with technologies and impact
- A technical decision with context and tradeoffs
- A measurable achievement
- A behavioral example suitable for interview answers

Chunk metadata should support filtering by user, company, role, project, date range, seniority signal, technologies, and evidence type.

## Retrieval Approach

Retrieval should combine semantic similarity with structured filters. For example, a question about React and Laravel should retrieve chunks with semantic relevance and technical metadata alignment.

Retrieved evidence should be ranked by relevance, specificity, recency when useful, and strength of support. The answer-generation step should receive only the evidence needed to answer the job question.

## Answer Generation Principles

Answers should:

- Use only retrieved or explicitly supplied evidence.
- Prefer concrete examples over broad claims.
- Match the question's language and intent.
- Separate strong evidence from partial evidence.
- Include citations or references to the source experiences used.
- Avoid exaggerated seniority, invented metrics, or generic application filler.

When evidence is insufficient, the system should produce a gap-aware response instead of a fabricated one.

## Evaluation

RAG quality should be evaluated by:

- Whether the answer is supported by cited evidence.
- Whether the answer directly addresses the job question.
- Whether important relevant experiences were retrieved.
- Whether weak or missing evidence is handled honestly.
- Whether the final text sounds specific to the developer rather than generic.
