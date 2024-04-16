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
                <li class="breadcrumb-item"><a href="<?= base_url('modulo'); ?>">Forma de pagamento</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_add">
                    <fieldset class="mt-4 mb-2 border p-2">
                        <legend class="font-small"> <i class="fas fa-money-check-alt"></i> &nbsp;Dados da forma</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="forma_pagamento_nome">Nome da forma de pagamento</label>
                                <input type="text" class="form-control form-control-user" name="forma_pagamento_nome" id="forma_pagamento_nome" placeholder="Nome do forma de pagamento" value="<?= set_value('forma_pagamento_nome') ?>">
                                <?= form_error('forma_pagamento_nome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="forma_pagamento_ativa">Forma de pagamento ativa</label>
                                <select class="custom-select" name="forma_pagamento_ativa" id="forma_pagamento_ativa">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="forma_pagamento_aceita_parc">Aceita parcelamento</label>
                                <select class="custom-select" name="forma_pagamento_aceita_parc" id="forma_pagamento_aceita_parc">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                    </fieldset>

                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url('modulo'); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->