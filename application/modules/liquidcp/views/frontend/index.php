<?php if (! defined('BASEPATH')) { exit('No direct script access allowed'); } ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Space+Grotesk:wght@400;500;700&display=swap');
.liquidcp-app{--ink:#0d2428;--muted:rgba(13,36,40,.72);--teal:#0f766e;--teal-deep:#0b4f58;--paper:rgba(255,252,247,.94);--line:rgba(13,36,40,.1);--gold:#ba7f2b;--shadow:0 28px 90px rgba(13,36,40,.12);background:radial-gradient(circle at 0 0,rgba(15,118,110,.24),transparent 28%),radial-gradient(circle at 100% 10%,rgba(186,127,43,.24),transparent 24%),linear-gradient(160deg,#fbf8f1 0%,#f1e7d8 46%,#efe4d2 100%);color:var(--ink);font-family:"Space Grotesk","Segoe UI",sans-serif;min-height:100vh;padding:34px 0 72px;position:relative;overflow:hidden}
.liquidcp-app:before,.liquidcp-app:after{content:"";position:absolute;border-radius:999px;filter:blur(12px);opacity:.75}.liquidcp-app:before{width:340px;height:340px;background:rgba(15,118,110,.08);top:-100px;left:-60px}.liquidcp-app:after{width:280px;height:280px;background:rgba(186,127,43,.08);right:-80px;bottom:40px}
.liquidcp-app h1,.liquidcp-app h2,.liquidcp-app h3,.liquidcp-app h4{font-family:"Fraunces",Georgia,serif;letter-spacing:-.03em}.liquidcp-shell{position:relative;z-index:1}.liquidcp-hero,.liquidcp-stage-wrap,.liquidcp-side-card,.liquidcp-category,.liquidcp-summary-card{background:var(--paper);border:1px solid var(--line);border-radius:28px;box-shadow:var(--shadow)}
.liquidcp-hero{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);gap:22px;padding:28px;margin-bottom:22px}.liquidcp-kicker,.liquidcp-chip,.liquidcp-hero-tag{display:inline-flex;align-items:center;gap:8px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}.liquidcp-kicker{padding:9px 14px;background:rgba(15,118,110,.12);color:var(--teal)}.liquidcp-title{font-size:58px;line-height:.92;margin:18px 0 16px;max-width:11ch}.liquidcp-subtitle{color:var(--muted);font-size:16px;line-height:1.7;max-width:58ch}
.liquidcp-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}.liquidcp-btn{display:inline-flex;align-items:center;justify-content:center;min-height:46px;border-radius:999px;padding:0 20px;font-weight:700;letter-spacing:.01em;text-decoration:none;transition:transform .18s ease,box-shadow .18s ease,background .18s ease}.liquidcp-btn:hover,.liquidcp-btn:focus{text-decoration:none;transform:translateY(-1px)}.liquidcp-btn-primary{background:linear-gradient(135deg,var(--teal) 0%,var(--teal-deep) 100%);border:1px solid var(--teal-deep);color:#fff;box-shadow:0 16px 34px rgba(11,79,88,.24)}.liquidcp-btn-secondary{background:rgba(255,255,255,.72);border:1px solid rgba(13,36,40,.12);color:var(--ink)}
.liquidcp-hero-board{background:linear-gradient(160deg,rgba(10,79,88,.98),rgba(7,45,52,.96));color:#ecf9f6;border-radius:24px;padding:22px;position:relative;overflow:hidden}.liquidcp-hero-board:before{content:"";position:absolute;inset:auto -40px -60px auto;width:180px;height:180px;border-radius:28px;transform:rotate(20deg);background:linear-gradient(180deg,rgba(255,255,255,.12),rgba(255,255,255,.02))}.liquidcp-hero-board h3{margin:10px 0 12px;font-size:30px}.liquidcp-hero-board p{color:rgba(236,249,246,.74);margin-bottom:18px}
.liquidcp-board-grid,.liquidcp-progress,.liquidcp-form-grid,.liquidcp-form-grid-3,.liquidcp-summary{display:grid;gap:14px}.liquidcp-board-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.liquidcp-board-metric,.liquidcp-step,.liquidcp-field,.liquidcp-metric,.liquidcp-mini,.liquidcp-summary-card{border-radius:20px}.liquidcp-board-metric{padding:16px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1)}.liquidcp-board-metric span,.liquidcp-label{display:block;font-size:11px;text-transform:uppercase;letter-spacing:.12em;margin-bottom:8px}.liquidcp-board-metric span{color:rgba(236,249,246,.66)}.liquidcp-board-metric strong{font-size:18px}
.liquidcp-progress{grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:22px}.liquidcp-step{padding:16px 18px;background:rgba(255,255,255,.62);border:1px solid rgba(13,36,40,.08)}.liquidcp-step.active{background:linear-gradient(135deg,var(--teal) 0%,var(--teal-deep) 100%);color:#fff;box-shadow:0 16px 34px rgba(11,79,88,.16)}.liquidcp-step strong{display:block;font-size:15px}.liquidcp-step span{color:rgba(13,36,40,.62)}.liquidcp-step.active span{color:rgba(255,255,255,.76)}
.liquidcp-main{display:grid;grid-template-columns:minmax(0,1.14fr) minmax(290px,.86fr);gap:22px;align-items:start}.liquidcp-stage-wrap{padding:24px}.liquidcp-stage{display:none}.liquidcp-stage.active{display:block;animation:liquidcp-fade .24s ease}@keyframes liquidcp-fade{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}.liquidcp-stage-header{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;margin-bottom:20px;flex-wrap:wrap}.liquidcp-stage-header h3{margin:0 0 8px;font-size:34px}.liquidcp-stage-header p{color:var(--muted);margin:0;max-width:54ch}
.liquidcp-field{background:linear-gradient(180deg,rgba(255,255,255,.88),rgba(245,238,225,.74));border:1px solid rgba(13,36,40,.08);padding:16px}.liquidcp-field label{display:block;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(13,36,40,.72);margin-bottom:8px}.liquidcp-field small{display:block;margin-top:8px;color:rgba(13,36,40,.52);line-height:1.45}.liquidcp-field .form-control{height:48px;border-radius:14px;border-color:rgba(13,36,40,.12);box-shadow:none;font-size:15px}.liquidcp-field .form-control:focus{border-color:rgba(15,118,110,.44);box-shadow:0 0 0 3px rgba(15,118,110,.09)}
.liquidcp-banner,.liquidcp-error{padding:14px 16px;border-radius:18px;margin-bottom:18px;line-height:1.55}.liquidcp-banner{background:rgba(15,118,110,.09);border:1px solid rgba(15,118,110,.14)}.liquidcp-banner.warn{background:rgba(186,127,43,.1);border-color:rgba(186,127,43,.18)}.liquidcp-error{background:rgba(191,61,61,.08);border:1px solid rgba(191,61,61,.14);color:#7e2727}
.liquidcp-summary{grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:16px}.liquidcp-metric,.liquidcp-mini,.liquidcp-summary-card{padding:18px;border:1px solid rgba(13,36,40,.08);background:linear-gradient(180deg,rgba(255,255,255,.86),rgba(244,237,227,.8))}.liquidcp-metric strong,.liquidcp-mini strong{font-size:24px;line-height:1.1}.liquidcp-review{display:grid;grid-template-columns:minmax(0,1fr) minmax(260px,.9fr);gap:16px}
.liquidcp-category{padding:0;overflow:hidden}.liquidcp-category-head{padding:16px 18px;display:flex;justify-content:space-between;align-items:center;gap:12px;background:rgba(243,235,222,.85);border-bottom:1px solid rgba(13,36,40,.08)}.liquidcp-category table{margin:0}.liquidcp-category th,.liquidcp-category td{vertical-align:middle!important}
.liquidcp-side{display:grid;gap:16px;position:sticky;top:24px}.liquidcp-side-card{padding:22px}.liquidcp-side-card h4{margin-top:0;margin-bottom:10px;font-size:24px}.liquidcp-side-card p,.liquidcp-side-card li{color:var(--muted);line-height:1.6}.liquidcp-chip{padding:8px 12px;background:rgba(15,118,110,.1);color:var(--teal-deep);margin:0 8px 8px 0}.liquidcp-chip.gold{background:rgba(186,127,43,.14);color:#8f6017}
.liquidcp-actions{display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-top:24px;padding-top:22px;border-top:1px solid rgba(13,36,40,.08)}.liquidcp-actions-left,.liquidcp-actions-right{display:flex;gap:12px;flex-wrap:wrap}.liquidcp-hidden{display:none!important}.liquidcp-save-results .alert{border-radius:18px;border:1px solid rgba(33,110,75,.14);background:rgba(33,110,75,.08)}
@media (max-width:1200px){.liquidcp-hero,.liquidcp-main,.liquidcp-review{grid-template-columns:1fr}.liquidcp-side{position:static}}@media (max-width:992px){.liquidcp-title{font-size:44px}.liquidcp-progress,.liquidcp-form-grid,.liquidcp-form-grid-3,.liquidcp-summary,.liquidcp-board-grid{grid-template-columns:1fr}}@media (max-width:768px){.liquidcp-app{padding-top:22px}.liquidcp-hero,.liquidcp-stage-wrap,.liquidcp-side-card{padding:20px;border-radius:24px}.liquidcp-stage-header h3{font-size:28px}.liquidcp-actions{flex-direction:column}}
</style>

<div class="liquidcp-app">
    <div class="container liquidcp-shell">
        <section class="liquidcp-hero">
            <div>
                <span class="liquidcp-kicker">Liquid CP Prototype</span>
                <h1 class="liquidcp-title">Estimate Studio for fibre quotations.</h1>
                <p class="liquidcp-subtitle">A four-step quotation flow backed by the contained Python engine. Enter workbook-aligned route inputs, inspect the breakdown live, then freeze the result as a saved quotation and PDF.</p>
                <div class="liquidcp-hero-actions">
                    <a class="liquidcp-btn liquidcp-btn-primary" href="#liquidcp-stage-wrap">Start estimate</a>
                    <a class="liquidcp-btn liquidcp-btn-secondary" href="<?php echo site_url('admin/liquidcp'); ?>" target="_blank">Open admin</a>
                </div>
            </div>
            <div class="liquidcp-hero-board">
                <span class="liquidcp-hero-tag">Prototype signal</span>
                <h3>Quotation-first flow</h3>
                <p>Modelled as a guided pricing product rather than a raw builder form, with Phase 1 constraints shown clearly.</p>
                <div class="liquidcp-board-grid">
                    <div class="liquidcp-board-metric"><span>Ruleset</span><strong><?php echo html_escape($active_rule_set['version']); ?></strong></div>
                    <div class="liquidcp-board-metric"><span>Workbook Anchor</span><strong>1000m sample</strong></div>
                    <div class="liquidcp-board-metric"><span>Currency</span><strong>ZMW</strong></div>
                    <div class="liquidcp-board-metric"><span>Save Mode</span><strong>Immutable snapshot</strong></div>
                </div>
            </div>
        </section>

        <div class="liquidcp-progress">
            <div class="liquidcp-step active" data-step-pill="1"><strong>01. Client</strong><span>Who the quotation is for</span></div>
            <div class="liquidcp-step" data-step-pill="2"><strong>02. Build</strong><span>Workbook-aligned route inputs</span></div>
            <div class="liquidcp-step" data-step-pill="3"><strong>03. Review</strong><span>Live engine breakdown</span></div>
            <div class="liquidcp-step" data-step-pill="4"><strong>04. Save</strong><span>Snapshot and quotation PDF</span></div>
        </div>

        <div class="liquidcp-main">
            <div class="liquidcp-stage-wrap" id="liquidcp-stage-wrap">
                <form id="liquidcp-wizard-form">
                    <input type="hidden" name="<?php echo html_escape($csrf_token_name); ?>" id="liquidcp-csrf-token" value="<?php echo html_escape($csrf_hash); ?>">
                    <div id="liquidcp-validation" class="liquidcp-error liquidcp-hidden"></div>

                    <div class="liquidcp-stage active" data-step="1">
                        <div class="liquidcp-stage-header">
                            <div>
                                <h3>Set the quotation context.</h3>
                                <p>Keep the opening step lean. Capture the commercial identity, then move straight into the build assumptions.</p>
                            </div>
                            <div class="liquidcp-chip gold">Required before pricing</div>
                        </div>
                        <div class="liquidcp-form-grid">
                            <div class="liquidcp-field">
                                <label>Client name</label>
                                <input class="form-control" name="client_name" value="Prototype Client">
                                <small>The customer or stakeholder receiving the quotation.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Client email</label>
                                <input class="form-control" name="client_email" value="client@example.com">
                                <small>Used on the saved quotation and public quote page.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Company name</label>
                                <input class="form-control" name="company_name" value="Liquid CP Demo Account">
                                <small>The contracting or billing entity shown with the quotation.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Quote title</label>
                                <input class="form-control" name="quote_title" value="Prototype fibre build estimate">
                                <small>A short commercial label for the costing.</small>
                            </div>
                        </div>
                    </div>

                    <div class="liquidcp-stage" data-step="2">
                        <div class="liquidcp-stage-header">
                            <div>
                                <h3>Enter build distances and commercial inputs.</h3>
                                <p>Phase 1 uses only the workbook fields already proven in the prototype engine. Unsupported finance and GIS fields stay out of the flow.</p>
                            </div>
                            <div class="liquidcp-chip">Seeded rules only</div>
                        </div>
                        <div class="liquidcp-form-grid-3">
                            <div class="liquidcp-field">
                                <label>Build profile</label>
                                <select class="form-control" name="build_profile">
                                    <option value="prototype-general">Prototype General</option>
                                </select>
                                <small>The seeded Phase 1 profile linked to the active ruleset.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Distance new aerial (m)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="distance_new_aerial" value="<?php echo html_escape($defaults['distance_new_aerial']); ?>">
                                <small>Primary workbook distance used in the sample sheet.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Distance new underground (m)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="distance_new_underground" value="<?php echo html_escape($defaults['distance_new_underground']); ?>">
                                <small>Prototype underground construction distance.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Distance existing aerial (m)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="distance_existing_aerial" value="<?php echo html_escape($defaults['distance_existing_aerial']); ?>">
                                <small>Used where existing aerial infrastructure offsets new build.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Distance existing duct (m)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="distance_existing_duct" value="<?php echo html_escape($defaults['distance_existing_duct']); ?>">
                                <small>Existing duct route distance carried into the engine.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Customer NRC (ZMW)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="customer_nrc" value="<?php echo html_escape($defaults['customer_nrc']); ?>">
                                <small>Non-recurring charge used when calculating net build cost.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Customer MRC (ZMW)</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="customer_mrc" value="<?php echo html_escape($defaults['customer_mrc']); ?>">
                                <small>Monthly recurring charge used for ROI timing.</small>
                            </div>
                            <div class="liquidcp-field">
                                <label>Exchange rate</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="exchange_rate" value="<?php echo html_escape($defaults['exchange_rate']); ?>">
                                <small>Applied to the equipment defaults seeded in the active ruleset.</small>
                            </div>
                        </div>
                    </div>

                    <div class="liquidcp-stage" data-step="3">
                        <div class="liquidcp-stage-header">
                            <div>
                                <h3>Review the live estimate.</h3>
                                <p>Run the Python engine, inspect the frozen category totals, and check for warnings before you move to save.</p>
                            </div>
                            <button class="liquidcp-btn liquidcp-btn-primary" type="button" id="liquidcp-calculate-btn">Calculate live estimate</button>
                        </div>
                        <div id="liquidcp-engine-message" class="liquidcp-banner">No live estimate yet. Move here after entering the build inputs, then calculate.</div>
                        <div id="liquidcp-summary" class="liquidcp-hidden">
                            <div class="liquidcp-summary">
                                <div class="liquidcp-metric"><span class="liquidcp-label">Labour</span><strong id="metric-labour">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">Materials</span><strong id="metric-materials">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">Admin</span><strong id="metric-admin">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">Wayleave</span><strong id="metric-wayleave">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">VAT</span><strong id="metric-vat">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">Total Due</span><strong id="metric-total">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">Net Build Cost</span><strong id="metric-net-build-cost">-</strong></div>
                                <div class="liquidcp-metric"><span class="liquidcp-label">ROI Years</span><strong id="metric-roi-years">-</strong></div>
                            </div>
                            <div class="liquidcp-review">
                                <div id="liquidcp-line-items"></div>
                                <div class="liquidcp-side" style="position:static;">
                                    <div class="liquidcp-summary-card"><h4>Snapshot</h4><div id="liquidcp-summary-chips"></div></div>
                                    <div class="liquidcp-summary-card"><h4>ROI</h4><div class="liquidcp-mini"><span class="liquidcp-label">ROI Months</span><strong id="metric-roi-months">-</strong></div></div>
                                    <div class="liquidcp-summary-card"><h4>Warnings</h4><ul id="liquidcp-warning-list"><li>No warnings yet.</li></ul></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="liquidcp-stage" data-step="4">
                        <div class="liquidcp-stage-header">
                            <div>
                                <h3>Save the quotation snapshot.</h3>
                                <p>Liquid CP stores the original request, full engine response, engine version, and ruleset version before opening the public quote or PDF.</p>
                            </div>
                            <div class="liquidcp-chip gold">Save blocks until a valid engine response exists</div>
                        </div>
                        <div id="liquidcp-save-message" class="liquidcp-banner warn">Calculate a live estimate before saving.</div>
                        <div class="liquidcp-form-grid">
                            <div class="liquidcp-side-card">
                                <h4>Prototype disclaimer</h4>
                                <p><?php echo html_escape($prototype_disclaimer); ?></p>
                            </div>
                            <div class="liquidcp-side-card">
                                <h4>Stored with each quotation</h4>
                                <p>Request payload, line-item breakdown, ROI outputs, engine metadata, ruleset version, and PDF reference.</p>
                            </div>
                        </div>
                        <div id="liquidcp-save-results" class="liquidcp-save-results liquidcp-hidden" style="margin-top:18px;"></div>
                    </div>

                    <div class="liquidcp-actions">
                        <div class="liquidcp-actions-left">
                            <button type="button" class="liquidcp-btn liquidcp-btn-secondary" id="liquidcp-prev-btn">Previous</button>
                            <button type="button" class="liquidcp-btn liquidcp-btn-primary" id="liquidcp-next-btn">Next step</button>
                        </div>
                        <div class="liquidcp-actions-right">
                            <button type="button" class="liquidcp-btn liquidcp-btn-primary liquidcp-hidden" id="liquidcp-save-btn">Save estimate</button>
                        </div>
                    </div>
                </form>
            </div>

            <aside class="liquidcp-side">
                <div class="liquidcp-side-card">
                    <h4>Phase 1 scope</h4>
                    <div>
                        <span class="liquidcp-chip">Wizard UX</span>
                        <span class="liquidcp-chip">Workbook rules</span>
                        <span class="liquidcp-chip">Saved quotations</span>
                        <span class="liquidcp-chip gold">PDF output</span>
                    </div>
                </div>
                <div class="liquidcp-side-card">
                    <h4>Sample anchor</h4>
                    <p>New aerial: 1000m</p>
                    <p>Customer NRC: ZMW 800</p>
                    <p>Customer MRC: ZMW 2765</p>
                    <p>Exchange rate: 28</p>
                </div>
                <div class="liquidcp-side-card">
                    <h4>Deferred for later phases</h4>
                    <ul>
                        <li>GIS routing and route intelligence</li>
                        <li>Reverse-solve finance optimisation</li>
                        <li>External integrations and imports</li>
                        <li>Broader finance decision workflows</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

<script type="text/javascript">
(function ($) {
    var currentStep = 1;
    var latestEstimate = null;

    function formatMoney(value) {
        if (value === null || typeof value === 'undefined') {
            return '-';
        }
        return 'ZMW ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function numberValue(name) {
        return Number($('*[name="' + name + '"]').val() || 0);
    }

    function setValidation(message) {
        $('#liquidcp-validation').toggleClass('liquidcp-hidden', !message).text(message || '');
    }

    function validateStep(step) {
        if (step === 1) {
            if (!$('input[name="client_name"]').val().trim()) {
                return 'Client name is required.';
            }
            if (!$('input[name="client_email"]').val().trim()) {
                return 'Client email is required.';
            }
            if (!$('input[name="quote_title"]').val().trim()) {
                return 'Quote title is required.';
            }
        }
        if (step === 2) {
            var totalDistance = numberValue('distance_new_aerial') + numberValue('distance_new_underground') + numberValue('distance_existing_aerial') + numberValue('distance_existing_duct');
            if (totalDistance <= 0) {
                return 'At least one route distance is required before pricing.';
            }
        }
        if (step === 3 && !latestEstimate) {
            return 'Run the live estimate before moving to the save stage.';
        }
        return '';
    }

    function syncActions() {
        $('#liquidcp-prev-btn').toggle(currentStep > 1);
        $('#liquidcp-next-btn').toggle(currentStep < 4);
        $('#liquidcp-save-btn').toggleClass('liquidcp-hidden', currentStep !== 4);
        $('#liquidcp-save-btn').prop('disabled', !latestEstimate);
    }

    function setStep(step) {
        currentStep = step;
        $('[data-step]').removeClass('active');
        $('[data-step="' + step + '"]').addClass('active');
        $('[data-step-pill]').removeClass('active');
        $('[data-step-pill="' + step + '"]').addClass('active');
        syncActions();
        setValidation('');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateCsrf(csrf) {
        if (csrf) {
            $('#liquidcp-csrf-token').attr('name', csrf.token_name).val(csrf.hash);
        }
    }

    function categoryTotal(items) {
        var total = 0;
        $.each(items, function (_, item) {
            total += Number(item.amount || 0);
        });
        return total;
    }

    function renderLineItems(lineItems) {
        var order = ['labour', 'materials', 'admin', 'wayleave', 'equipment'];
        var html = '';
        $.each(order, function (_, category) {
            var items = lineItems[category] || [];
            html += '<div class="liquidcp-category"><div class="liquidcp-category-head"><div><strong>' + category.charAt(0).toUpperCase() + category.slice(1) + '</strong></div><div><span class="liquidcp-label">Category Total</span><strong>' + formatMoney(categoryTotal(items)) + '</strong></div></div><div class="table-responsive"><table class="table table-striped"><thead><tr><th>Item</th><th>Qty</th><th>Unit</th><th>Rate</th><th>Amount</th></tr></thead><tbody>';
            if (!items.length) {
                html += '<tr><td colspan="5">No line items for this category.</td></tr>';
            }
            $.each(items, function (_, item) {
                html += '<tr><td>' + item.label + '</td><td>' + Number(item.quantity).toLocaleString() + '</td><td>' + item.unit + '</td><td>' + formatMoney(item.unit_rate) + '</td><td>' + formatMoney(item.amount) + '</td></tr>';
            });
            html += '</tbody></table></div></div>';
        });
        $('#liquidcp-line-items').html(html);
    }

    function renderEstimate(estimate) {
        latestEstimate = estimate;
        $('#liquidcp-summary').removeClass('liquidcp-hidden');
        $('#metric-labour').text(formatMoney(estimate.labour_total));
        $('#metric-materials').text(formatMoney(estimate.materials_total));
        $('#metric-admin').text(formatMoney(estimate.admin_total));
        $('#metric-wayleave').text(formatMoney(estimate.wayleave_total));
        $('#metric-vat').text(formatMoney(estimate.vat));
        $('#metric-total').text(formatMoney(estimate.total_due));
        $('#metric-net-build-cost').text(formatMoney(estimate.net_build_cost));
        $('#metric-roi-months').text(estimate.roi_months === null ? 'N/A' : Number(estimate.roi_months).toFixed(2));
        $('#metric-roi-years').text(estimate.roi_years === null ? 'N/A' : Number(estimate.roi_years).toFixed(3));
        renderLineItems(estimate.line_items);
        $('#liquidcp-summary-chips').html('<span class="liquidcp-chip">Ruleset ' + estimate.rule_set_version + '</span><span class="liquidcp-chip">Engine ' + estimate.engine_version + '</span><span class="liquidcp-chip gold">Latency ' + Number(estimate._duration_ms || 0) + 'ms</span>');

        var warnings = estimate.warnings || [];
        var warningHtml = !warnings.length ? '<li>No warnings returned by the engine.</li>' : '';
        $.each(warnings, function (_, warning) {
            warningHtml += '<li>' + warning + '</li>';
        });
        $('#liquidcp-warning-list').html(warningHtml);

        $('#liquidcp-engine-message').removeClass('liquidcp-error').addClass('liquidcp-banner').text(warnings.length ? 'Estimate calculated. Review the warnings before saving.' : 'Estimate calculated successfully.');
        $('#liquidcp-save-message').removeClass('liquidcp-error').addClass('liquidcp-banner warn').text('Estimate is ready to save as a quotation snapshot.');
        syncActions();
    }

    function submitTo(url, onSuccess) {
        $.ajax({ url: url, method: 'POST', data: $('#liquidcp-wizard-form').serialize(), dataType: 'json' }).done(function (response) {
            updateCsrf(response.csrf);
            if (!response.success) {
                throw new Error(response.message || 'Request failed.');
            }
            onSuccess(response);
        }).fail(function (xhr) {
            var message = 'Request failed.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
                updateCsrf(xhr.responseJSON.csrf);
            }
            $('#liquidcp-engine-message, #liquidcp-save-message').removeClass('liquidcp-banner warn').addClass('liquidcp-error').text(message);
        });
    }

    $('#liquidcp-next-btn').on('click', function () {
        var message = validateStep(currentStep);
        if (message) {
            setValidation(message);
            return;
        }
        if (currentStep < 4) {
            setStep(currentStep + 1);
        }
    });

    $('#liquidcp-prev-btn').on('click', function () {
        if (currentStep > 1) {
            setStep(currentStep - 1);
        }
    });

    $('#liquidcp-calculate-btn').on('click', function () {
        var message = validateStep(2);
        if (message) {
            setValidation(message);
            setStep(2);
            return;
        }
        submitTo('<?php echo site_url('liquidcp/estimate'); ?>', function (response) {
            renderEstimate(response.estimate);
        });
    });

    $('#liquidcp-save-btn').on('click', function () {
        if (!latestEstimate) {
            $('#liquidcp-save-message').removeClass('liquidcp-banner warn').addClass('liquidcp-error').text('Calculate a live estimate first.');
            return;
        }
        submitTo('<?php echo site_url('liquidcp/save'); ?>', function (response) {
            $('#liquidcp-save-results').removeClass('liquidcp-hidden').html('<div class="alert"><strong>' + response.quote_number + '</strong> saved. <a href="' + response.quote_url + '" target="_blank">Open quote</a> <a href="' + response.pdf_url + '" target="_blank">Open PDF</a></div>');
        });
    });

    setStep(1);
})(jQuery);
</script>
