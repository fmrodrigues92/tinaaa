---
name: inertia-react-bridge
description: Use when connecting TINAAA Laravel backend behavior to Inertia React pages, typed props, Wayfinder routes, forms, navigation, page contracts, or frontend state driven by server data.
---

# Inertia React Bridge

Use this skill for work that crosses Laravel, Inertia, React, and Wayfinder in TINAAA.

## Project Baseline

- Follow `AGENTS.md` and Laravel Boost guidelines first.
- Use Inertia React v3 patterns.
- Use Wayfinder route and action helpers instead of hardcoded URLs.
- Keep backend/frontend contracts explicit and typed.
- Reuse existing starter-kit layouts, components, hooks, and UI patterns.

## Page Contracts

Before changing UI behavior, identify:

- The route or controller action that owns the page.
- The Inertia page component under `resources/js/pages`.
- The props sent by the backend.
- The TypeScript type that represents those props.
- The form submit route or action helper.

## Backend to Frontend Flow

- Controllers should return Inertia responses with clear prop names.
- Form Requests should own validation for submitted data.
- React forms should display server validation errors.
- Server flash messages should follow existing project conventions.
- Shared data should come from Inertia middleware only when it is truly global.

## Frontend Rules

- Prefer existing UI components before creating new ones.
- Use typed props and avoid broad `any` shapes.
- Keep page components focused on composition.
- Extract reusable UI only after repetition appears.
- Preserve accessibility and responsive behavior from the starter kit.

## Verification

- Run TypeScript checks for typed contract changes.
- Run frontend lint checks for React changes.
- Run focused backend tests when controller, request, or route behavior changes.
