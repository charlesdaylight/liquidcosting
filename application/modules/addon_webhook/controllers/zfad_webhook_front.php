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
if ( class_exists('zfad_webhook_front')) {
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
class zfad_webhook_front extends FrontendController
{

    const VERSION = '0.1';

    private $pagination = '';
    private $per_page       = 5;


    // adding libs
    public $local_controllers = array(
        'animation' => array(
            'class_name' => 'zfaddn_anim_back_lib',

        ),
    );
    // adding actions
    public $local_actions = array(
        array(
            'action'        => 'onSubmitForm_pos',
            'function'      => 'submit_data',
            'accepted_args' => 0,
            'priority'      => 1,
        ),
    );
    // adding js actions
    public $js_actions = array();

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

        // filter
        add_filter('zgfm_front_enqueue_scripts', array( &$this, 'filter_add_scripts' ));
        
        //save on submit form
        add_action('zgfm_onSubmitForm_pos', array( &$this, 'submit_data' ), 10);
    }

    public function filter_add_scripts($value)
    {

        return $value;
    }

    public function loadStyleOnFront()
    {
    }

    /*
     * sending info
     */

    public function submit_data()
    {
        $form_id    = self::$_form_data['form_id'];
        $addon_data = $this->model_addon_details->getAddonDataByForm('webhook', $form_id);

        // return if data is null
        if ( empty($addon_data)) {
            return;
        }

        $addon_data_tmp = json_decode($addon_data->adet_data, true);

        // return if status is zero
        if ( isset($addon_data_tmp['status']) && intval($addon_data_tmp['status']) != 1) {
            return;
        }

        $isSohoFormat = isset($addon_data_tmp['format'])?$addon_data_tmp['format']:"1";
         
        $form_data = $this->model_forms->getTitleFormById($form_id);
        if (intval($isSohoFormat) === 2) {
            $send_data = new stdClass();
            foreach ( $addon_data_tmp['fields'] as $key => $value) {
                $colname = $value['name'];
                $send_data->$colname = $this->get_value_fields($value);
            }
            
            foreach ( $addon_data_tmp['customs'] as $key => $value) {
                $colname = $value['name'];
                $send_data->$colname = do_shortcode($value['value']);
            }
        }else {
            $send_data              = array();
            $send_data['form_id']   = $form_id;
            $send_data['form_name'] = $form_data->fmb_name;
            $send_data['fields']    = array();
    
            foreach ( $addon_data_tmp['fields'] as $key => $value) {
                $send_data_inner                = array();
                $send_data_inner['field_value'] = html_entity_decode($this->get_value_fields($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                 
                $labelVal = $this->get_label_fields($value);
                if (!empty($labelVal)) {
                    $send_data_inner['field_label'] = $labelVal;
                }
                
                $send_data_inner['field_id']    = $value['id'];
                $send_data_inner['field_name']  = $value['name'];
                $send_data['fields'][]          = $send_data_inner;
            }
    
            $send_data['customs'] = array();
            foreach ( $addon_data_tmp['customs'] as $key => $value) {
                $send_data_inner                 = array();
                $send_data_inner['custom_value'] = do_shortcode($value['value']);
                $send_data_inner['custom_name']  = $value['name'];
                $send_data['customs'][]          = $send_data_inner;
            }
        }

        $ch  = curl_init();
        $url = $addon_data_tmp['url'];
        curl_setopt($ch, CURLOPT_URL, $url);
    
        //store logs
        if ( isset($addon_data_tmp['log']) && intval($addon_data_tmp['log']) === 1) {
            $data_webhook = json_encode($send_data);
            $targetDir = FCPATH . 'application/logs';
            
            $fileName= $targetDir.'/log_'.$form_id.'_'.date('d-M-Y'). '.log';
            @file_put_contents($fileName, $data_webhook . "\n\n", FILE_APPEND);
        }
        
        switch ( intval($addon_data_tmp['type'])) {
            case 3:
                // multipart/form-data

                $data_webhook = json_encode($send_data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // This should be a POST request
                curl_setopt($ch, CURLOPT_POST, true);
                // This is the data to send
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_webhook);
                break;
            case 2:
                // application/x-www-form-urlencoded
                $postdata = http_build_query($send_data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // Set the request type to POST
                curl_setopt($ch, CURLOPT_POST, true);
                // Pass the post parameters as a naked string
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

                // Option to Return the Result, rather than just true/false
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                break;
            case 1:
            default:
                // json
                // Setup request to send json via POST.
                $data_webhook = json_encode($send_data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_webhook);
                curl_setopt(
                    $ch,
                    CURLOPT_HTTPHEADER,
                    array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_webhook),
                    )
                );

                // Return response instead of printing.
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_VERBOSE, true);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                break;
        }

        // Send request.
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /*
     * get value of field
     */

    public function get_value_fields($field)
    {
        $output     = '';
        $record_id  = self::$_form_data['record_id'];
        $form_id    = self::$_form_data['form_id'];
        $field_id   = $field['field'];
        $field_type = $field['type'];
        switch (intval($field_type)) {
            case 16:
            case 17:
            case 18:
                $output = $this->model_record->getFieldOptRecord($record_id, $field_type, $field_id, 'input', 'qty');
                break;
            case 8:
            case 9:
            case 10:
            case 11:
                $output = $this->model_record->getFieldOptRecord($record_id, $field_type, $field_id, 'input', 'value');
                break;
            default:
                $output = $this->model_record->getFieldOptRecord($record_id, $field_type, $field_id, 'input');
                break;
        }
        
        return $output;
    }
    
    public function get_label_fields($field)
    {
        $output     = '';
        $record_id  = self::$_form_data['record_id'];
        $form_id    = self::$_form_data['form_id'];
        $field_id   = $field['field'];
        $field_type = $field['type'];
        switch (intval($field_type)) {
            case 8:
            case 9:
            case 10:
            case 11:
                $output = $this->model_record->getFieldOptRecord($record_id, $field_type, $field_id, 'input', 'label');
                break;
        }
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
