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
                <li class="breadcrumb-item"><a href="<?= base_url('marcas'); ?>">marcas</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_edit">
                    <p><strong> <i class="fas fa-cubes"></i> &nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($marca->marca_data_alteracao); ?></p>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-laptop"></i> &nbsp;Dados do marca</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-8 ">
                                <label for="marca_nome">Nome da marca</label>
                                <input type="text" class="form-control form-control-user" name="marca_nome" id="marca_nome" placeholder="Nome do marca" value="<?= $marca->marca_nome; ?>">
                                <?= form_error('marca_nome', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>


                            <div class="col-md-4">
                                <label for="marca_ativa">marca ativa</label>
                                <select class="custom-select" name="marca_ativa" id="marca_ativa">
                                    <option value="0" <?= ($marca->marca_ativa == 0) ? "selected" : "" ?>>Não</option>
                                    <option value="1" <?= ($marca->marca_ativa == 1) ? "selected" : "" ?>>Sim</option>
                                </select>
                            </div>
                    </fieldset>

                    <input type="hidden" name="marca_id" value="<?= $marca->marca_id; ?>" />
                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->