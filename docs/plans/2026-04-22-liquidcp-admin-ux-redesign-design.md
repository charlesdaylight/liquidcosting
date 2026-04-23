# Liquid CP Admin UX Redesign Design

## Goal

Make Liquid CP feel like the real product instead of a vendor shell by:

- surfacing saved quotations as first-class admin records,
- reducing the noisy Ziga menu into a smaller Liquid CP-first navigation,
- and redesigning the public estimator UI so it looks like a polished quotation workflow.

## Current State

- Saved costings already exist in `cepf_liquidcp_quote_meta` and related engine runs exist in `cepf_liquidcp_engine_run`.
- Those records are only visible from the custom `admin/liquidcp` page.
- The stock Ziga admin navigation still dominates the backend and points users toward unrelated vendor features.
- The public wizard works, but it is too text-heavy and visually basic for a client-facing quote flow.

## Recommended Approach

Use a `Liquid CP-first shell`.

- Make Liquid CP the primary backend experience.
- Keep Ziga available as a subsystem under a single `Advanced / Ziga Tools` area.
- Reuse the existing Liquid CP quote and engine-run tables instead of inventing a separate invoice store.
- Redesign the public wizard within the current CodeIgniter/Ziga frontend so the save and PDF flow stay intact.

## Admin Information Architecture

Primary navigation should become:

- `Dashboard`
- `Quotations`
- `Rules`
- `Engine Runs`
- `Advanced / Ziga Tools`

### Dashboard

The admin landing page should become a Liquid CP operations dashboard instead of the vendor form list.

It should show:

- recent quotations,
- quotation counts,
- PDF status counts,
- active ruleset information,
- and clear shortcuts to `Quotations` and `Rules`.

### Quotations

This becomes the canonical place for saved costings.

The quotations list should show:

- quote number,
- client,
- quote title,
- created date,
- total due,
- ruleset version,
- PDF status,
- and actions.

Actions should include:

- open detail,
- open public quotation,
- open or generate PDF,
- and access to the saved engine snapshot.

### Quotation Detail

A quotation detail screen should render the saved snapshot, not a recalculation.

It should include:

- quotation metadata,
- original request inputs,
- saved line-item breakdown,
- warning messages,
- total summaries,
- ruleset version,
- engine version,
- and links to the public quote and PDF.

### Rules

Keep the existing narrow Phase 1 ruleset editor, but present it as a core Liquid CP admin section.

### Engine Runs

Keep this in the main nav for now because it supports prototype verification and troubleshooting.
If it becomes too technical later, it can move behind quotation detail screens.

### Advanced / Ziga Tools

Move vendor-heavy navigation here:

- forms,
- records,
- import/export,
- charts,
- backup,
- payment methods,
- extensions,
- system check,
- and vendor help links.

This preserves operational access without forcing users through vendor concepts first.

## Public Frontend Redesign

The public estimator should keep the same four-step flow:

1. Quote context
2. Build inputs
3. Estimate review
4. Save quotation

But the presentation should change significantly:

- less explanatory text,
- stronger hierarchy,
- more card-based input layouts,
- more visual summary blocks,
- and a cleaner quotation result state.

Visual direction should take inspiration from:

- estimator/product framing similar to [instaprice](https://onepagelove.com/instaprice/),
- invoice/dashboard layout patterns similar to [Invoice Dashboard | UI UX | SaaS](https://www.behance.net/gallery/197687689/Invoice-Dashboard-UI-UX-Saas),
- and multi-step quote/pricing wizard patterns similar to [pricing wizard inspiration on Dribbble](https://dribbble.com/search/pricing-wizard).

The key design rule is to make the page feel product-led rather than documentation-led.

## Data Model Decisions

No new invoice store should be introduced in this pass.

Use the existing:

- `cepf_liquidcp_quote_meta`
- `cepf_liquidcp_engine_run`
- `cepf_liquidcp_rule_set`

The admin work in this pass is primarily a visibility and workflow problem, not a missing persistence problem.

## Error Handling and Empty States

- If a quotation has no `pdf_path`, show `Generate PDF` instead of implying the record is broken.
- If engine-run metadata is missing for an older quote, still render the quotation detail from the saved quote snapshot and clearly flag the missing run data.
- If there are no quotations, show an intentional empty state with a `Create first quotation` call to action.

## Verification Requirements

The implementation will be considered correct when all of the following are true:

- existing saved rows in `cepf_liquidcp_quote_meta` are visible in a proper `Quotations` admin screen,
- admin login lands on the Liquid CP dashboard,
- the vendor license gate remains out of the primary path,
- the public estimator still calculates, saves, reopens, and generates PDFs,
- and `Advanced / Ziga Tools` still reaches the vendor screens when needed.

## Constraints

- Work inside the current workspace only.
- Preserve the current Liquid CP save and PDF flow.
- Do not remove vendor tooling entirely; consolidate it.
- Keep Liquid CP branding primary throughout the backend and public UX.

## Notes

- This workspace is not a git worktree, so the design doc cannot be committed here.
