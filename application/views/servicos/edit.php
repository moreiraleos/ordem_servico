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
                <li class="breadcrumb-item"><a href="<?= base_url('servicos'); ?>">servicos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_edit">
                    <p><strong> <i class="fas fa-clock"></i>&nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($servico->servico_data_alteracao); ?></p>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-laptop"></i> &nbsp;Dados do serviço</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-4 ">
                                <label for="servico_nome">Nome do serviço</label>
                                <input type="text" class="form-control form-control-user" name="servico_nome" id="servico_nome" placeholder="Nome do servico" value="<?= $servico->servico_nome; ?>">
                                <?= form_error('servico_nome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="servico_preco">Preço</label>
                                <input type="text" class="form-control form-control-user money" name="servico_preco" id="servico_preco" placeholder="Preço do serviço" value="<?= $servico->servico_preco; ?>">
                                <?= form_error('servico_preco', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>

                            <div class="col-md-4">
                                <label for="servico_ativo">Servico ativo</label>
                                <select class="custom-select" name="servico_ativo" id="servico_ativo">
                                    <option value="0" <?= ($servico->servico_ativo == 0) ? "selected" : "" ?>>Não</option>
                                    <option value="1" <?= ($servico->servico_ativo == 1) ? "selected" : "" ?>>Sim</option>
                                </select>
                            </div>

                    </fieldset>

                    <fieldset class="mt-4 border p-2 mb-3">
                        <legend class="font-small"><i class="fas fa-cogs"></i> &nbsp; Configurações</legend>
                        <div class="form-group row">


                            <div class="col-md-12">
                                <label for="servico_descricao">Observações</label>
                                <textarea type="text" class="form-control form-control-user" rows="10" style="min-height:100px!important;" name="servico_descricao" id="servico_descricao" placeholder="Observações"><?= $servico->servico_descricao; ?></textarea>
                                <?= form_error('servico_descricao', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="servico_id" value="<?= $servico->servico_id; ?>" />
                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->