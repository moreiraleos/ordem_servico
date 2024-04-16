<?php

defined('BASEPATH') or exit('Ação não permitida');

class Custom_error_404 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Página não encontrada'
        ];
        $this->load->view('custom_error_404',$data);
    }
}
