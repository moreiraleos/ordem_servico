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
                <form class="user" method="post" name="form_edit">
                    <p><strong> <i class="fas fa-clock"></i>&nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($cliente->cliente_data_alteracao); ?></p>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-user-tie"></i>&nbsp;Dados pessoais</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-3 ">
                                <label for="first_name">Nome</label>
                                <input type="text" class="form-control form-control-user" name="cliente_nome" id="cliente_nome" placeholder="Nome do cliente" value="<?= $cliente->cliente_nome; ?>">
                                <?= form_error('cliente_nome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_sobrenome">Sobrenome</label>
                                <input type="text" class="form-control form-control-user" name="cliente_sobrenome" id="cliente_sobrenome" placeholder="Sobrenome do cliente" value="<?= $cliente->cliente_sobrenome; ?>">
                                <?= form_error('cliente_sobrenome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>

                            <div class="col-md-3">
                                <label for="cliente_data_nascimento">Data nascimento</label>
                                <input type="date" class="form-control form-control-user-date" name="cliente_data_nascimento" id="cliente_data_nascimento" value="<?= $cliente->cliente_data_nascimento; ?>">
                                <?= form_error('cliente_data_nascimento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>


                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-3">
                                <?php if ($cliente->cliente_tipo == 1) : ?>
                                    <label for="cliente_cpf_cnpj">CPF</label>
                                    <input type="text" class="form-control form-control-user cpf" name="cliente_cpf" id="cliente_cpf_cnpj" placeholder="<?= ($cliente->cliente_tipo == 1) ? "CPF do cliente" : "CNPJ do cliente" ?>" value="<?= $cliente->cliente_cpf_cnpj; ?>">
                                    <?= form_error('cliente_cpf', '<small class="form-text text-danger">', '</small>'); ?>
                                <?php else : ?>
                                    <label for="cliente_cpf_cnpj">CNPJ</label>
                                    <input type="text" class="form-control form-control-user cnpj" name="cliente_cnpj" id="cliente_cpf_cnpj" placeholder="<?= ($cliente->cliente_tipo == 1) ? "CPF do cliente" : "CNPJ do cliente" ?>" value="<?= $cliente->cliente_cpf_cnpj; ?>">
                                    <?= form_error('cliente_cnpj', '<small class="form-text text-danger">', '</small>'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <?php if ($cliente->cliente_tipo == 1) : ?>
                                    <label for="cliente_rg_ie">RG</label>
                                <?php else : ?>
                                    <label for="cliente_rg_ie">Inscrição estadual</label>
                                <?php endif; ?>
                                <input type="text" class="form-control form-control-user" name="cliente_rg_ie" id="cliente_rg_ie" placeholder="<?= ($cliente->cliente_tipo == 1) ? "RG" : "Inscrição estadual"; ?>" value="<?= $cliente->cliente_rg_ie; ?>">
                                <?= form_error('cliente_rg_ie', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6 ">
                                <label for="cliente_email">Email</label>
                                <input type="email" class="form-control form-control-user" name="cliente_email" id="cliente_email" placeholder="Email do cliente" value="<?= $cliente->cliente_email; ?>">
                                <?= form_error('cliente_email', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="cliente_telefone">Telefone fixo</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="cliente_telefone" id="cliente_telefone" placeholder="Telefone do cliente" value="<?= $cliente->cliente_telefone; ?>">
                                <?= form_error('cliente_telefone', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_celular">Celular</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="cliente_celular" id="cliente_celular" placeholder="Celular do cliente" value="<?= $cliente->cliente_celular; ?>">
                                <?= form_error('cliente_celular', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-map-marker-alt "></i> &nbsp; Dados de endereço </legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="cliente_endereco">Endereço</label>
                                <input type="text" class="form-control form-control-user" name="cliente_endereco" id="cliente_endereco" placeholder="Endereço do cliente" value="<?= $cliente->cliente_endereco; ?>">
                                <?= form_error('cliente_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="cliente_numero_endereco">Número</label>
                                <input type="text" class="form-control form-control-user" name="cliente_numero_endereco" id="cliente_numero_endereco" placeholder="Número endereço" value="<?= $cliente->cliente_numero_endereco; ?>">
                                <?= form_error('cliente_numero_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="cliente_complemento">Complemento</label>
                                <input type="text" class="form-control form-control-user" name="cliente_complemento" id="cliente_complemento" placeholder="Complemento" value="<?= $cliente->cliente_complemento; ?>">
                                <?= form_error('cliente_complemento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="cliente_bairro">Bairro</label>
                                <input type="text" class="form-control form-control-user" name="cliente_bairro" id="cliente_bairro" placeholder="Bairro do cliente" value="<?= $cliente->cliente_bairro; ?>">
                                <?= form_error('cliente_bairro', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="cliente_cep">CEP</label>
                                <input type="text" class="form-control form-control-user cep" name="cliente_cep" id="cliente_cep" placeholder="CEP do cliente" value="<?= $cliente->cliente_cep; ?>">
                                <?= form_error('cliente_cep', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-5">
                                <label for="cliente_cidade">Cidade</label>
                                <input type="text" class="form-control form-control-user" name="cliente_cidade" id="cliente_cidade" placeholder="Cidade do cliente" value="<?= $cliente->cliente_cidade; ?>">
                                <?= form_error('cliente_cidade', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-1">
                                <label for="cliente_estado">UF</label>
                                <input type="text" class="form-control form-control-user uf" name="cliente_estado" id="cliente_estado" placeholder="UF" value="<?= $cliente->cliente_estado; ?>">
                                <?= form_error('cliente_estado', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2 mb-3">
                        <legend class="font-small"><i class="fas fa-cogs"></i> &nbsp; Configurações</legend>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="cliente_ativo">Cliente ativo</label>
                                <select class="custom-select " name="cliente_ativo" id="cliente_ativo">
                                    <option value="0" <?= ($cliente->cliente_ativo == 0) ? "selected" : "" ?>>Não</option>
                                    <option value="1" <?= ($cliente->cliente_ativo == 1) ? "selected" : "" ?>>Sim</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="cliente_obs">Observações</label>
                                <textarea type="text" class="form-control form-control-user" rows="4" name="cliente_obs" id="cliente_obs" placeholder="Observações"><?= $cliente->cliente_obs; ?></textarea>
                                <?= form_error('cliente_obs', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="cliente_id" value="<?= $cliente->cliente_id; ?>" />
                    <input type="hidden" name="cliente_tipo" value="<?= $cliente->cliente_tipo; ?>" />
                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->