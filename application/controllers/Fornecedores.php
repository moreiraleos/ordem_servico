<?php
defined('BASEPATH') or exit('Ação não permitida');

class Fornecedores extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou!');
            redirect('login');
        }
    }

    public function index()
    {
        $data = [
            'titulo' => 'Fornecedores cadastrados',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'fornecedores' => $this->CoreModel->getAll('fornecedores')
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('fornecedores/index');
        $this->load->view('layout/footer');
    }

    public function edit($fornecedor_id = null)
    {


        if (!$fornecedor_id || !$this->CoreModel->getById('fornecedores', ['fornecedor_id' => $fornecedor_id])) {
            $this->session->set_flashdata('error', 'Fornecedor não encontrado');
            redirect('fornecedores');
        } else {

            $this->form_validation->set_rules('fornecedor_razao', '', 'trim|required|min_length[4]|max_length[200]|callback_check_razao_social');
            $this->form_validation->set_rules('fornecedor_nome_fantasia', '', 'trim|required|min_length[4]|max_length[145]|callback_check_nome_fantasia');


            $this->form_validation->set_rules('fornecedor_cnpj', '', 'trim|required|exact_length[18]|callback_valida_cnpj');

            $this->form_validation->set_rules('fornecedor_ie', '', 'trim|required|max_length[20]|callback_check_fornecedor_ie');

            $this->form_validation->set_rules('fornecedor_email', '', 'trim|required|valid_email|max_length[50]|callback_check_email');

            $this->form_validation->set_rules('fornecedor_telefone', '', 'trim|required|max_length[14]|callback_check_fornecedor_telefone');
            $this->form_validation->set_rules('fornecedor_celular', '', 'trim|required|max_length[15]|callback_check_fornecedor_celular');
            $this->form_validation->set_rules('fornecedor_cep', '', 'trim|required|exact_length[9]');
            $this->form_validation->set_rules('fornecedor_endereco', '', 'trim|required|max_length[155]');
            $this->form_validation->set_rules('fornecedor_numero_endereco', '', 'trim|max_length[20]');
            $this->form_validation->set_rules('fornecedor_bairro', '', 'trim|required|max_length[45]');
            $this->form_validation->set_rules('fornecedor_complemento', '', 'trim|max_length[145]');
            $this->form_validation->set_rules('fornecedor_cidade', '', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('fornecedor_estado', '', 'trim|required|exact_length[2]');
            $this->form_validation->set_rules('fornecedor_obs', '', 'max_length[500]');


            if ($this->form_validation->run()) {

                $fornecedor_ativo = $this->input->post('fornecedor_ativo');

                if ($this->db->table_exists('fornecedores')) {
                    if ($fornecedor_ativo == 0 && $this->CoreModel->getById('produtos', ['produto_fornecedor_id' => $fornecedor_id, 'produto_ativo' => 1])) {
                        $this->session->set_flashdata('info', 'Este fornecedor não pode ser desativado, pois está sendo usado em produtos');
                        redirect('fornecedores');
                    }
                }

                $data = elements([
                    'fornecedor_razao',
                    'fornecedor_nome_fantasia',
                    'fornecedor_cnpj',
                    'fornecedor_ie',
                    'fornecedor_telefone',
                    'fornecedor_celular',
                    'fornecedor_email',
                    'fornecedor_contato',
                    'fornecedor_cep',
                    'fornecedor_endereco',
                    'fornecedor_numero_endereco',
                    'fornecedor_bairro',
                    'fornecedor_complemento',
                    'fornecedor_cidade',
                    'fornecedor_estado',
                    'fornecedor_ativo',
                    'fornecedor_obs'
                ], $this->input->post());

                $data = html_escape($data);

                $data['fornecedor_estado'] = strtoupper($this->input->post('fornecedor_estado'));

                $this->CoreModel->update('fornecedores', $data, ['fornecedor_id' => $fornecedor_id]);
                redirect('fornecedores');
            } else {
                // Erro de validação
                $data = [
                    'titulo' => 'Atualizar fornecedor',
                    'scripts' => [
                        'vendor/mask/jquery.mask.min.js',
                        'vendor/mask/app.js'
                    ],
                    'fornecedor' => $this->CoreModel->getById('fornecedores', ['fornecedor_id' => $fornecedor_id])
                ];

                $this->load->view('layout/header', $data);
                $this->load->view('fornecedores/edit');
                $this->load->view('layout/footer');
            }

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            // [fornecedor_id] => 1
            // [fornecedor_data_cadastro] => 2023-09-30 09:02:44
            // [fornecedor_razao] => Empresa componentes LTDA
            // [fornecedor_nome_fantasia] => Empresa inc.
            // [fornecedor_cnpj] => 51.182.043/0001-76
            // [fornecedor_ie] => 
            // [fornecedor_telefone] => 
            // [fornecedor_celular] => 
            // [fornecedor_email] => empresa@email.com.br
            // [fornecedor_contato] => Fulano de tal
            // [fornecedor_cep] => 
            // [fornecedor_endereco] => 
            // [fornecedor_numero_endereco] => 
            // [fornecedor_bairro] => 
            // [fornecedor_complemento] => 
            // [fornecedor_cidade] => 
            // [fornecedor_estado] => 
            // [fornecedor_ativo] => 1
            // [fornecedor_obs] => 
            // [fornecedor_data_alteracao] => 2023-09-30 09:02:44

        }
    }

    public function add()
    {

        $this->form_validation->set_rules('fornecedor_razao', '', 'trim|required|min_length[4]|max_length[200]|is_unique[fornecedores.fornecedor_razao]');
        $this->form_validation->set_rules('fornecedor_nome_fantasia', '', 'trim|required|min_length[4]|max_length[145]|is_unique[fornecedores.fornecedor_nome_fantasia]');


        $this->form_validation->set_rules('fornecedor_cnpj', '', 'trim|required|exact_length[18]|is_unique[fornecedores.fornecedor_cnpj]|callback_valida_cnpj');

        $this->form_validation->set_rules('fornecedor_ie', '', 'trim|required|max_length[20]|is_unique[fornecedores.fornecedor_ie]');

        $this->form_validation->set_rules('fornecedor_email', '', 'trim|required|valid_email|max_length[50]|is_unique[fornecedores.fornecedor_email]');

        $this->form_validation->set_rules('fornecedor_telefone', '', 'trim|required|max_length[14]|is_unique[fornecedores.fornecedor_telefone]');
        $this->form_validation->set_rules('fornecedor_celular', '', 'trim|required|max_length[15]|is_unique[fornecedores.fornecedor_celular]');
        $this->form_validation->set_rules('fornecedor_cep', '', 'trim|required|exact_length[9]');
        $this->form_validation->set_rules('fornecedor_endereco', '', 'trim|required|max_length[155]');
        $this->form_validation->set_rules('fornecedor_numero_endereco', '', 'trim|max_length[20]');
        $this->form_validation->set_rules('fornecedor_bairro', '', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('fornecedor_complemento', '', 'trim|max_length[145]');
        $this->form_validation->set_rules('fornecedor_cidade', '', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('fornecedor_estado', '', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('fornecedor_obs', '', 'max_length[500]');


        if ($this->form_validation->run()) {
            $data = elements([
                'fornecedor_razao',
                'fornecedor_nome_fantasia',
                'fornecedor_cnpj',
                'fornecedor_ie',
                'fornecedor_telefone',
                'fornecedor_celular',
                'fornecedor_email',
                'fornecedor_contato',
                'fornecedor_cep',
                'fornecedor_endereco',
                'fornecedor_numero_endereco',
                'fornecedor_bairro',
                'fornecedor_complemento',
                'fornecedor_cidade',
                'fornecedor_estado',
                'fornecedor_ativo',
                'fornecedor_obs'
            ], $this->input->post());

            $data = html_escape($data);

            $data['fornecedor_estado'] = strtoupper($this->input->post('fornecedor_estado'));

            $this->CoreModel->insert('fornecedores', $data);
            redirect('fornecedores');
        } else {
            // Erro de validação
            $data = [
                'titulo' => 'Cadastrar fornecedor',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ]
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('fornecedores/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($fornecedor_id)
    {
        if (!$fornecedor_id || !$this->CoreModel->getById('fornecedores', ['fornecedor_id' => $fornecedor_id])) {
            $this->form_validation->set_flashdata('error', 'Fornecedor não encontrado');
            redirect($this->router->fetch_class());
        }

        $this->CoreModel->delete('fornecedores', ['fornecedor_id' => $fornecedor_id]);
        redirect($this->router->fetch_class());
    }
    public function check_fornecedor_ie($fornecedor_ie)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', [
            "fornecedor_ie" => $fornecedor_ie,
            "fornecedor_id !=" => $fornecedor_id
        ])) {
            $this->form_validation->set_message('check_fornecedor_ie', 'Esse documento já existe');
            return false;
        }
        return true;
    }

    public function valida_cnpj($cnpj)
    {
        // Verifica se um número foi informado
        if (empty($cnpj)) {
            $this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
            return false;
        }

        if ($this->input->post('fornecedor_id')) {
            $fornecedor_id = $this->input->post('fornecedor_id');

            if ($this->CoreModel->getById('fornecedores', [
                'fornecedor_id !=' => $fornecedor_id,
                'fornecedor_cnpj' => $cnpj
            ])) {
                $this->form_validation->set_message('valida_cnpj', 'Esse CNPJ já existe');
                return false;
            }
        }

        // Elimina possivel mascara
        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
        $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cnpj) != 14) {
            $this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
            return false;
        }

        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if (
            $cnpj == '00000000000000' ||
            $cnpj == '11111111111111' ||
            $cnpj == '22222222222222' ||
            $cnpj == '33333333333333' ||
            $cnpj == '44444444444444' ||
            $cnpj == '55555555555555' ||
            $cnpj == '66666666666666' ||
            $cnpj == '77777777777777' ||
            $cnpj == '88888888888888' ||
            $cnpj == '99999999999999'
        ) {
            $this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
            return false;

            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {
            $j = 5;
            $k = 6;
            $soma1 = "";
            $soma2 = "";

            for ($i = 0; $i < 13; $i++) {

                $j = $j == 1 ? 9 : $j;
                $k = $k == 1 ? 9 : $k;

                //$soma2 += ($cnpj{$i} * $k);

                //$soma2 = intval($soma2) + ($cnpj{$i} * $k); //Para PHP com versão < 7.4
                $soma2 = intval($soma2) + ($cnpj[$i] * $k); //Para PHP com versão > 7.4

                if ($i < 12) {
                    //$soma1 = intval($soma1) + ($cnpj{$i} * $j); //Para PHP com versão < 7.4
                    $soma1 = intval($soma1) + ($cnpj[$i] * $j); //Para PHP com versão > 7.4
                }

                $k--;
                $j--;
            }

            $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
            $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

            if (!(str_pad($cnpj, 12, '0', STR_PAD_LEFT) == $digito1) and (str_pad($cnpj, 13, '0', STR_PAD_LEFT) == $digito2)) {
                $this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
                return false;
            } else {
                return true;
            }
        }
    }

    public function check_email($fornecedor_email)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', [
            "fornecedor_email" => $fornecedor_email,
            'fornecedor_id !=' => $fornecedor_id
        ])) {
            $this->form_validation->set_message('check_email', 'Esse e-mail já existe');
            return false;
        }

        return true;
    }

    public function check_fornecedor_telefone($fornecedor_telefone)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', [
            "fornecedor_telefone" => $fornecedor_telefone,
            'fornecedor_id != ' => $fornecedor_id
        ])) {
            $this->form_validation->set_message('check_fornecedor_telefone', 'Esse telefone já existe');
            return false;
        }
        return true;
    }

    public function check_fornecedor_celular($fornecedor_celular)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', [
            'fornecedor_celular' => $fornecedor_celular,
            'fornecedor_id !=' => $fornecedor_id
        ])) {
            $this->form_validation->set_message('check_fornecedor_celular', 'Celular já existe');
            return false;
        }
        return true;
    }

    public function check_razao_social($fornecedor_razao)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', ['fornecedor_razao ' => $fornecedor_razao, 'fornecedor_id !=' => $fornecedor_id])) {
            $this->form_validation->set_message('check_razao_social', 'Esta razão social já existe');
            return FALSE;
        }

        return TRUE;
    }

    public function check_nome_fantasia($fornecedor_nome_fantasia)
    {
        $fornecedor_id = $this->input->post('fornecedor_id');

        if ($this->CoreModel->getById('fornecedores', ['fornecedor_nome_fantasia' => $fornecedor_nome_fantasia, 'fornecedor_id !=' => $fornecedor_id])) {
            $this->form_validation->set_message('check_nome_fantasia', 'Este nome fantasia já existe');
            return FALSE;
        }
        return TRUE;
    }
}
