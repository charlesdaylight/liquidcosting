<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <strong>Liquid CP Quotations</strong>
        <a class="btn btn-default btn-sm pull-right" href="<?php echo site_url('admin/liquidcp'); ?>">Back to dashboard</a>
    </div>
    <div class="panel-body">
        <form class="form-inline" method="get" action="<?php echo site_url('admin/liquidcp/quotations'); ?>" style="margin-bottom:16px;">
            <div class="form-group">
                <label for="quotation-search">Search</label>
                <input id="quotation-search" class="form-control" type="text" name="q" value="<?php echo html_escape($search); ?>" placeholder="Quote, client, title">
            </div>
            <button class="btn btn-primary" type="submit">Filter</button>
            <?php if ($search !== '') { ?>
                <a class="btn btn-default" href="<?php echo site_url('admin/liquidcp/quotations'); ?>">Clear</a>
            <?php } ?>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Quote</th>
                        <th>Client</th>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Total Due</th>
                        <th>Ruleset</th>
                        <th>PDF Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quotes)) { ?>
                        <tr>
                            <td colspan="8">
                                No quotations found.
                                <a href="<?php echo site_url(); ?>" target="_blank">Create first quotation</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($quotes as $quote) { ?>
                        <tr>
                            <td><?php echo html_escape($quote['quote_number']); ?></td>
                            <td>
                                <?php echo html_escape($quote['client_name']); ?><br>
                                <small><?php echo html_escape($quote['client_email']); ?></small>
                            </td>
                            <td><?php echo html_escape($quote['quote_title']); ?></td>
                            <td><?php echo html_escape($quote['created_at']); ?></td>
                            <td><?php echo number_format((float) $quote['total_due'], 2); ?> ZMW</td>
                            <td><?php echo html_escape($quote['rule_set_version']); ?></td>
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
