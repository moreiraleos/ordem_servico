<?php
defined('BASEPATH') or exit('Ação não permitida');

class Vendedores extends CI_Controller
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
            'titulo' => 'Vendedores cadastrados',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',
                'vendor/datatables/app.js'
            ),
            'vendedores' => $this->CoreModel->getAll('vendedores'),
        ];


        // [vendedor_id] => 1
        // [vendedor_codigo] => 09842571
        // [vendedor_data_cadastro] => 2020-01-27 22:24:17
        // [vendedor_nome_completo] => Lucio Antonio de Souza
        // [vendedor_cpf] => 946.873.070-00
        // [vendedor_rg] => 36.803.319-3
        // [vendedor_telefone] => 
        // [vendedor_celular] => (41) 99999-9999
        // [vendedor_email] => vendedor@gmail.com
        // [vendedor_cep] => 80530-000
        // [vendedor_endereco] => Rua das vendas
        // [vendedor_numero_endereco] => 45
        // [vendedor_complemento] => 
        // [vendedor_bairro] => Centro
        // [vendedor_cidade] => Curitiba
        // [vendedor_estado] => PR
        // [vendedor_ativo] => 1
        // [vendedor_obs] => 
        // [vendedor_data_alteracao] => 2020-01-27 22:24:17

        // echo "<pre>";
        // print_r($data);
        // exit;
        // echo "</pre>";


        $this->load->view('layout/header', $data);
        $this->load->view('vendedores/index');
        $this->load->view('layout/footer');
    }


    public function edit($vendedor_id = null)
    {
        if (!$vendedor_id ||  !$this->CoreModel->getById('vendedores', ['vendedor_id' => $vendedor_id])) {
            $this->session->set_flashdata('error', 'Vendedor não encontrado');
            redirect('vendedores');
        } else {

            // [vendedor_id] => 1
            // [vendedor_codigo] => 09842571
            // [vendedor_data_cadastro] => 2020-01-27 22:24:17
            // [vendedor_nome_completo] => Lucio Antonio de Souza
            // [vendedor_cpf] => 946.873.070-00
            // [vendedor_rg] => 36.803.319-3
            // [vendedor_telefone] => 
            // [vendedor_celular] => (41) 99999-9999
            // [vendedor_email] => vendedor@gmail.com
            // [vendedor_cep] => 80530-000
            // [vendedor_endereco] => Rua das vendas
            // [vendedor_numero_endereco] => 45
            // [vendedor_complemento] => 
            // [vendedor_bairro] => Centro
            // [vendedor_cidade] => Curitiba
            // [vendedor_estado] => PR
            // [vendedor_ativo] => 1
            // [vendedor_obs] => 
            // [vendedor_data_alteracao] => 2020-01-27 22:24:17

            $this->form_validation->set_rules('vendedor_cpf', '', 'trim|required|exact_length[14]|callback_valida_cpf');
            $this->form_validation->set_rules('vendedor_rg', '', 'trim|required|min_length[4]|max_length[200]|callback_check_rg');
            $this->form_validation->set_rules('vendedor_email', '', 'trim|required|valid_email|max_length[50]|callback_check_email');
            $this->form_validation->set_rules('vendedor_telefone', '', 'trim|required|max_length[14]|callback_check_telefone');
            $this->form_validation->set_rules('vendedor_celular', '', 'trim|required|max_length[15]|callback_check_celular');
            $this->form_validation->set_rules('vendedor_nome_completo', '', 'trim|required|min_length[4]|max_length[200]');
            $this->form_validation->set_rules('vendedor_cep', '', 'trim|required|exact_length[9]');
            $this->form_validation->set_rules('vendedor_endereco', '', 'trim|required|max_length[155]');
            $this->form_validation->set_rules('vendedor_numero_endereco', '', 'trim|max_length[20]');
            $this->form_validation->set_rules('vendedor_complemento', '', 'trim|max_length[145]');
            $this->form_validation->set_rules('vendedor_bairro', '', 'trim|required|max_length[45]');
            $this->form_validation->set_rules('vendedor_cidade', '', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('vendedor_estado', '', 'trim|required|exact_length[2]');
            $this->form_validation->set_rules('vendedor_obs', '', 'max_length[500]');



            if ($this->form_validation->run()) {
                $data = elements(
                    [
                        'vendedor_codigo',
                        'vendedor_nome_completo',
                        'vendedor_cpf',
                        'vendedor_rg',
                        'vendedor_email',
                        'vendedor_telefone',
                        'vendedor_celular',
                        'vendedor_endereco',
                        'vendedor_numero_endereco',
                        'vendedor_complemento',
                        'vendedor_bairro',
                        'vendedor_cep',
                        'vendedor_cidade',
                        'vendedor_estado',
                        'vendedor_ativo',
                        'vendedor_obs'
                    ],
                    $this->input->post()
                );

                $data['vendedor_estado'] = strtoupper($this->input->post('vendedor_estado'));

                $data = html_escape($data);
                $this->CoreModel->update('vendedores', $data, ['vendedor_id' => $vendedor_id]);
                redirect('vendedores');
            } else {

                $data = [
                    'titulo' => 'Atualizar vendedores',
                    'scripts' => [
                        'vendor/mask/jquery.mask.min.js',
                        'vendor/mask/app.js'
                    ],
                    'vendedor' => $this->CoreModel->getById('vendedores', ["vendedor_id" => $vendedor_id])
                ];

                $this->load->view('layout/header', $data);
                $this->load->view('vendedores/edit');
                $this->load->view('layout/footer');
            }
        }
    }

    public function add()
    {

        $this->form_validation->set_rules('vendedor_cpf', '', 'trim|required|exact_length[14]|is_unique[vendedores.vendedor_cpf]|callback_valida_cpf');
        $this->form_validation->set_rules('vendedor_rg', '', 'trim|required|min_length[4]|max_length[200]|is_unique[vendedores.vendedor_rg]');
        $this->form_validation->set_rules('vendedor_email', '', 'trim|required|valid_email|max_length[50]|is_unique[vendedores.vendedor_email]');
        $this->form_validation->set_rules('vendedor_telefone', '', 'trim|required|max_length[14]|is_unique[vendedores.vendedor_telefone]');
        $this->form_validation->set_rules('vendedor_celular', '', 'trim|required|max_length[15]|is_unique[vendedores.vendedor_celular]');
        $this->form_validation->set_rules('vendedor_nome_completo', '', 'trim|required|min_length[4]|max_length[200]');
        $this->form_validation->set_rules('vendedor_cep', '', 'trim|required|exact_length[9]');
        $this->form_validation->set_rules('vendedor_endereco', '', 'trim|required|max_length[155]');
        $this->form_validation->set_rules('vendedor_numero_endereco', '', 'trim|max_length[20]');
        $this->form_validation->set_rules('vendedor_complemento', '', 'trim|max_length[145]');
        $this->form_validation->set_rules('vendedor_bairro', '', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('vendedor_cidade', '', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('vendedor_estado', '', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('vendedor_obs', '', 'max_length[500]');



        if ($this->form_validation->run()) {
            $data = elements(
                [
                    'vendedor_codigo',
                    'vendedor_nome_completo',
                    'vendedor_cpf',
                    'vendedor_rg',
                    'vendedor_email',
                    'vendedor_telefone',
                    'vendedor_celular',
                    'vendedor_endereco',
                    'vendedor_numero_endereco',
                    'vendedor_complemento',
                    'vendedor_bairro',
                    'vendedor_cep',
                    'vendedor_cidade',
                    'vendedor_estado',
                    'vendedor_ativo',
                    'vendedor_obs'
                ],
                $this->input->post()
            );

            $data['vendedor_estado'] = strtoupper($this->input->post('vendedor_estado'));

            $data = html_escape($data);
            $this->CoreModel->insert('vendedores', $data);
            redirect('vendedores');
        } else {
            $data = [
                'titulo' => 'Cadastrar vendedores',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js',
                    'js/clientes.js'
                ],
                'vendedor_codigo' => $this->CoreModel->generate_unique_code(8, 'vendedor_codigo', 'vendedores', 'numeric')
            ];

            $this->load->view('layout/header', $data);
            $this->load->view('vendedores/add');
            $this->load->view('layout/footer');
        }
    }

    public function del($vendedor_id = null)
    {
        if (!$vendedor_id || !$this->CoreModel->getById('vendedores', ['vendedor_id' => $vendedor_id])) {
            $this->session->set_flashdata('error', 'Vendedor não encontrado');
            redirect('vendedores');
        }
        $this->CoreModel->delete('vendedores', ['vendedor_id' => $vendedor_id]);
        redirect('vendedores');
    }
    public function check_rg($vendedor_rg)
    {
        $vendedor_id = $this->input->post('vendedor_id');

        if ($this->CoreModel->getById('vendedores', [
            "vendedor_rg" => $vendedor_rg,
            "vendedor_id !=" => $vendedor_id
        ])) {
            $this->form_validation->set_message('check_rg', 'Esse documento já existe');
            return false;
        }
        return true;
    }

    public function check_email($vendedor_email)
    {
        $vendedor_id = $this->input->post('vendedor_id');

        if ($this->CoreModel->getById('vendedores', [
            "vendedor_email" => $vendedor_email,
            'vendedor_id !=' => $vendedor_id
        ])) {
            $this->form_validation->set_message('check_email', 'Esse e-mail já existe');
            return false;
        }

        return true;
    }

    public function check_telefone($vendedor_telefone)
    {
        $vendedor_id = $this->input->post('vendedor_id');

        if ($this->CoreModel->getById('vendedores', [
            "vendedor_telefone" => $vendedor_telefone,
            'vendedor_id != ' => $vendedor_id
        ])) {
            $this->form_validation->set_message('check_telefone', 'Esse telefone já existe');
            return false;
        }
        return true;
    }

    public function check_celular($vendedor_celular)
    {
        $vendedor_id = $this->input->post('vendedor_id');

        if ($this->CoreModel->getById('vendedores', [
            'vendedor_celular' => $vendedor_celular,
            'vendedor_id !=' => $vendedor_id
        ])) {
            $this->form_validation->set_message('check_celular', 'Celular já existe');
            return false;
        }
        return true;
    }


    public function valida_cpf($cpf)
    {

        if ($this->input->post('vendedor_id')) {
            $vendedor_id = $this->input->post('vendedor_id');

            if ($this->CoreModel->getById('vendedores', [
                'vendedor_id != ' => $vendedor_id,
                'vendedor_cpf' => $cpf
            ])) {
                $this->form_validation->set_message('valida_cpf', 'Este CPF já existe');
                return false;
            }
        }

        $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (
            strlen($cpf) != 11 ||
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {

            $this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
            return FALSE;
        } else {
            // Calcula os números para verificar se o CPF é verdadeiro

            // Extrai somente os números
            $cpf = preg_replace('/[^0-9]/is', '', $cpf);

            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    $this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
                    return false;
                }
            }
            return true;
        }
    }
}
