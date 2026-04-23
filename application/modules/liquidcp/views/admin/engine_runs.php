<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <strong>Liquid CP Engine Runs</strong>
        <a class="btn btn-default btn-sm pull-right" href="<?php echo site_url('admin/liquidcp'); ?>">Back to dashboard</a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Run ID</th>
                        <th>Quote</th>
                        <th>Client</th>
                        <th>Rule Set ID</th>
                        <th>Engine Version</th>
                        <th>Duration</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($engine_runs)) { ?>
                        <tr><td colspan="7">No engine runs recorded yet.</td></tr>
                    <?php } ?>
                    <?php foreach ($engine_runs as $run) { ?>
                        <tr>
                            <td><?php echo (int) $run['id']; ?></td>
                            <td>
                                <?php if (! empty($run['quote_id'])) { ?>
                                    <a href="<?php echo site_url('admin/liquidcp/quotation/' . $run['quote_id']); ?>">
                                        <?php echo html_escape($run['quote_number'] ?: ('Quote #' . $run['quote_id'])); ?>
                                    </a>
                                <?php } else { ?>
                                    Missing
                                <?php } ?>
                            </td>
                            <td><?php echo html_escape($run['client_name']); ?></td>
                            <td><?php echo (int) $run['rule_set_id']; ?></td>
                            <td><?php echo html_escape($run['engine_version']); ?></td>
                            <td><?php echo isset($run['duration_ms']) ? (int) $run['duration_ms'] . 'ms' : 'n/a'; ?></td>
                            <td><?php echo html_escape($run['created_at']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
