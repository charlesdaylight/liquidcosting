<?php
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );}

$uri_string           = trim($this->uri->uri_string(), '/');
$current_module       = method_exists($this->router, 'fetch_module') ? $this->router->fetch_module() : $this->uri->segment(1);
$current_action       = $this->uri->segment('3');
$is_builder_create    = $current_module === 'formbuilder' && Uiform_Form_Helper::sanitizeInput($current_action) === 'create_uiform';
$is_dashboard         = $uri_string === 'admin/liquidcp' || $uri_string === '';
$is_quotations        = strpos($uri_string, 'admin/liquidcp/quotation') === 0 || $uri_string === 'admin/liquidcp/quotations';
$is_rules             = $uri_string === 'admin/liquidcp/rules';
$is_engine_runs       = $uri_string === 'admin/liquidcp/engine-runs';
?>
<div class="uiform-editing-header">
   <nav class="sfdc-navbar sfdc-navbar-default" role="navigation">
  <div class="sfdc-navbar-inner">
<div class="sfdc-navbar-header">
		  <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
			<span class="sr-only"><?php echo __( 'Toggle navigation', 'FRocket_admin' ); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a href="<?php echo site_url('admin/liquidcp'); ?>" class="navbar-brand" style="display:flex;align-items:center;gap:10px;">
			<img title="Liquid CP" src="<?php echo base_url(); ?>assets/backend/image/rockfm-logo-header.png">
			<span style="font-weight:700;color:#11343d;">Liquid CP</span>
		  </a>
		</div>
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	  <ul class="sfdc-nav sfdc-navbar-nav">
		  <li class="divider-menu"></li>
		<li class="divider-menu"></li>
		<li class="<?php echo $is_dashboard ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp'); ?>"><span class="fa fa-dashboard"></span> Dashboard</a></li>
		<li class="divider-menu"></li>
		<li class="<?php echo $is_quotations ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/quotations'); ?>"><span class="fa fa-files-o"></span> Quotations</a></li>
		<li class="divider-menu"></li>
		<li class="<?php echo $is_rules ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/rules'); ?>"><span class="fa fa-sliders"></span> Rules</a></li>
		<li class="divider-menu"></li>
		<li class="<?php echo $is_engine_runs ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/liquidcp/engine-runs'); ?>"><span class="fa fa-cogs"></span> Engine Runs</a></li>
		<?php if ( $is_builder_create ) { ?>
		<li class="divider-menu"></li>
		<li class="sfdc-dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="sfdc-dropdown"><span class="fa fa-desktop"></span> <?php echo __( 'Preview', 'FRocket_admin' ); ?> <span class="caret"></span></a>
		  <ul class="sfdc-dropdown-menu" role="menu">
			<li><a onclick="javascript:rocketform.previewform_showForm(1);" href="javascript:void(0);"><?php echo __( 'desktop', 'FRocket_admin' ); ?></a></li>
			<li><a onclick="javascript:rocketform.previewform_showForm(2);" href="javascript:void(0);"><?php echo __( 'Tablet', 'FRocket_admin' ); ?></a></li>
			<li><a onclick="javascript:rocketform.previewform_showForm(3);" href="javascript:void(0);"><?php echo __( 'smartphone', 'FRocket_admin' ); ?></a></li>
		  </ul>
		</li>
		<?php } ?>
		<li class="divider-menu"></li>
		<li class="sfdc-dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="sfdc-dropdown"><span class="fa fa-briefcase"></span> Advanced Tools <span class="caret"></span></a>
		  <ul class="sfdc-dropdown-menu" role="menu">
			<li><a href="<?php echo site_url() . 'formbuilder/forms/choose_mode'; ?>"><span class="fa fa-plus"></span> New Form</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/forms/list_uiforms'; ?>"><span class="fa fa-th-list"></span> Forms</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/records/list_records'; ?>"><span class="fa fa-database"></span> Records</a></li>
			<li><a href="<?php echo site_url() . 'gateways/records/list_records'; ?>"><span class="fa fa-money"></span> Invoices</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/forms/import_form'; ?>"><span class="fa fa-reply"></span> Import</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/forms/export_form'; ?>"><span class="fa fa-share"></span> Export</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/records/view_charts'; ?>"><span class="fa fa-area-chart"></span> Charts</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/settings/view_settings'; ?>"><span class="fa fa-cog"></span> Builder Settings</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/settings/backup_settings'; ?>"><span class="fa fa-cloud-download"></span> Backup</a></li>
			<li><a href="<?php echo site_url() . 'gateways/settings/view_settings'; ?>"><span class="fa fa-credit-card"></span> Payment Methods</a></li>
			<li><a href="<?php echo site_url() . 'formbuilder/settings/system_check'; ?>"><span class="fa fa-stethoscope"></span> System Check</a></li>
			<li><a href="<?php echo site_url() . 'addon/zfad_backend/list_extensions'; ?>"><span class="fa fa-plug"></span> Extensions</a></li>
			<?php if ( UIFORM_DEMO === 0 ) { ?>
			<li><a href="<?php echo site_url() . 'user/intranet/index'; ?>"><span class="fa fa-user"></span> Users</a></li>
			<li><a href="<?php echo site_url() . 'default/intranet/settings'; ?>"><span class="fa fa-wrench"></span> More Settings</a></li>
			<?php } ?>
			<li><a href="<?php echo site_url() . 'default/intranet/showfilemanager'; ?>"><span class="fa fa-code"></span> File manager</a></li>
			<li><a href="<?php echo site_url() . 'addon_mgtranslate/zfad_mgtranslate_back/show_list'; ?>"><span class="fa fa-globe"></span> Translation Manager</a></li>
		  </ul>
		</li>
		<li class="divider-menu"></li>
		<li class="sfdc-dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="sfdc-dropdown"><span class="fa fa-life-ring"></span> Help <span class="caret"></span></a>
		  <ul class="sfdc-dropdown-menu" role="menu">
			<li><a href="https://php-cost-estimator.zigaform.com/docs/" target="_blank">Vendor Documentation</a></li>
			<li><a href="<?php echo site_url() . 'default/intranet/help'; ?>">Local Help</a></li>
			<li><a href="<?php echo site_url() . 'default/intranet/about'; ?>">About</a></li>
		  </ul>
		</li>
	  </ul>
	  <div id="uifm-loading-box" style="display:none;">
		  <div class="uifm-alert"></div>
	  </div>
	  
	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>
