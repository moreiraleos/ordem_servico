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
                <li class="breadcrumb-item"><a href="<?= base_url('/'); ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <?php if ($message = $this->session->flashdata('sucesso')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong> <i class="far fa-smile"></i> &nbsp;<?= $message ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <?php if ($message = $this->session->flashdata('error')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation"></i>&nbsp;<?= $message ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-body">
                <form class="user" method="post" name="form_edit">
                    <div class="form-group row mb-3">
                        <div class="col-md-3">
                            <label for="first_name">Razão social</label>
                            <input type="text" class="form-control form-control-user" name="sistema_razao_social" id="sistema_razao_social" placeholder="Razão social" value="<?= $sistema->sistema_razao_social; ?>">
                            <?= form_error('sistema_razao_social', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="sistema_nome_fantasia">Nome fantasia</label>
                            <input type="text" class="form-control form-control-user" name="sistema_nome_fantasia" id="sistema_nome_fantasia" placeholder="Nome fanasia" value="<?= $sistema->sistema_nome_fantasia; ?>">
                            <?= form_error('sistema_nome_fantasia', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="sistema_cnpj">CNPJ</label>
                            <input type="text" class="form-control form-control-user cnpj" name="sistema_cnpj" id="sistema_cnpj" placeholder="CNPJ" value="<?= $sistema->sistema_cnpj; ?>">
                            <?= form_error('sistema_cnpj', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="email">Inscrição estadual</label>
                            <input type="text" class="form-control form-control-user" name="sistema_ie" id="sistema_ie" placeholder="Inscrição estadual" value="<?= $sistema->sistema_ie; ?>">
                            <?= form_error('sistema_ie', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>

                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-3">
                            <label for="sistema_telefone_fixo">Telefone fixo</label>
                            <input type="text" class="form-control form-control-user phone_with_ddd" name="sistema_telefone_fixo" id="sistema_telefone_fixo" placeholder="Telefone fixo" value="<?= $sistema->sistema_telefone_fixo; ?>">
                            <?= form_error('sistema_telefone_fixo', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="sistema_telefone_movel">Telefone Móvel</label>
                            <input type="text" class="form-control form-control-user phone_with_ddd" name="sistema_telefone_movel" id="sistema_telefone_movel" placeholder="Telefone Móvel" value="<?= $sistema->sistema_telefone_movel; ?>">
                            <?= form_error('sistema_telefone_movel', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="sistema_site_url">URL do site</label>
                            <input type="url" class="form-control form-control-user" name="sistema_site_url" id="sistema_site_url" placeholder="URL" value="<?= $sistema->sistema_site_url; ?>">
                            <?= form_error('sistema_site_url', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="sistema_email">Email de contato</label>
                            <input type="email" class="form-control form-control-user" name="sistema_email" id="sistema_email" placeholder="Email de contato" value="<?= $sistema->sistema_email; ?>">
                            <?= form_error('sistema_email', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-4">
                            <label for="sistema_endereco">Endereço</label>
                            <input type="text" class="form-control form-control-user" name="sistema_endereco" id="sistema_endereco" placeholder="Endereço" value="<?= $sistema->sistema_endereco; ?>">
                            <?= form_error('sistema_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-2">
                            <label for="sistema_cep">CEP</label>
                            <input type="text" class="form-control form-control-user cep" name="sistema_cep" id="sistema_cep" placeholder="Inscrição estadual" value="<?= $sistema->sistema_cep; ?>">
                            <?= form_error('sistema_cep', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-2">
                            <label for="sistema_numero">Número</label>
                            <input type="text" class="form-control form-control-user" name="sistema_numero" id="sistema_numero" placeholder="Número" value="<?= $sistema->sistema_numero; ?>">
                            <?= form_error('sistema_numero', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-2">
                            <label for="sistema_cidade">Cidade</label>
                            <input type="text" class="form-control form-control-user" name="sistema_cidade" id="sistema_cidade" placeholder="Cidade" value="<?= $sistema->sistema_cidade; ?>">
                            <?= form_error('sistema_cidade', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-2">
                            <label for="sistema_estado">UF</label>
                            <input type="text" class="form-control uf form-control-user" name="sistema_estado" id="sistema_estado" placeholder="UF" value="<?= $sistema->sistema_estado; ?>">
                            <?= form_error('sistema_estado', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-12">
                            <label for="sistema_txt_ordem_servico">Texto da ordem de serviço</label>
                            <textarea name="sistema_txt_ordem_servico" id="sistema_txt_ordem_servico" class="form-control form-control-user" placeholder="Texto da ordem de serviço e venda sistema"><?= $sistema->sistema_txt_ordem_servico; ?></textarea>
                            <?= form_error('sistema_endereco', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-sm btn-primary"> <i class="fas fa-database"></i> Salvar</button>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->