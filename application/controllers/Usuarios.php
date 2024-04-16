<?php

defined('BASEPATH') or exit('Ação não permitida');

class Usuarios extends CI_Controller
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
        // if (!$usuario_id || !$this->ion_auth->user($usuario_id)->row()) {
        //     $this->session->set_flashdata('error', 'Usuário não encontrado');
        //     redirect('home');
        // }
        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não é tem permissão para acessar o menu usuários');
            redirect('home');
        }
        $data = array(
            'titulo' => 'Usuários cadastrados',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'usuarios' => $this->ion_auth->users()->result(),
        );

        $this->load->view('layout/header', $data);
        $this->load->view('usuarios/index');
        $this->load->view('layout/footer');
    }

    public function edit($usuario_id = NULL)
    {

        // Se nãofor admin e tentar editar user diferente do seu
        if (!$this->ion_auth->is_admin()) {
            if ($this->session->userdata('user_id') != $usuario_id) {
                $this->session->set_flashdata('info', 'Você não pode editar um usuário diferente do seu');
                redirect('home');
            }
        }

        if (!$usuario_id || !$this->ion_auth->user($usuario_id)->row()) {
            $this->session->set_flashdata('error', 'Usuário não encontrado');
            redirect('usuarios');
        }


        $this->form_validation->set_rules('first_name', '', 'trim|required');
        $this->form_validation->set_rules('last_name', '', 'trim|required');
        $this->form_validation->set_rules('email', '', 'trim|required|valid_email|callback_email_check');
        $this->form_validation->set_rules('username', '', 'trim|required|callback_username_check');
        $this->form_validation->set_rules('password', 'Senha', 'min_length[5]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirme', 'matches[password]');

        if ($this->form_validation->run()) {

            // echo "<pre>";
            // print_r($this->input->post());
            // exit;
            // echo "</pre>";

            $data = elements(
                array(
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    'active',
                    'password'
                ),
                $this->input->post()
            );

            if (!$this->ion_auth->is_admin()) {
                unset($data['active']);
            }


            $data = $this->security->xss_clean($data);

            $password = $this->input->post('password');
            if (!$password) {
                unset($data['password']);
            }

            // $this->CoreModel->update('users', $data, array('id' => $usuario_id))
            if ($this->ion_auth->update($usuario_id, $data)) {
                $perfil_usuario_db = $this->ion_auth->get_users_groups($usuario_id)->row();
                $perfil_usuario_post = $this->input->post('perfil_usuario');
                // Se for diferente, atualiza o grupo
                if ($this->ion_auth->is_admin()) {
                    if ($perfil_usuario_post != $perfil_usuario_db->id) {
                        $this->ion_auth->remove_from_group($perfil_usuario_db->id, $usuario_id);
                        $this->ion_auth->add_to_group($perfil_usuario_post, $usuario_id);
                    }
                }
                $this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');
            } else {
                $this->session->set_flashdata('error', 'Erro ao salvar os dados');
            }

            if ($this->ion_auth->is_admin()) {
                redirect('usuarios');
            } else {
                redirect('home');
            }
        } else {
            $data = array(
                "titulo" => 'Editar usuário',
                "usuario" => $this->ion_auth->user($usuario_id)->row(),
                "perfil_usuario" => $this->ion_auth->get_users_groups($usuario_id)->row()
            );

            $this->load->view('layout/header', $data);
            $this->load->view('usuarios/edit');
            $this->load->view('layout/footer');
        }
    }

    public function email_check($email)
    {
        $usuario_id = $this->input->post('usuario_id');
        if ($this->CoreModel->getById('users', array('email' => $email, 'id !=' => $usuario_id))) {
            $this->form_validation->set_message('email_check', 'Esse e-mail já existe');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function username_check($username)
    {
        $usuario_id = $this->input->post('usuario_id');
        if ($this->CoreModel->getById('users', array('username' => $username, 'id !=' => $usuario_id))) {
            $this->form_validation->set_message('username_check', 'Esse usuário já existe');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function add()
    {

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não é tem permissão para acessar o menu usuários');
            redirect('home');
        }

        $this->form_validation->set_rules('first_name', '', 'trim|required');
        $this->form_validation->set_rules('last_name', '', 'trim|required');
        $this->form_validation->set_rules('email', '', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', '', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Senha', 'required|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirme', 'matches[password]');

        if ($this->form_validation->run()) {


            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $email = $this->security->xss_clean($this->input->post('email'));

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'username' => $this->security->xss_clean($this->input->post('username')),
                'active' => $this->input->post('active')
            );
            $group = array($this->input->post('perfil_usuario'));

            $additional_data = $this->security->xss_clean($additional_data);
            $group = $this->security->xss_clean($group);

            // echo "<pre>";
            // print_r($additional_data);
            // echo "</pre>";
            // exit;

            if ($this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
                $this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');
            } else {
                $this->session->set_flashdata('error', 'Erro ao salvar os dados');
            }

            redirect('usuarios');
        } else {
            // Erroo de validação
            $data = array(
                "titulo" => "Cadastrar usuário"
            );
            $this->load->view('layout/header', $data);
            $this->load->view('usuarios/add');
            $this->load->view('layout/footer');
        }
    }


    public function del($usuario_id = null)
    {

        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('info', 'Você não é tem permissão para acessar o menu usuários');
            redirect('home');
        }
        if (!$usuario_id || !$this->ion_auth->user($usuario_id)->row()) {
            $this->session->set_flashdata('error', 'Usuário não encontrado');
            redirect("usuarios");
        }

        if ($this->ion_auth->is_admin($usuario_id)) {
            $this->session->set_flashdata('error', 'O administrador não pode ser excluído');
            redirect("usuarios");
        }

        if ($this->ion_auth->delete_user($usuario_id)) {
            $this->session->set_flashdata('sucesso', 'Usuário excluído com sucesso');
            redirect('usuarios');
        } else {
            $this->session->set_flashdata('error', 'Erro ao exclui usuário');
            redirect('usuarios');
        }
    }
}
