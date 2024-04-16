<?php

defined('BASEPATH') or exit('Ação não permitida');

class Marcas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
    }

    public function index()
    {
        $data = [
            'titulo' => 'Marcas cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'marcas' => $this->CoreModel->getAll('marcas')
        ];


        $this->load->view('layout/header', $data);
        $this->load->view('marcas/index');
        $this->load->view('layout/footer');
    }

    public function edit($marca_id)
    {
        if (!$marca_id || !$this->CoreModel->getById('marcas', ['marca_id' => $marca_id])) {
            $this->session->set_flashdata('error', 'Marca não encontrada');
            redirect('marcas');
        }


        $this->form_validation->set_rules('marca_nome', '', 'trim|required|min_length[2]|max_length[45]|callback_check_marca_nome');

        if ($this->form_validation->run()) {
            $marca_ativa = $this->input->post('marca_ativa');

            if ($this->db->table_exists('marcas')) {
                if ($marca_ativa == 0 && $this->CoreModel->getById('produtos', ['produto_marca_id' => $marca_id, 'produto_ativo' => 1])) {
                    $this->session->set_flashdata('info', 'Essa marca não pode ser desativada, pois está sendo usada em produtos.');
                    redirect('marcas');
                }
            }

            $data = elements(
                [
                    'marca_nome',
                    'marca_ativa'
                ],
                $this->input->post()
            );

            $data = html_escape($data);
            $this->CoreModel->update('marcas', $data, ['marca_id' => $marca_id]);
            redirect('marcas');
        } else {
            $data = [
                'titulo' => 'Atualizar marca',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'marca' => $this->CoreModel->getById('marcas', ["marca_id" => $marca_id])
            ];

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            $this->load->view('layout/header', $data);
            $this->load->view('marcas/edit');
            $this->load->view('layout/footer');
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('marca_nome', '', 'trim|required|min_length[2]|max_length[45]|is_unique[marcas.marca_nome]');

        if ($this->form_validation->run()) {
            $data = elements(
                [
                    'marca_nome',
                    'marca_ativa'
                ],
                $this->input->post()
            );
            $data = html_escape($data);
            $this->CoreModel->insert('marcas', $data);

            redirect('marcas');
        } else {

            $data = [
                'titulo' => 'Cadastrar marca',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ]
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('marcas/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($marca_id = NULL)
    {
        if (!$marca_id || !$this->CoreModel->getById('marcas', ['marca_id' => $marca_id])) {
            $this->session->set_flashdata('error', 'Marca não encontrada');
            redirect('marcas');
        } else {
            $this->CoreModel->delete('marcas', ['marca_id' => $marca_id]);

            redirect('marcas');
        }
    }

    public function check_marca_nome($marca_nome)
    {
        $marca_id = $this->input->post('marca_id');
        if (!$marca_nome || $this->CoreModel->getById('marcas', ['marca_id !=' => $marca_id, 'marca_nome ' => $marca_nome])) {
            $this->form_validation->set_message('check_marca_nome', 'Esta marca já existe');
            return FALSE;
        }
        return TRUE;
    }
}
