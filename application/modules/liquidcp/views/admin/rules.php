<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
$rules = $active_rule_set['data'];
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <strong>Liquid CP Ruleset Editor</strong>
        <span class="pull-right">Active version: <?php echo html_escape($active_rule_set['version']); ?></span>
    </div>
    <div class="panel-body">
        <p>Edit only safe Phase 1 rates and thresholds. Saving creates a new versioned ruleset and marks it active for future estimates.</p>
        <form method="post" action="<?php echo site_url('admin/liquidcp/save-rules'); ?>">
            <input type="hidden" name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>" value="<?php echo html_escape($this->security->get_csrf_hash()); ?>">
            <div class="row">
                <?php foreach ($rules as $key => $value) { if ($key === 'rule_set_version') { continue; } ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><?php echo html_escape(ucwords(str_replace('_', ' ', $key))); ?></label>
                            <input class="form-control" type="number" step="0.0001" name="<?php echo html_escape($key); ?>" value="<?php echo html_escape($value); ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button class="btn btn-primary" type="submit">Save as next ruleset version</button>
            <a class="btn btn-default" href="<?php echo site_url('admin/liquidcp'); ?>">Back to Liquid CP admin</a>
        </form>
    </div>
</div>
