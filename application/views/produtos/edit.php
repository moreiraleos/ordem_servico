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
                <li class="breadcrumb-item"><a href="<?= base_url('produtos'); ?>">produtos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
                <form class="user" method="post" name="form_edit">
                    <p><strong> <i class="fas fa-clock"></i>&nbsp;&nbsp; última alteração:&nbsp;</strong><?= formata_data_banco_com_hora($produto->produto_data_alteracao); ?></p>
                    <fieldset class="mt-4 border p-2">
                        <legend class="font-small"> <i class="fas fa-product-hunt"></i> &nbsp;Dados principais</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-2 mb-3">
                                <label for="produto_codigo">Código produto</label>
                                <input type="text" class="form-control form-control-user" name="produto_codigo" id="produto_codigo" readonly value="<?= $produto->produto_codigo; ?>">
                            </div>
                            <div class="col-md-10">
                                <label for="produto_descricao">Descrição do produto</label>
                                <input type="text" class="form-control form-control-user" name="produto_descricao" id="produto_descricao" placeholder="Descrição do produto" value="<?= $produto->produto_descricao; ?>">
                                <?= form_error('produto_descricao', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-3 mb-3">
                                <label for="produto_marca_id">Marca</label>
                                <select class="form-control custom-select" name="produto_marca_id" id="produto_marca_id">
                                    <option value="">Selecione a marca</option>
                                    <?php foreach ($marcas as $marca) : ?>
                                        <option title="<?= ($marca->marca_ativa == 0) ? 'Não pode ser escolhida' : 'Marca ativa' ?>" value="<?= $marca->marca_id ?>" <?= ($marca->marca_id == $produto->produto_marca_id) ? 'selected' : ''; ?> <?= ($marca->marca_ativa == 0) ? 'disabled' : ''; ?>><?= ($marca->marca_ativa == 0) ? $marca->marca_nome . '&nbsp;->&nbsp;Inativa' : $marca->marca_nome . ''; ?></option>
                                    <?php endforeach; ?>

                                </select>
                                <?= form_error('produto_marca_id', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="produto_categoria_id">Categoria</label>
                                <select class="form-control custom-select" name="produto_categoria_id" id="produto_categoria_id">
                                    <option value="">Selecione a categoria</option>
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option title="<?= ($categoria->categoria_ativa == 0) ? 'Não pode ser escolhida' : 'Categoria ativa' ?>" value="<?= $categoria->categoria_id  ?>" <?= ($categoria->categoria_id  == $produto->produto_categoria_id) ? 'selected' : ''; ?> <?= ($categoria->categoria_ativa == 0) ? 'disabled' : ''; ?>><?= ($categoria->categoria_ativa == 0) ? $categoria->categoria_nome . '&nbsp;->&nbsp;Inativa' : $categoria->categoria_nome . ''; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('produto_categoria_id', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="produto_fornecedor_id">Fornecedor</label>
                                <select class="form-control custom-select" name="produto_fornecedor_id" id="produto_fornecedor_id">
                                    <option value="">Selecione a fornecedor</option>
                                    <?php foreach ($fornecedores as $fornecedor) : ?>
                                        <option title="<?= ($fornecedor->fornecedor_ativo == 0) ? 'Não pode ser escolhido' : 'Fornecedor ativo' ?>" value="<?= $fornecedor->fornecedor_id  ?>" <?= ($fornecedor->fornecedor_id  == $produto->produto_fornecedor_id) ? 'selected' : ''; ?> <?= ($fornecedor->fornecedor_ativo == 0) ? 'disabled' : ''; ?>><?= ($fornecedor->fornecedor_ativo == 0) ? $fornecedor->fornecedor_nome_fantasia . '&nbsp;->&nbsp;Inativo' : $fornecedor->fornecedor_nome_fantasia . ''; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('produto_fornecedor_id', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="produto_unidade">Produto unidade</label>
                                <input type="text" class="form-control form-control-user" name="produto_unidade" id="produto_unidade" value="<?= $produto->produto_unidade; ?>">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mt-4 border p-2 mb-3">
                        <legend class="font-small"> <i class="fas fa-funnel-dollar"></i> &nbsp;Precificação e estoque</legend>
                        <div class="form-group row mb-3">
                            <div class="col-md-3">
                                <label for="produto_preco_custo">Preço de custo</label>
                                <input type="text" class="form-control form-control-user money" name="produto_preco_custo" id="produto_preco_custo" placeholder="Preço de custo" value="<?= $produto->produto_preco_custo; ?>">
                                <?= form_error('produto_preco_custo', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="produto_preco_venda">Preço de venda</label>
                                <input type="text" class="form-control form-control-user money" name="produto_preco_venda" id="produto_preco_venda" placeholder="Preço de venda" value="<?= $produto->produto_preco_venda; ?>">
                                <?= form_error('produto_preco_venda', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="produto_estoque_minimo">Estoque mínimo</label>
                                <input type="number" class="form-control form-control-user" name="produto_estoque_minimo" id="produto_estoque_minimo" placeholder="Estoque mínimo" value="<?= $produto->produto_estoque_minimo; ?>">
                                <?= form_error('produto_estoque_minimo', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                            <div class="col-md-3">
                                <label for="produto_qtde_estoque">Qtd em estoque</label>
                                <input type="number" class="form-control form-control-user" name="produto_qtde_estoque" id="produto_qtde_estoque" placeholder="Quantidade em estoque" value="<?= $produto->produto_qtde_estoque; ?>">
                                <?= form_error('produto_qtde_estoque', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-3 mb-3">
                                <label for="produto_ativo">Produto ativo</label>
                                <select class="form-control custom-select" name="produto_ativo" id="produto_ativo">
                                    <option>Selecione o status do produto</option>
                                    <option value="0" <?= ($produto->produto_ativo == 0) ? 'selected' : ''; ?>>Inativo</option>
                                    <option value="1" <?= ($produto->produto_ativo == 1) ? 'selected' : ''; ?>>Ativo</option>
                                </select>
                            </div>

                            <div class="col-md-9 mb-3">
                                <label for="produto_obs">Observações do produto</label>
                                <textarea class="form-control control-user" name="produto_obs" id="produto_obs" rows="5"><?= $produto->produto_obs; ?></textarea>
                                <?= form_error('produto_obs', '<small class="form-text text-danger">', '</small>'); ?>
                            </div>
                        </div>
                    </fieldset>

                    <input type="hidden" name="produto_id" value="<?= $produto->produto_id; ?>" />
                    <button type="submit" class="btn btn-sm btn-primary"> Salvar</button>
                    <a title="Voltar" href="<?= base_url($this->router->fetch_class()); ?>" class="btn btn-success btn-sm ml-2">Voltar</a>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->