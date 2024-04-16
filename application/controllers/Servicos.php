<?php

defined('BASEPATH') or exit('Ação não permitida');

class Servicos extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->flash_data('error', 'Sua sessão expirou');
            redirect('login');
        }
    }

    public function index()
    {
        $data = [
            'titulo' => 'Serviços cadastrados',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'servicos' => $this->CoreModel->getAll('servicos')
        ];


        // [servico_id] => 1
        // [servico_nome] => Limpeza geral
        // [servico_preco] => 50,00
        // [servico_descricao] => Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem
        // [servico_ativo] => 1
        // [servico_data_alteracao] => 2020-02-21 22:22:20

        // echo "<pre>";
        // print_r($data['servicos']);
        // echo "<pre>";

        $this->load->view('layout/header', $data);
        $this->load->view('servicos/index');
        $this->load->view('layout/footer');
    }

    public function edit($servico_id)
    {
        if (!$servico_id || !$this->CoreModel->getById('servicos', ['servico_id' => $servico_id])) {
            $this->session->set_flashdata('error', 'Serviço não encontrado');
            redirect('servicos');
        }


        $this->form_validation->set_rules('servico_nome', '', 'trim|required|min_length[10]|max_length[145]');
        $this->form_validation->set_rules('servico_preco', '', 'trim|required');
        $this->form_validation->set_rules('servico_descricao', '', 'trim|required|max_length[700]');

        if ($this->form_validation->run()) {

            $data = elements(
                [
                    'servico_nome',
                    'servico_preco',
                    'servico_descricao',
                    'servico_ativo'
                ],
                $this->input->post()
            );

            $data = html_escape($data);

            $this->CoreModel->update('servicos', $data, ['servico_id' => $servico_id]);

            redirect('servicos');
        } else {

            $data = [
                'titulo' => 'Atualizar serviços',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'servico' => $this->CoreModel->getById('servicos', ["servico_id" => $servico_id])
            ];

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            $this->load->view('layout/header', $data);
            $this->load->view('servicos/edit');
            $this->load->view('layout/footer');
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('servico_nome', '', 'trim|required|min_length[10]|max_length[145]');
        $this->form_validation->set_rules('servico_preco', '', 'trim|required');
        $this->form_validation->set_rules('servico_descricao', '', 'trim|required|max_length[700]');

        if ($this->form_validation->run()) {

            $data = elements(
                [
                    'servico_nome',
                    'servico_preco',
                    'servico_descricao',
                    'servico_ativo'
                ],
                $this->input->post()
            );

            $data = html_escape($data);

            // echo "<pre>";
            // print_r($data);
            // exit;
            // echo "</pre>";

            $this->CoreModel->insert('servicos', $data);

            redirect('servicos');
        } else {

            $data = [
                'titulo' => 'Cadastrar serviços',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ]
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('servicos/add');
            $this->load->view('layout/footer');
        }
    }
    public function del($servico_id = NULL)
    {
        if (!$servico_id || !$this->CoreModel->getById('servicos', ['servico_id' => $servico_id])) {
            $this->session->set_flashdata('error', 'Serviço não encontrado');
            redirect('servicos');
        } else {
            $this->CoreModel->delete('servicos', ['servico_id' => $servico_id]);

            redirect('servicos');
        }
    }

    public function check_nome_servico($servico_nome)
    {
        $servico_id = $this->input->post('servico_id');
        if (!$servico_nome || $this->CoreModel->getById('servicos', ['servico_id !=' => $servico_id, 'servico_nome ' => $servico_nome])) {
            $this->form_validation->set_message('servico_nome', 'Este serviço já existe');
            return FALSE;
        }
        return TRUE;
    }
}
