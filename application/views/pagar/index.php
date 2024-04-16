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
        <?php if ($message = $this->session->flashdata('info')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
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
                <a title="Cadastrar novo conta" href="<?= base_url('pagar/add') ?>" class="btn btn-success btn-sm float-right">
                    <i class="fab fa-product-hunt"></i> &nbsp;Nova</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fornecedor</th>
                                <th>Valor da conta</th>
                                <th>Data de vencimento</th>
                                <th>Data de pagamento</th>
                                <th>Situação</th>
                                <th class="text-center no-sort pr-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contas_pagar as $conta) : ?>
                                <tr>
                                    <td><?= $conta->conta_pagar_id ?></td>
                                    <td><?= $conta->fornecedor ?></td>
                                    <td><?= 'R$&nbsp;' . $conta->conta_pagar_valor ?></td>
                                    <td><?= formata_data_banco_sem_hora($conta->conta_pagar_data_vencimento) ?></td>
                                    <td><?= ($conta->conta_pagar_status == 1) ? formata_data_banco_com_hora($conta->conta_pagar_data_pagamento) : 'Aguardando pagamento'; ?></td>
                                    <td><?php
                                        if ($conta->conta_pagar_status == 1) {
                                            echo '<span class="badge badge-success btn-sm">Paga</span>';
                                        } else if (strtotime($conta->conta_pagar_data_vencimento)  > strtotime(date('Y-m-d'))) {
                                            echo '<span class="badge badge-secondary btn-sm">A pagar</span>';
                                        } else if (strtotime($conta->conta_pagar_data_vencimento)  == strtotime(date('Y-m-d'))) {
                                            echo '<span class="badge badge-warning btn-sm">Vence hoje</span>';
                                        } else {
                                            echo '<span class="badge badge-danger btn-sm">Vencida</span>';
                                        }
                                        ?></td>
                                    <td class="" width="150px"><a title="Editar" href="<?= base_url('pagar/edit/' . $conta->conta_pagar_id) ?>" class="btn btn-sm btn-primary"> <i class="fas fa-edit"></i> Editar</a>
                                        <a title="Excluir" href="javascript(void)" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#conta-<?= $conta->conta_pagar_id  ?>"> <i class="fas fa-trash"></i> Excluir</a>
                                    </td>
                                </tr>
                                <!-- Del Modal-->
                                <div class="modal fade" id="conta-<?= $conta->conta_pagar_id  ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <a class="btn btn-danger btn-sm" href="<?= base_url('pagar/del/' . $conta->conta_pagar_id)  ?>">Sim</a>
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