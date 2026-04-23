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
 * @link      https://php-cost-estimator.zigaform.com/
 */
if ( ! defined('BASEPATH')) {
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
 * @link      https://php-cost-estimator.zigaform.com/
 */
class zfad_func_anim_back extends BackendController
{

    const VERSION       = '0.1';
    private $pagination = '';
    private $per_page       = 5;


    // adding libs
    private $local_controllers = array(
        'animation' => array(
            'class_name' => 'zfad_anim_back_lib',
        ),
    );

    // adding actions
    public $local_back_actions = array(
        array(
            'action'        => 'back_field_opt_more',
            'function'      => 'get_field_back_animation',
            'accepted_args' => 0,
            'priority'      => 1,
        ),


    );


    // adding js actions
    public $js_back_actions = array(
        array(
            'action'        => 'getData_beforeSubmitForm',
            'function'      => 'get_currentDataToSave',
            'controller'    => 'zgfm_back_addon_anim',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'getData_toFields',
            'function'      => 'load_fieldsettings',
            'controller'    => 'zgfm_back_addon_anim',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'onLoadForm_loadAddon',
            'function'      => 'load_settings',
            'controller'    => 'zgfm_back_addon_anim',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
    );


    /**
     * Constructor
     *
     * @mvc Controller
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->language_alt(model_settings::$db_config['language']);
        $this->template->set('controller', $this);
        // $this->load->model('model_addon');
        
        $this->load->model('addon/model_addon_details');
        
        add_filter('zgfm_back_filter_globalvars', array(&$this, 'filter_add_globalvariable'));
        // add content to more tab
        add_filter('zgfm_back_field_opt_more', array(&$this, 'get_field_back_animation'));

        add_filter('zgfm_back_addon_obtain_data', array(&$this, 'obtain_data'), 1);
    }



    public function filter_add_globalvariable($value)
    {
        /*
        $value['addon']['func_anim'] = array(
            'func_name' => 'zgfm_back_addon_anim'
        );*/
        return $value;
    }

    public function obtain_data($default, $formId)
    {
    
        $tmp_data = $this->model_addon_details->getAddonDataByForm('func_anim', $formId);
        if (!empty($tmp_data)) {
            $tmp_addon = json_decode($tmp_data->adet_data, true);
        } else {
            $tmp_addon = array();
        }

        $default['func_anim'] = [
            'data'=> $tmp_addon
        ];
        return $default;
    }
    
    /**
     * Adding new controllers
     *
     * @mvc Controller
     */
    public function add_controllers()
    {

        $tmp_flag = array();

        foreach ( $this->local_controllers as $key => $value) {
             // load controllers
            // require_once( FCPATH . '/modules/addon_func_anim/controllers/'.$key.'.php');
            if ( isset($value['class_name'])) {
                // $tmp_flag[$key]=$value['class_name'];
                $tmp_flag[ $key ] = modules::run('addon_func_anim/' . $value['class_name'] . '/get_instance');
            }
        }

        return $tmp_flag;
    }


    public function get_field_back_animation()
    {
        $data                 = array();
        $data['select_types'] = self::$_addons['func_anim']['animation']->getBackHtml();

        return $this->load->view('addon_func_anim/backend/get_field_back_animation', $data, true);
    }

    public function ajax_load_settings()
    {
        
        $json      = array();
        $tmp_addon = array();
        $form_id   = (isset($_POST['form_id'])) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';

        $tmp_data = $this->model_addon_details->getAddonDataByForm('func_anim', $form_id);
        if (!empty($tmp_data)) {
            $tmp_addon = json_decode($tmp_data->adet_data, true);
        }
        $json['data'] = $tmp_addon;
        //return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }
    
    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks()
    {
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init()
    {

        try {
            // $instance_example = new WPPS_Instance_Class( 'Instance example', '42' );
            // add_notice('ba');
        } catch ( Exception $exception) {
            add_notice(__METHOD__ . ' error: ' . $exception->getMessage(), 'error');
        }
    }

    /*
     * Instance methods
     */

    /**
     * Prepares sites to use the plugin during single or network-wide activation
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    public function activate($network_wide)
    {

        return true;
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate()
    {
        return true;
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade($db_version = 0)
    {
        return true;
    }

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or 'all' to check all of them
     * @return bool
     */
    protected function is_valid($property = 'all')
    {
        return true;
    }
}
