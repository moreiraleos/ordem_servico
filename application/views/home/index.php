<?php
$this->load->view('layout/sidebar');
?>

<!-- Main Content -->
<div id="content">

    <?php $this->load->view('layout/navbar'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Home</h1>


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

        <?php if ($message = $this->session->flashdata('info')) : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation"></i>&nbsp;<?= $message ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <!-- Content Row -->
        <?php if($this->ion_auth->is_admin()): ?>
        <div class="row">

            <!-- TOTAL VENDAS -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total de vendas</div>
                                <div class="h5 mb-0 font-weight-bold success"><?= "R$&nbsp;" . $soma_vendas->venda_valor_total ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOTAL SERVIÇOS -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de serviços</div>
                                <div class="h5 mb-0 font-weight-bold text-primary"><?= "R$&nbsp" . $soma_ordem_servicos->ordem_servico_valor_total ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-basket fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOTAL CONTAS A PAGAR-->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total a pagar</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-danger"><?= !empty($total_pagar->conta_pagar_valor) == NULL ? '0,00' : "R$&nbsp;" . $total_pagar->conta_pagar_valor; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-alt fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOTAL CONTAS A RECEBER -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total a receber</div>
                                <div class="h5 mb-0 font-weight-bold text-warning"><?= !empty($total_receber->conta_receber_valor) ? "R$&nbsp;" . $total_receber->conta_receber_valor : "R$&nbsp;0,00"; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-usd fa-3x text-warning  "></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-6 mb-4">

                <!--  Produtos mais vendidos -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">TOP 3 Produtos mais vendidos</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;" src="<?= base_url('/public/img/servicos_vendidos.svg') ?>" alt="">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nome produto</th>
                                        <th class="text-center">Vendidos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtos_mais_vendidos as $produto) : ?>
                                        <tr>
                                            <td><?= $produto->produto_descricao ?></td>
                                            <td class="text-center"><?= '<span class="badge badge-success">' . $produto->quantidades_vendidas . '</span>' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6 mb-4">
                <!-- Serviços mais vendidos -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">TOP 3 Serviços mais vendidos</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;" src="<?= base_url('/public/img/produtos_mais_vendidos.svg') ?>" alt="">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-stripped table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nome serviço</th>
                                        <th>Vendidos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($servicos_mais_vendidos as $servico) : ?>
                                        <tr>
                                            <td><?= $servico->servico_nome ?></td>
                                            <td><?= '<span class="badge badge-primary">' . $servico->quantidade_vendidos . '</span>' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->