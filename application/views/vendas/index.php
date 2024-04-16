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
                <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
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
            <div class="card-header py-3">
                <a title="Cadastrar nova venda" href="<?= base_url('vendas/add') ?>" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-shopping-cart"></i> &nbsp;Nova</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data emissão</th>
                                <th>Cliente</th>
                                <th>Forma de pagamento</th>
                                <th>Valor total</th>
                                <th class="text-center no-sort pr-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vendas as $venda) : ?>
                                <tr>
                                    <td><?= $venda->venda_id ?></td>
                                    <td><?= formata_data_banco_com_hora($venda->venda_data_emissao) ?></td>
                                    <td><?= $venda->cliente_nome_completo ?></td>
                                    <td><?= $venda->forma_pagamento; ?></td>
                                    <td><?= 'R$&nbsp;' . $venda->venda_valor_total ?></td>
                                    <td class="text-center">
                                        <a title="Imprimir" href="<?= base_url('vendas/pdf/' . $venda->venda_id) ?>" class="btn btn-sm btn-dark"> <i class="fas fa-print"></i> Imprimir</a>
                                        <a title="Visualizar" href="<?= base_url('vendas/edit/' . $venda->venda_id) ?>" class="btn btn-sm btn-primary"> <i class="fas fa-eye"></i> Visualizar</a>
                                        <a title="Excluir" href="javascript(void)" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#venda-<?= $venda->venda_id  ?>"> <i class="fas fa-trash-alt"></i> Excluir</a>
                                    </td>
                                </tr>
                                <!-- Del Modal-->
                                <div class="modal fade" id="venda-<?= $venda->venda_id  ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tem certeza da deleção?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Para excluir o registro, clique em <strong>"Sim"</strong>.</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Não</button>
                                                <a class="btn btn-danger btn-sm" href="<?= base_url('vendas/del/' .  $venda->venda_id)  ?>">Sim</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->