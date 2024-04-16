<?php

defined("BASEPATH") or exit("Ação não permitida");

class Formas_pagamento extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não tem permissão o menu formas de pagamento!');
            redirect('home');
        }
    }

    public function index()
    {
        $data = [
            'titulo' => 'Formas de pagamento cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'formas_pagamento' => $this->CoreModel->getAll('formas_pagamentos')
        ];

        // echo "<pre>";
        // print_r($data['formas_pagamento']);
        // exit;
        // echo "</pre>";

        // (
        //     [forma_pagamento_id] => 3
        //     [forma_pagamento_nome] => Boleto bancário
        //     [forma_pagamento_aceita_parc] => 1
        //     [forma_pagamento_ativa] => 0
        //     [forma_pagamento_data_alteracao] => 2020-01-29 18:44:14
        // )


        $this->load->view('layout/header', $data);
        $this->load->view('formas_pagamento/index');
        $this->load->view('layout/footer');
    }

    public function edit($forma_pagamento_id = NULL)
    {
        if (!$forma_pagamento_id || !$this->CoreModel->getById('formas_pagamentos', ['forma_pagamento_id' => $forma_pagamento_id])) {
            $this->session->set_flashdata('error', 'categoria não encontrada');
            redirect('categorias');
        } else {

            $this->form_validation->set_rules('forma_pagamento_nome', 'Nome da forma de pagamento', 'trim|required|min_length[4]|max_length[45]|callback_check_pagamento_nome');
            if ($this->form_validation->run()) {
                // exit('Validado');


                // Criar impedimento de desativação
                $forma_pagamento_ativa = $this->input->post('forma_pagamento_ativa');

                // Para vendas
                if ($this->db->table_exists('vendas')) {
                    if ($forma_pagamento_ativa == 0 && $this->CoreModel->getById('vendas', ['venda_forma_pagamento_id' => $forma_pagamento_id, 'venda_status' => 0])) {
                        $this->session->set_flashdata('info', 'Forma de pagamento não pode ser desativada, pois está sendo utilzada em vendas');
                        redirect('modulo');
                    }
                }

                // Para ordens de serviço
                if ($this->db->table_exists('ordem_servicos')) {
                    if ($forma_pagamento_ativa == 0 && $this->CoreModel->getById('ordens_servicos', ['ordem_servico_forma_pagamento_id' => $forma_pagamento_id, 'ordem_servico_status' => 0])) {
                        $this->session->set_flashdata('info', 'Forma de pagamento não pode ser desativada, pois está sendo utilzada em ordem de serviço');
                        redirect('modulo');
                    }
                }

                $data = elements([
                    'forma_pagamento_nome',
                    'forma_pagamento_ativa',
                    'forma_pagamento_aceita_parc'
                ], $this->input->post());
                $data = html_escape($data);
                $this->CoreModel->update('formas_pagamentos', $data, ['forma_pagamento_id' => $forma_pagamento_id]);
                redirect('modulo');
            } else {
                $data = [
                    'titulo' => 'Editar forma de pagamento',
                    'forma_pagamento' => $this->CoreModel->getById('formas_pagamentos', ["forma_pagamento_id" => $forma_pagamento_id])
                ];

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";

                $this->load->view('layout/header', $data);
                $this->load->view('formas_pagamento/edit');
                $this->load->view('layout/footer');
            }
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('forma_pagamento_nome', 'Nome da forma de pagamento', 'trim|required|min_length[4]|max_length[45]|is_unique[formas_pagamentos.forma_pagamento_nome]');

        if ($this->form_validation->run()) {
            // exit('Validado');
            $data = elements([
                'forma_pagamento_nome',
                'forma_pagamento_ativa',
                'forma_pagamento_aceita_parc'
            ], $this->input->post());
            $data = html_escape($data);
            $this->CoreModel->insert('formas_pagamentos', $data);
            redirect('modulo');
        } else {
            $data = [
                'titulo' => 'Cadastrar forma de pagamento',
            ];

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            $this->load->view('layout/header', $data);
            $this->load->view('formas_pagamento/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($forma_pagamento_id = NULL)
    {
        if (!$forma_pagamento_id || !$this->CoreModel->getById('formas_pagamentos', ['forma_pagamento_id' => $forma_pagamento_id])) {
            $this->session->set_flashdata('error', 'categoria não encontrada');
            redirect('categorias');
        } else {

            // Para vendas
            // if ($this->db->table_exists('vendas')) {
            //     if ($this->CoreModel->getById('vendas', ['venda_forma_pagamento_id' => $forma_pagamento_id])) {
            //         $this->session->set_flashdata('info', 'Forma de pagamento não pode ser excluída, pois está sendo utilzada em vendas');
            //         redirect('modulo');
            //     }
            // }

            // Para ordens de serviço
            // if ($this->db->table_exists('ordem_servicos')) {
            //     if ($this->CoreModel->getById('ordens_servicos', ['ordem_servico_forma_pagamento_id' => $forma_pagamento_id])) {
            //         $this->session->set_flashdata('info', 'Forma de pagamento não pode ser excluída, pois está sendo utilzada em ordem de serviço');
            //         redirect('modulo');
            //     }
            // }

            if ($this->CoreModel->getById('formas_pagamentos', ['forma_pagamento_id' => $forma_pagamento_id, 'forma_pagamento_ativa' => 1])) {
                $this->session->set_flashdata('info', 'Não é possível excluir uma forma de pagamento que está ativa');
                redirect('modulo');
            }

            $this->CoreModel->delete('formas_pagamentos', ['forma_pagamento_id' => $forma_pagamento_id]);
            redirect('modulo');
        }
    }

    public function check_pagamento_nome($forma_pagamento_nome)
    {
        $forma_pagamento_id = $this->input->post('forma_pagamento_id');

        if ($this->CoreModel->getById('formas_pagamentos', ['forma_pagamento_nome' => $forma_pagamento_nome, 'forma_pagamento_id  !=' => $forma_pagamento_id,])) {
            $this->form_validation->set_message('check_pagamento_nome', 'Essa forma de pagamento já existe');
            return FALSE;
        }
        return TRUE;
    }
}
