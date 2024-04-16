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
                <li class="breadcrumb-item"><a href="<?= base_url('vendedores'); ?>">Vendedores</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_edit">
                    <p><strong> <i class="fas fa-clock"></i>&nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($vendedor->vendedor_data_alteracao); ?></p>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-user-secret"></i> &nbsp;Dados pessoais</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6 ">
                                <label for="vendedor_nome_completo">Nome completo</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_nome_completo" id="vendedor_nome_completo" placeholder="Nome do vendedor" value="<?= $vendedor->vendedor_nome_completo; ?>">
                                <?= form_error('vendedor_nome_completo', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="vendedor_cpf">CPF</label>
                                <input type="text" class="form-control form-control-user cpf" name="vendedor_cpf" id="vendedor_cpf" placeholder="CPF do vendedor" value="<?= $vendedor->vendedor_cpf; ?>">
                                <?= form_error('vendedor_cpf', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="vendedor_rg">RG</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_rg" id="vendedor_rg" placeholder="RG do vendedor" value="<?= $vendedor->vendedor_rg; ?>">
                                <?= form_error('vendedor_rg', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="vendedor_email">E-mail</label>
                                <input type="email" class="form-control form-control-user" name="vendedor_email" id="vendedor_email" placeholder="E-mail do vendedor" value="<?= $vendedor->vendedor_email; ?>">
                                <?= form_error('vendedor_email', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="vendedor_telefone">Telefone fixo</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="vendedor_telefone" id="vendedor_telefone" placeholder="Telefone do vendedor" value="<?= $vendedor->vendedor_telefone; ?>">
                                <?= form_error('vendedor_telefone', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="vendedor_celular">Celular</label>
                                <input type="text" class="form-control form-control-user sp_celphones" name="vendedor_celular" id="vendedor_celular" placeholder="Celular do vendedor" value="<?= $vendedor->vendedor_celular; ?>">
                                <?= form_error('vendedor_celular', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-map-marker-alt "></i> &nbsp; Dados de endereço </legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="vendedor_endereco">Endereço</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_endereco" id="vendedor_endereco" placeholder="Endereço do vendedor" value="<?= $vendedor->vendedor_endereco; ?>">
                                <?= form_error('vendedor_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="vendedor_numero_endereco">Número</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_numero_endereco" id="vendedor_numero_endereco" placeholder="Número endereço" value="<?= $vendedor->vendedor_numero_endereco; ?>">
                                <?= form_error('vendedor_numero_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="vendedor_complemento">Complemento</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_complemento" id="vendedor_complemento" placeholder="Complemento" value="<?= $vendedor->vendedor_complemento; ?>">
                                <?= form_error('vendedor_complemento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="vendedor_bairro">Bairro</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_bairro" id="vendedor_bairro" placeholder="Bairro do vendedor" value="<?= $vendedor->vendedor_bairro; ?>">
                                <?= form_error('vendedor_bairro', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2">
                                <label for="vendedor_cep">CEP</label>
                                <input type="text" class="form-control form-control-user cep" name="vendedor_cep" id="vendedor_cep" placeholder="CEP do vendedor" value="<?= $vendedor->vendedor_cep; ?>">
                                <?= form_error('vendedor_cep', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-5">
                                <label for="vendedor_cidade">Cidade</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_cidade" id="vendedor_cidade" placeholder="Cidade do vendedor" value="<?= $vendedor->vendedor_cidade; ?>">
                                <?= form_error('vendedor_cidade', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-1">
                                <label for="vendedor_estado">UF</label>
                                <input type="text" class="form-control form-control-user uf" name="vendedor_estado" id="vendedor_estado" placeholder="UF" value="<?= $vendedor->vendedor_estado; ?>">
                                <?= form_error('vendedor_estado', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2 mb-3">
                        <legend class="font-small"><i class="fas fa-cogs"></i> &nbsp; Configurações</legend>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="vendedor_ativo">vendedor ativo</label>
                                <select class="custom-select" name="vendedor_ativo" id="vendedor_ativo">
                                    <option value="0" <?= ($vendedor->vendedor_ativo == 0) ? "selected" : "" ?>>Não</option>
                                    <option value="1" <?= ($vendedor->vendedor_ativo == 1) ? "selected" : "" ?>>Sim</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="vendedor_codigo">Matrícula</label>
                                <input type="text" class="form-control form-control-user" name="vendedor_codigo" id="vendedor_codigo" placeholder="Matrícula do vendedor" readonly value="<?= $vendedor->vendedor_codigo; ?>">
                                <?= form_error('vendedor_codigo', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="vendedor_obs">Observações</label>
                                <textarea type="text" class="form-control form-control-user" rows="4" name="vendedor_obs" id="vendedor_obs" placeholder="Observações"><?= $vendedor->vendedor_obs; ?></textarea>
                                <?= form_error('vendedor_obs', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="vendedor_id" value="<?= $vendedor->vendedor_id; ?>" />
                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->