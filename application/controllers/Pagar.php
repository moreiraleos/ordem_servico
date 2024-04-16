<?php

defined('BASEPATH') or exit("Ação não permitida");


class Pagar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não tem permissão o menu contas a pagar!');
            redirect('home');
        }
        $this->load->model('FinanceiroModel');
    }

    public function index()
    {
        $data = [
            'titulo' => 'Contas a pagar cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'contas_pagar' => $this->FinanceiroModel->getAllPagar()
        ];

        // echo "<pre>";
        // print_r($data['contas_pagar']);
        // echo "</pre>";
        // exit;

        // [conta_pagar_id] => 1
        // [conta_pagar_fornecedor_id] => 2
        // [conta_pagar_data_vencimento] => 2023-10-09
        // [conta_pagar_data_pagamento] => 
        // [conta_pagar_valor] => 800.00
        // [conta_pagar_status] => 0
        // [conta_pagar_obs] => 
        // [conta_pagar_data_alteracao] => 2023-10-17 21:59:50
        // [fornecedor_id] => 2
        // [fornecedor_nome_fantasia] => Casa Noturna Ltda


        $this->load->view('layout/header', $data);
        $this->load->view('pagar/index');
        $this->load->view('layout/footer');
    }

    public function edit($conta_pagar_id = null)
    {
        if (!$conta_pagar_id || !$this->CoreModel->getById('contas_pagar', ['conta_pagar_id' => $conta_pagar_id])) {
            $this->session->set_flashdata('error', 'Conta não encontrada');
            redirect('pagar');
        } else {

            // Form_validation

            $this->form_validation->set_rules('conta_pagar_fornecedor_id', '', 'required');
            $this->form_validation->set_rules('conta_pagar_data_vencimento', '', 'required');
            $this->form_validation->set_rules('conta_pagar_valor', '', 'required');
            $this->form_validation->set_rules('conta_pagar_obs', 'Observações', 'max_length[100]');

            if ($this->form_validation->run()) {

                $data = elements(
                    [
                        'conta_pagar_fornecedor_id',
                        'conta_pagar_data_vencimento',
                        'conta_pagar_valor',
                        'conta_pagar_status',
                        'conta_pagar_obs'
                    ],
                    $this->input->post()
                );

                $conta_pagar_status = $this->input->post('conta_pagar_status');
                if ($conta_pagar_status == 1) {
                    $data['conta_pagar_data_pagamento'] = date('Y-m-d h:i:s');
                }


                $data = html_escape($data);

                $this->CoreModel->update('contas_pagar', $data, ['conta_pagar_id' => $conta_pagar_id]);

                redirect('pagar');
            } else {
                $data = [
                    'titulo' => 'Atualizar contas a pagar',
                    'styles' => [
                        'vendor/select2/select2.min.css'
                    ],
                    'scripts' => [
                        'vendor/select2/select2.min.js',
                        'vendor/select2/app.js',
                        'vendor/mask/jquery.mask.min.js',
                        'vendor/mask/app.js'
                    ],
                    'conta_pagar' => $this->FinanceiroModel->getById(['conta_pagar_id' => $conta_pagar_id]),
                    // 'conta_pagar' => $this->CoreModel->getById('contas_pagar',['conta_pagar_id' => $conta_pagar_id]),

                    'fornecedores' => $this->CoreModel->getAll('fornecedores')
                ];

                // echo "<pre>";
                // print_r($data['conta_pagar']);
                // exit;
                // echo "</pre>";


                // (
                //     [conta_pagar_id] => 1
                //     [conta_pagar_fornecedor_id] => 2
                //     [conta_pagar_data_vencimento] => 2023-10-11
                //     [conta_pagar_data_pagamento] => 
                //     [conta_pagar_valor] => 800.00
                //     [conta_pagar_status] => 0
                //     [conta_pagar_obs] => 
                //     [conta_pagar_data_alteracao] => 2023-10-22 17:09:42
                //     [fornecedor_id] => 2
                //     [fornecedor] => Casa Noturna Ltda
                // )


                $this->load->view('layout/header', $data);
                $this->load->view('pagar/edit');
                $this->load->view('layout/footer');
            }
        }
    }

    public function add()
    {
        // Form_validation

        $this->form_validation->set_rules('conta_pagar_fornecedor_id', '', 'required');
        $this->form_validation->set_rules('conta_pagar_data_vencimento', '', 'required');
        $this->form_validation->set_rules('conta_pagar_valor', '', 'required');
        $this->form_validation->set_rules('conta_pagar_obs', 'Observações', 'max_length[100]');

        if ($this->form_validation->run()) {

            $data = elements(
                [
                    'conta_pagar_fornecedor_id',
                    'conta_pagar_data_vencimento',
                    'conta_pagar_valor',
                    'conta_pagar_status',
                    'conta_pagar_obs'
                ],
                $this->input->post()
            );

            $conta_pagar_status = $this->input->post('conta_pagar_status');
            if ($conta_pagar_status == 1) {
                $data['conta_pagar_data_pagamento'] = date('Y-m-d h:i:s');
            }


            $data = html_escape($data);

            $this->CoreModel->insert('contas_pagar', $data);

            redirect('pagar');
        } else {
            $data = [
                'titulo' => 'Atualizar contas a pagar',
                'styles' => [
                    'vendor/select2/select2.min.css'
                ],
                'scripts' => [
                    'vendor/select2/select2.min.js',
                    'vendor/select2/app.js',
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'fornecedores' => $this->CoreModel->getAll('fornecedores')
            ];

            // echo "<pre>";
            // print_r($data['conta_pagar']);
            // exit;
            // echo "</pre>";


            // (
            //     [conta_pagar_id] => 1
            //     [conta_pagar_fornecedor_id] => 2
            //     [conta_pagar_data_vencimento] => 2023-10-11
            //     [conta_pagar_data_pagamento] => 
            //     [conta_pagar_valor] => 800.00
            //     [conta_pagar_status] => 0
            //     [conta_pagar_obs] => 
            //     [conta_pagar_data_alteracao] => 2023-10-22 17:09:42
            //     [fornecedor_id] => 2
            //     [fornecedor] => Casa Noturna Ltda
            // )


            $this->load->view('layout/header', $data);
            $this->load->view('pagar/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($conta_pagar_id = null)
    {
        if (!$conta_pagar_id || !$this->CoreModel->getById('contas_pagar', ['conta_pagar_id' => $conta_pagar_id])) {
            $this->session->set_flashdata('error', 'Conta não encontrada');
            redirect('pagar');
        } else {

            if ($this->CoreModel->getById('contas_pagar', ['conta_pagar_id' => $conta_pagar_id, 'conta_pagar_status !=' => 0])) {
                $this->session->set_flashdata('info', 'Esta conta não pode ser excluída, pois já foi paga');
                redirect('pagar');
            } else {
                $this->CoreModel->delete('contas_pagar', ['conta_pagar_id' => $conta_pagar_id]);
                redirect('pagar');
            }
        }
    }
}
