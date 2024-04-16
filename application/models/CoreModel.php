<?php

defined('BASEPATH') or exit('Ação não permitida.');

class CoreModel extends CI_Model
{
    public function getAll($table = null, $cond = null)
    {
        if ($table) {
            if (is_array($cond)) {
                $this->db->where($cond);
            }
            return $this->db->get($table)->result();
        }
        return false;
    }

    public function getById($table = null, $cond = null)
    {
        if ($table && is_array($cond)) {
            $this->db->where($cond);
            $this->db->limit(1);
            return $this->db->get($table)->row();
        }
        return false;
    }

    public function insert($table = null, $data = null, $getLastId = null)
    {
        if ($table && is_array($data)) {
            $this->db->insert($table, $data);
            if ($getLastId) {
                $this->session->set_userdata('last_id', $this->db->insert_id());
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');
            } else {
                $this->session->set_flashdata('error', 'Erro ao salvar dados');
            }
        }
    }


    public function update($table = null, $data = null, $cond = null)
    {
        if ($table && is_array($cond) && is_array($data)) {
            if ($this->db->update($table, $data, $cond)) {
                $this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar os dados');
            }
        } else {
            return false;
        }
    }


    public function delete($table = null, $cond = null)
    {
        $this->db->db_debug = false;
        if ($table && is_array($cond)) {
            $status = $this->db->delete($table, $cond);
            $error = $this->db->error();
            if (!$status) {
                foreach ($error as $code) {
                    if ($code == 1451) {
                        $this->session->set_flashdata('error', 'Esse registro não poderá ser excluído, pois está sendo utilizado em outra tabela!');
                    };
                }
            } else {
                $this->session->set_flashdata('sucesso', 'Registro excluído com sucesso!');
            }
            $this->db->db_debug = true;
        }
        return false;
    }

    public function generate_unique_code($size_of_code, $field_search, $table = NULL, $type_of_code = NULL)
    {
        do {
            $code = random_string($type_of_code, $size_of_code);
            $this->db->where($field_search, $code);
            $this->db->from($table);
        } while ($this->db->count_all_results() >= 1);
        return $code;
    }

    public function auto_complete_produtos($busca = NULL)
    {
        if ($busca) {
            $this->db->like('produto_descricao', $busca, 'both');
            $this->db->where('produto_ativo', 1);
            $this->db->where('produto_qtde_estoque >', 0);
            return $this->db->get('produtos')->result();
        } else {
            return FALSE;
        }
    }

    public function auto_complete_servicos($busca = NULL)
    {

        if ($busca) {
            $this->db->like('servico_descricao', $busca, 'both');
            $this->db->where('servico_ativo', 1);
            return $this->db->get('servicos')->result();
        } else {
            return FALSE;
        }
    }
}
