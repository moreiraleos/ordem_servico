<?php

defined('BASEPATH') or die('Ação não permitida');

class Vendas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
        $this->load->model('VendasModel');
        $this->load->model('ProdutosModel');
    }
    public function index()
    {
        $data = [
            'titulo' => 'Vendas cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'vendas' => $this->VendasModel->getAll(),
        ];

        // echo "<pre>";
        // print_r($data['vendas']);
        // echo "</pre>";
        // exit;


        // (
        //     [cliente_id] => 1
        //     [cliente_nome] => Gisele
        //     [vendedor_id] => 1
        //     [vendedor_nome_completo] => Lucio Antonio de Souza  - TESTE
        //     [forma_pagamento_id] => 5
        //     [forma_pagamento] => Cartão de débito
        //     [venda_id] => 1
        //     [venda_cliente_id] => 1
        //     [venda_forma_pagamento_id] => 5
        //     [venda_vendedor_id] => 1
        //     [venda_tipo] => 
        //     [venda_data_emissao] => 2023-11-20 10:06:14
        //     [venda_valor_desconto] => 
        //     [venda_valor_total] => 15.22
        //     [venda_data_alteracao] => 
        // )



        $this->load->view('layout/header', $data);
        $this->load->view('vendas/index');
        $this->load->view('layout/footer');
    }

    public function edit($venda_id = NULL)
    {
        if (!$venda_id || !$this->CoreModel->getById('vendas', ['venda_id' => $venda_id])) {
            $this->session->set_flashdata('error', 'Venda não encontrada!');
            redirect('vendas');
        } else {


            // Form validation set rules()
            $this->form_validation->set_rules('venda_cliente_id', '', 'required');
            $this->form_validation->set_rules('venda_tipo', '', 'required');
            $this->form_validation->set_rules('venda_forma_pagamento_id', '', 'required');
            $this->form_validation->set_rules('venda_vendedor_id', '', 'required');
            // Atualização de estoque
            $venda_produtos = $data['venda_produtos'] =  $this->VendasModel->getAllProdutosByVenda($venda_id);
            if ($this->form_validation->run()) {

                $venda_valor_total = str_replace('R$', '', trim($this->input->post('venda_valor_total')));

                $data = elements([
                    'venda_cliente_id',
                    'venda_forma_pagamento_id',
                    'venda_tipo',
                    'venda_vendedor_id',
                    'venda_valor_desconto',
                    'venda_valor_total'
                ], $this->input->post());

                $data['venda_valor_total'] = trim(preg_replace('/\$/', '', $venda_valor_total));
                $data = html_escape($data);
                $this->CoreModel->update('vendas', $data, ['venda_id' => $venda_id]);

                // Deletando de vendas, os produtos antigos da venda editada
                $this->VendasModel->deleteOldProdutos($venda_id);

                $produto_id = $this->input->post('produto_id');
                $produto_quantidade = $this->input->post('produto_quantidade');
                $produto_desconto = str_replace('%', '', $this->input->post('produto_desconto'));

                $produto_preco_venda = str_replace('R$', '', $this->input->post('produto_preco_venda'));
                $produto_item_total = str_replace('R$', '', $this->input->post('produto_item_total'));

                $produto_preco_venda = str_replace(',', '', $produto_preco_venda);
                $produto_item_total = str_replace(',', '', $produto_item_total);

                $qty_produto = count($produto_id);


                // $venda_id = $this->input->post('venda_id');

                for ($i = 0; $i < $qty_produto; $i++) {
                    $data = [
                        'venda_produto_id_venda' => $venda_id,
                        'venda_produto_id_produto' => $produto_id[$i],
                        'venda_produto_quantidade' => $produto_quantidade[$i],
                        'venda_produto_valor_unitario' => $produto_preco_venda[$i],
                        'venda_produto_desconto' => $produto_desconto[$i],
                        'venda_produto_valor_total' => $produto_item_total[$i],
                    ];

                    $data = html_escape($data);

                    $this->CoreModel->insert('venda_produtos', $data);


                    // Início atualização estoque
                    // Removido, pois é somente a página é somente para visualização

                    // foreach ($venda_produtos as $venda_p) {
                    //     if ($venda_p->venda_produto_quantidade < $produto_quantidade[$i]) {
                    //         $produto_qtde_estoque = 0;
                    //         $produto_qtde_estoque += intval($produto_quantidade[$i]);
                    //         $diferenca = ($produto_qtde_estoque - $venda_p->venda_produto_quantidade);

                    //         $this->ProdutosModel->update($produto_id[$i], $diferenca);
                    //     }
                    // } 
                    //Fim foreach

                    // Fim atualização estoque

                };

                // Recurso PDF
                // redirect('vendas/imprimir/' . $venda_id);
                redirect('vendas');
            } else {
                $data = [
                    'titulo' => 'Atualizar venda',
                    'styles' => array(
                        'vendor/select2/select2.min.css',
                        'vendor/autocomplete/jquery-ui.css',
                        'vendor/autocomplete/estilo.css',
                    ),
                    'scripts' => array(
                        'vendor/autocomplete/jquery-migrate.js',
                        'vendor/calcx/jquery-calx-sample-2.2.8.min.js',
                        'vendor/calcx/venda.js',
                        'vendor/select2/select2.min.js',
                        'vendor/select2/app.js',
                        'vendor/sweetalert2/sweetalert2.js',
                        'vendor/autocomplete/jquery-ui.js'
                    ),
                    'venda' => $this->VendasModel->getById($venda_id),
                    'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1]),
                    'formas_pagamentos' => $this->CoreModel->getAll('formas_pagamentos', ['forma_pagamento_ativa' => 1]),
                    'vendedores' => $this->CoreModel->getAll('vendedores', ['vendedor_ativo' => 1]),
                    'venda_produtos' => $this->VendasModel->getAllProdutosByVenda($venda_id),
                    'desabilitar' => TRUE //Desabilita botão de submit
                ];
                // $venda = $data['venda'] = $this->VendasModel->getById($venda_id);
                // $data['venda_produtos'] =  $this->VendasModel->getAllProdutosByVenda($venda_id);

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";
                // exit;

                $this->load->view('layout/header', $data);
                $this->load->view('vendas/edit');
                $this->load->view('layout/footer');
            }
        }
    }
    public function add()
    {
        // Form validation set rules()
        $this->form_validation->set_rules('venda_cliente_id', '', 'required');
        $this->form_validation->set_rules('venda_tipo', '', 'required');
        $this->form_validation->set_rules('venda_forma_pagamento_id', '', 'required');
        $this->form_validation->set_rules('venda_vendedor_id', '', 'required');
        // Atualização de estoque
        // $venda_produtos = $data['venda_produtos'] =  $this->VendasModel->getAllProdutosByVenda($venda_id);
        if ($this->form_validation->run()) {

            $venda_valor_total = str_replace('R$', '', trim($this->input->post('venda_valor_total')));

            $data = elements([
                'venda_cliente_id',
                'venda_forma_pagamento_id',
                'venda_tipo',
                'venda_vendedor_id',
                'venda_valor_desconto',
                'venda_valor_total'
            ], $this->input->post());

            $data['venda_valor_total'] = trim(preg_replace('/\$/', '', $venda_valor_total));
            $data = html_escape($data);
            $this->CoreModel->insert('vendas', $data, TRUE);

            // RECUPERR O ID
            $id_venda = $this->session->userdata('last_id');

            $produto_id = $this->input->post('produto_id');
            $produto_quantidade = $this->input->post('produto_quantidade');
            $produto_desconto = str_replace('%', '', $this->input->post('produto_desconto'));

            $produto_preco_venda = str_replace('R$', '', $this->input->post('produto_preco_venda'));
            $produto_item_total = str_replace('R$', '', $this->input->post('produto_item_total'));

            $produto_preco_venda = str_replace(',', '', $produto_preco_venda);
            $produto_item_total = str_replace(',', '', $produto_item_total);

            $qty_produto = count($produto_id);


            for ($i = 0; $i < $qty_produto; $i++) {
                $data = [
                    'venda_produto_id_venda' => $id_venda,
                    'venda_produto_id_produto' => $produto_id[$i],
                    'venda_produto_quantidade' => $produto_quantidade[$i],
                    'venda_produto_valor_unitario' => $produto_preco_venda[$i],
                    'venda_produto_desconto' => $produto_desconto[$i],
                    'venda_produto_valor_total' => $produto_item_total[$i],
                ];

                $data = html_escape($data);

                $this->CoreModel->insert('venda_produtos', $data);

                // Início atualização estoque

                $produto_quantidade_estoque = 0;

                $produto_quantidade_estoque += intval($produto_quantidade[$i]);
                $produtos = [
                    'produto_qtde_estoque' => $produto_quantidade_estoque
                ];
                $this->ProdutosModel->update($produto_id[$i], $produto_quantidade_estoque);
                // Fim atualização estoque

            };

            // Recurso PDF
            redirect('vendas/imprimir/' . $id_venda);
            // redirect('vendas');
        } else {
            $data = [
                'titulo' => 'Cadastrar venda',
                'styles' => array(
                    'vendor/select2/select2.min.css',
                    'vendor/autocomplete/jquery-ui.css',
                    'vendor/autocomplete/estilo.css',
                ),
                'scripts' => array(
                    'vendor/autocomplete/jquery-migrate.js',
                    'vendor/calcx/jquery-calx-sample-2.2.8.min.js',
                    'vendor/calcx/venda.js',
                    'vendor/select2/select2.min.js',
                    'vendor/select2/app.js',
                    'vendor/sweetalert2/sweetalert2.js',
                    'vendor/autocomplete/jquery-ui.js'
                ),
                'clientes' => $this->CoreModel->getAll('clientes', ['cliente_ativo' => 1]),
                'formas_pagamentos' => $this->CoreModel->getAll('formas_pagamentos', ['forma_pagamento_ativa' => 1]),
                'vendedores' => $this->CoreModel->getAll('vendedores', ['vendedor_ativo' => 1])
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('vendas/add');
            $this->load->view('layout/footer');
        }
    }
    public function del($venda_id = NULL)
    {
        if (!$venda_id || !$this->CoreModel->getById('vendas', ['venda_id' => $venda_id])) {
            $this->session->set_flashdata('error', 'Venda não encontrada!');
            redirect('vendas');
        } else {
            $this->CoreModel->delete('vendas', ['venda_id' => $venda_id]);
            redirect('vendas');
        }
    }

    public function imprimir($venda_id = NULL)
    {
        if (!$venda_id || !$this->CoreModel->getById('vendas', ['venda_id' => $venda_id])) {
            $this->session->set_flashdata('error', 'Venda não encontrada');
            redirect('vendas');
        } else {
            $data = [
                'titulo' => 'Escolha uma opção',
                'venda' => $this->CoreModel->getById('vendas', ['venda_id' => $venda_id])
            ];

            // [venda_id] => 7
            // [venda_cliente_id] => 3
            // [venda_forma_pagamento_id] => 3
            // [venda_vendedor_id] => 1
            // [venda_tipo] => 1
            // [venda_data_emissao] => 2023-11-20 19:47:59
            // [venda_valor_desconto] => R$ 0.00
            // [venda_valor_total] => 15,031.00
            // [venda_data_alteracao] => 

            // echo "<pre>";
            // print_r($data['venda']);
            // exit;
            // echo "</pre>";


            $this->load->view('layout/header', $data);
            $this->load->view('vendas/imprimir');
            $this->load->view('layout/footer');
        }
    }

    public function pdf($venda_id = NULL)
    {
        if (!$venda_id || !$this->CoreModel->getById('vendas', ['vendas.venda_id' => $venda_id])) {
            $this->session->set_flashdata('error', 'Venda não encontrada');
            redirect('vendas');
        } else {

            $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);

            // echo "<pre>";
            // print_r($empresa);
            // echo "</pre>";
            // exit;

            $venda = $this->VendasModel->getById($venda_id);

            // echo "<pre>";
            // print_r($ordem_servico);
            // echo "</pre>";
            // exit;

            $file_name = 'Venda&nbsp;' . $venda->venda_id;

            // Início do HTML
            $html = '<html>';
            $html .= '<head>';
            $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Impressão de venda</title>';
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
            $html .= '<p align="right" style="font-size:12px;">Venda Nº&nbsp;' . $venda->venda_id . '</p>';
            $html .= '<p>'
                . '<strong>Cliente:&nbsp;</strong>' . $venda->cliente_nome_completo . '<br />' .
                '<strong>CPF:&nbsp;</strong>' . $venda->cliente_cpf_cnpj . '<br />' .
                '<strong>Celular:&nbsp;</strong>' . $venda->cliente_celular . '<br />' .
                '<strong>Data de emissão:&nbsp;</strong>' . formata_data_banco_com_hora($venda->venda_data_emissao)  . '<br />' .
                '<strong>Forma de pagamento:&nbsp;</strong>' . $venda->forma_pagamento . '<br />' .
                '</p>';
            $html .= '<hr/>';
            // Dados da ordem
            $html .= '<table width="100%" border="0">';
            $html .= '<tr>';
            $html .= '<th>Código produto</th>';
            $html .= '<th>Descrição</th>';
            $html .= '<th>Quantidade</th>';
            $html .= '<th>Valor unitário</th>';
            $html .= '<th>Desconto</th>';
            $html .= '<th>Valor total</th>';
            $html .= '</tr>';

            // $venda_id = $venda->venda_id;
            $produtos_venda = $this->VendasModel->getAllProdutos($venda_id);

            // $valor_final_venda = $this->VendasModel->getValorFinalVenda($venda_id);
            $valor_final_venda = $this->VendasModel->get_valor_final_relatorio($venda_id);


            foreach ($produtos_venda as $produto) {
                $html .= '<tr align="center">';
                $html .= '<td >' . $produto->venda_produto_id_produto . '</td>';
                $html .= '<td>' . $produto->produto_descricao . '</td>';
                $html .= '<td>R$&nbsp;' . $produto->venda_produto_quantidade . '</td>';
                $html .= '<td>R$&nbsp;' . $produto->venda_produto_valor_unitario . '</td>';
                $html .= '<td>%&nbsp;' . $produto->venda_produto_desconto . '</td>';
                $html .= '<td>R$&nbsp;' . $produto->venda_produto_valor_total . '</td>';
                $html .= '</tr>';
            }
            $html .= '<th colspan="4">';

            $html .= '<td style="border-top:solid #ddd 1px"><strong>Valor final</strong></td>';
            $html .= '<td style="border-top:solid #ddd 1px">R$&nbsp;' . $valor_final_venda->venda_valor_total . '</td>';

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
