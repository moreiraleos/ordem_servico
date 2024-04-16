<?php
defined('BASEPATH') or exit('Ação não permitida');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $data = [
            'titulo' => 'Login'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('login/index');
        $this->load->view('layout/footer');
    }

    public function auth()
    {

        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $remebemder = FALSE;

        if ($this->ion_auth->login($email, $password, $remebemder)) {
            redirect('home');
        } else {
            $this->session->set_flashdata('error', 'Verifique seu e-mail ou senha');
            redirect('/login');
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('login');
    }
}
