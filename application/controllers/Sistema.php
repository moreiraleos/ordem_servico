<?php
defined('BASEPATH') or exit('Ação não permitida');
class Sistema extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não tem permissão o menu relatórios!');
            redirect('home');
        }
    }

    public function index()
    {

        $data = [
            'titulo' => 'Editar informações do Sistema',
            'scripts' => [
                'vendor/mask/jquery.mask.min.js',
                'vendor/mask/app.js'
            ],
            'sistema' => $this->CoreModel->getById('sistema', ['sistema_id' => 1])
        ];

        $this->form_validation->set_rules('sistema_razao_social', 'Razão social', 'required|min_length[10]|max_length[145]');
        $this->form_validation->set_rules('sistema_nome_fantasia', 'Nome fantasia', 'required|min_length[10]|max_length[145]');
        $this->form_validation->set_rules('sistema_cnpj', '', 'required|exact_length[18]'); //18 caracteres
        $this->form_validation->set_rules('sistema_ie', '', 'required|max_length[25]');
        $this->form_validation->set_rules('sistema_telefone_fixo', '', 'required|max_length[25]');
        $this->form_validation->set_rules('sistema_telefone_movel', '', 'required|max_length[25]');
        $this->form_validation->set_rules('sistema_email', '', 'required|valid_email|max_length[100]');
        $this->form_validation->set_rules('sistema_site_url', 'URL do site', 'required|valid_url|max_length[100]');
        $this->form_validation->set_rules('sistema_cep', 'URL do site', 'required|exact_length[9]');
        $this->form_validation->set_rules('sistema_endereco', 'Endereço', 'required|max_length[145]');
        $this->form_validation->set_rules('sistema_numero', 'Número', 'max_length[25]');
        $this->form_validation->set_rules('sistema_cidade', 'Cidade', 'required|max_length[25]');
        $this->form_validation->set_rules('sistema_estado', 'UF', 'required|max_length[2]');
        $this->form_validation->set_rules('sistema_txt_ordem_servico', 'Texto ordem de serviço', 'max_length[500]');


        if ($this->form_validation->run()) {

            $data = elements(
                array(
                    'sistema_razao_social',
                    'sistema_nome_fantasia',
                    'sistema_cnpj',
                    'sistema_ie',
                    'sistema_telefone_fixo',
                    'sistema_telefone_movel',
                    'sistema_email',
                    'sistema_site_url',
                    'sistema_cep',
                    'sistema_endereco',
                    'sistema_numero',
                    'sistema_cidade',
                    'sistema_estado',
                    'sistema_txt_ordem_servico'
                ),
                $this->input->post()
            );

            $data = html_escape($data);

            $this->CoreModel->update('sistema', $data, ['sistema_id' => 1]);

            redirect('sistema');
        } else {
            // Erro de validação
            $this->load->view('layout/header', $data);
            $this->load->view('sistema/index');
            $this->load->view('layout/footer');
        }
    }
}
