# Liquid CP Estimate Studio Redesign Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Redesign the Phase 1 Liquid CP estimate studio into a gated premium product flow with self-service auth, owner-scoped dashboards, tooltip-driven guidance, and full Liquid CP branding.

**Architecture:** Extend the existing `liquidcp` module into a user-facing product surface instead of exposing the estimate wizard publicly. Reuse the existing legacy user table for self-service Phase 1 authentication, add ownership to saved quote records, create a user dashboard and auth flow, then restyle the studio into a focused one-step-at-a-time wizard with minimal visible copy. Preserve the existing Python engine bridge, quote snapshot persistence, and PDF generation contract.

**Tech Stack:** CodeIgniter PHP app, existing Auth/session library, MariaDB/MySQL, Liquid CP custom module, Ziga shell views/layouts, jQuery frontend behavior, PHP CLI linting, MySQL CLI inspection, Playwright browser verification.

---

### Task 1: Add estimate ownership to Liquid CP quote records

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/models/model_liquidcp.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/frontend.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/admin.php`

**Step 1: Write the failing database check**

Run:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "DESCRIBE cepf_liquidcp_quote_meta;"
```

Expected: no owner user column exists yet.

**Step 2: Add the owner column**

Run a non-destructive migration query:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "ALTER TABLE cepf_liquidcp_quote_meta ADD COLUMN owner_user_id INT(11) NULL AFTER record_id;"
```

Expected: column added successfully.

**Step 3: Update save logic**

In `application/modules/liquidcp/controllers/frontend.php` and `application/modules/liquidcp/models/model_liquidcp.php`:

- require an authenticated user before saving
- read the logged-in user id from session
- persist `owner_user_id` on each quote

**Step 4: Add scoped quote retrieval helpers**

In `model_liquidcp.php`, add or extend helpers for:

- `listQuotesByOwner($owner_user_id, $limit = 50)`
- `countQuotesByOwner($owner_user_id)`
- admin-safe fallback methods that still list all quotes

**Step 5: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\models\model_liquidcp.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\frontend.php'
```

Expected: `No syntax errors detected`.

**Step 6: Verify persistence**

Run:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "DESCRIBE cepf_liquidcp_quote_meta;"
```

Expected: `owner_user_id` appears.

### Task 2: Build self-service Liquid CP auth routes and screens

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/config/routes.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/default/controllers/intranet.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/user/models/model_user.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/account.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/account/login.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/account/signup.php`

**Step 1: Write the failing route check**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/index.php/liquidcp/signup'
```

Expected: `404` before implementation.

**Step 2: Add auth routes**

Add explicit routes for:

- `liquidcp/login`
- `liquidcp/signup`
- `liquidcp/logout`
- `liquidcp/account/create`
- `liquidcp/account/authenticate`

**Step 3: Add account controller**

Create `application/modules/liquidcp/controllers/account.php` with methods:

- `login()`
- `signup()`
- `authenticate()`
- `create()`
- `logout()`

Use the existing Auth/session patterns, but keep user-facing redirects pointed at the Liquid CP user dashboard.

**Step 4: Add model helpers for self-service signup**

In `application/modules/user/models/model_user.php`, add helpers for:

- lookup by username
- lookup by email
- create active user

Use the existing `cepf_cest_uiform_user` table and keep the Phase 1 model simple.

**Step 5: Create premium auth views**

Create the new login and signup views with:

- Liquid CP branding
- no vendor naming
- concise copy
- focused mobile-style card layout

**Step 6: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\config\routes.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\account.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\user\models\model_user.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\account\login.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\account\signup.php'
```

Expected: `No syntax errors detected`.

**Step 7: Browser verify**

Open:

- `http://localhost/LiquidCostingPlatform/index.php/liquidcp/login`
- `http://localhost/LiquidCostingPlatform/index.php/liquidcp/signup`

Expected: both load with Liquid CP branding.

### Task 3: Add a logged-in user dashboard and set it as the user landing page

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/frontend.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/dashboard.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/models/model_liquidcp.php`

**Step 1: Write the failing flow check**

Open the current root route and confirm it loads the estimate wizard directly for anonymous users.

Expected today: studio is publicly visible.

**Step 2: Add dashboard action**

In `application/modules/liquidcp/controllers/frontend.php`, add:

- `dashboard()`

This action should:

- require login
- load only the current user’s estimates
- show a deliberate empty state if none exist

**Step 3: Change root flow**

Adjust `index()` so:

- anonymous users see a Liquid CP public gateway
- authenticated normal users are routed to dashboard

If splitting public entry and dashboard is cleaner, add a dedicated gateway action and route instead of overloading one view.

**Step 4: Build the dashboard view**

Create `application/modules/liquidcp/views/frontend/dashboard.php` with:

- welcome header
- `New estimate` primary CTA
- recent estimates list
- actions for `Open`, `PDF`, and `New estimate`
- empty state with `Create your first estimate`

**Step 5: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\frontend.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\dashboard.php'
```

Expected: `No syntax errors detected`.

**Step 6: Browser verify**

Expected:

- after login/signup, user lands on dashboard
- dashboard shows only owned estimates
- `New estimate` is obvious

### Task 4: Gate the estimate studio and quote save flow behind authentication

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/frontend.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/config/routes.php`

**Step 1: Write the failing access check**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/index.php/liquidcp/estimate'
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/index.php/liquidcp/save'
```

Expected today: endpoints are reachable without Liquid CP user auth assumptions.

**Step 2: Gate studio and mutations**

Require login for:

- opening the estimate studio
- running a live estimate if that is considered tracked in-app usage
- saving a quote

Public access should remain allowed for:

- `liquidcp/quote/{id}`
- `liquidcp/pdf/{id}`

**Step 3: Add redirect behavior**

Anonymous users attempting studio access should be redirected to Liquid CP login.

**Step 4: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\frontend.php'
```

Expected: `No syntax errors detected`.

**Step 5: Browser verify**

Expected:

- anonymous user cannot interact with studio
- authenticated user can
- public quote/PDF still work by direct link

### Task 5: Redesign the estimate studio into a focused premium wizard

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/index.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/frontend.php`

**Step 1: Capture failing UX baseline**

Open the current studio and confirm:

- explanatory text dominates the top of the page
- helper copy is always visible
- the form is not the only primary focus

**Step 2: Replace visible helper copy with tooltip model**

In `frontend/index.php`:

- keep field labels visible
- move detailed explanations into question-mark tooltip icons
- remove always-on helper paragraphs where possible

**Step 3: Remove distracting navigation**

Ensure the studio view has:

- no breadcrumb
- no side nav
- no admin CTA
- no dashboard clutter in the studio body

**Step 4: Strengthen the wizard interaction**

Implement or restyle:

- compact progress indicator
- one-step-at-a-time flow
- consistent fixed-position or anchored next/back actions
- stronger review metrics
- compact success state after save

**Step 5: Replace typography and visual language**

Update the user-facing typography and surface styling so the page feels:

- modern
- premium
- mobile-first

Avoid the current editorial font direction if it still reads as wrong for the product.

**Step 6: Keep Phase 1 logic intact**

Do not change:

- field meaning
- engine payload contract
- save/PDF behavior

unless strictly required for the redesign.

**Step 7: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\index.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\frontend.php'
```

Expected: `No syntax errors detected`.

**Step 8: Browser verify**

Expected:

- form dominates initial view
- detail copy is hidden behind tooltips
- user is guided linearly
- save still requires a valid calculation

### Task 6: Update the quote page and dashboard return flow

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/quote.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/index.php`

**Step 1: Verify current behavior**

Open a saved quote and confirm current actions and hierarchy.

Expected today: quote page works, but still follows the previous aesthetic and action structure.

**Step 2: Update quote page styling and hierarchy**

Refresh `frontend/quote.php` so it matches the new user app branding and visual system.

**Step 3: Return users to dashboard-oriented flow**

Update the post-save state in `frontend/index.php` so success actions include:

- `Open quote`
- `Open PDF`
- `Back to dashboard`

**Step 4: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\quote.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\index.php'
```

Expected: `No syntax errors detected`.

### Task 7: Remove visible vendor branding and rename visible product surfaces to Liquid CP

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/default/views/intranet/login.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/views/header.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/views/header-uiform.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/index.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/quote.php`
- Modify any other user-visible Liquid CP views identified during implementation

**Step 1: Write the failing text search**

Run:

```powershell
Get-ChildItem 'C:\xampp8_3\www\LiquidCostingPlatform\application' -Recurse -File -Include *.php | Select-String -Pattern 'Ziga|ZigaForm|UiForm' -SimpleMatch:$false | Select-Object -First 100
```

Expected: visible vendor branding appears in user-facing files.

**Step 2: Replace visible user-facing branding**

Change user-visible brand strings and headings to `Liquid CP`.

Keep internal technical references untouched unless needed.

**Step 3: Leave vendor access functional**

Do not break admin advanced/vendor tooling just because branding text changes.

**Step 4: Run syntax checks**

Run PHP lint on each touched file.

Expected: `No syntax errors detected`.

**Step 5: Browser verify**

Expected:

- frontend auth, dashboard, studio, and quote views no longer show vendor branding
- admin shell is Liquid CP-first

### Task 8: Final end-to-end verification

**Files:**
- No code changes required unless verification finds regressions

**Step 1: Verify localhost health**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/' | Select-Object StatusCode
Invoke-WebRequest -UseBasicParsing 'http://127.0.0.1:8001/health' | Select-Object StatusCode,Content
```

Expected:

- app returns `200`
- engine health returns `200`

**Step 2: Verify Python engine tests**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\www\LiquidCostingPlatform\liquidcp-python\.venv\Scripts\python.exe' -m pytest 'C:\xampp8_3\www\LiquidCostingPlatform\liquidcp-python\tests\test_engine.py' -q
```

Expected: tests pass.

**Step 3: Browser verify full user flow**

Use Playwright to verify:

1. open public entry
2. create a self-service user
3. land on dashboard
4. open new estimate
5. calculate
6. save
7. return/open dashboard
8. confirm only owned estimates appear
9. open quote
10. open PDF

**Step 4: Browser verify admin oversight**

Login as:

- username: `codex_admin`
- password: `LiquidCP!Admin2026`

Verify:

- admin can still access Liquid CP admin screens
- admin can see all estimates
- public quote/PDF behavior still works

**Step 5: Database verification**

Run:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "SELECT id, owner_user_id, quote_number, client_name, total_due, created_at FROM cepf_liquidcp_quote_meta ORDER BY id DESC LIMIT 10;"
```

Expected: recent saved estimates include the owning user id.

**Step 6: Stop if verification fails**

Do not claim completion until:

- PHP lint passes
- engine tests pass
- signup/login flow works
- dashboard scoping works
- studio flow works
- quote/PDF links work
- branding changes are visible

### Notes

- This workspace is not a git worktree, so commit steps are intentionally omitted.
- Use the existing Liquid CP admin accounts unchanged.
- Keep the implementation Phase 1 only; do not add team/account sharing in this pass.
