# Liquid CP Estimate Studio Redesign Design

## Goal

Redesign the Phase 1 Liquid CP estimate experience so it feels like a premium mobile-style product instead of a vendor form shell, while gating all in-app usage behind authentication and keeping Phase 1 scope intact.

## Approved Product Decisions

- Brand name everywhere is `Liquid CP`.
- Estimate interaction requires authentication.
- Authentication for normal users is self-service.
- Normal users land on a dashboard of their own past estimates after login.
- Saved estimates are private in-app to the owner and admins.
- Quote and PDF links remain publicly shareable by direct link.
- The estimate studio remains a guided Phase 1 wizard.
- The user journey should feel forced and linear, without breadcrumb or extra navigation inside the studio.

## Current State

- The main public experience lives in `application/modules/liquidcp/views/frontend/index.php`.
- The current Phase 1 studio already uses a four-step wizard:
  1. Quote context
  2. Build inputs
  3. Review estimate
  4. Save quotation
- The page is visually improved from stock vendor UI, but it still leads with too much explanation and still exposes admin-oriented framing.
- Authentication currently exists only for backend/admin usage through the legacy user table `cepf_cest_uiform_user`.
- Saved estimates in `cepf_liquidcp_quote_meta` do not currently store a user owner.

## Recommended Architecture

Keep Liquid CP as a dedicated application layer on top of the existing shell, not as a single undeletable builder form.

Reasons:

- The current `liquidcp` module is already the correct seam for product behavior.
- The wizard, engine bridge, quote persistence, and PDF output are already custom enough that forcing them back into generic form-builder semantics would add friction.
- User dashboard, self-service auth, owner-scoped estimate lists, and premium app UX all fit better in dedicated controllers/views than in a protected form artifact.

This keeps:

- ZigaForm as the underlying shell where needed
- Python as the calculation source of truth
- Liquid CP as the visible user product and admin brand

## User Experience Design

### 1. Public Entry

The root entry should no longer expose the estimate form to anonymous visitors.

It should become a simple product gateway with:

- Liquid CP brand framing
- concise positioning copy
- `Sign in`
- `Create account`

The estimate studio is not visible until after authentication.

### 2. Authentication

Normal users get a dedicated app-facing login and signup experience.

Characteristics:

- minimal, premium, mobile-style layout
- no vendor names
- no backend/admin framing
- immediate sign-in after successful signup

Use the existing user table as the base account store for Phase 1 rather than creating a second auth store.

### 3. User Dashboard

After login, normal users land on a dashboard instead of the studio.

The dashboard should show:

- estimate list owned by the logged-in user
- quote number
- quote title
- created date
- total due
- actions for `Open`, `PDF`, and `New estimate`

It should feel like a lightweight history screen, not an admin table.

Primary CTA:

- `New estimate`

### 4. Estimate Studio

The studio should become a focused, full-screen wizard flow.

Rules:

- only one step visible at a time
- no breadcrumb
- no sidebar navigation
- no admin links
- no extra route-hopping while inside the flow
- user cannot skip directly around the journey

The visible UI on page load should prioritize:

- step title
- core fields
- progress indicator
- next action

Detailed explanations should move behind question-mark tooltip icons beside labels or headings.

### 5. After Save

When an estimate is saved:

- show a short success state
- provide `Open quote`
- provide `Open PDF`
- provide `Back to dashboard`

The intended flow returns the user to their dashboard list.

## Studio Visual Direction

The estimate studio should feel like a premium mobile app translated to desktop, not like a form-builder page.

### Principles

- Form-first, not copy-first
- Large tap/click targets
- Tight hierarchy
- Controlled use of color
- Strong but restrained typography
- Minimal visible guidance
- Summary surfaces only when useful

### Layout

- compact top bar with title and progress only
- large central card or stacked pane for current step
- fixed-position or consistently placed `Back` / `Next`
- review state uses finance-style metric cards instead of dense documentation blocks

### Typography

Replace the current font direction with a more app-like premium sans-forward system.

The type should feel:

- modern
- premium
- clean on mobile
- less editorial and less decorative than the current pairing

### Tooltips

Every detailed explanation that is currently always visible should move behind a `?` icon.

Tooltip behavior:

- hover on desktop
- focus/tap accessible on mobile
- short, direct language
- no long paragraphs

### Content Reduction

Unsupported Phase 1 concepts should not be explained in large visible blocks.

Preferred pattern:

- remove them from the main flow where possible
- mention only when necessary via tooltip, compact notice, or admin-facing rules context

## Data Ownership Design

Add estimate ownership to saved records.

Each saved estimate should store:

- owning user id

Visibility rules:

- normal users see only their own estimates in-app
- admins can see all estimates
- public quote and PDF endpoints remain accessible by direct link

This gives the product basic accountability and usage traceability without introducing full team/account modeling in Phase 1.

## Admin Experience

Admins keep the broader system view.

Admin should be able to:

- view all estimates
- view rules
- view engine runs
- inspect user activity at an aggregate level where relevant

The user-facing app and the admin area should feel separate.

User app:

- client product
- private records
- guided estimate flow

Admin:

- operations shell
- oversight
- diagnostics

## Branding Design

Remove visible mentions of:

- `Ziga`
- `ZigaForm`
- `UiForm`
- vendor-first product labels

Replace them with `Liquid CP` in:

- login and signup screens
- studio screens
- dashboard screens
- admin navigation labels where shown to operators
- titles and headers
- browser/page titles
- visible buttons and notices

Vendor tooling may remain technically present underneath, but it should not dominate visible language.

## Phase 1 Scope Guardrails

This redesign still respects the current Phase 1 boundary:

- manual inputs only
- no GIS routing
- no route intelligence
- no reverse-solve finance optimizer
- no external integration layer

The redesign is about product UX, access control, ownership, and branding, not a Phase 2 calculation expansion.

## Error Handling And Empty States

### Anonymous access

- anonymous user trying to open studio routes should be redirected to login

### Empty dashboard

- new user with no estimates should see a deliberate empty state with `Create your first estimate`

### Public quote/PDF

- direct quote/PDF links continue to work without requiring dashboard access

### Invalid save flow

- save remains blocked until a valid estimate has been calculated

## Verification Requirements

The redesign is correct when all of the following are true:

- anonymous users cannot interact with the estimate studio
- new users can self-register and log in
- logged-in users land on a dashboard of their own estimates
- users can create a new estimate from the dashboard
- studio shows minimal visible copy and uses tooltip-driven detail
- breadcrumb and extra navigation are removed from the studio journey
- saved estimates are private in-app to the owner and admins
- quote and PDF links remain shareable by direct link
- visible Ziga/ZigaForm branding is removed from frontend and admin framing

## Constraints

- Work within the existing CodeIgniter/Ziga shell
- Preserve the Python engine request/response contract
- Preserve quote and PDF generation behavior
- Keep Phase 1 calculation boundaries intact
- Use the existing user table for Phase 1 auth expansion unless blocked

## Notes

- This workspace is not a git repository, so the design doc cannot be committed here.
- The next step is implementation planning, not code changes.
