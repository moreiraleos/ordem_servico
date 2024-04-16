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
                <li class="breadcrumb-item"><a href="<?= base_url('receber'); ?>">Contas a receber</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_add">
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-money-bill-alt"></i> &nbsp;Dados da conta</legend>

                        <div class="form-group row mb-3">

                            <div class="col-md-5 mb-3">
                                <label for="conta_receber_cliente_id">Cliente</label>
                                <select class="form-control custom-select contas_receber" name="conta_receber_cliente_id" id="conta_receber_cliente_id">
                                    <?php foreach ($clientes as $cliente) : ?>
                                        <option value="<?= $cliente->cliente_id  ?>"><?= $cliente->cliente_nome; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('conta_receber_cliente_id', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="conta_receber_data_vencimento">Data de vencimento</label>
                                <input type="date" class="form-control form-control-user-date" name="conta_receber_data_vencimento" value="<?= set_value('conta_receber_data_vencimento') ?>" id="conta_receber_data_vencimento">
                                <?= form_error('conta_receber_data_vencimento', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="conta_receber_valor">Valor da conta</label>
                                <input type="text" class="form-control form-control-user money2" name="conta_receber_valor" value="<?= set_value('conta_receber_valor') ?>" id="conta_receber_valor">
                                <?= form_error('conta_receber_valor', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="conta_receber_cliente_id">Situação</label>
                                <select class="form-control custom-select" name="conta_receber_status" id="conta_receber_status">
                                    <option value="1">Paga</option>
                                    <option value="0">Pendente</option>
                                </select>
                                <?= form_error('conta_receber_status', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">

                            <div class="col-md-12 mb-3">
                                <label for="conta_receber_data_vencimento">Observações</label>
                                <textarea name="conta_receber_obs" id="conta_receber_obs" class="form-control form-control-user" cols="30" rows="10"><?= set_value('conta_receber_obs') ?></textarea>
                                <?= form_error('conta_receber_obs', '<small class="form-text text-danger">', '</small>'); ?>
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