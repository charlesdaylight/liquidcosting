<?php

/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if ( class_exists('zfad_webhook_back')) {
    return;
}

/**
 * Controller Settings class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
class zfad_webhook_back extends BackendController
{

    const VERSION       = '0.1';
    private $pagination = '';
    private $per_page       = 5;


    // adding libs
    public $local_controllers = array();

    // adding routes
    public $local_back_actions = array(
        array(
            'action'        => 'back_exttab_block',
            'function'      => 'get_content',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'saveForm_store',
            'function'      => 'saveData',
            'accepted_args' => 0,
            'priority'      => 1,
        ),

    );


        // adding js actions
    public $js_back_actions = array(

        array(
            'action'        => 'onLoadForm_loadAddon',
            'function'      => 'load_settings',
            'controller'    => 'zgfm_back_addon_webhook',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'fieldName_onBlur',
            'function'      => 'refresh_options',
            'controller'    => 'zgfm_back_addon_webhook',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'onFieldCreation_post',
            'function'      => 'onFieldCreation_post',
            'controller'    => 'zgfm_back_addon_webhook',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
        array(
            'action'        => 'getData_beforeSubmitForm',
            'function'      => 'get_currentDataToSave',
            'controller'    => 'zgfm_back_addon_webhook',
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
        $this->load->model('addon/model_addon');
        $this->load->model('formbuilder/model_forms');
        $this->load->model('formbuilder/model_record');
        $this->load->model('addon/model_addon_details');

        // admin resources
        add_action('admin_enqueue_scripts', array( &$this, 'loadStyle' ));
        
        add_filter('zgfm_saveForm_store', array( &$this, 'saveData' ), 10);
    }
    public function ajax_load_settings()
    {
        
        $json      = array();
        $tmp_addon = array();
        $form_id   = ( isset($_POST['form_id']) ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';

        $tmp_data = $this->model_addon_details->getAddonDataByForm('webhook', $form_id);
        if (! empty($tmp_data)) {
            $tmp_addon = json_decode($tmp_data->adet_data, true);
        } else {
            $tmp_addon = array();
        }
        $json['data'] = $tmp_addon;
          //return data to ajax callback
          header('Content-Type: application/json');
          echo json_encode($json);
          die();
    }
    private function displayTree($data, $indent = 0, $content = '')
    {
        
        foreach ($data as $key => $value) {
            $content.=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $indent); // Adjust the number of spaces for indentation
            $content.="|— " . $key;
    
            if (is_array($value)) {
                $content.="<br>";
                $content= $this->displayTree($value, $indent + 1, $content);
            } else {
                $content.=": " . $value . "<br>";
            }
        }
        return $content;
    }
    
    public function ajaxShowLogs()
    {
 
        $data     = array();
        $form_id  = ( isset($_POST['form_id']) ) ? Uiform_Form_Helper::sanitizeInput(trim($_POST['form_id'])) : '';
        
        $targetDir = FCPATH . 'application/logs';
        $files = glob($targetDir.'/log_'.$form_id.'_*');
        $message = __('Logs not found', 'FRocket_admin');
        if(!empty($files)){
            rsort($files);
            foreach ($files as $logFile) {
                $contTmp = file_get_contents($logFile);
                
                $parts = explode("\n", $contTmp);
                rsort($parts);
                $newParts = [];
                foreach ($parts as $key2 => $value2) {
                    if (!empty($value2)) {
                        $newFormat = $this->displayTree(json_decode($value2, true), 0, '');
                        $newParts[]=$newFormat;
                    }
                }
                
                $data['files'][] = [
                    'content'=> $newParts,
                    'file'=>$logFile
                ];
            }
            
            $message = $this->load->view('addon_webhook/backend/logs', $data, true);
        }
        
        
        
        
        $json                 = array();
        $json['modal_header'] = '<h3>' . __('Webhook logs', 'FRocket_admin') . '</h3>';
        $json['modal_body']   = $message;
        $json['modal_footer'] = '';

        // return data to ajax callback
        header('Content-Type: application/json');
        echo json_encode($json);
        die();
    }

    public function saveData($fmb_data, $form_id)
    {

        $data_addon = $fmb_data['addons']['webhook'];

        $data_addon_store = json_encode($data_addon);

        $newdata = array();

        if ( $this->model_addon_details->existRecord('webhook', $form_id)) {
            $where    = array(
                'add_name' => 'webhook',
                'fmb_id'   => $form_id,
            );
                $data = array(
                    'adet_data' => $data_addon_store,
                );

                $this->db->set($data);
                $this->db->where($where);
                $this->db->update($this->model_addon_details->table);
        } else {
            $newdata['add_name']  = 'webhook';
            $newdata['fmb_id']    = $form_id;
            $newdata['adet_data'] = $data_addon_store;

            $this->db->set($newdata);
            $this->db->insert($this->model_addon_details->table);
        }
        
        return $fmb_data;
    }


    /*
    * load css, and javascript files
    */
    public function loadStyle()
    {

        ob_start();
        ?>
        <link href="<?php echo base_url(); ?>application/modules/addon_webhook/views/backend/assets/style.css" rel="stylesheet">
        
        <script type="text/javascript" src="<?php echo base_url(); ?>application/modules/addon_webhook/views/backend/assets/back.js"></script>    
        <?php
         $str_output = ob_get_contents();
        ob_end_clean();
        echo $str_output;
    }

    public function get_content()
    {
        $data       = array();
              $data = array();

        $output                = array();
        $output['tab_link']    = array( 'name' => 'webhoook settings' );
        $output['tab_content'] = $this->load->view('addon_webhook/backend/get_content', $data, true);

        return $output;
    }

    /**
     * Adding new controllers
     *
     * @mvc Controller
     */
    public function add_controllers()
    {

        $tmp_flag = array();

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

?>
