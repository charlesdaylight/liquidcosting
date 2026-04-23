<?php
/**
 * Sidebar
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id: sidebar.php, v2.00 2013-11-30 02:52:40 Softdiscover $
 * @link      https://php-cost-estimator.zigaform.com/
 */
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

$uri_string        = trim($this->uri->uri_string(), '/');
$is_liquidcp       = strpos($uri_string, 'admin/liquidcp') === 0;
$is_liquidcp_index = $uri_string === 'admin/liquidcp' || $uri_string === '';
$is_quotations     = strpos($uri_string, 'admin/liquidcp/quotation') === 0 || $uri_string === 'admin/liquidcp/quotations';
$is_rules          = $uri_string === 'admin/liquidcp/rules';
$is_engine_runs    = $uri_string === 'admin/liquidcp/engine-runs';
?>
<div class="sidebar-wrap">
   <ul class="nav navbar-nav side-nav">
	<li class="nav-profile">
		<div class="user_profile clearfix">
		<?php
		$gravatar = 'https://www.gravatar.com/avatar/'.md5( strtolower( trim( model_settings::$db_config['admin_mail'] ) ) ).'?s=50';
		if(Uiform_Form_Helper::urlIsValid($gravatar)){
			?>
			<img alt="" src="<?php echo $gravatar; ?>">
			<?php
		}
		?>	
		<h5><?php echo $this->session->userdata( 'use_login' ); ?></h5>
	</div>
	</li>
			<li class="<?php echo $is_liquidcp_index ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp'); ?>"><i class="fa fa-dashboard"></i> Liquid CP Dashboard</a></li>
			<li class="<?php echo $is_quotations ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/quotations'); ?>"><i class="fa fa-files-o"></i> Quotations</a></li>
			<li class="<?php echo $is_rules ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/rules'); ?>"><i class="fa fa-sliders"></i> Rules</a></li>
			<li class="<?php echo $is_engine_runs ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/engine-runs'); ?>"><i class="fa fa-cogs"></i> Engine Runs</a></li>
			<li class="nav-divider" style="padding:12px 15px 6px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.55);">Advanced Tools</li>
			<li><a href="<?php echo site_url(); ?>formbuilder/forms/list_uiforms"><i class="fa fa-th-list"></i> Forms</a></li>
			<li><a href="<?php echo site_url(); ?>formbuilder/records/list_records"><i class="fa fa-database"></i> Records</a></li>
			<li><a href="<?php echo site_url(); ?>gateways/records/list_records"><i class="fa fa-money"></i> Invoices</a></li>
			<li><a href="<?php echo site_url(); ?>formbuilder/settings/view_settings"><i class="fa fa-cog"></i> Builder Settings</a></li>
<?php if ( UIFORM_DEMO === 0 ) { ?>
			<li><a href="<?php echo site_url(); ?>user/intranet/index"><i class="fa fa-user"></i> Users</a></li>
			<li><a href="<?php echo site_url(); ?>default/intranet/settings"><i class="fa fa-wrench"></i> More Settings</a></li>
<?php } ?>
			<li><a href="<?php echo site_url(); ?>default/intranet/showfilemanager"><i class="fa fa-code"></i> File manager</a></li>
			<li><a href="<?php echo site_url(); ?>addon_mgtranslate/zfad_mgtranslate_back/show_list"><i class="fa fa-globe" aria-hidden="true"></i> Translation Manager</a></li>
			<li><a href="<?php echo site_url(); ?>default/intranet/help"><i class="fa fa-question-circle"></i> Help</a></li>
			<li><a href="<?php echo site_url(); ?>default/intranet/about"><i class="fa fa-info"></i> About</a></li>
<?php if ( ZIGAFORM_C_LITE == 1 ) { ?>
			<li><a href="<?php echo site_url(); ?>default/intranet/gopro"><i class="fa fa-angle-right"></i> <?php echo __( 'Go Pro', 'FRocket_admin' ); ?></a></li>
<?php } ?>
		  </ul> 
	<div id="zgfm-sidebar-show-ver">
		Liquid CP Platform <?php echo model_settings::$db_config['version']; ?> <?php echo (ZIGAFORM_C_LITE)?'Free':'Pro'; ?>
	</div> 
</div>
