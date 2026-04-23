<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>
<style>
.liquidcp-admin-shell{color:#10232b}
.liquidcp-admin-shell .panel{border-radius:18px;border-color:rgba(16,35,43,.12);box-shadow:0 10px 28px rgba(16,35,43,.05)}
.liquidcp-admin-shell .panel-heading{background:linear-gradient(135deg,#0f766e 0%,#0a4e58 100%);color:#fff;border-top-left-radius:18px;border-top-right-radius:18px}
.liquidcp-metric{padding:18px;border:1px solid rgba(16,35,43,.1);border-radius:16px;background:linear-gradient(180deg,#fffefb 0%,#f2eee5 100%);margin-bottom:16px}
.liquidcp-metric label{display:block;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:rgba(16,35,43,.6);margin-bottom:7px}
.liquidcp-metric strong{font-size:24px}
.liquidcp-shortcut{display:block;padding:16px;border:1px solid rgba(16,35,43,.08);border-radius:16px;background:#fff;text-decoration:none;color:#10232b;margin-bottom:12px}
.liquidcp-shortcut:hover{text-decoration:none;background:#f7fbfa}
</style>

<div class="liquidcp-admin-shell">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <strong>Liquid CP Operations Dashboard</strong>
            <a class="btn btn-default btn-sm pull-right" href="<?php echo site_url('admin/liquidcp/rules'); ?>">Edit active ruleset</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="liquidcp-metric">
                        <label>Total Quotations</label>
                        <strong><?php echo (int) $dashboard_stats['quotation_count']; ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="liquidcp-metric">
                        <label>PDF Ready</label>
                        <strong><?php echo (int) $dashboard_stats['pdf_ready_count']; ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="liquidcp-metric">
                        <label>PDF Missing</label>
                        <strong><?php echo (int) $dashboard_stats['pdf_missing_count']; ?></strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="liquidcp-metric">
                        <label>Latest Total Due</label>
                        <strong><?php echo number_format((float) $dashboard_stats['latest_total_due'], 2); ?> ZMW</strong>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4 style="margin-top:0;">Control center</h4>
                            <a class="liquidcp-shortcut" href="<?php echo site_url('admin/liquidcp/quotations'); ?>">
                                <strong>Quotations</strong><br>
                                View all saved costings and quotation snapshots.
                            </a>
                            <a class="liquidcp-shortcut" href="<?php echo site_url('admin/liquidcp/rules'); ?>">
                                <strong>Rules</strong><br>
                                Manage the active Phase 1 ruleset values.
                            </a>
                            <a class="liquidcp-shortcut" href="<?php echo site_url('admin/liquidcp/engine-runs'); ?>">
                                <strong>Engine Runs</strong><br>
                                Inspect saved engine execution metadata.
                            </a>
                            <a class="liquidcp-shortcut" href="<?php echo site_url('formbuilder/forms/list_uiforms'); ?>">
                                <strong>Advanced Tools</strong><br>
                                Open the vendor form and maintenance tools.
                            </a>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4 style="margin-top:0;">Active ruleset</h4>
                            <p><strong>Code:</strong> <?php echo html_escape($active_rule_set['code']); ?></p>
                            <p><strong>Version:</strong> <?php echo html_escape($active_rule_set['version']); ?></p>
                            <p><strong>Engine base URL:</strong> <?php echo html_escape($engine_url); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="clearfix">
                                <h4 class="pull-left" style="margin-top:0;">Recent quotations</h4>
                                <a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('admin/liquidcp/quotations'); ?>">Open full list</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quote</th>
                                            <th>Client</th>
                                            <th>Title</th>
                                            <th>Created</th>
                                            <th>Total Due</th>
                                            <th>PDF</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recent_quotes)) { ?>
                                            <tr><td colspan="7">No quotations saved yet.</td></tr>
                                        <?php } ?>
                                        <?php foreach ($recent_quotes as $quote) { ?>
                                            <tr>
                                                <td><?php echo html_escape($quote['quote_number']); ?></td>
                                                <td><?php echo html_escape($quote['client_name']); ?></td>
                                                <td><?php echo html_escape($quote['quote_title']); ?></td>
                                                <td><?php echo html_escape($quote['created_at']); ?></td>
                                                <td><?php echo number_format((float) $quote['total_due'], 2); ?> ZMW</td>
                                                <td><?php echo empty($quote['pdf_path']) ? 'Missing' : 'Ready'; ?></td>
                                                <td>
                                                    <a href="<?php echo site_url('admin/liquidcp/quotation/' . $quote['id']); ?>">View</a>
                                                    |
                                                    <a target="_blank" href="<?php echo site_url('liquidcp/quote/' . $quote['id']); ?>">Public</a>
                                                    |
                                                    <a target="_blank" href="<?php echo site_url('liquidcp/pdf/' . $quote['id']); ?>"><?php echo empty($quote['pdf_path']) ? 'Generate PDF' : 'Open PDF'; ?></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
