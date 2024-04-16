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
                <a title="Cadastrar novo produto" href="<?= base_url('produtos/add') ?>" class="btn btn-success btn-sm float-right">
                    <i class="fab fa-product-hunt"></i> &nbsp;Novo</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered produtos" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Código do produto</th>
                                <th class="text-center" width="100px">Nome</th>
                                <th>Marca do produto</th>
                                <th>Categoria</th>
                                <th class="text-center">Estoque mínimo</th>
                                <th class="text-center">Qtde estoque</th>

                                <th class="text-center">Ativo</th>
                                <th class="text-center no-sort pr-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto) : ?>
                                <tr>
                                    <td><?= $produto->produto_id ?></td>
                                    <td><?= $produto->produto_codigo ?></td>
                                    <td><?= word_limiter($produto->produto_descricao, 3) ?></td>
                                    <td><?= $produto->produto_marca ?></td>
                                    <td><?= $produto->produto_categoria ?></td>
                                    <td class="text-center pr-2"><?= '<span class="badge badge-success btn-sm">' . $produto->produto_estoque_minimo . '</span>' ?></td>
                                    <td class="text-center pr-2"><?= '<span class="badge badge-' . ($produto->produto_qtde_estoque <= $produto->produto_estoque_minimo ? 'warning text-gray-900' : 'info') . ' btn-sm ">' . $produto->produto_qtde_estoque . '</span>' ?></td>
                                    <td class="text-center pr-2"><?= ($produto->produto_ativo == 1) ? '<span class="badge badge-info btn-sm">Sim</span>' : '<span class="badge badge-pill badge-warning btn-sm">Não</span>' ?></td>
                                    <td class="" width="150px"><a title="Editar" href="<?= base_url('produtos/edit/' . $produto->produto_id) ?>" class="btn btn-sm btn-primary"> <i class="fas fa-edit"></i> Editar</a>
                                        <a title="Excluir" href="javascript(void)" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#vendedor-<?= $produto->produto_id  ?>"> <i class="fas fa-trash"></i> Excluir</a>
                                    </td>
                                </tr>
                                <!-- Del Modal-->
                                <div class="modal fade" id="vendedor-<?= $produto->produto_id  ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <a class="btn btn-danger btn-sm" href="<?= base_url('produtos/del/' . $produto->produto_id)  ?>">Sim</a>
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