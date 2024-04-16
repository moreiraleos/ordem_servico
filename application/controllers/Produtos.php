<?php

defined('BASEPATH') or exit('Ação não permitida');

class Produtos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('info', 'Sua sessão expirou');
            redirect('login');
        }
        $this->load->model('ProdutosModel');
    }

    public function index()
    {

        $data = [
            'titulo' => 'Produtos cadastrados',
            'styles' => array(
                'vendor/datatables/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/datatables/dataTables.bootstrap4.min.js',

                'vendor/datatables/export/dataTables.buttons.min.js',
                'vendor/datatables/export/pdfmake.min.js',
                'vendor/datatables/export/vfs_fonts.js',
                'vendor/datatables/export/buttons.html5.min.js',

                'vendor/datatables/app.js'
            ),
            'produtos' => $this->ProdutosModel->getAll()
        ];

        // echo "<pre>";
        // print_r($data['produtos']);
        // exit;
        // echo "</pre>";


        $this->load->view('layout/header', $data);
        $this->load->view('produtos/index');
        $this->load->view('layout/footer');
    }

    public function edit($produto_id = NULL)
    {
        if (!$produto_id or !$this->CoreModel->getById('produtos', ['produto_id' => $produto_id])) {
            $this->session->set_flashdata('error', 'Produto não encontrado');
            redirect('produtos');
        } else {


            $this->form_validation->set_rules('produto_categoria_id', '', 'trim|required');
            $this->form_validation->set_rules('produto_marca_id', '', 'trim|required');
            $this->form_validation->set_rules('produto_fornecedor_id', '', 'trim|required');
            $this->form_validation->set_rules('produto_descricao', 'Descrição', 'trim|required|min_length[5]|max_length[145]|callback_check_produto_descricao');
            $this->form_validation->set_rules('produto_unidade', 'Unidade', 'trim|required|min_length[2]|max_length[5]');
            $this->form_validation->set_rules('produto_preco_custo', 'Preço de custo', 'trim|required|min_length[1]|max_length[45]');
            $this->form_validation->set_rules('produto_preco_venda', 'Preço de venda', 'trim|required|min_length[1]|max_length[45]|callback_check_produto_preco_venda');
            $this->form_validation->set_rules('produto_estoque_minimo', 'Estoque mínimo', 'trim|required|greater_than_equal_to[0]|min_length[1]|max_length[45]');
            $this->form_validation->set_rules('produto_qtde_estoque', 'Quantidade em estoque', 'trim|required');
            $this->form_validation->set_rules('produto_obs', 'Observação', 'trim|max_length[200]');


            if ($this->form_validation->run()) {
                $data = elements(
                    [
                        'produto_codigo',
                        'produto_categoria_id',
                        'produto_marca_id',
                        'produto_fornecedor_id',
                        'produto_descricao',
                        'produto_unidade',
                        'produto_preco_custo',
                        'produto_preco_venda',
                        'produto_estoque_minimo',
                        'produto_qtde_estoque',
                        'produto_ativo',
                        'produto_obs'
                    ],
                    $this->input->post()
                );

                $data = html_escape($data);

                $this->CoreModel->update('produtos', $data, ['produto_id' => $produto_id]);
                redirect('produtos');
            } else {
                $data = [
                    'titulo' => 'Atualizar produtos',
                    'scripts' => [
                        'vendor/mask/jquery.mask.min.js',
                        'vendor/mask/app.js'
                    ],
                    'produto' => $this->CoreModel->getById('produtos', ["produto_id" => $produto_id]),
                    'marcas' => $this->CoreModel->getAll('marcas'),
                    'categorias' => $this->CoreModel->getAll('categorias'),
                    'fornecedores' => $this->CoreModel->getAll('fornecedores'),
                ];

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";
                // exit;

                // [produto_id] => 1
                // [produto_codigo] => 72495380
                // [produto_data_cadastro] => 
                // [produto_categoria_id] => 1
                // [produto_marca_id] => 1
                // [produto_fornecedor_id] => 1
                // [produto_descricao] => Notebook gamer
                // [produto_unidade] => UN
                // [produto_preco_custo] => 1.800,00
                // [produto_preco_venda] => 15.031,00
                // [produto_estoque_minimo] => 2
                // [produto_qtde_estoque] => 3
                // [produto_ativo] => 1
                // [produto_obs] => 


                $this->load->view('layout/header', $data);
                $this->load->view('produtos/edit');
                $this->load->view('layout/footer');
            }
        }
    }

    public function add()
    {

        $this->form_validation->set_rules('produto_categoria_id', '', 'trim|required');
        $this->form_validation->set_rules('produto_marca_id', '', 'trim|required');
        $this->form_validation->set_rules('produto_fornecedor_id', '', 'trim|required');
        $this->form_validation->set_rules('produto_descricao', 'Descrição', 'trim|required|min_length[5]|max_length[145]|is_unique[produtos.produto_descricao]');
        $this->form_validation->set_rules('produto_unidade', 'Unidade', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('produto_preco_custo', 'Preço de custo', 'trim|required|min_length[1]|max_length[45]');
        $this->form_validation->set_rules('produto_preco_venda', 'Preço de venda', 'trim|required|min_length[1]|max_length[45]|callback_check_produto_preco_venda');
        $this->form_validation->set_rules('produto_estoque_minimo', 'Estoque mínimo', 'trim|required|greater_than_equal_to[0]|min_length[1]|max_length[45]');
        $this->form_validation->set_rules('produto_qtde_estoque', 'Quantidade em estoque', 'trim|required');
        $this->form_validation->set_rules('produto_obs', 'Observação', 'trim|max_length[200]');




        if ($this->form_validation->run()) {
            $data = elements(
                [
                    'produto_codigo',
                    'produto_categoria_id',
                    'produto_marca_id',
                    'produto_fornecedor_id',
                    'produto_descricao',
                    'produto_unidade',
                    'produto_preco_custo',
                    'produto_preco_venda',
                    'produto_estoque_minimo',
                    'produto_qtde_estoque',
                    'produto_ativo',
                    'produto_obs'
                ],
                $this->input->post()
            );

            $data = html_escape($data);

            $this->CoreModel->insert('produtos', $data);
            redirect('produtos');
        } else {
            $data = [
                'titulo' => 'Cadastrar produto',
                'scripts' => [
                    'vendor/mask/jquery.mask.min.js',
                    'vendor/mask/app.js'
                ],
                'produto_codigo' => $this->CoreModel->generate_unique_code(8, 'produto_codigo', 'produtos', 'numeric',),
                'marcas' => $this->CoreModel->getAll('marcas', ['marca_ativa' => 1]),
                'categorias' => $this->CoreModel->getAll('categorias', ['categoria_ativa' => 1]),
                'fornecedores' => $this->CoreModel->getAll('fornecedores', ['fornecedor_ativo' => 1]),
            ];



            $this->load->view('layout/header', $data);
            $this->load->view('produtos/add');
            $this->load->view('layout/footer');
        }
    }

    public function check_produto_descricao($produto_descricao)
    {
        $produto_id = $this->input->post('produto_id');
        if ($this->CoreModel->getById('produtos', ['produto_descricao' => $produto_descricao, 'produto_id !=' => $produto_id])) {
            $this->form_validation->set_message('check_produto_descricao', 'Este produto já existe');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function del($produto_id = NULL)
    {
        if (!$produto_id or !$this->CoreModel->getById('produtos', ['produto_id' => $produto_id])) {
            $this->session->set_flashdata('error', 'Produto não encontrado');
            redirect('produtos');
        } else {

            $this->CoreModel->delete('produtos', ['produto_id' => $produto_id]);
            redirect('produtos');
        }
    }

    public function check_produto_preco_venda($produto_preco_venda)
    {
        $produto_preco_custo = $this->input->post('produto_preco_custo');

        // $produto_preco_custo = str_replace('.','',$produto_preco_custo);
        // $produto_preco_venda = str_replace('.','',$produto_preco_venda);
        // $produto_preco_custo = str_replace(',','',$produto_preco_custo);
        // $produto_preco_venda = str_replace(',','',$produto_preco_venda);

        if ($produto_preco_custo > $produto_preco_venda) {
            $this->form_validation->set_message('check_produto_preco_venda', 'Preço de venda deve ser igual ou maior que o preço de custo');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
