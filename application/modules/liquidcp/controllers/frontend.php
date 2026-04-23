<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Frontend extends FrontendController
{
    private $liquidcp_session_prefix = 'liquidcp_';

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
        if ($this->getLiquidcpUser()) {
            redirect(site_url('liquidcp/dashboard'));
        }

        $data = array(
            'page_title' => 'Liquid CP',
            'login_url' => site_url('liquidcp/login'),
            'signup_url' => site_url('liquidcp/signup'),
        );

        $this->load->view('frontend/gateway', $data);
    }

    public function dashboard()
    {
        $user = $this->requireLiquidcpUser();
        $data = array(
            'page_title' => 'Liquid CP Dashboard',
            'user' => $user,
            'is_admin_viewer' => $this->isLiquidcpAdmin($user),
            'quotes' => $this->isLiquidcpAdmin($user)
                ? $this->model_liquidcp->listQuotes(100)
                : $this->model_liquidcp->listQuotesByOwner($user['id'], 100),
            'studio_url' => site_url('liquidcp/studio'),
            'logout_url' => site_url('liquidcp/logout'),
        );

        $this->load->view('frontend/dashboard', $data);
    }

    public function studio()
    {
        $this->requireLiquidcpUser();
        $data = array(
            'active_rule_set' => $this->model_liquidcp->getActiveRuleSet(),
            'page_title' => 'Liquid CP Estimate Studio',
            'prototype_disclaimer' => $this->config->item('liquidcp_prototype_disclaimer'),
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'dashboard_url' => site_url('liquidcp/dashboard'),
            'defaults' => array(
                'build_profile' => 'prototype-general',
                'distance_new_aerial' => 1000,
                'distance_new_underground' => 0,
                'distance_existing_aerial' => 0,
                'distance_existing_duct' => 0,
                'customer_nrc' => 800,
                'customer_mrc' => 2765,
                'exchange_rate' => 28,
            ),
        );

        $this->load->view('frontend/studio', $data);
    }

    public function estimate()
    {
        $this->requireLiquidcpUser(true);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        try {
            $request = $this->buildEstimateRequest($this->input->post());
            $estimate = $this->callEstimateEngine($request);
            $this->respondJson(array(
                'success' => true,
                'estimate' => $estimate,
                'rule_set_version' => $estimate['rule_set_version'],
            ));
        } catch (Exception $e) {
            $this->respondJson(array(
                'success' => false,
                'message' => $e->getMessage(),
            ), 500);
        }
    }

    public function save()
    {
        $user = $this->requireLiquidcpUser(true);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        try {
            $post = $this->input->post();
            $request = $this->buildEstimateRequest($post);
            $estimate = $this->callEstimateEngine($request);
            $quote = $this->model_liquidcp->saveQuote(
                array(
                    'client_name' => $this->safeText($post['client_name'] ?? ''),
                    'client_email' => $this->safeText($post['client_email'] ?? ''),
                    'company_name' => $this->safeText($post['company_name'] ?? ''),
                    'quote_title' => $this->safeText($post['quote_title'] ?? ''),
                ),
                $request,
                $estimate,
                $this->model_liquidcp->getActiveRuleSet()['id'],
                $user['id']
            );

            $this->respondJson(array(
                'success' => true,
                'quote_id' => $quote['id'],
                'quote_number' => $quote['quote_number'],
                'quote_url' => site_url('liquidcp/quote/' . $quote['id']),
                'pdf_url' => site_url('liquidcp/pdf/' . $quote['id']),
            ));
        } catch (Exception $e) {
            $this->respondJson(array(
                'success' => false,
                'message' => $e->getMessage(),
            ), 500);
        }
    }

    public function quote($id)
    {
        $quote = $this->model_liquidcp->getQuoteById($id);
        if (! $quote) {
            show_404();
        }

        $data = array(
            'quote' => $quote,
            'prototype_disclaimer' => $this->config->item('liquidcp_prototype_disclaimer'),
        );

        $this->load->view('frontend/quote', $data);
    }

    public function pdf($id)
    {
        $quote = $this->model_liquidcp->getQuoteById($id);
        if (! $quote) {
            show_404();
        }

        $html = $this->load->view('frontend/pdf', array(
            'quote' => $quote,
            'prototype_disclaimer' => $this->config->item('liquidcp_prototype_disclaimer'),
        ), true);

        $filename = 'liquidcp-quotation-' . strtolower($quote['quote_number']);
        $file_to_save = generate_pdf($html, $filename, 'A4', 'portrait', false);
        $relative_path = str_replace(FCPATH, '', $file_to_save);
        $this->model_liquidcp->updateQuotePdfPath($quote['id'], $relative_path);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($file_to_save) . '"');
        readfile($file_to_save);
        exit;
    }

    private function buildEstimateRequest($source)
    {
        return array(
            'build_profile' => $this->safeText($source['build_profile'] ?? 'prototype-general'),
            'distance_new_aerial' => $this->numberValue($source['distance_new_aerial'] ?? 0),
            'distance_new_underground' => $this->numberValue($source['distance_new_underground'] ?? 0),
            'distance_existing_aerial' => $this->numberValue($source['distance_existing_aerial'] ?? 0),
            'distance_existing_duct' => $this->numberValue($source['distance_existing_duct'] ?? 0),
            'customer_nrc' => $this->numberValue($source['customer_nrc'] ?? 0),
            'customer_mrc' => $this->numberValue($source['customer_mrc'] ?? 0),
            'exchange_rate' => $this->numberValue($source['exchange_rate'] ?? 0),
            'manual_overrides' => $this->model_liquidcp->getActiveRuleSet()['data'],
        );
    }

    private function callEstimateEngine($request)
    {
        $url = rtrim($this->config->item('liquidcp_engine_base_url'), '/') . '/estimate';
        $payload = json_encode($request, JSON_UNESCAPED_SLASHES);
        $started = microtime(true);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_TIMEOUT => 15,
            ));
            $response = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            if ($response === false || $status >= 400) {
                throw new RuntimeException('Liquid CP engine call failed: ' . ($error ?: 'HTTP ' . $status));
            }
        } else {
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => $payload,
                    'timeout' => 15,
                ),
            ));
            $response = file_get_contents($url, false, $context);
            if ($response === false) {
                throw new RuntimeException('Liquid CP engine call failed.');
            }
        }

        $decoded = json_decode($response, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('Liquid CP engine returned invalid JSON.');
        }

        $decoded['_duration_ms'] = (int) round((microtime(true) - $started) * 1000);

        return $decoded;
    }

    private function respondJson($payload, $status_code = 200)
    {
        $payload['csrf'] = array(
            'token_name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
        );

        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload, JSON_UNESCAPED_SLASHES));
    }

    private function safeText($value)
    {
        return trim(strip_tags((string) $value));
    }

    private function numberValue($value)
    {
        return (float) preg_replace('/[^0-9.\-]/', '', (string) $value);
    }

    private function getLiquidcpUser()
    {
        if ($this->session->userdata($this->liquidcp_session_prefix . 'logged_in')) {
            return array(
                'id' => (int) $this->session->userdata($this->liquidcp_session_prefix . 'user_id'),
                'login' => (string) $this->session->userdata($this->liquidcp_session_prefix . 'user_login'),
                'mail' => (string) $this->session->userdata($this->liquidcp_session_prefix . 'user_mail'),
                'role' => (string) $this->session->userdata($this->liquidcp_session_prefix . 'user_role'),
                'source' => 'liquidcp',
            );
        }

        if ($this->auth->loggedIn() && $this->session->userdata('use_role') === 'admin') {
            return array(
                'id' => (int) $this->session->userdata('use_id'),
                'login' => (string) $this->session->userdata('use_login'),
                'mail' => (string) $this->session->userdata('use_mail'),
                'role' => 'admin',
                'source' => 'backend',
            );
        }

        return null;
    }

    private function requireLiquidcpUser($json = false)
    {
        $user = $this->getLiquidcpUser();
        if ($user) {
            return $user;
        }

        if ($json) {
            $this->respondJson(array(
                'success' => false,
                'message' => 'Login required.',
                'redirect_url' => site_url('liquidcp/login'),
            ), 401);
            exit;
        }

        redirect(site_url('liquidcp/login'));
    }

    private function isLiquidcpAdmin($user)
    {
        return ! empty($user['role']) && $user['role'] === 'admin';
    }
}
