---
name: rag-specialist
description: Use when working on TINAAA semantic search, embeddings, chunking, Postgres/pgvector planning, retrieval, prompt assembly, grounded answer generation, citations, hallucination controls, or answer quality evaluation.
---

# RAG Specialist

Use this skill for RAG and semantic-search work in TINAAA.

## Product Principle

TINAAA must generate answers grounded in the developer's real professional history. It should not invent experience, metrics, technologies, roles, or confidence.

If evidence is insufficient, the system should expose the gap and suggest an honest answer strategy.

## Storage Baseline

- The project uses Sail with Postgres.
- Use Postgres as the default application database.
- Plan vector search around pgvector unless the user explicitly chooses an external vector store later.
- Keep metadata and embeddings queryable together whenever practical.

## Evidence Model

Treat professional profile data as source evidence:

- Roles and companies.
- Projects and responsibilities.
- Technical decisions.
- Business or product impact.
- Technologies and practices.
- Measurable achievements.
- Lessons learned.
- Constraints and tradeoffs.

Every generated answer should be traceable to source evidence.

## Chunking Guidance

Chunk by meaning, not arbitrary size alone.

Good chunks include:

- One responsibility plus outcome.
- One project summary plus technologies and impact.
- One decision plus context and tradeoffs.
- One achievement with measurable support.
- One behavioral interview example.

Metadata should support filters such as user, role, company, project, time period, technology, evidence type, and source strength.

## Retrieval Guidance

- Combine semantic similarity with structured metadata filters.
- Rank by relevance, specificity, evidence strength, and recency when useful.
- Retrieve enough context to answer well, but avoid flooding generation with weakly related chunks.
- Preserve source identifiers for citations.

## Generation Guidance

Generated answers should:

- Use only retrieved or explicitly supplied evidence.
- Be specific to the developer.
- Cite or reference supporting experiences.
- Distinguish strong evidence from partial evidence.
- Avoid generic job-application filler.
- Refuse or qualify unsupported claims.

## Evaluation

Check whether the answer is supported, direct, specific, honest about gaps, and useful for the target job question.
