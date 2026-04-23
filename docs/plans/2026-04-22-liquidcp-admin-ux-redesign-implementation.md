# Liquid CP Admin UX Redesign Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Make Liquid CP the primary admin experience, expose saved quotations as first-class records, and redesign the public estimator so it feels like a polished quotation product.

**Architecture:** Keep the existing Liquid CP persistence and engine bridge intact. Add dedicated quotation admin screens on top of `cepf_liquidcp_quote_meta` and `cepf_liquidcp_engine_run`, reroute the admin entry point to Liquid CP, and rewrite the shared backend navigation so vendor features move under a single `Advanced / Ziga Tools` bucket. Redesign the public wizard in the existing Liquid CP frontend views without changing the save/PDF contract.

**Tech Stack:** CodeIgniter PHP app, ZigaForm shell, MySQL/MariaDB tables, FastAPI Python engine, Playwright browser verification, PHP CLI linting, MySQL CLI inspection.

---

### Task 1: Add quotation admin routes and controller actions

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/config/routes.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/admin.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/models/model_liquidcp.php`

**Step 1: Write the failing behavior check**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/index.php/admin/liquidcp/quotations'
```

Expected: `404` or missing route before implementation.

**Step 2: Add explicit admin routes**

Add routes for:

- `admin/liquidcp`
- `admin/liquidcp/quotations`
- `admin/liquidcp/quotation/(:num)`
- `admin/liquidcp/rules`
- `admin/liquidcp/save-rules`
- `admin/liquidcp/engine-runs`

Also change the admin default route so `/admin` lands on the Liquid CP dashboard instead of the vendor form list.

**Step 3: Add controller methods**

In `application/modules/liquidcp/controllers/admin.php`, add:

- `quotations()`
- `quotation($id)`
- `engine_runs()`

Each method should:

- authenticate through the existing backend controller flow,
- load the model,
- fetch only saved snapshot data,
- and pass structured arrays into dedicated admin views.

**Step 4: Extend the model with exact retrieval helpers**

In `application/modules/liquidcp/models/model_liquidcp.php`, add:

- `listQuotes($limit = 50)`
- `searchQuotes($search = '', $limit = 50)`
- `getQuoteDetail($id)`
- `listEngineRuns($limit = 50)`
- `getDashboardStats()`

`getQuoteDetail($id)` should join quote meta and engine run data where available, then decode:

- `input_json`
- `response_json`
- `request_json`
- `response_json` from engine run if present

**Step 5: Run syntax checks**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\config\routes.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\admin.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\models\model_liquidcp.php'
```

Expected: `No syntax errors detected` for all files.

**Step 6: Verify the route exists**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/index.php/admin/liquidcp/quotations' | Select-Object StatusCode
```

Expected: `200`.

**Step 7: Commit**

If this workspace is under git:

```bash
git add application/config/routes.php application/modules/liquidcp/controllers/admin.php application/modules/liquidcp/models/model_liquidcp.php
git commit -m "feat: add liquidcp admin quotation routes"
```

If not, skip commit and continue.

### Task 2: Build the Liquid CP dashboard, quotations list, and quotation detail views

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/admin/index.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/admin/quotations.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/admin/quotation.php`
- Create: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/admin/engine_runs.php`

**Step 1: Write the failing visibility check**

Run:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "SELECT id,quote_number FROM cepf_liquidcp_quote_meta ORDER BY id DESC LIMIT 3;"
```

Confirm the rows exist in DB, then open the current admin dashboard and confirm the quotations are not visible in a dedicated list screen yet.

**Step 2: Rewrite the dashboard view**

Update `application/modules/liquidcp/views/admin/index.php` to show:

- summary cards for total quotations, PDFs generated, missing PDFs, latest total due, active ruleset,
- a compact recent quotations table,
- shortcut cards to `Quotations`, `Rules`, and `Advanced / Ziga Tools`.

**Step 3: Create the quotations list view**

Create `application/modules/liquidcp/views/admin/quotations.php` with:

- title row and search form,
- searchable table of saved quotations,
- columns: quote number, client, title, created date, total due, ruleset version, PDF status, actions,
- empty state with `Create first quotation`.

Actions should include:

- `View`
- `Public Quote`
- `Open PDF` or `Generate PDF`

**Step 4: Create the quotation detail view**

Create `application/modules/liquidcp/views/admin/quotation.php` with:

- top summary cards,
- original request inputs,
- saved line-item tables by category,
- warnings block,
- snapshot metadata block,
- engine metadata block,
- PDF action block.

The detail page must render the saved JSON snapshot even when engine-run metadata is missing.

**Step 5: Create engine runs view**

Create `application/modules/liquidcp/views/admin/engine_runs.php` with a compact table:

- quote id
- quote number
- ruleset id
- engine version
- duration ms
- created date

**Step 6: Lint the new views**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\admin\index.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\admin\quotations.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\admin\quotation.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\admin\engine_runs.php'
```

Expected: `No syntax errors detected`.

**Step 7: Browser verify existing records**

Open:

- `http://localhost/LiquidCostingPlatform/index.php/admin/liquidcp`
- `http://localhost/LiquidCostingPlatform/index.php/admin/liquidcp/quotations`
- `http://localhost/LiquidCostingPlatform/index.php/admin/liquidcp/quotation/3`

Expected:

- existing DB rows are visible,
- quotation `LCP-20260422-0003` appears,
- detail page shows saved totals and line items.

**Step 8: Commit**

```bash
git add application/modules/liquidcp/views/admin/index.php application/modules/liquidcp/views/admin/quotations.php application/modules/liquidcp/views/admin/quotation.php application/modules/liquidcp/views/admin/engine_runs.php
git commit -m "feat: add liquidcp quotation admin screens"
```

Skip if git is unavailable.

### Task 3: Consolidate the backend menu into a Liquid CP-first shell

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/views/header.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/views/header-uiform.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/default/controllers/intranet.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/views/layout.php`

**Step 1: Verify current clutter**

Open the backend and list the current top-level items visible in the main nav.

Expected today:

- forms
- records
- invoices
- import/export
- charts
- settings
- backup
- payment methods
- system check
- help
- extensions

**Step 2: Redirect admin landing**

In `application/modules/default/controllers/intranet.php`, update:

- `dashboard()`
- post-login redirect in `authenticate()`

so both land on `admin/liquidcp`.

**Step 3: Rewrite the shared backend nav**

Update `application/views/header-uiform.php` so top-level items become:

- `Dashboard`
- `Quotations`
- `Rules`
- `Engine Runs`
- `Advanced`

Under `Advanced`, move vendor links such as:

- list forms
- records
- import/export
- charts
- settings
- backup
- payment methods
- system check
- help
- extensions

Keep the links valid. Do not remove vendor access.

**Step 4: Update brand framing**

In `application/views/header.php`, swap vendor-forward branding for Liquid CP branding and point the brand home link to `admin/liquidcp`.

**Step 5: Lint the touched files**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\views\header.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\views\header-uiform.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\default\controllers\intranet.php'
```

Expected: `No syntax errors detected`.

**Step 6: Browser verify navigation**

Login with:

- username: `codex_admin`
- password: `LiquidCP!Admin2026`

Expected:

- login lands on Liquid CP dashboard,
- quotations are reachable in one click,
- `Advanced` contains vendor tools,
- vendor license validation does not interrupt the primary path.

**Step 7: Commit**

```bash
git add application/views/header.php application/views/header-uiform.php application/modules/default/controllers/intranet.php
git commit -m "feat: make liquidcp the primary admin shell"
```

Skip if git is unavailable.

### Task 4: Redesign the public estimator and saved quotation presentation

**Files:**
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/index.php`
- Modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/views/frontend/quote.php`
- Optionally modify: `C:/xampp8_3/www/LiquidCostingPlatform/application/modules/liquidcp/controllers/frontend.php`

**Step 1: Capture current working baseline**

Browser verify the existing flow:

1. open home page
2. fill inputs
3. calculate
4. save
5. open quotation
6. open PDF

Record the working quote number so regressions are obvious.

**Step 2: Tighten the frontend content hierarchy**

In `frontend/index.php`:

- reduce long explanatory paragraphs,
- keep the four-step flow,
- use card-based input groups,
- present stronger summary metrics,
- make the review step more commercial and less technical,
- keep validation and save-blocking intact.

Model the visual direction on the approved references:

- `instaprice` for estimator framing,
- invoice/dashboard layout inspiration for summary rhythm,
- pricing wizard inspiration for the step flow.

**Step 3: Improve the saved quote page**

In `frontend/quote.php`:

- show clearer quotation hierarchy,
- emphasize totals and snapshot identity,
- reduce visual flatness,
- keep direct PDF access obvious,
- keep all data based on saved snapshot.

**Step 4: Only adjust controller payloads if view data is insufficient**

If necessary, add compact derived values in `frontend.php`, but do not change the save or PDF contract unless required.

**Step 5: Lint**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\index.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\views\frontend\quote.php'
& 'C:\xampp8_3\apps\php\php.exe' -l 'C:\xampp8_3\www\LiquidCostingPlatform\application\modules\liquidcp\controllers\frontend.php'
```

Expected: `No syntax errors detected`.

**Step 6: Browser verify end-to-end**

Use Playwright to:

1. open `http://localhost/LiquidCostingPlatform/`
2. create a quotation with the workbook sample or mixed-route sample
3. calculate a live estimate
4. save the record
5. open the saved quotation
6. open the PDF

Expected:

- no save before estimate,
- quotation saved successfully,
- quote page loads,
- PDF returns `application/pdf`.

**Step 7: Commit**

```bash
git add application/modules/liquidcp/views/frontend/index.php application/modules/liquidcp/views/frontend/quote.php application/modules/liquidcp/controllers/frontend.php
git commit -m "feat: redesign liquidcp quotation frontend"
```

Skip if git is unavailable.

### Task 5: Final verification and data proof

**Files:**
- No code changes required unless verification finds a regression

**Step 1: Run Python verification**

Run:

```powershell
$env:XAMPP_LITE_ROOT='C:\xampp8_3'
& 'C:\xampp8_3\www\LiquidCostingPlatform\liquidcp-python\.venv\Scripts\python.exe' -m pytest 'C:\xampp8_3\www\LiquidCostingPlatform\liquidcp-python\tests\test_engine.py' -q
```

Expected: all tests pass.

**Step 2: Verify localhost availability**

Run:

```powershell
Invoke-WebRequest -UseBasicParsing 'http://localhost/LiquidCostingPlatform/' | Select-Object StatusCode
Invoke-WebRequest -UseBasicParsing 'http://127.0.0.1:8001/health' | Select-Object StatusCode,Content
```

Expected:

- app returns `200`
- health returns `200` and `{"status":"ok"}`

**Step 3: Verify quotations exist in admin and DB**

Run:

```powershell
& 'C:\xampp8_3\apps\mysql\bin\mysql.exe' -u root -h 127.0.0.1 -P 3306 liquid_cp -e "SELECT id,quote_number,client_name,quote_title,total_due,created_at,pdf_path FROM cepf_liquidcp_quote_meta ORDER BY id DESC LIMIT 10;"
```

Expected: existing Liquid CP rows are visible, including recent saved quotations.

**Step 4: Browser verify the full admin/user flow**

Verify all of:

- admin login lands on Liquid CP dashboard
- quotations list loads
- quotation detail loads
- advanced menu still reaches vendor form list
- frontend creates a new quote
- new quote appears in quotations admin
- PDF action works

**Step 5: If verification fails, stop and debug before claiming completion**

Do not proceed to any completion statement until:

- syntax checks pass,
- Python tests pass,
- browser end-to-end flow passes,
- and database rows match the visible admin list.

**Step 6: Commit verification-safe final state**

```bash
git add .
git commit -m "feat: deliver liquidcp admin and frontend redesign"
```

Skip if git is unavailable.

### Notes

- This workspace currently appears not to be a git worktree. If that remains true during execution, skip commit steps and continue with verification.
- Use `codex_admin` / `LiquidCP!Admin2026` for admin-side browser verification.
- Do not move or alter the owner `admin` account.
