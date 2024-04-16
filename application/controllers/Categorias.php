<?php

defined('BASEPATH') or exit('Ação não permitida');

class Categorias extends CI_Controller
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
            'titulo' => 'Categorias cadastradas',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'categorias' => $this->CoreModel->getAll('categorias')
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('categorias/index');
        $this->load->view('layout/footer');
    }

    public function edit($categoria_id)
    {
        if (!$categoria_id || !$this->CoreModel->getById('categorias', ['categoria_id' => $categoria_id])) {
            $this->session->set_flashdata('error', 'categoria não encontrada');
            redirect('categorias');
        }


        $this->form_validation->set_rules('categoria_nome', '', 'trim|required|min_length[2]|max_length[45]|callback_check_categoria_nome');

        if ($this->form_validation->run()) {

            $categoria_ativa = $this->input->post('categoria_ativa');
            if ($this->db->table_exists('produtos')) {
                if ($categoria_ativa == 0 && $this->CoreModel->getById('produtos', ['produto_categoria_id' => $categoria_id, 'produto_ativo' => 1])) {
                    $this->session->set_flashdata('info', 'Essa categoria não pode ser desativada, pois está sendo utilizada em produtos!');
                    redirect('categorias');
                }
            }

            $data = elements(
                [
                    'categoria_nome',
                    'categoria_ativa'
                ],
                $this->input->post()
            );

            $data = html_escape($data);

            $this->CoreModel->update('categorias', $data, ['categoria_id' => $categoria_id]);

            redirect('categorias');
        } else {

            $data = [
                'titulo' => 'Atualizar categoria',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'categoria' => $this->CoreModel->getById('categorias', ["categoria_id" => $categoria_id])
            ];

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            $this->load->view('layout/header', $data);
            $this->load->view('categorias/edit');
            $this->load->view('layout/footer');
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('categoria_nome', '', 'trim|required|min_length[2]|max_length[45]|is_unique[categorias.categoria_nome]');

        if ($this->form_validation->run()) {

            $data = elements(
                [
                    'categoria_nome',
                    'categoria_ativa'
                ],
                $this->input->post()
            );

            $data = html_escape($data);

            $this->CoreModel->insert('categorias', $data);

            redirect('categorias');
        } else {

            $data = [
                'titulo' => 'Cadastrar categoria',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ]
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('categorias/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($categoria_id = NULL)
    {
        if (!$categoria_id || !$this->CoreModel->getById('categorias', ['categoria_id' => $categoria_id])) {
            $this->session->set_flashdata('error', 'categoria não encontrada');
            redirect('categorias');
        } else {
            $this->CoreModel->delete('categorias', ['categoria_id' => $categoria_id]);

            redirect('categorias');
        }
    }

    public function check_categoria_nome($categoria_nome)
    {
        $categoria_id = $this->input->post('categoria_id');
        if (!$categoria_nome || $this->CoreModel->getById('categorias', ['categoria_id !=' => $categoria_id, 'categoria_nome ' => $categoria_nome])) {
            $this->form_validation->set_message('check_categoria_nome', 'Esta categoria já existe');
            return FALSE;
        }
        return TRUE;
    }
}
