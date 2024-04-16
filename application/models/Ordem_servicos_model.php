<?php

defined("BASEPATH") or exit("Ação não permitida");

class Ordem_servicos_model extends CI_Model
{


    public function getAll()
    {
        $this->db->select([
            'clientes.cliente_id',
            'clientes.cliente_nome',
            'formas_pagamentos.forma_pagamento_id',
            'formas_pagamentos.forma_pagamento_nome as forma_pagamento',
            'ordens_servicos.*'
        ]);
        $this->db->join('clientes', 'cliente_id = ordens_servicos.ordem_servico_cliente_id');
        $this->db->join('formas_pagamentos', 'forma_pagamento_id = ordens_servicos.ordem_servico_forma_pagamento_id');
        return $this->db->get('ordens_servicos')->result();
    }
    public function getAllServicosByOrdem($ordem_servico_id = NULL)
    {
        if ($ordem_servico_id) {
            $this->db->select([
                'ordem_tem_servicos.*',
                'servicos.descricao'
            ]);
            $this->db->join('servicos', 'servico_id = ordem_ts_id_servico', 'LEFT');
            $this->db->where('order_ts_id_ordem_servico', $ordem_servico_id);

            return $this->db->get('ordem_tem_servicos')->result();
        }
    }

    public function deleteOldServices($ordem_servico_id = NULL)
    {
        if ($ordem_servico_id) {
            $this->db->delete('ordem_tem_servicos', [
                'ordem_ts_id_ordem_servico' => $ordem_servico_id
            ]);
        }
    }
}
