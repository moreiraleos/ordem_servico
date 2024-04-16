<?php
defined('BASEPATH') or exit('Ação não permitida');

class Relatorios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou!');
            redirect('login');
        }

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não tem permissão o menu relatórios!');
            redirect('home');
        }
    }

    public function vendas()
    {

        $data_inicial = $this->input->post('data_inicial');
        $data_final = $this->input->post('data_final');


        if ($data_inicial) {
            $this->load->model('VendasModel');

            if ($this->VendasModel->gerar_relatorio_vendas($data_inicial, $data_final)) {
                // Montar o pDF
                $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);

                $vendas = $this->VendasModel->gerar_relatorio_vendas($data_inicial, $data_final);

                // echo "<pre>";
                // print_r($vendas);
                // echo "</pre>";
                // exit;

                // $file_name = 'Relatório de Vendas&nbsp;' . $venda->venda_id;
                $file_name = 'Relatório de Vendas&nbsp;';

                // Início do HTML
                $html = '<html>';
                $html .= '<head>';
                $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de vendas</title>';
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


                if ($data_inicial && $data_final) {
                    // Dados das vendas no período
                    $html .= '<p align="center" style="font-size:12px;">Relatório de vendas realizadas entre as seguintes datas: </p>';
                    $html .= '<p align="center" style="font-size:12px;">' . formata_data_banco_sem_hora($data_inicial) . ' - ' . formata_data_banco_sem_hora($data_final) . ' </p>';
                } else {
                    // Dados das vendas no período
                    $html .= '<p align="center" style="font-size:12px;">Relatório de vendas a partir da data :</p>';
                    $html .= '<p align="center" style="font-size:12px;">' . formata_data_banco_sem_hora($data_inicial) . ' </p>';
                }


                // $html .= '<p>'
                //     . '<strong>Cliente:&nbsp;</strong>' . $venda->cliente_nome_completo . '<br />' .
                //     '<strong>CPF:&nbsp;</strong>' . $venda->cliente_cpf_cnpj . '<br />' .
                //     '<strong>Celular:&nbsp;</strong>' . $venda->cliente_celular . '<br />' .
                //     '<strong>Data de emissão:&nbsp;</strong>' . formata_data_banco_com_hora($venda->venda_data_emissao)  . '<br />' .
                //     '<strong>Forma de pagamento:&nbsp;</strong>' . $venda->forma_pagamento . '<br />' .
                //     '</p>';
                $html .= '<hr/>';
                // Dados da ordem
                $html .= '<table width="100%" border="0">';
                $html .= '<tr>';
                $html .= '<th>Venda</th>';
                $html .= '<th>Data</th>';
                $html .= '<th>Cliente</th>';
                $html .= '<th>Forma de pagamento</th>';
                $html .= '<th>Valor total</th>';
                $html .= '</tr>';

                // $produtos_venda = $this->VendasModel->getAllProdutos($venda_id);

                // $valor_final_venda = $this->VendasModel->getValorFinalVenda($venda_id);

                $valor_final_venda = $this->VendasModel->get_valor_final_relatorio($data_inicial, $data_final);
                // echo "<pre>";
                // print_r($valor_final_venda);
                // exit;
                // echo "</pre>";

                foreach ($vendas as $venda) {

                    $html .= '<tr align="center">';
                    $html .= '<td >' . $venda->venda_id . '</td>';
                    $html .= '<td>' . formata_data_banco_com_hora($venda->venda_data_emissao) . '</td>';
                    $html .= '<td>&nbsp;' . $venda->cliente_nome_completo . '</td>';
                    $html .= '<td>&nbsp;' . $venda->forma_pagamento . '</td>';
                    $html .= '<td>R$&nbsp;' . $venda->venda_valor_total . '</td>';
                    $html .= '</tr>';
                }
                $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_venda->venda_valor_total . '</td>';

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
            } else {
                if (!empty($data_inicial) && !empty($data_final)) {
                    $this->session->set_flashdata('info', 'Não foram encontradas vendas entre as datas ' . formata_data_banco_sem_hora($data_inicial) . '&nbsp;e&nbsp;' . formata_data_banco_sem_hora($data_final));
                } else {
                    $this->session->set_flashdata('info', 'Não foram encontradas vendas a partir de ' . formata_data_banco_sem_hora($data_inicial));
                }
                redirect('relatorios/vendas');
            }
        }

        $data = [
            'titulo' => 'Relatório de vendas'
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/vendas');
        $this->load->view('layout/footer');
    }
    public function os()
    {

        $data_inicial = $this->input->post('data_inicial');
        $data_final = $this->input->post('data_final');


        if ($data_inicial) {
            $this->load->model('OrdemServicosModel');

            if ($this->OrdemServicosModel->gerar_relatorio_os($data_inicial, $data_final)) {
                // Montar o pDF
                $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);

                $ordens_servicos = $this->OrdemServicosModel->gerar_relatorio_os($data_inicial, $data_final);

                // echo "<pre>";
                // print_r($ordens_servicos);
                // echo "</pre>";
                // exit;

                // $file_name = 'Relatório de Vendas&nbsp;' . $venda->venda_id;
                $file_name = 'Relatório de ordens de serviços&nbsp;';

                // Início do HTML
                $html = '<html>';
                $html .= '<head>';
                $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de os</title>';
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


                if ($data_inicial && $data_final) {
                    // Dados das os no período
                    $html .= '<p align="center" style="font-size:12px;">Relatório de ordem de serviços realizadas entre as seguintes datas: </p>';
                    $html .= '<p align="center" style="font-size:12px;">' . formata_data_banco_sem_hora($data_inicial) . ' - ' . formata_data_banco_sem_hora($data_final) . ' </p>';
                } else {
                    // Dados das os no período
                    $html .= '<p align="center" style="font-size:12px;">Relatório de ordem de serviços a partir da data :</p>';
                    $html .= '<p align="center" style="font-size:12px;">' . formata_data_banco_sem_hora($data_inicial) . ' </p>';
                }

                $html .= '<hr/>';
                // Dados da ordem
                $html .= '<table width="100%" border="0">';
                $html .= '<tr>';
                $html .= '<th>Ordem</th>';
                $html .= '<th>Data</th>';
                $html .= '<th>Cliente</th>';
                $html .= '<th>Forma de pagamento</th>';
                $html .= '<th>Valor total</th>';
                $html .= '</tr>';

                // $produtos_venda = $this->VendasModel->getAllProdutos($venda_id);

                // $valor_final_venda = $this->VendasModel->getValorFinalVenda($venda_id);

                $valor_final_os = $this->OrdemServicosModel->get_valor_final_relatorio_os($data_inicial, $data_final);
                // echo "<pre>";
                // print_r($valor_final_os);
                // exit;
                // echo "</pre>";

                foreach ($ordens_servicos as $os) {

                    $html .= '<tr align="center">';
                    $html .= '<td >' . $os->ordem_servico_id . '</td>';
                    $html .= '<td>' . formata_data_banco_com_hora($os->ordem_servico_data_emissao) . '</td>';
                    $html .= '<td>&nbsp;' . $os->cliente_nome_completo . '</td>';
                    $html .= '<td>&nbsp;' . $os->forma_pagamento . '</td>';
                    $html .= '<td>R$&nbsp;' . $os->ordem_servico_valor_total . '</td>';
                    $html .= '</tr>';
                }
                $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_os->os_valor_total . '</td>';

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
            } else {
                if (!empty($data_inicial) && !empty($data_final)) {
                    $this->session->set_flashdata('info', 'Não foram encontradas ordens de serviço entre as datas ' . formata_data_banco_sem_hora($data_inicial) . '&nbsp;e&nbsp;' . formata_data_banco_sem_hora($data_final));
                } else {
                    $this->session->set_flashdata('info', 'Não foram encontradas ordens de serviço a partir de ' . formata_data_banco_sem_hora($data_inicial));
                }
                redirect('relatorios/os');
            }
        }

        $data = [
            'titulo' => 'Relatório de ordens de serviços'
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/os');
        $this->load->view('layout/footer');
    }

    public function receber()
    {

        $data = [
            'titulo' => 'Relatório de contas a receber'
        ];

        $contas = $this->input->post('contas');

        if ($contas == 'vencidas' || $contas == 'pagas' || $contas == 'receber') {
            $this->load->model('FinanceiroModel');

            if ($contas == 'vencidas') {

                $conta_receber_status = 0;
                $data_vencimento = true;

                if ($this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status, $data_vencimento)) {
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status, $data_vencimento);


                    $file_name = 'Relatório de contas vencidas&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas vencidas</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data vencimento</th>';
                    $html .= '<th>Cliente</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {

                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_receber_id . '</td>';
                        $html .= '<td>' . formata_data_banco_com_hora($conta->conta_receber_data_vencimento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->cliente_nome_completo . '</td>';
                        $html .= '<td>Vencida</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_receber_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaReceberRelatorio($conta_receber_status, $data_vencimento);

                    // echo "<pre>";
                    // print_r($valor_final_contas);
                    // exit;
                    // echo "</pre>";

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_receber_valor_total . '</td>';

                    $html .= '</th>';

                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não exitem contas vencidas na base de dados');
                    redirect('relatorios/receber');
                }
                // Formar o PDF

            }
            if ($contas == 'pagas') {

                $conta_receber_status = 1;

                // Formar o PDF

                if ($this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status)) {
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status);


                    $file_name = 'Relatório de contas pagas&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas pagas</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data pagamento</th>';
                    $html .= '<th>Cliente</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {
                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_receber_id . '</td>';
                        $html .= '<td>' . formata_data_banco_com_hora($conta->conta_receber_data_pagamento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->cliente_nome_completo . '</td>';
                        $html .= '<td>Vencida</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_receber_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaReceberRelatorio($conta_receber_status);

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_receber_valor_total . '</td>';

                    $html .= '</th>';
                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não existem contas pagas na base de dados');
                }
            }
            if ($contas == 'receber') {

                $conta_receber_status = 0;
                $data_vencimento = true;

                if ($this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status)) {
                    // Formar o PDF
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasReceberRelatorio($conta_receber_status);

                    $file_name = 'Relatório de contas a receber&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas a receber</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data vencimento</th>';
                    $html .= '<th>Cliente</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {
                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_receber_id . '</td>';
                        $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_receber_data_vencimento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->cliente_nome_completo . '</td>';
                        $html .= '<td>A receber</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_receber_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaReceberRelatorio($conta_receber_status);

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_receber_valor_total . '</td>';

                    $html .= '</th>';

                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não existem contas a receber no banco de dados');
                }
            }
            //pagas 
        }


        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/receber');
        $this->load->view('layout/footer');
    }

    public function pagar()
    {

        $data = [
            'titulo' => 'Relatório de contas a pagar'
        ];

        $contas = $this->input->post('contas');

        if ($contas == 'vencidas' || $contas == 'pagas' || $contas == 'a_pagar') {
            $this->load->model('FinanceiroModel');

            if ($contas == 'vencidas') {

                $conta_pagar_status = 0;
                $data_vencimento = true;

                if ($this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status, $data_vencimento)) {
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status, $data_vencimento);


                    $file_name = 'Relatório de contas vencidas&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas vencidas</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data vencimento</th>';
                    $html .= '<th>Fornecedor</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {

                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_pagar_id . '</td>';
                        $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_pagar_data_vencimento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->fornecedor_nome_fantasia . '</td>';
                        $html .= '<td>Vencida</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_pagar_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaPagarRelatorio($conta_pagar_status, $data_vencimento);

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total . '</td>';

                    $html .= '</th>';

                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não existem contas vencidas na base de dados');
                    redirect('relatorios/pagar');
                }
                // Formar o PDF

            }
            if ($contas == 'pagas') {

                $conta_pagar_status = 1;

                if ($this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status)) {
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status);

                    $file_name = 'Relatório de contas pagas&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas vencidas</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data pagamento</th>';
                    $html .= '<th>Fornecedor</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {

                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_pagar_id . '</td>';
                        $html .= '<td>' . formata_data_banco_com_hora($conta->conta_pagar_data_pagamento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->fornecedor_nome_fantasia . '</td>';
                        $html .= '<td>Paga</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_pagar_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaPagarRelatorio($conta_pagar_status);

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total . '</td>';

                    $html .= '</th>';

                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não existem contas pagas na base de dados');
                    redirect('relatorios/pagar');
                }
                // Formar o PDF

            }
            if ($contas == 'a_pagar') {

                $conta_pagar_status = 0;

                if ($this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status)) {
                    $empresa = $this->CoreModel->getById('sistema', ['sistema_id' => 1]);
                    $contas = $this->FinanceiroModel->getContasPagarRelatorio($conta_pagar_status);

                    $file_name = 'Relatório de contas a pagar&nbsp;';

                    // Início do HTML
                    $html = '<html>';
                    $html .= '<head>';
                    $html .= '<title>' . $empresa->sistema_nome_fantasia . '| Relatório de contas a pagar</title>';
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

                    // Dados da ordem
                    $html .= '<table width="100%" border="0">';
                    $html .= '<tr>';
                    $html .= '<th>Conta ID</th>';
                    $html .= '<th>Data vencimento</th>';
                    $html .= '<th>Fornecedor</th>';
                    $html .= '<th>Situação</th>';
                    $html .= '<th>Valor total</th>';
                    $html .= '</tr>';

                    foreach ($contas as $conta) {

                        $html .= '<tr align="center">';
                        $html .= '<td >' . $conta->conta_pagar_id . '</td>';
                        $html .= '<td>' . formata_data_banco_sem_hora($conta->conta_pagar_data_vencimento) . '</td>';
                        $html .= '<td>&nbsp;' . $conta->fornecedor_nome_fantasia . '</td>';
                        $html .= '<td>A pagar</td>';
                        $html .= '<td>R$&nbsp;' . $conta->conta_pagar_valor . '</td>';
                        $html .= '</tr>';
                    }
                    $valor_final_contas = $this->FinanceiroModel->getSumContaPagarRelatorio($conta_pagar_status);

                    $html .= '<th  style="border-top:solid #ddd 1px" colspan="3">';

                    $html .= '<td align="center" style="border-top:solid #ddd 2px"><strong>Valor final</strong></td>';
                    $html .= '<td  align="center" style="border-top:solid #ddd 2px">R$&nbsp;' . $valor_final_contas->conta_pagar_valor_total . '</td>';

                    $html .= '</th>';

                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';

                    // False - Abre PDF no navegador
                    // True - Faz o download 

                    $this->pdf->createPDF($html, $file_name, false);
                } else {
                    $this->session->set_flashdata('info', 'Não existem contas a pagar na base de dados');
                    redirect('relatorios/pagar');
                }
                // Formar o PDF

            }
        }


        $this->load->view('layout/header', $data);
        $this->load->view('relatorios/pagar');
        $this->load->view('layout/footer');
    }
}
