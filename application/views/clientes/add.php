<?php
$this->load->view('layout/sidebar');
?>

<!-- Main Content -->
<div id="content">

    <?php $this->load->view('layout/navbar'); ?>


    <!-- Begin Page Content -->
    <div class="container-fluid">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('clientes'); ?>">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_add">
                    <div class="custom-control custom-radio custom-control-inline mt-2">
                        <input type="radio" name="cliente_tipo" id="pessoa_fisica" value="1" class="custom-control-input" <?= set_checkbox('cliente_tipo', '1') ?> checked="">
                        <label for="pessoa_fisica" class="custom-control-label pt-1">Pessoa física</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="cliente_tipo" id="pessoa_juridica" class="custom-control-input" value="2" <?= set_checkbox('cliente_tipo', '2') ?>>
                        <label for="pessoa_juridica" class="custom-control-label pt-1">Pessoa jurídica</label>
                    </div>


                    <fieldset class="mt-5 border p-2">
                        <legend class="font-small"> <i class="fas fa-user-tie"></i>&nbsp;Dados pessoais</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-3 ">
                                <label for="first_name">Nome</label>
                                <input type="text" class="form-control form-control-user" name="cliente_nome" id="cliente_nome" placeholder="Nome do cliente" value="<?= set_value('cliente_nome') ?>">
                                <?= form_error('cliente_nome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_sobrenome">Sobrenome</label>
                                <input type="text" class="form-control form-control-user" name="cliente_sobrenome" id="cliente_sobrenome" placeholder="Sobrenome do cliente" value="<?= set_value('cliente_sobrenome') ?>">
                                <?= form_error('cliente_sobrenome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>

                            <div class="col-md-3">
                                <label for="cliente_data_nascimento">Data nascimento</label>
                                <input type="date" class="form-control form-control-user-date" name="cliente_data_nascimento" id="cliente_data_nascimento" value="<?= set_value('cliente_data_nascimento') ?>">
                                <?= form_error('cliente_data_nascimento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>


                        </div>
                        <div class="form-group row mb-3">



                            <div class="col-md-3">

                                <div class="pessoa_fisica">
                                    <label for="cliente_cpf_cnpj">CPF</label>
                                    <input type="text" class="form-control form-control-user cpf" name="cliente_cpf" id="cliente_cpf_cnpj" placeholder="CPF do cliente" value="<?= set_value('cliente_cpf') ?>">
                                    <?= form_error('cliente_cpf', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>

                                <div class="pessoa_juridica">
                                    <label for="cliente_cpf_cnpj">CNPJ</label>
                                    <input type="text" class="form-control form-control-user cnpj" name="cliente_cnpj" id="cliente_cpf_cnpj" placeholder="CNPJ do cliente" value="<?= set_value('cliente_cnpj') ?>">
                                    <?= form_error('cliente_cnpj', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <label for="cliente_rg_ie" class="pessoa_fisica">RG</label>

                                <label for="cliente_rg_ie" class="pessoa_juridica">Inscrição estadual</label>
                                <input type="text" class="form-control form-control-user" name="cliente_rg_ie" id="cliente_rg_ie">
                                <?= form_error('cliente_rg_ie', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6 ">
                                <label for="cliente_email">Email</label>
                                <input type="email" class="form-control form-control-user" name="cliente_email" id="cliente_email" placeholder="Email do cliente" value="<?= set_value('cliente_email') ?>">
                                <?= form_error('cliente_email', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="cliente_telefone">Telefone fixo</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="cliente_telefone" id="cliente_telefone" placeholder="Telefone do cliente" value="<?= set_value('cliente_telefone') ?>">
                                <?= form_error('cliente_telefone', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_celular">Celular</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="cliente_celular" id="cliente_celular" placeholder="Celular do cliente" value="<?= set_value('cliente_celular') ?>">
                                <?= form_error('cliente_celular', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-map-marker-alt "></i> &nbsp; Dados de endereço </legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="cliente_endereco">Endereço</label>
                                <input type="text" class="form-control form-control-user" name="cliente_endereco" id="cliente_endereco" placeholder="Endereço do cliente" value="<?= set_value('cliente_endereco'); ?>">
                                <?= form_error('cliente_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="cliente_numero_endereco">Número</label>
                                <input type="text" class="form-control form-control-user" name="cliente_numero_endereco" id="cliente_numero_endereco" placeholder="Número endereço" value="<?= set_value('cliente_numero_endereco') ?>">
                                <?= form_error('cliente_numero_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="cliente_complemento">Complemento</label>
                                <input type="text" class="form-control form-control-user" name="cliente_complemento" id="cliente_complemento" placeholder="Complemento" value="<?= set_value('cliente_complemento') ?>">
                                <?= form_error('cliente_complemento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="cliente_bairro">Bairro</label>
                                <input type="text" class="form-control form-control-user" name="cliente_bairro" id="cliente_bairro" placeholder="Bairro do cliente" value="<?= set_value('cliente_bairro') ?>">
                                <?= form_error('cliente_bairro', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="cliente_cep">CEP</label>
                                <input type="text" class="form-control form-control-user cep" name="cliente_cep" id="cliente_cep" placeholder="CEP do cliente" value="<?= set_value('cliente_cep') ?>">
                                <?= form_error('cliente_cep', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-5">
                                <label for="cliente_cidade">Cidade</label>
                                <input type="text" class="form-control form-control-user" name="cliente_cidade" id="cliente_cidade" placeholder="Cidade do cliente" value="<?= set_value('cliente_cidade') ?>">
                                <?= form_error('cliente_cidade', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-1">
                                <label for="cliente_estado">UF</label>
                                <input type="text" class="form-control form-control-user uf" name="cliente_estado" id="cliente_estado" placeholder="UF" value="<?= set_value('cliente_estado') ?>">
                                <?= form_error('cliente_estado', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2 mb-3">
                        <legend class="font-small"><i class="fas fa-cogs"></i> &nbsp; Configurações</legend>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="cliente_ativo">Cliente ativo</label>
                                <select class="custom-select" name="cliente_ativo" id="cliente_ativo">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="cliente_obs">Observações</label>
                                <textarea type="text" class="form-control form-control-user" rows="4" name="cliente_obs" id="cliente_obs" placeholder="Observações"><?= set_value('cliente_obs') ?></textarea>
                                <?= form_error('cliente_obs', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url('clientes'); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->