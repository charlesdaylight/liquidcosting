<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account extends FrontendController
{
    private $session_prefix = 'liquidcp_';

    public function __construct()
    {
        parent::__construct();
        $this->config->load('liquidcp');
        $this->load->language_alt(model_settings::$db_config['language']);
        $this->load->model('user/model_user');
    }

    public function login()
    {
        if ($this->hasLiquidcpSession()) {
            redirect(site_url('liquidcp/dashboard'));
        }

        $data = array(
            'page_title' => 'Liquid CP Login',
            'signup_url' => site_url('liquidcp/signup'),
            'action_url' => site_url('liquidcp/account/authenticate'),
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );

        $this->load->view('account/login', $data);
    }

    public function signup()
    {
        if ($this->hasLiquidcpSession()) {
            redirect(site_url('liquidcp/dashboard'));
        }

        $data = array(
            'page_title' => 'Create Liquid CP Account',
            'login_url' => site_url('liquidcp/login'),
            'action_url' => site_url('liquidcp/account/create'),
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        );

        $this->load->view('account/signup', $data);
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        $username = trim((string) $this->input->post('username', true));
        $password = trim((string) $this->input->post('password', true));

        if ($username === '' || $password === '') {
            $this->session->set_flashdata('liquidcp_auth_error', 'Username and password are required.');
            redirect(site_url('liquidcp/login'));
        }

        $user = $this->model_user->authenticateLiquidcpUser($username, $password);
        if (! $user) {
            $this->session->set_flashdata('liquidcp_auth_error', 'Invalid username or password.');
            redirect(site_url('liquidcp/login'));
        }

        $this->setLiquidcpSession($user);
        redirect(site_url('liquidcp/dashboard'));
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
        }

        $username = trim((string) $this->input->post('username', true));
        $email = trim((string) $this->input->post('email', true));
        $password = trim((string) $this->input->post('password', true));
        $password_confirm = trim((string) $this->input->post('password_confirm', true));

        if ($username === '' || $email === '' || $password === '') {
            $this->session->set_flashdata('liquidcp_signup_error', 'Username, email, and password are required.');
            redirect(site_url('liquidcp/signup'));
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('liquidcp_signup_error', 'Enter a valid email address.');
            redirect(site_url('liquidcp/signup'));
        }

        if (strlen($password) < 8) {
            $this->session->set_flashdata('liquidcp_signup_error', 'Password must be at least 8 characters.');
            redirect(site_url('liquidcp/signup'));
        }

        if ($password !== $password_confirm) {
            $this->session->set_flashdata('liquidcp_signup_error', 'Passwords do not match.');
            redirect(site_url('liquidcp/signup'));
        }

        if ($this->model_user->getUserByUsername($username)) {
            $this->session->set_flashdata('liquidcp_signup_error', 'That username is already in use.');
            redirect(site_url('liquidcp/signup'));
        }

        if ($this->model_user->getUserByEmail($email)) {
            $this->session->set_flashdata('liquidcp_signup_error', 'That email is already in use.');
            redirect(site_url('liquidcp/signup'));
        }

        $user = $this->model_user->createLiquidcpUser(array(
            'use_login' => $username,
            'use_mail' => $email,
            'use_password' => $password,
            'use_role' => 'user',
            'created_ip' => $this->input->ip_address(),
            'updated_ip' => $this->input->ip_address(),
        ));

        $this->setLiquidcpSession($user);
        redirect(site_url('liquidcp/dashboard'));
    }

    public function logout()
    {
        $keys = array(
            $this->session_prefix . 'logged_in',
            $this->session_prefix . 'user_id',
            $this->session_prefix . 'user_login',
            $this->session_prefix . 'user_mail',
            $this->session_prefix . 'user_role',
        );

        $this->session->unset_userdata($keys);
        redirect(site_url('liquidcp/login'));
    }

    private function hasLiquidcpSession()
    {
        return (bool) $this->session->userdata($this->session_prefix . 'logged_in');
    }

    private function setLiquidcpSession($user)
    {
        $this->session->set_userdata(array(
            $this->session_prefix . 'logged_in' => true,
            $this->session_prefix . 'user_id' => (int) $user->use_id,
            $this->session_prefix . 'user_login' => (string) $user->use_login,
            $this->session_prefix . 'user_mail' => (string) $user->use_mail,
            $this->session_prefix . 'user_role' => ! empty($user->use_role) ? (string) $user->use_role : 'user',
        ));
    }
}
