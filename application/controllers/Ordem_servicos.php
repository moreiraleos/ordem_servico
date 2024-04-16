<?php

defined('BASEPATH') or die('Ação não permitida');

class Ordem_servicos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
        $this->load->model('OrdemServicosModel');
    }
    public function index()
    {
        $data = [
            'titulo' => 'Ordens de serviços cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'ordens_servicos' => $this->OrdemServicosModel->getAll()
        ];

        // echo "<pre>";
        // print_r($data['ordens_servicos']);
        // echo "</pre>";
        // exit;
        // 
        // 
        // 
        //         
        // [cliente_id] => 1
        // [cliente_nome] => Gisele
        // [forma_pagamento_id] => 1
        // [forma_pagamento] => Cartão de crédito
        // [ordem_servico_id] => 1
        // [ordem_servico_forma_pagamento_id] => 1
        // [ordem_servico_cliente_id] => 1
        // [ordem_servico_data_emissao] => 2020-02-14 17:30:35
        // [ordem_servico_data_conclusao] => 
        // [ordem_servico_equipamento] => Fone de ouvido
        // [ordem_servico_marca_equipamento] => Awell
        // [ordem_servico_modelo_equipamento] => AV1801
        // [ordem_servico_acessorios] => Mouse e carregador
        // [ordem_servico_defeito] => Não sai aúdio no lado esquerdo
        // [ordem_servico_valor_desconto] => R$ 0.00
        // [ordem_servico_valor_total] => 490.00
        // [ordem_servico_status] => 0
        // [ordem_servico_obs] => 
        // [ordem_servico_data_alteracao] => 2020-02-19 22:58:42


        $this->load->view('layout/header', $data);
        $this->load->view('Ordem_servicos/index');
        $this->load->view('layout/footer');
    }
    public function edit($ordem_servico_id = NULL)
    {
        if (!$ordem_servico_id || !$this->CoreModel->getById('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id])) {
            $this->session->set_flashdata('error', 'Ordem de serviço não encontrada!');
            redirect('os');
        } else {

            // Form validation set rules()
            $this->form_validation->set_rules('ordem_servico_cliente_id', '', 'required');
            $ordem_servico_status = $this->input->post('ordem_servico_status');
            if ($ordem_servico_status == 1) {
                $this->form_validation->set_rules('ordem_servico_forma_pagamento_id', '', 'required');
            }
            $this->form_validation->set_rules('ordem_servico_equipamento', 'Equipamento', 'trim|required|min_length[2]|max_length[80]');
            $this->form_validation->set_rules('ordem_servico_marca_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
            $this->form_validation->set_rules('ordem_servico_modelo_equipamento', 'Modelo', 'trim|required|min_length[2]|max_length[80]');
            $this->form_validation->set_rules('ordem_servico_acessorios', 'Acessórios', 'trim|required|max_length[300]');
            $this->form_validation->set_rules('ordem_servico_defeito', 'Defeito', 'trim|required|max_length[700]');


            if ($this->form_validation->run()) {

                $ordem_servico_valor_total = str_replace('R$', '', trim($this->input->post('ordem_servico_valor_total')));

                $data = elements([
                    'ordem_servico_cliente_id',
                    'ordem_servico_forma_pagamento_id',
                    'ordem_servico_equipamento',
                    'ordem_servico_status',
                    'ordem_servico_equipamento',
                    'ordem_servico_marca_equipamento',
                    'ordem_servico_modelo_equipamento',
                    'ordem_servico_defeito',
                    'ordem_servico_acessorios',
                    'ordem_servico_obs',
                    'ordem_servico_valor_desconto',
                    'ordem_servico_valor_total'
                ], $this->input->post());

                if ($ordem_servico_status == 0) {
                    unset($data['ordem_servico_forma_pagamento_id']);
                }

                $data['ordem_servico_valor_total'] = trim(preg_replace('/\$/', '', $ordem_servico_valor_total));
                $data = html_escape($data);
                $this->CoreModel->update('ordens_servicos', $data, ['ordem_servico_id' => $ordem_servico_id]);

                // Deletando de ordem-tem_servicos, os seviços antigos da ordem editada
                $this->OrdemServicosModel->deleteOldServices($ordem_servico_id);

                $servico_id = $this->input->post('servico_id');
                $servico_quantidade = $this->input->post('servico_quantidade');
                $servico_desconto = str_replace('%', '', $this->input->post('servico_desconto'));

                $servico_preco = str_replace('R$', '', $this->input->post('servico_preco'));
                $servico_item_total = str_replace('R$', '', $this->input->post('servico_item_total'));

                $servico_preco = str_replace(',', '', $servico_preco);
                $servico_item_total = str_replace(',', '', $servico_item_total);
                // echo "<pre>";
                // print_r($servico_id);
                // echo "</pre>";
                // exit;
                $qty_servico = count($servico_id);

                $ordem_servico_id = $this->input->post('ordem_servico_id');

                for ($i = 0; $i < $qty_servico; $i++) {
                    $data = [
                        'ordem_ts_id_ordem_servico' => $ordem_servico_id,
                        'ordem_ts_id_servico' => $servico_id[$i],
                        'ordem_ts_quantidade' => $servico_quantidade[$i],
                        'ordem_ts_valor_unitario' => $servico_preco[$i],
                        'ordem_ts_valor_desconto' => $servico_desconto[$i],
                        'ordem_ts_valor_total' => $servico_item_total[$i],
                    ];

                    $data = html_escape($data);

                    // echo "<pre>";
                    // print_r($data);
                    // echo "</pre>";
                    // exit;
                    $this->CoreModel->insert('ordem_tem_servicos', $data);
                };

                // Recurso PDF
                redirect('os/imprimir/' . $ordem_servico_id);
            } else {
                $data = [
                    'titulo' => 'Atualizar ordem de serviço',
                    'styles' => array(
                        'vendor/select2/select2.min.css',
                        'vendor/autocomplete/jquery-ui.css',
                        'vendor/autocomplete/estilo.css',
                    ),
                    'scripts' => array(
                        'vendor/autocomplete/jquery-migrate.js',
                        'vendor/calcx/jquery-calx-sample-2.2.8.min.js',
                        'vendor/calcx/os.js',
                        'vendor/select2/select2.min.js',
                        'vendor/select2/app.js',
                        'vendor/sweetalert2/sweetalert2.js',
                        'vendor/autocomplete/jquery-ui.js'
                    ),
                    'ordem_servico' => $this->OrdemServicosModel->getById($ordem_servico_id),
                    'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1]),
                    'formas_pagamentos' => $this->CoreModel->getAll('formas_pagamentos', ['forma_pagamento_ativa' => 1]),
                    'os_tem_servicos' => $this->OrdemServicosModel->getAllServicosByOrdem($ordem_servico_id)
                ];
                $ordem_servico = $data['ordem_servico'];

                // echo "<pre>";
                // echo print_r($oderm_servico);
                // echo "</pre>";
                // exit;

                // (
                //     [ordem_servico_id] => 1
                //     [ordem_servico_forma_pagamento_id] => 1
                //     [ordem_servico_cliente_id] => 1
                //     [ordem_servico_data_emissao] => 2020-02-14 17:30:35
                //     [ordem_servico_data_conclusao] => 
                //     [ordem_servico_equipamento] => Fone de ouvido
                //     [ordem_servico_marca_equipamento] => Awell
                //     [ordem_servico_modelo_equipamento] => AV1801
                //     [ordem_servico_acessorios] => Mouse e carregador
                //     [ordem_servico_defeito] => Não sai aúdio no lado esquerdo
                //     [ordem_servico_valor_desconto] => R$ 0.00
                //     [ordem_servico_valor_total] => 490.00
                //     [ordem_servico_status] => 0
                //     [ordem_servico_obs] => 
                //     [ordem_servico_data_alteracao] => 2020-02-19 22:58:42
                //     [cliente_id] => 1
                //     [cliente_cpf_cnpj] => 128.857.367-71
                //     [cliente_nome_completo] => Gisele dos Santos Moreira
                //     [forma_pagamento_id] => 1
                //     [forma_pagamento] => Cartão de crédito
                // )


                $this->load->view('layout/header', $data);
                $this->load->view('ordem_servicos/edit');
                $this->load->view('layout/footer');
            }
        }
    }
    public function add()
    {
        // Form validation set rules()
        $this->form_validation->set_rules('ordem_servico_cliente_id', '', 'required');
        $this->form_validation->set_rules('ordem_servico_equipamento', 'Equipamento', 'trim|required|min_length[2]|max_length[80]');
        $this->form_validation->set_rules('ordem_servico_marca_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
        $this->form_validation->set_rules('ordem_servico_modelo_equipamento', 'Modelo', 'trim|required|min_length[2]|max_length[80]');
        $this->form_validation->set_rules('ordem_servico_acessorios', 'Acessórios', 'trim|required|max_length[300]');
        $this->form_validation->set_rules('ordem_servico_defeito', 'Defeito', 'trim|required|max_length[700]');


        if ($this->form_validation->run()) {

            $ordem_servico_valor_total = str_replace('R$', '', trim($this->input->post('ordem_servico_valor_total')));

            $data = elements([
                'ordem_servico_cliente_id',
                'ordem_servico_equipamento',
                'ordem_servico_status',
                'ordem_servico_equipamento',
                'ordem_servico_marca_equipamento',
                'ordem_servico_modelo_equipamento',
                'ordem_servico_defeito',
                'ordem_servico_acessorios',
                'ordem_servico_obs',
                'ordem_servico_valor_desconto',
                'ordem_servico_valor_total'
            ], $this->input->post());

            $data['ordem_servico_valor_total'] = trim(preg_replace('/\$/', '', $ordem_servico_valor_total));
            $data = html_escape($data);
            $this->CoreModel->insert('ordens_servicos', $data, TRUE);

            // RECUPERAR ID

            $id_ordem_servico = $this->session->userdata('last_id');


            $servico_id = $this->input->post('servico_id');
            $servico_quantidade = $this->input->post('servico_quantidade');
            $servico_desconto = str_replace('%', '', $this->input->post('servico_desconto'));

            $servico_preco = str_replace('R$', '', $this->input->post('servico_preco'));
            $servico_item_total = str_replace('R$', '', $this->input->post('servico_item_total'));

            $servico_preco = str_replace(',', '', $servico_preco);
            $servico_item_total = str_replace(',', '', $servico_item_total);

            $qty_servico = count($servico_id);

            $ordem_servico_id = $this->input->post('ordem_servico_id');

            for ($i = 0; $i < $qty_servico; $i++) {
                $data = [
                    'ordem_ts_id_ordem_servico' => $id_ordem_servico,
                    'ordem_ts_id_servico' => $servico_id[$i],
                    'ordem_ts_quantidade' => $servico_quantidade[$i],
                    'ordem_ts_valor_unitario' => $servico_preco[$i],
                    'ordem_ts_valor_desconto' => $servico_desconto[$i],
                    'ordem_ts_valor_total' => $servico_item_total[$i],
                ];

                $data = html_escape($data);

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";
                // exit;
                $this->CoreModel->insert('ordem_tem_servicos', $data);
            };

            // Recurso PDF
            redirect('os/imprimir/' . $id_ordem_servico);
        } else {
            $data = [
                'titulo' => 'Cadastrar ordem de serviço',
                'styles' => array(
                    'vendor/select2/select2.min.css',
                    'vendor/autocomplete/jquery-ui.css',
                    'vendor/autocomplete/estilo.css',
                ),
                'scripts' => array(
                    'vendor/autocomplete/jquery-migrate.js',
                    'vendor/calcx/jquery-calx-sample-2.2.8.min.js',
                    'vendor/calcx/os.js',
                    'vendor/select2/select2.min.js',
                    'vendor/select2/app.js',
                    'vendor/sweetalert2/sweetalert2.js',
                    'vendor/autocomplete/jquery-ui.js'
                ),
                // 'ordem_servico' => $this->OrdemServicosModel->getById($ordem_servico_id),
                'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1]),
            ];
            // $ordem_servico = $data['ordem_servico'];

            $this->load->view('layout/header', $data);
            $this->load->view('ordem_servicos/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($ordem_servico_id = NULL)
    {
        if (!$ordem_servico_id || !$this->CoreModel->getById('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id])) {
            $this->session->set_flashdata('error', 'Ordem de serviço não encontrada!');
            redirect('os');
        }

        if ($this->CoreModel->getById('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id, 'ordem_servico_status' => 0])) {
            $this->session->set_flashdata('error', 'Não é possível excluir uma ordem de serviço Em aberto!');
            redirect('os');
        }

        $this->CoreModel->delete('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id]);
        redirect('os');
    }

    public function imprimir($ordem_servico_id = NULL)
    {
        if (!$ordem_servico_id || !$this->CoreModel->getById('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id])) {
            $this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
            redirect('os');
        } else {
            $data = [
                'titulo' => 'Escolha uma opção',
                'ordem_servico' => $this->CoreModel->getById('ordens_servicos', ['ordem_servico_id' => $ordem_servico_id])
            ];
            $this->load->view('layout/header', $data);
            $this->load->view('ordem_servicos/imprimir');
            $this->load->view('layout/footer');
        }
    }
    public function pdf($ordem_servico_id = NULL)
    {
        if (!$ordem_servico_id || !$this->CoreModel->getById('ordens_servicos', ['ordens_servicos.ordem_servico_id' => $ordem_servico_id])) {
            $this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
            redirect('os');
        } else {

            $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);

            // echo "<pre>";
            // print_r($empresa);
            // echo "</pre>";
            // exit;

            $ordem_servico = $this->OrdemServicosModel->getById($ordem_servico_id);

            // echo "<pre>";
            // print_r($ordem_servico);
            // echo "</pre>";
            // exit;

            $file_name = 'O.S$nbsp;' . $ordem_servico->ordem_servico_id;

            // Início do HTML
            $html = '<html>';
            $html .= '<head>';
            $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Impressão de Ordem de serviço</title>';
            $html .= '</head>';
            $html .= '<body style="font-size:14px;">';

            $html .= '<h4 align="center">' .
                $empresa->sistema_razao_social . '<br />' .
                ' CNPJ ' . $empresa->sistema_cnpj . ' ' .
                $empresa->sistema_endereco . ', &nbsp;' . $empresa->sistema_numero  . '<br />' .
                'CEP: ' . $empresa->sistema_cep . ', &nbsp;' . $empresa->sistema_cidade   . ',&nbsp;' . $empresa->sistema_estado . '<br/>' .
                'Telefone: ' . $empresa->sistema_telefone_fixo . ' ' .
                'E-mail: ' . $empresa->sistema_email . '<br />'
                . '<h4>';

            $html .= '<hr/>';

            // Dados do cliente
            $html .= '<p align="right" style="font-size:12px;">O.S Nº&nbsp;' . $ordem_servico->ordem_servico_id . '</p>';
            $html .= '<p>'
                . '<strong>Cliente:&nbsp;</strong>' . $ordem_servico->cliente_nome_completo . '<br />' .
                '<strong>CPF:&nbsp;</strong>' . $ordem_servico->cliente_cpf_cnpj . '<br />' .
                '<strong>Celular:&nbsp;</strong>' . $ordem_servico->cliente_celular . '<br />' .
                '<strong>Data de emissão:&nbsp;</strong>' . formata_data_banco_com_hora($ordem_servico->ordem_servico_data_emissao)  . '<br />' .
                '<strong>Forma de pagamento:&nbsp;</strong>' . ($ordem_servico->ordem_servico_status == 1 ? $ordem_servico->forma_pagamento : 'Em aberto')  . '<br />' .
                '</p>';
            $html .= '<hr/>';
            // Dados da ordem
            $html .= '<table width="100%" border="solid #ddd 1px">';
            $html .= '<tr>';
            $html .= '<th>Servico</th>';
            $html .= '<th>Quantidade</th>';
            $html .= '<th>Valor unitário</th>';
            $html .= '<th>Desconto</th>';
            $html .= '<th>Valor total</th>';
            $html .= '</tr>';

            $ordem_servico_id = $ordem_servico->ordem_servico_id;
            $servicos_ordem = $this->OrdemServicosModel->getAllServicos($ordem_servico_id);

            $valor_final_os = $this->OrdemServicosModel->getValorFinalOs($ordem_servico_id);


            foreach ($servicos_ordem as $servico) {
                $html .= '<tr>';
                $html .= '<td>' . $servico->servico_nome . '</td>';
                $html .= '<td>' . $servico->ordem_ts_quantidade . '</td>';
                $html .= '<td>R$&nbsp;' . $servico->ordem_ts_valor_unitario . '</td>';
                $html .= '<td>%&nbsp;' . $servico->ordem_ts_valor_desconto . '</td>';
                $html .= '<td>R$&nbsp;' . $servico->ordem_ts_valor_total . '</td>';
                $html .= '</tr>';
            }
            $html .= '<th colspan="3">';

            $html .= '<td style="border-top:solid #ddd 1px"><strong>Valor final</strong></td>';
            $html .= '<td style="border-top:solid #ddd 1px">R$&nbsp;' . $valor_final_os->os_valor_total . '</td>';

            $html .= '</th>';

            $html .= '</table>';
            $html .= '</body>';
            $html .= '</html>';

            // False - Abre PDF no navegador
            // True - Faz o download 
            // echo "<pre>";
            // print_r($html);
            // echo "</pre>";
            // exit;
            $this->pdf->createPDF($html, $file_name, false);
        }
    }
}
