<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
$response = $quote['response'];
$input = $quote['input'];
$warnings = ! empty($response['warnings']) ? $response['warnings'] : array();
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <strong>Quotation Detail: <?php echo html_escape($quote['quote_number']); ?></strong>
        <a class="btn btn-default btn-sm pull-right" href="<?php echo site_url('admin/liquidcp/quotations'); ?>">Back to quotations</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="well">
                    <small>Total Due</small><br>
                    <strong><?php echo number_format((float) $response['total_due'], 2); ?> ZMW</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <small>Build Cost</small><br>
                    <strong><?php echo number_format((float) $response['build_cost'], 2); ?> ZMW</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <small>Net Build Cost</small><br>
                    <strong><?php echo number_format((float) $response['net_build_cost'], 2); ?> ZMW</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <small>ROI Years</small><br>
                    <strong><?php echo $response['roi_years'] === null ? 'N/A' : number_format((float) $response['roi_years'], 3); ?></strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h4>Quotation metadata</h4>
                <p><strong>Client:</strong> <?php echo html_escape($quote['client_name']); ?></p>
                <p><strong>Email:</strong> <?php echo html_escape($quote['client_email']); ?></p>
                <p><strong>Company:</strong> <?php echo html_escape($quote['company_name']); ?></p>
                <p><strong>Title:</strong> <?php echo html_escape($quote['quote_title']); ?></p>
                <p><strong>Created:</strong> <?php echo html_escape($quote['created_at']); ?></p>
                <p><strong>Status:</strong> <?php echo html_escape($quote['status']); ?></p>
            </div>
            <div class="col-md-4">
                <h4>Snapshot metadata</h4>
                <p><strong>Ruleset Version:</strong> <?php echo html_escape($quote['rule_set_version']); ?></p>
                <p><strong>Engine Version:</strong> <?php echo html_escape($quote['engine_version']); ?></p>
                <p><strong>Engine Run ID:</strong> <?php echo empty($quote['engine_run_row_id']) ? 'Missing' : (int) $quote['engine_run_row_id']; ?></p>
                <p><strong>Rule Set ID:</strong> <?php echo empty($quote['rule_set_id']) ? 'Missing' : (int) $quote['rule_set_id']; ?></p>
                <p><strong>Duration:</strong> <?php echo isset($quote['duration_ms']) ? (int) $quote['duration_ms'] . 'ms' : 'Missing'; ?></p>
            </div>
            <div class="col-md-4">
                <h4>Actions</h4>
                <p><a target="_blank" href="<?php echo site_url('liquidcp/quote/' . $quote['id']); ?>">Open public quotation</a></p>
                <p><a target="_blank" href="<?php echo site_url('liquidcp/pdf/' . $quote['id']); ?>"><?php echo empty($quote['pdf_path']) ? 'Generate PDF now' : 'Open saved PDF'; ?></a></p>
                <p><strong>PDF Path:</strong> <?php echo empty($quote['pdf_path']) ? 'Not generated yet' : html_escape($quote['pdf_path']); ?></p>
            </div>
        </div>

        <hr>

        <h4>Original request inputs</h4>
        <div class="row">
            <div class="col-md-3"><p><strong>Build Profile:</strong><br><?php echo html_escape($input['build_profile']); ?></p></div>
            <div class="col-md-3"><p><strong>New Aerial:</strong><br><?php echo number_format((float) $input['distance_new_aerial'], 2); ?> m</p></div>
            <div class="col-md-3"><p><strong>New Underground:</strong><br><?php echo number_format((float) $input['distance_new_underground'], 2); ?> m</p></div>
            <div class="col-md-3"><p><strong>Existing Aerial:</strong><br><?php echo number_format((float) $input['distance_existing_aerial'], 2); ?> m</p></div>
            <div class="col-md-3"><p><strong>Existing Duct:</strong><br><?php echo number_format((float) $input['distance_existing_duct'], 2); ?> m</p></div>
            <div class="col-md-3"><p><strong>Customer NRC:</strong><br><?php echo number_format((float) $input['customer_nrc'], 2); ?> ZMW</p></div>
            <div class="col-md-3"><p><strong>Customer MRC:</strong><br><?php echo number_format((float) $input['customer_mrc'], 2); ?> ZMW</p></div>
            <div class="col-md-3"><p><strong>Exchange Rate:</strong><br><?php echo number_format((float) $input['exchange_rate'], 2); ?></p></div>
        </div>

        <hr>

        <h4>Warnings</h4>
        <?php if (empty($warnings)) { ?>
            <p>No warnings returned by the saved snapshot.</p>
        <?php } else { ?>
            <ul>
                <?php foreach ($warnings as $warning) { ?>
                    <li><?php echo html_escape($warning); ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <hr>

        <?php foreach ($response['line_items'] as $category => $items) { ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong><?php echo html_escape(ucfirst($category)); ?></strong>
                    <span class="pull-right">
                        <?php
                        $category_total = 0;
                        foreach ($items as $item) {
                            $category_total += (float) $item['amount'];
                        }
                        echo number_format($category_total, 2);
                        ?> ZMW
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) { ?>
                                <tr>
                                    <td><?php echo html_escape($item['label']); ?></td>
                                    <td><?php echo html_escape($item['quantity']); ?></td>
                                    <td><?php echo html_escape($item['unit']); ?></td>
                                    <td><?php echo number_format((float) $item['unit_rate'], 2); ?></td>
                                    <td><?php echo number_format((float) $item['amount'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
