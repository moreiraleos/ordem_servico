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
                <li class="breadcrumb-item"><a href="<?= base_url('Fornecedores'); ?>">Fornecedores</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_add">
                    <?php if (isset($fornecedor)) : ?>
                        <p><strong> <i class="fas fa-clock"></i>&nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($fornecedor->fornecedor_data_alteracao); ?></p>
                    <?php endif; ?>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-user-tie"></i>&nbsp;Dados principais</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="first_name">Razão social</label>
                                <input type="text" class="form-control form-control-user" name="fornecedor_razao" id="fornecedor_razao" placeholder="Razão social" value="<?= set_value('fornecedor_razao') ?>">
                                <?= form_error('fornecedor_razao', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="fornecedor_nome_fantasia">Nome fantasia</label>
                                <input type="text" class="form-control form-control-user" name="fornecedor_nome_fantasia" id="fornecedor_nome_fantasia" placeholder="Nome fantasia" value="<?= set_value('fornecedor_nome_fantasia') ?>">
                                <?= form_error('fornecedor_nome_fantasia', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>

                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="fornecedor_cnpj">CNPJ</label>
                                <input type="text" class="form-control form-control-user cnpj" name="fornecedor_cnpj" id="fornecedor_cnpj" placeholder="CNPJ" value="<?= set_value('fornecedor_cnpj') ?>">
                                <?= form_error('fornecedor_cnpj', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="fornecedor_ie">Inscrição Estadual</label>
                                <input type="text" class="form-control form-control-user" name="fornecedor_ie" id="fornecedor_ie" placeholder="Inscrição estadual" value="<?= set_value('fornecedor_ie') ?>">
                                <?= form_error('fornecedor_ie', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="fornecedor_telefone">Telefone fixo</label>
                                <input type="text" class="form-control form-control-user phone_with_ddd" name="fornecedor_telefone" id="fornecedor_telefone" placeholder="Telefone fixo" value="<?= set_value('fornecedor_telefone') ?>">
                                <?= form_error('fornecedor_telefone', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="fornecedor_celular">Celular</label>
                                <input type="text" class="form-control form-control-user cell_phone_with_ddd" name="fornecedor_celular" id="fornecedor_celular" placeholder="Telefone celular" value="<?= set_value('fornecedor_celular') ?>">
                                <?= form_error('fornecedor_celular', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="fornecedor_email">E-mail</label>
                                <input type="email" class="form-control form-control-user" name="fornecedor_email" id="fornecedor_email" placeholder="E-mail" value="<?= set_value('fornecedor_email') ?>">
                                <?= form_error('fornecedor_email', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="fornecedor_contato">Nome do contato</label>
                                <input type="text" class="form-control form-control-user" name="fornecedor_contato" id="fornecedor_contato" placeholder="Nome do atendente" value="<?= set_value('fornecedor_contato') ?>">
                                <?= form_error('fornecedor_contato', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <fieldset class="mt-4 border p-2">
                            <legend class="font-small"> <i class="fas fa-map-marker-alt "></i> &nbsp; Dados de endereço </legend>
                            <div class="form-group row mb-3">
                                <div class="col-md-6">
                                    <label for="fornecedor_endereco">Endereço</label>
                                    <input type="text" class="form-control form-control-user" name="fornecedor_endereco" id="fornecedor_endereco" placeholder="Endereço do Fornecedor" value="<?= set_value('fornecedor_endereco') ?>">
                                    <?= form_error('fornecedor_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="fornecedor_numero_endereco">Número</label>
                                    <input type="text" class="form-control form-control-user" name="fornecedor_numero_endereco" id="fornecedor_numero_endereco" placeholder="Número endereço" value="<?= set_value('fornecedor_numero_endereco') ?>">
                                    <?= form_error('fornecedor_numero_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label for="fornecedor_complemento">Complemento</label>
                                    <input type="text" class="form-control form-control-user" name="fornecedor_complemento" id="fornecedor_complemento" placeholder="Complemento" value="<?= set_value('fornecedor_complemento') ?>">
                                    <?= form_error('fornecedor_complemento', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-md-4">
                                    <label for="fornecedor_bairro">Bairro</label>
                                    <input type="text" class="form-control form-control-user" name="fornecedor_bairro" id="fornecedor_bairro" placeholder="Bairro do Fornecedor" value="<?= set_value('fornecedor_bairro') ?>">
                                    <?= form_error('fornecedor_bairro', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="fornecedor_cep">CEP</label>
                                    <input type="text" class="form-control form-control-user cep" name="fornecedor_cep" id="fornecedor_cep" placeholder="CEP do Fornecedor" value="<?= set_value('fornecedor_cep') ?>">
                                    <?= form_error('fornecedor_cep', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-5">
                                    <label for="fornecedor_cidade">Cidade</label>
                                    <input type="text" class="form-control form-control-user" name="fornecedor_cidade" id="fornecedor_cidade" placeholder="Cidade do Fornecedor" value="<?= set_value('fornecedor_cidade') ?>">
                                    <?= form_error('fornecedor_cidade', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-1">
                                    <label for="fornecedor_estado">UF</label>
                                    <input type="text" class="form-control form-control-user uf" name="fornecedor_estado" id="fornecedor_estado" placeholder="UF" value="<?= set_value('fornecedor_estado') ?>">
                                    <?= form_error('fornecedor_estado', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-4 border p-2 mb-3">
                            <legend class="font-small"><i class="fas fa-cogs"></i> &nbsp; Configurações</legend>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="fornecedor_ativo">Fornecedor ativo</label>
                                    <select class="custom-select " name="fornecedor_ativo" id="fornecedor_ativo">
                                        <option value="0" selected>Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="fornecedor_obs">Observações</label>
                                    <textarea type="text" class="form-control form-control-user" rows="4" name="fornecedor_obs" id="fornecedor_obs" placeholder="Observações"><?= set_value('fornecedor_obs') ?></textarea>
                                    <?= form_error('fornecedor_obs', '<small class="form-text text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                        <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->