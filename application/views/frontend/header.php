<?php
/**
 * Frontend header
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id: frontend_header.php, v2.00 2013-11-30 02:52:40 Softdiscover $
 * @link      https://php-cost-estimator.zigaform.com/
 */
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}
?>
<div class="sfdc-navbar sfdc-navbar-default" data-enhance="false">
	<div class="container">
		<div class="sfdc-navbar-header">
			<button data-target=".sfdc-navbar-collapse" data-toggle="sfdc-collapse" class="sfdc-navbar-toggle" type="button">
				<span class="sfdc-icon-bar"></span>
				<span class="sfdc-icon-bar"></span>
				<span class="sfdc-icon-bar"></span>
			</button>
			<a data-ajax="false"  href="<?php echo site_url(); ?>" class="sfdc-navbar-brand"><?php echo model_settings::$db_config['site_title']; ?></a>
		</div>
		<div class="sfdc-navbar-collapse sfdc-collapse">
			<ul class="sfdc-nav sfdc-navbar-nav">
				<li><a data-ajax="false" href="<?php echo site_url('liquidcp/studio'); ?>"><?php echo __( 'Estimate Studio', 'frocket_front' ); ?></a></li>
				<li><a data-ajax="false" href="<?php echo site_url('liquidcp/login'); ?>"><?php echo __( 'Sign In', 'frocket_front' ); ?></a></li>
			</ul>
		</div>
	</div>
</div>
