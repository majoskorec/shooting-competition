# AGENTS.md

## Purpose

This document defines working rules for any AI agent implementing or modifying the **Shooting Competition** project.

Before making any meaningful change, read these documents in this order:

1. `README.md`
2. `docs/domain-model.md`
3. `docs/implementation-plan.md`
4. `docs/architecture.md`

These documents together define the product scope, domain rules, implementation priorities, and technical architecture of the system.

If this document conflicts with those files, treat the domain and architecture documents as the source of truth for business behavior and system design.

---

## Project goal

The primary goal of the project is to implement a reliable web-based system for managing shooting competitions, with **M400** as the first supported competition type.

The system must be designed so that it can later support:

- `M800`
- `G200`
- `G400`

without requiring a redesign of the core scoring model.

Do not optimize for all future scenarios at once. Build a clean MVP for M400 first, while preserving the architectural seams needed for later extension.

---

## Scope discipline

When implementing changes, prefer the smallest complete solution that is consistent with the existing domain model and architecture.

Do not introduce new features that are outside the currently documented MVP unless explicitly requested.

Out of scope for the first implementation phase:

- shotgun disciplines
- online registration
- payments
- advanced exports
- complex shoot-off workflow
- advanced statistics
- excessive abstraction for hypothetical future use cases

Do not add these preemptively.

---

## Source of truth

The source of truth for scoring is:

- the competition snapshot configuration
- the stored target results
- the hit breakdown for each target result

The following are **derived data** and must never be treated as the only authoritative source:

- target subtotals
- competitor total score
- standings
- ranking snapshots
- published ranking views

Derived values may be stored for performance or presentation, but they must always be reproducible from source data.

---

## Domain modeling rules

Follow the domain model exactly unless explicitly instructed otherwise.

Key rules:

- A **target definition** is a reusable catalog concept.
- A **competition type** defines a reusable format such as M400.
- A **competition** is a concrete event.
- A competition stores a **snapshot of target configuration** used for that event.
- A **shooter** is a person.
- A **competition entry** represents a shooter participating in one competition.
- A **target result** belongs to one competition entry and one target within that competition.
- A target result is based on a **hit breakdown**, not just a manually entered subtotal.
- Rankings are derived from results and competition rules.
- Rankings may also be materialized as **snapshots** for presentation.

Do not collapse these concepts into fewer models just for convenience if doing so breaks domain clarity.

---

## JSON usage rules

The project intentionally uses structured JSON in selected places to keep the MVP simpler.

This is allowed and expected for:

- target scoring schema
- competition target configuration snapshot
- hit breakdown stored on a target result

Do not normalize these structures into additional tables unless explicitly requested.

Do not replace JSON snapshots with live references to mutable templates. Historical competition data must remain stable even if templates change later.

When reading JSON-backed structures, always validate them against expected domain rules before using them in scoring.

---

## Scoring rules

Scoring logic must live in dedicated services or domain-oriented calculation classes.

Do not place scoring rules in:

- controllers
- form classes
- templates
- UI components
- frontend code
- repository query hacks

The scoring pipeline must behave like this:

1. Store or update the hit breakdown for a target result.
2. Check consistency against the competition snapshot.
3. Compute the target subtotal.
4. Compute competitor totals from valid target results.
5. Apply tie-break rules.
6. Generate ranking snapshots for presentation.

For M400, the current tie-break order is:

1. `diviak`
2. `kamzik`
3. `srnec`
4. `liska`

Do not hardcode this in UI or presentation logic. It must come from competition configuration or its snapshot.

---

## Validation and consistency rules

The system must allow storing incomplete or inconsistent input data.

Do **not** block form submission just because:

- hit counts do not match expected shot count
- an invalid score value appears in the breakdown
- subtotal cannot yet be considered valid
- a result is incomplete

Instead:

- store the data
- mark it as inconsistent or incomplete
- prevent it from being used in official scoring and final competition closure

This distinction is important.

The agent must preserve the difference between:

- **stored data**
- **valid data**
- **officially usable data**

Do not implement hard validation that prevents users from saving working or incorrect drafts unless explicitly requested.

---

## Competition closure rules

A competition must not be marked as finalized or officially published unless the system can determine that all required data for official evaluation is sufficiently complete and consistent.

Do not allow final closure if:

- required results are missing
- unresolved inconsistencies remain
- final ranking cannot be determined
- a shoot-off or judge decision is still unresolved

The system should support draft and in-progress states separately from final published results.

---

## Ranking and result snapshot rules

Ranking must be computed from source data and competition rules.

Use ranking snapshots for:

- admin overviews
- public result pages
- category standings
- team standings

These snapshots are optimized views, not the source of truth.

The ranking layer must support:

- overall standings
- category-based standings
- team standings

The same scoring rules apply; only the evaluated subset or aggregation changes.

The architecture should allow final ranking to include explicit overrides or adjudication outcomes such as:

- shoot-off resolution
- judge decision

These actions must not silently mutate raw source scoring data.

---

## Team result rules

Team results are derived from individual competitor results.

Do not create a separate source scoring model for teams.

A team standing must be built by aggregating the results of team members within the same competition.

Store or generate team ranking snapshots as a derived output layer.

Team logic must remain separate from individual raw result storage.

---

## Start number and organizational logic

Start number assignment belongs to the competition participation layer, not to shooter identity and not to scoring logic.

Treat the following as organizational data attached to a competition entry:

- start number
- participation status
- category
- shared weapon information

The logic for assigning start numbers is organizational workflow logic, not scoring logic.

If implementing automatic assignment, keep it separate from calculation services used for scoring.

---

## Persistence guidance

Prefer a hybrid persistence model:

- relational storage for stable entities
- JSON for snapshots and structured nested data

Do not over-engineer persistence for hypothetical reporting use cases.

Use explicit, readable structures. Favor maintainability over maximal normalization.

Where denormalized fields exist, ensure they can always be rebuilt from source data.

---

## Service layer guidance

Prefer dedicated services with narrow responsibilities.

Typical responsibilities should remain separated, for example:

- competition creation from competition type
- competition snapshot generation
- target result save/update
- hit breakdown consistency checking
- subtotal calculation
- total score calculation
- ranking generation
- ranking snapshot publishing
- team aggregation
- start number assignment

Do not create large “god services” that handle every workflow in one place.

Do not move domain decisions into repositories.

Repositories should load and save data, not define scoring behavior.

---

## UI and controller guidance

Controllers should orchestrate requests and responses only.

They may:

- call services
- pass DTOs or validated request data
- return views or API responses

They must not:

- implement scoring math
- implement tie-break logic
- interpret ranking rules on their own
- duplicate business logic already present in services

Forms and UI should help users enter data and understand system state, but they must not become the place where business rules live.

---

## Migration and refactoring guidance

When adding features, prefer incremental changes that preserve the current documented model.

Do not refactor core structures just because an alternative design seems cleaner in isolation.

Before changing core behavior, verify whether the change is consistent with:

- `docs/domain-model.md`
- `docs/implementation-plan.md`
- `docs/architecture.md`

If code and documentation disagree, bring the implementation back in line with documentation unless explicitly instructed to revise the docs.

Avoid “hidden redesigns”.

If a larger redesign is truly necessary, make it explicit and keep changes scoped and well documented.

---

## Naming guidance

Use names that reflect the domain language from the documentation.

Prefer stable terms such as:

- `TargetDefinition`
- `CompetitionType`
- `Competition`
- `Competitor`
- `TargetResult`
- `RankingSnapshot`

Avoid vague names like:

- `DataManager`
- `Helper`
- `Processor`
- `Utils`
- `MixedResultThing`

Calculation services should be named by responsibility, not by generic utility wording.

---

## Testing expectations

Any meaningful scoring-related implementation should be testable.

Prioritize tests for:

- subtotal calculation
- hit breakdown consistency checking
- total result calculation
- tie-break ordering
- ranking generation
- exclusion of invalid results from official standings
- team aggregation
- competition closure conditions

Prefer focused tests around business behavior over broad integration tests with weak assertions.

---

## When uncertain

If a required behavior is not obvious, resolve uncertainty using this priority order:

1. `docs/domain-model.md`
2. `docs/implementation-plan.md`
3. `docs/architecture.md`
4. existing code, if it matches the documents

Do not invent new scoring behavior when the documents are silent.

If needed, choose the most conservative implementation that preserves:

- source-of-truth integrity
- snapshot stability
- separation of scoring from UI
- MVP simplicity

---

## Practical implementation mindset

Build the simplest correct thing.

Preserve these invariants:

- source data must remain authoritative
- snapshots must remain reproducible
- inconsistent data may be stored but not officially evaluated
- historical competitions must not change when competition types change
- scoring logic must remain outside UI
- M400 must work first
- future formats must remain possible without redesign

If a solution is simpler, clearer, and consistent with the documents, prefer it.
