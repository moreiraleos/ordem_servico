<?php

defined('BASEPATH') or exit("Ação não permitida");


class Receber extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não tem permissão o menu contas a receber!');
            redirect('home');
        }
        $this->load->model('FinanceiroModel');
    }

    public function index()
    {
        $data = [
            'titulo' => 'Contas a receber cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'contas_receber' => $this->FinanceiroModel->getAllReceber()
        ];

        // echo "<pre>";
        // print_r($data['contas_receber']);
        // echo "</pre>";
        // exit;

        // [conta_receber_id] => 1
        // [conta_receber_cliente_id] => 1
        // [conta_receber_data_vencto] => 2020-02-28
        // [conta_receber_data_pagamento] => 2020-02-28 17:48:21
        // [conta_receber_valor] => 150,226.22
        // [conta_receber_status] => 0
        // [conta_receber_obs] => 
        // [conta_receber_data_alteracao] => 2020-02-28 22:36:29
        // [cliente_id] => 1
        // [cliente] => Gisele

        $this->load->view('layout/header', $data);
        $this->load->view('receber/index');
        $this->load->view('layout/footer');
    }

    public function edit($conta_receber_id = null)
    {
        if (!$conta_receber_id || !$this->CoreModel->getById('contas_receber', ['conta_receber_id' => $conta_receber_id])) {
            $this->session->set_flashdata('error', 'Conta não encontrada');
            redirect('receber');
        } else {

            // Form_validation

            $this->form_validation->set_rules('conta_receber_cliente_id', '', 'required');
            $this->form_validation->set_rules('conta_receber_data_vencimento', '', 'required');
            $this->form_validation->set_rules('conta_receber_valor', '', 'required');
            $this->form_validation->set_rules('conta_receber_obs', 'Observações', 'max_length[100]');

            if ($this->form_validation->run()) {

                $data = elements(
                    [
                        'conta_receber_cliente_id',
                        'conta_receber_data_vencimento',
                        'conta_receber_valor',
                        'conta_receber_status',
                        'conta_receber_obs'
                    ],
                    $this->input->post()
                );

                $conta_receber_status = $this->input->post('conta_receber_status');
                if ($conta_receber_status == 1) {
                    $data['conta_receber_data_pagamento'] = date('Y-m-d h:i:s');
                }

                $data = html_escape($data);

                $this->CoreModel->update('contas_receber', $data, ['conta_receber_id' => $conta_receber_id]);

                redirect('receber');
            } else {
                $data = [
                    'titulo' => 'Editar conta',
                    'styles' => [
                        'vendor/select2/select2.min.css'
                    ],
                    'scripts' => [
                        'vendor/select2/select2.min.js',
                        'vendor/select2/app.js',
                        'vendor/mask/jquery.mask.min.js',
                        'vendor/mask/app.js'
                    ],
                    // 'conta_receber' => $this->FinanceiroModel->getById(['conta_receber_id' => $conta_receber_id]),
                    'conta_receber' => $this->CoreModel->getById('contas_receber', ['conta_receber_id' => $conta_receber_id]),

                    'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1])
                ];

                // echo "<pre>";
                // print_r($data['conta_receber']);
                // exit;
                // echo "</pre>";


                // (
                //     [conta_receber_id] => 1
                //     [conta_receber_cliente_id] => 2
                //     [conta_receber_data_vencimento] => 2023-10-11
                //     [conta_receber_data_pagamento] => 
                //     [conta_receber_valor] => 800.00
                //     [conta_receber_status] => 0
                //     [conta_receber_obs] => 
                //     [conta_receber_data_alteracao] => 2023-10-22 17:09:42
                //     [cliente_id] => 2
                //     [fornecedor] => Casa Noturna Ltda
                // )


                $this->load->view('layout/header', $data);
                $this->load->view('receber/edit');
                $this->load->view('layout/footer');
            }
        }
    }

    public function add()
    {
        // Form_validation

        $this->form_validation->set_rules('conta_receber_cliente_id', '', 'required');
        $this->form_validation->set_rules('conta_receber_data_vencimento', '', 'required');
        $this->form_validation->set_rules('conta_receber_valor', '', 'required');
        $this->form_validation->set_rules('conta_receber_obs', 'Observações', 'max_length[100]');

        if ($this->form_validation->run()) {

            $data = elements(
                [
                    'conta_receber_cliente_id',
                    'conta_receber_data_vencimento',
                    'conta_receber_valor',
                    'conta_receber_status',
                    'conta_receber_obs'
                ],
                $this->input->post()
            );

            $conta_receber_status = $this->input->post('conta_receber_status');
            if ($conta_receber_status == 1) {
                $data['conta_receber_data_pagamento'] = date('Y-m-d h:i:s');
            }

            $data = html_escape($data);

            $this->CoreModel->insert('contas_receber', $data);

            redirect('receber');
        } else {
            $data = [
                'titulo' => 'Cadastrar conta',
                'styles' => [
                    'vendor/select2/select2.min.css'
                ],
                'scripts' => [
                    'vendor/select2/select2.min.js',
                    'vendor/select2/app.js',
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1])
            ];

            // echo "<pre>";
            // print_r($data['conta_receber']);
            // exit;
            // echo "</pre>";


            // (
            //     [conta_receber_id] => 1
            //     [conta_receber_cliente_id] => 2
            //     [conta_receber_data_vencimento] => 2023-10-11
            //     [conta_receber_data_pagamento] => 
            //     [conta_receber_valor] => 800.00
            //     [conta_receber_status] => 0
            //     [conta_receber_obs] => 
            //     [conta_receber_data_alteracao] => 2023-10-22 17:09:42
            //     [cliente_id] => 2
            //     [fornecedor] => Casa Noturna Ltda
            // )


            $this->load->view('layout/header', $data);
            $this->load->view('receber/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($conta_receber_id = null)
    {
        if (!$conta_receber_id || !$this->CoreModel->getById('contas_receber', ['conta_receber_id' => $conta_receber_id])) {
            $this->session->set_flashdata('error', 'Conta não encontrada');
            redirect('receber');
        } else {

            if ($this->CoreModel->getById('contas_receber', ['conta_receber_id' => $conta_receber_id, 'conta_receber_status !=' => 0])) {
                $this->session->set_flashdata('info', 'Esta conta não pode ser excluída, pois já foi paga');
                redirect('receber');
            } else {
                $this->CoreModel->delete('contas_receber', ['conta_receber_id' => $conta_receber_id]);
                redirect('receber');
            }
        }
    }
}
