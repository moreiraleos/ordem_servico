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
                <li class="breadcrumb-item"><a href="<?= base_url('usuarios'); ?>">Usuários</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $titulo ?></li>
            </ol>
        </nav>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a title="Voltar" href="<?= base_url('usuarios'); ?>" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Voltar</a>
            </div>
            <div class="card-body">
                <form method="post" name="form_edit">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="first_name">Nome</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Seu nome" value="<?= $usuario->first_name; ?>">
                            <?= form_error('first_name', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name">Sobrenome</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Seu nome" value="<?= $usuario->last_name; ?>">
                            <?= form_error('last_name', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="email">E-mail&nbsp;(Login)</label>
                            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Seu email (login)" value="<?= $usuario->email; ?>">
                            <?= form_error('email', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="username">Usuário</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Seu usuário" value="<?= $usuario->username; ?>">
                            <?= form_error('username', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="active">Ativo</label>
                            <select class="form-control" name="active" id="" <?= (!$this->ion_auth->is_admin()) ? "disabled" : ""; ?>>
                                <option value="0" <?= ($usuario->active == 0) ? "selected" : ""; ?>>Não</option>
                                <option value="1" <?= ($usuario->active == 1) ? "selected" : ""; ?>>Ativo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="active">Perfil de acesso</label>
                            <select class="form-control" name="perfil_usuario" id="" <?= (!$this->ion_auth->is_admin()) ? "disabled" : ""; ?>>
                                <option value="2" <?= ($perfil_usuario->id == 2) ? "selected" : ""; ?>>Vendedor</option>
                                <option value="1" <?= ($perfil_usuario->id == 1) ? "selected" : ""; ?>>Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Sua senha">
                            <?= form_error('password', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password">Confirme</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirme sua senha">
                            <?= form_error('confirm_password', '<small class="form-text text-danger">', '</small>'); ?>
                        </div>
                        <input type="hidden" name="usuario_id" value="<?= $usuario->id; ?>">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary"> <i class="fas fa-database"></i> Salvar</button>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->