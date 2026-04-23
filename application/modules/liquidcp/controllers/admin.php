<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('liquidcp');
        $this->load->language_alt(model_settings::$db_config['language']);
        $this->template->set('controller', $this);
        $this->load->model('liquidcp/model_liquidcp');
        $this->model_liquidcp->ensureSeedRuleSet();
    }

    public function index()
    {
        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
            'recent_quotes' => $this->model_liquidcp->listRecentQuotes(20),
            'dashboard_stats' => $this->model_liquidcp->getDashboardStats(),
            'engine_url' => $this->config->item('liquidcp_engine_base_url'),
        );
        $this->template->loadPartial('layout', 'admin/index', $data);
    }

    public function quotations()
    {
        $search = trim((string) $this->input->get('q', true));
        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
            'quotes' => $this->model_liquidcp->searchQuotes($search, 200),
            'search' => $search,
        );
        $this->template->loadPartial('layout', 'admin/quotations', $data);
    }

    public function quotation($id)
    {
        $quote = $this->model_liquidcp->getQuoteDetail($id);
        if (! $quote) {
            show_404();
        }

        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
            'quote' => $quote,
        );
        $this->template->loadPartial('layout', 'admin/quotation', $data);
    }

    public function rules()
    {
        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
        );
        $this->template->loadPartial('layout', 'admin/rules', $data);
    }

    public function save_rules()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        $fields = array(
            'vat_rate',
            'supervision_rate',
            'thrust_boring_fee_rate',
            'cisco_4221_exchange_multiplier',
            'tplink_archer_c6_exchange_multiplier',
            'media_converter',
            'sfp_10km',
            'patch_code',
            'warning_tape',
            'hdpe_pipe',
            'inline_joint_closure',
            'patch_panel_splice_box',
            'joint_closure_dome',
            'cable_frame',
            'pole_clamps',
            'tensioners',
            'suspension_clamps',
            'stay_wire_assembly',
        );

        $data = array();
        foreach ($fields as $field) {
            $data[$field] = (float) $this->input->post($field, true);
        }

        $this->model_liquidcp->createRuleSetVersion($data, 'admin');
        redirect(site_url('admin/liquidcp/rules'));
    }

    public function engine_runs()
    {
        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
            'engine_runs' => $this->model_liquidcp->listEngineRuns(200),
        );
        $this->template->loadPartial('layout', 'admin/engine_runs', $data);
    }
}
