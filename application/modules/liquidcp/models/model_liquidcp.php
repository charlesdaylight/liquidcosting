<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Model_liquidcp extends CI_Model
{
    public $table_rule_set = '';
    public $table_engine_run = '';
    public $table_quote_meta = '';

    public function __construct()
    {
        parent::__construct();
        $this->config->load('liquidcp');
        $prefix = $this->db->dbprefix;
        $this->table_rule_set = $prefix . 'liquidcp_rule_set';
        $this->table_engine_run = $prefix . 'liquidcp_engine_run';
        $this->table_quote_meta = $prefix . 'liquidcp_quote_meta';
    }

    public function ensureSeedRuleSet()
    {
        $existing = $this->db->order_by('id', 'DESC')->get($this->table_rule_set)->row_array();
        if (! empty($existing)) {
            return $existing;
        }

        $data = array(
            'code' => 'prototype-general',
            'version' => 'prototype-v1',
            'status' => 'ACTIVE',
            'data_json' => json_encode($this->defaultRuleData(), JSON_UNESCAPED_SLASHES),
            'created_by' => 'system',
            'activated_at' => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->table_rule_set, $data);

        return $this->getActiveRuleSet();
    }

    public function getActiveRuleSet()
    {
        $this->ensureSeedRuleSet();
        $row = $this->db
            ->where('status', 'ACTIVE')
            ->order_by('id', 'DESC')
            ->get($this->table_rule_set)
            ->row_array();

        if (! $row) {
            return null;
        }

        $row['data'] = json_decode($row['data_json'], true);

        return $row;
    }

    public function listRecentQuotes($limit = 20)
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get($this->table_quote_meta)
            ->result_array();
    }

    public function listQuotesByOwner($owner_user_id, $limit = 50)
    {
        return $this->db
            ->where('owner_user_id', (int) $owner_user_id)
            ->order_by('id', 'DESC')
            ->limit((int) $limit)
            ->get($this->table_quote_meta)
            ->result_array();
    }

    public function listQuotes($limit = 50)
    {
        return $this->db
            ->select('q.*')
            ->from($this->table_quote_meta . ' q')
            ->order_by('q.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();
    }

    public function searchQuotes($search = '', $limit = 50)
    {
        $search = trim((string) $search);
        if ($search === '') {
            return $this->listQuotes($limit);
        }

        return $this->db
            ->select('q.*')
            ->from($this->table_quote_meta . ' q')
            ->group_start()
            ->like('q.quote_number', $search)
            ->or_like('q.client_name', $search)
            ->or_like('q.client_email', $search)
            ->or_like('q.company_name', $search)
            ->or_like('q.quote_title', $search)
            ->group_end()
            ->order_by('q.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();
    }

    public function countQuotesByOwner($owner_user_id)
    {
        return (int) $this->db
            ->where('owner_user_id', (int) $owner_user_id)
            ->count_all_results($this->table_quote_meta);
    }

    public function getQuoteById($id)
    {
        $row = $this->db->where('id', (int) $id)->get($this->table_quote_meta)->row_array();
        if (! $row) {
            return null;
        }

        $row['input'] = json_decode($row['input_json'], true);
        $row['response'] = json_decode($row['response_json'], true);

        return $row;
    }

    public function getQuoteDetail($id)
    {
        $row = $this->db
            ->select('q.*, er.id AS engine_run_row_id, er.rule_set_id, er.request_json AS engine_request_json, er.response_json AS engine_response_json, er.duration_ms, er.created_at AS engine_run_created_at')
            ->from($this->table_quote_meta . ' q')
            ->join($this->table_engine_run . ' er', 'er.quote_id = q.id', 'left')
            ->where('q.id', (int) $id)
            ->order_by('er.id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (! $row) {
            return null;
        }

        $row['input'] = json_decode($row['input_json'], true);
        $row['response'] = json_decode($row['response_json'], true);
        $row['engine_request'] = ! empty($row['engine_request_json']) ? json_decode($row['engine_request_json'], true) : null;
        $row['engine_response'] = ! empty($row['engine_response_json']) ? json_decode($row['engine_response_json'], true) : null;

        return $row;
    }

    public function listEngineRuns($limit = 50)
    {
        return $this->db
            ->select('er.*, q.quote_number, q.client_name, q.quote_title')
            ->from($this->table_engine_run . ' er')
            ->join($this->table_quote_meta . ' q', 'q.id = er.quote_id', 'left')
            ->order_by('er.id', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();
    }

    public function getDashboardStats()
    {
        $stats = array(
            'quotation_count' => 0,
            'pdf_ready_count' => 0,
            'pdf_missing_count' => 0,
            'latest_total_due' => 0.0,
        );

        $row = $this->db
            ->select('COUNT(*) AS quotation_count, SUM(CASE WHEN pdf_path IS NULL OR pdf_path = "" THEN 0 ELSE 1 END) AS pdf_ready_count, SUM(CASE WHEN pdf_path IS NULL OR pdf_path = "" THEN 1 ELSE 0 END) AS pdf_missing_count')
            ->from($this->table_quote_meta)
            ->get()
            ->row_array();

        if (! empty($row)) {
            $stats['quotation_count'] = (int) $row['quotation_count'];
            $stats['pdf_ready_count'] = (int) $row['pdf_ready_count'];
            $stats['pdf_missing_count'] = (int) $row['pdf_missing_count'];
        }

        $latest = $this->db
            ->select('total_due')
            ->from($this->table_quote_meta)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (! empty($latest)) {
            $stats['latest_total_due'] = (float) $latest['total_due'];
        }

        return $stats;
    }

    public function createRuleSetVersion($data, $created_by = 'admin')
    {
        $active = $this->getActiveRuleSet();
        $next_version = 'prototype-v1';
        if (! empty($active['version']) && preg_match('/^prototype-v(\d+)$/', $active['version'], $matches)) {
            $next_version = 'prototype-v' . ((int) $matches[1] + 1);
        }

        $payload = $this->defaultRuleData();
        foreach ($payload as $key => $value) {
            if (array_key_exists($key, $data)) {
                $payload[$key] = is_numeric($data[$key]) ? (float) $data[$key] : $data[$key];
            }
        }

        $payload['rule_set_version'] = $next_version;

        $this->db->where('status', 'ACTIVE')->update($this->table_rule_set, array('status' => 'INACTIVE'));
        $this->db->insert($this->table_rule_set, array(
            'code' => 'prototype-general',
            'version' => $next_version,
            'status' => 'ACTIVE',
            'data_json' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'created_by' => $created_by,
            'activated_at' => date('Y-m-d H:i:s'),
        ));

        return $this->getActiveRuleSet();
    }

    public function saveQuote($meta, $engine_request, $engine_response, $rule_set_id, $owner_user_id = null)
    {
        $this->db->trans_start();

        $quote = array(
            'owner_user_id' => $owner_user_id !== null ? (int) $owner_user_id : null,
            'client_name' => $meta['client_name'],
            'client_email' => $meta['client_email'],
            'company_name' => $meta['company_name'],
            'quote_title' => $meta['quote_title'],
            'status' => 'saved',
            'rule_set_version' => $engine_response['rule_set_version'],
            'engine_version' => $engine_response['engine_version'],
            'input_json' => json_encode($engine_request, JSON_UNESCAPED_SLASHES),
            'response_json' => json_encode($engine_response, JSON_UNESCAPED_SLASHES),
            'total_due' => $engine_response['total_due'],
        );
        $this->db->insert($this->table_quote_meta, $quote);
        $quote_id = (int) $this->db->insert_id();

        $quote_number = $this->config->item('liquidcp_quote_prefix') . '-' . date('Ymd') . '-' . str_pad((string) $quote_id, 4, '0', STR_PAD_LEFT);
        $this->db->where('id', $quote_id)->update($this->table_quote_meta, array('quote_number' => $quote_number));

        $engine_run = array(
            'quote_id' => $quote_id,
            'rule_set_id' => (int) $rule_set_id,
            'request_json' => json_encode($engine_request, JSON_UNESCAPED_SLASHES),
            'response_json' => json_encode($engine_response, JSON_UNESCAPED_SLASHES),
            'engine_version' => $engine_response['engine_version'],
            'duration_ms' => isset($engine_response['_duration_ms']) ? (int) $engine_response['_duration_ms'] : null,
        );
        $this->db->insert($this->table_engine_run, $engine_run);
        $engine_run_id = (int) $this->db->insert_id();

        $this->db->where('id', $quote_id)->update($this->table_quote_meta, array('engine_run_id' => $engine_run_id));

        $this->db->trans_complete();

        return $this->getQuoteById($quote_id);
    }

    public function updateQuotePdfPath($quote_id, $pdf_path)
    {
        $this->db->where('id', (int) $quote_id)->update($this->table_quote_meta, array('pdf_path' => $pdf_path));
    }

    public function defaultRuleData()
    {
        return array(
            'rule_set_version' => 'prototype-v1',
            'vat_rate' => 0.16,
            'supervision_rate' => 0.05,
            'thrust_boring_fee_rate' => 100,
            'cisco_4221_exchange_multiplier' => 580,
            'tplink_archer_c6_exchange_multiplier' => 55,
            'media_converter' => 66.5,
            'sfp_10km' => 12.49,
            'patch_code' => 2.2019,
            'warning_tape' => 0.0725,
            'hdpe_pipe' => 0.581,
            'inline_joint_closure' => 32.9,
            'patch_panel_splice_box' => 12.7699,
            'joint_closure_dome' => 34.3015,
            'cable_frame' => 21.22,
            'pole_clamps' => 11.2445,
            'tensioners' => 17.2204,
            'suspension_clamps' => 21.3468,
            'stay_wire_assembly' => 74.43,
        );
    }
}
