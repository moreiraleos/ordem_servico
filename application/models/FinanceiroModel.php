<?php

defined('BASEPATH') or exit("Ação não permitida");
class FinanceiroModel extends CI_Model
{

    public function getAllPagar()
    {
        $this->db->select([
            'contas_pagar.*',
            'fornecedores.fornecedor_id',
            'fornecedores.fornecedor_nome_fantasia as fornecedor'
        ]);
        $this->db->join('fornecedores', 'fornecedor_id = conta_pagar_fornecedor_id');
        return $this->db->get('contas_pagar')->result();
    }
    public function getAllReceber()
    {
        $this->db->select([
            'contas_receber.*',
            'clientes.cliente_id',
            'clientes.cliente_nome as cliente'
        ]);
        $this->db->join('clientes', 'cliente_id = conta_receber_cliente_id', 'LEFT');
        return $this->db->get('contas_receber')->result();
    }
    public function getById($conta_pagar_id = null)
    {
        $this->db->select([
            'contas_pagar.*',
            'fornecedores.fornecedor_id',
            'fornecedores.fornecedor_nome_fantasia as fornecedor'
        ]);
        $this->db->join('fornecedores', 'fornecedor_id = conta_pagar_fornecedor_id', 'LEFT');
        $this->db->where($conta_pagar_id);
        return $this->db->get('contas_pagar')->row();
    }

    // Utilização para relatórios
    public function getContasReceberRelatorio($conta_receber_status = null, $data_vencimento = NULL)
    {
        $this->db->select([
            'contas_receber.*',
            'clientes.cliente_id',
            'CONCAT(clientes.cliente_nome," ", clientes.cliente_sobrenome) as cliente_nome_completo',
        ]);
        $this->db->join('clientes', 'cliente_id = conta_receber_cliente_id', 'LEFT');

        $this->db->where('conta_receber_status', $conta_receber_status);

        if ($data_vencimento) {
            date_default_timezone_set('America/Sao_Paulo');
            $this->db->where('conta_receber_data_vencimento <  ', date('Y-m-d'));
        }

        return $this->db->get('contas_receber')->result();
    }


    public function getSumContaReceberRelatorio($conta_receber_status = null, $data_vencimento = NULL)
    {
        $this->db->select([
            'FORMAT(SUM(REPLACE(conta_receber_valor, ",","")),2) as conta_receber_valor_total'
        ]);

        $this->db->where('conta_receber_status', $conta_receber_status);

        if ($data_vencimento) {
            date_default_timezone_set('America/Sao_Paulo');
            $this->db->where('conta_receber_data_vencimento <  ', date('Y-m-d'));
        }

        return $this->db->get('contas_receber')->row();
    }

    public function getContasPagarRelatorio($conta_pagar_status = null, $data_vencimento = NULL)
    {
        $this->db->select([
            'contas_pagar.*',
            'fornecedores.fornecedor_id',
            'fornecedores.fornecedor_nome_fantasia',
            'fornecedores.fornecedor_cnpj',
        ]);
        $this->db->join('fornecedores', 'fornecedor_id = conta_pagar_fornecedor_id', 'LEFT');

        $this->db->where('conta_pagar_status', $conta_pagar_status);

        if ($data_vencimento) {
            date_default_timezone_set('America/Sao_Paulo');
            $this->db->where('conta_pagar_data_vencimento <  ', date('Y-m-d'));
        }

        return $this->db->get('contas_pagar')->result();
    }


    public function getSumContaPagarRelatorio($conta_pagar_status = null, $data_vencimento = NULL)
    {
        $this->db->select([
            'FORMAT(SUM(REPLACE(conta_pagar_valor, ",","")),2) as conta_pagar_valor_total'
        ]);

        $this->db->where('conta_pagar_status', $conta_pagar_status);

        if ($data_vencimento) {
            date_default_timezone_set('America/Sao_Paulo');
            $this->db->where('conta_pagar_data_vencimento <  ', date('Y-m-d'));
        }

        return $this->db->get('contas_pagar')->row();
    }

    // Fim para relatórios
}
