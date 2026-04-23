<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Zigapage_wp
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://zigapage.softdiscover.com
 */
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>

<div class='sfdclauncher'>

</div>
<div class="sfdclauncher zgfm-block1-container sfdc-clearfix" >
    <div class="space20"></div>
    <div class="">
        <div class="col-lg-12">
            <div class="widget widget-padding span12">
                <div class="widget-header">
                    <i class="fa fa-list-alt"></i>
                    <h5>
                        <?php echo __('Webhook settings', 'FRocket_admin'); ?>
                    </h5>

                </div>  
                <div class="widget-body">

                    <div class="alert alert-info" role="alert">
                        <?php echo __('Send data collected through forms to other services', 'FRocket_admin'); ?>
                    </div>
                    <?php if ( ENVIRONMENT === 'development') { ?>
                    <a href="javascript:void(0);" onclick="javascript:zgfm_back_addon_webhook.dev_show_vars();" class="sfdc-btn sfdc-btn-primary">
                    <span class="fa fa-desktop"></span> show data</a>
                    <?php } ?>
                    
                        <div class="form-group row">
                            <label  class="col-sm-2 col-form-label"><?php echo __('Webhook Status', 'FRocket_admin'); ?></label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input webhook-input" data-options="status" type="radio" id="webhook_status_1" name="webhook[status][]" value="0">
                                    <label for="webhook_status_1" class="form-check-label" ><?php echo __('disabled', 'FRocket_admin'); ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input webhook-input" data-options="status"  type="radio" id="webhook_status_2"  name="webhook[status][]" value="1">
                                    <label for="webhook_status_2" class="form-check-label" ><?php echo __('enabled', 'FRocket_admin'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-sm-2 col-form-label"><?php echo __('Webhook URL', 'FRocket_admin'); ?></label>
                            <div class="col-sm-10">
                                <input class="form-control webhook-input" data-options="url"  id="webhook_url" type="text"  name="webhook[url]"  placeholder="Type URL here">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label"><?php echo __('Content Type', 'FRocket_admin'); ?></label>
                            <div class="col-sm-10">
                                <select class="custom-select webhook-input" data-options="type"  id="webhook_type"  name="webhook[type]" >
                                    <option value="1">application/json</option>
                                    <option value="2">application/x-www-form-urlencoded</option>
                                    <option value="3">multipart/form-data</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label"><?php echo __('Format', 'FRocket_admin'); ?></label>
                            <div class="col-sm-10">
                                <select class="custom-select webhook-input" data-options="format"  id="webhook_format"  name="webhook[format]" >
                                    <option value="1"><?php echo __('Default', 'FRocket_admin'); ?></option>
                                    <option value="2"><?php echo __('Soho', 'FRocket_admin'); ?></option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="card">
                            <div class="card-header">
                                <?php echo __('Form Fields', 'FRocket_admin'); ?>
                            </div>
                            <div class="card-body">
                                <!-- <h5 class="card-title">Test</h5>--->
                                <p class="card-text"><?php echo __('Under this section, choose the fields whose data should be transferred using Webhooks', 'FRocket_admin'); ?></p>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label  ><?php echo __('Map URL parameters', 'FRocket_admin'); ?></label>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label  ><?php echo __('To form values', 'FRocket_admin'); ?></label>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label  ><?php echo __('Options', 'FRocket_admin'); ?></label>

                                    </div>
                                </div>
                                
                                <div id='zgfm_webhook_back_form_fields'>
                                        
                                </div>

                                
                                
                                <div class="form-group row">
                                    <div class="col-md-6">

                                    </div>
                                    <div class="col-md-6">
                                        <button onclick="javascript:zgfm_back_addon_webhook.settings_field_new();" class="btn btn-warning btn-lg btn-block" ><?php echo __('Add New Field Parameter', 'FRocket_admin'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card">
                            <div class="card-header">
                                <?php echo __('Additional Parameters', 'FRocket_admin'); ?>
                            </div>
                            <div class="card-body">
                                <!-- <h5 class="card-title">Test</h5>--->
                                <p class="card-text"><?php echo __('Under this section, you can add additional parameters whose data should be transferred using Webhooks', 'FRocket_admin'); ?></p>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label  ><?php echo __('Map URL parameters', 'FRocket_admin'); ?></label>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label  ><?php echo __('To custom values', 'FRocket_admin'); ?></label>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label  ><?php echo __('Options', 'FRocket_admin'); ?></label>

                                    </div>
                                </div>
                                <div id='zgfm_webhook_back_form_cfields'>
                                    
                                </div>
                                
                                
                                <div class="form-group row">
                                    <div class="col-md-6">

                                    </div>
                                    <div class="col-md-6">
                                        <button onclick="javascript:zgfm_back_addon_webhook.settings_custom_new();" class="btn btn-warning btn-lg btn-block" ><?php echo __('Add New Custom Parameter', 'FRocket_admin'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <hr>
                        <div class="card">
                            <div class="card-header">
                                <?php echo __('Logs', 'FRocket_admin'); ?>
                            </div>
                            <div class="card-body">
                                <!-- <h5 class="card-title">Test</h5>--->
                                <p class="card-text"><?php echo __('Under this section, you can see webhook logs', 'FRocket_admin'); ?></p>
                                
                                <div class="form-group row">
                                    <div class="col-md-6">
                                    <div class="form-group row">
                                        <label  class="col-sm-2 col-form-label"><?php echo __('Logs', 'FRocket_admin'); ?></label>
                                            <div class="col-sm-10">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input webhook-input" data-options="log" type="radio" id="webhook_log_1" name="webhook[log][]" value="0">
                                                    <label for="webhook_log_1" class="form-check-label" ><?php echo __('disabled', 'FRocket_admin'); ?></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input webhook-input" data-options="log"  type="radio" id="webhook_log_2"  name="webhook[log][]" value="1">
                                                    <label for="webhook_log_2" class="form-check-label" ><?php echo __('enabled', 'FRocket_admin'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button onclick="javascript:zgfm_back_addon_webhook.settings_show_logs();" class="btn btn-info btn-lg btn-block" ><?php echo __('Show logs', 'FRocket_admin'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>


                </div>    
            </div>
        </div>
    </div>
</div>    
 
<script type="text/html" id="tmpl-zgfm-webhook-template1">
    <div class="form-row" data-number="{{ data.number }}" data-uniqueid="{{ data.id }}">
                                            <div class="col-md-6 mb-3">

                                                <input type="text" class="form-control webhook-input" data-options="fields-{{ data.number }}-name"  name="webhook[fields][{{ data.number }}][name]" placeholder="Type your var name here" value="{{ data.name }}" >
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <select onchange="javascript:zgfm_back_addon_webhook.settings_updateField(this);" class="custom-select webhook-field-value" name="webhook[fields][{{ data.number }}][id]" >
                                                    <option value="0"><?php echo __('choose an option', 'FRocket_admin'); ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group row">

                                                    <div class="col-md-12">
                                                        <button onclick="javascript:zgfm_back_addon_webhook.delete_field(this);" class="btn btn-danger btn-lg btn-block"  ><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

</script>
<script type="text/html" id="tmpl-zgfm-webhook-template2">
    
 <div class="form-row" data-number="{{ data.number }}"  data-uniqueid="{{ data.id }}">
                                        <div class="col-md-6 mb-3">

                                            <input type="text" class="form-control webhook-input webhook-field-name" data-options="customs-{{ data.number }}-name" name="webhook[custom][{{ data.number }}][name]" placeholder="Type your var name here" value="{{ data.name }}"  >
                                        </div>
                                        <div class="col-md-3 mb-3">

                                            <input type="text" class="form-control webhook-input webhook-field-value" data-options="customs-{{ data.number }}-value"  name="webhook[custom][{{ data.number }}][value]" placeholder="Type the value here"  value="{{ data.cvalue }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group row">

                                                <div class="col-md-12">
                                                    <button onclick="javascript:zgfm_back_addon_webhook.delete_custom(this);"  class="btn btn-danger btn-lg btn-block"  ><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
</script>
<?php
$cntACmp = ob_get_contents();

$cntACmp = preg_replace('/\s+/', ' ', $cntACmp);
ob_end_clean();
echo $cntACmp;
?>
