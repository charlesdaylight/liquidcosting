<?php

/**
 * Settings
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id: intranet.php, v2.00 2013-11-30 02:52:40 Softdiscover $
 * @link      https://php-form-builder.zigaform.com/
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Estimator intranet class
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://php-form-builder.zigaform.com/
 */
class license extends BackendController
{
	/**
	 * max number of forms in order show by pagination
	 *
	 * @var int
	 */

	const VERSION = '0.1';

	/**
	 * name of form estimator table
	 *
	 * @var string
	 */
	protected $modules;
	public $CI;
	/**
	 * Settings::__construct()
	 *
	 * @return
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->language_alt(model_settings::$db_config['language']);
		$this->template->set('controller', $this);
		$this->load->model('model_settings');
		$this->CI = &get_instance();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('file');
		$this->load->helper('form');
		$this->load->library('curl');

	}

	public function update_option(){
		// Get the purchase code from the AJAX request
		$purchase_code = $this->input->post('pcode', TRUE);
		
		$status= false;
		$option = ['is_valid' => 1, 'code' => $purchase_code ?: 'liquidcp-local'];
		update_option('zgfm_wpfb_code', $option);
		$status= true;
		
		$json = ['success' => $status];
		header('Content-Type: application/json');
		echo json_encode($json);
		die();
	}
	
	public function validatepurchasecode()
	{
		$purchase_code = $this->input->post('pcode', TRUE);
		$option = ['is_valid' => 1, 'code' => $purchase_code ?: 'liquidcp-local'];
		update_option('zgfm_wpfb_code', $option);
		$json = ['success' => true];

		header('Content-Type: application/json');
		echo json_encode($json);
		die();
	}
	
}
