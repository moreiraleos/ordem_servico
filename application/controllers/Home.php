<?php


defined('BASEPATH') or exit('Ação não permitida.');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
        $this->load->model('HomeModel');
    }
    public function index()
    {

        $data = [
            'titulo' => 'Home',
            'soma_vendas' => $this->HomeModel->get_sum_vendas(),
            'soma_ordem_servicos' => $this->HomeModel->get_sum_ordem_servicos(),
            'total_pagar' => $this->HomeModel->get_sum_pagar(),
            'total_receber' => $this->HomeModel->get_sum_receber(),
            'produtos_mais_vendidos' => $this->HomeModel->get_produtos_mais_vendidos(),
            'servicos_mais_vendidos' => $this->HomeModel->get_servicos_mais_vendidos(),

            // CENTRAL DE NOTIFICAÇÕES
            // 'contas_receber_vencidas' => $this->HomeModel->get_contas_receber_vencidas()
        ];
        // CENTRAL DE NOTIFICAÇÕES
        $contador_notificacoes = 0;

        if ($this->HomeModel->get_contas_receber_vencidas()) {
            $data['contas_receber_vencidas'] = TRUE;
            $contador_notificacoes++;
        }


        if ($this->HomeModel->get_contas_pagar_vencidas()) {
            $data['contas_pagar_vencidas'] = TRUE;
            $contador_notificacoes++;
        }

        if ($this->HomeModel->get_contas_pagar_vencem_hoje()) {
            $data['contas_pagar_vencem_hoje'] = TRUE;
            $contador_notificacoes++;
        }
        if ($this->HomeModel->get_contas_receber_vencem_hoje()) {
            $data['contas_receber_vencem_hoje'] = TRUE;
            $contador_notificacoes++;
        }
        if ($this->HomeModel->get_usuarios_desativados()) {
            $data['usuarios_desativados'] = TRUE;
            $contador_notificacoes++;
        }

        $data['contador_notificacoes'] = $contador_notificacoes;

        // echo "<pre>";
        // print_r($data['usuarios_desativados']);
        // echo "</pre>";
        // exit;
        $this->load->view('layout/header', $data);
        $this->load->view('home/index');
        $this->load->view('layout/footer');
    }
}
