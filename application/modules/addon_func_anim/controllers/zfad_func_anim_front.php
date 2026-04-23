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
class zfad_func_anim_front extends FrontendController
{

    const VERSION       = '0.1';
    private $pagination = '';
    private $per_page       = 5;

    protected $modules;

    // adding libs
     public $local_controllers = array(
         'animation' => array(
             'class_name' => 'zfad_anim_back_lib',

         ),
     );
        // adding actions
     public $local_actions = array();
        // adding js actions
     public $js_actions = array(
         array(
             'action'        => 'initForm_loadAddLibs',
             'function'      => 'initialize',
             'controller'    => 'zfaddn_anim_front',
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

         // filter
         add_filter('zgfm_front_enqueue_scripts', array( &$this, 'filter_add_scripts' ));
     }

     public function filter_add_scripts($value)
     {

         // description: $value[priority][]
         $value[0][] = array(
             'scripts' => array(
                 2 => array(
                     'src' => base_url() . 'application/modules/addon_func_anim/views/frontend/assets/js/script.js',
                     'id'  => 'zfaddn_front_script_js',
                 ),
             ),
             'styles'  => array(
                 1 => array(
                     'src' => base_url() . 'application/modules/addon_func_anim/views/frontend/assets/style-front.css',
                     'id'  => 'zfaddn_front_animate_style',
                 ),
                 2 => array(
                     'src' => base_url() . 'application/modules/addon_func_anim/views/common/assets/css/animate.min.css',
                     'id'  => 'zfaddn_front_animate_style-2',
                 ),
                 3 => array(
                     'src' => base_url() . 'application/modules/addon_func_anim/views/common/assets/css/customs.css',
                     'id'  => 'zfaddn_front_animate_style-3',
                 ),

             ),
         );
         return $value;
     }

     public function loadStyleOnFront()
     {
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
                 // $tmp_flag[$key]=call_user_func(array($value['class_name'], 'get_instance'));
                 $tmp_flag[ $key ] = modules::run('addon_func_anim/' . $value['class_name'] . '/get_instance');
             }
         }

         return $tmp_flag;
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
