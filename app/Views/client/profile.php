<?php $title = $title ?? 'Meu Perfil - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="mb-4">Meu Perfil</h3>

                    <form method="POST" action="/cliente/perfil">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= $user['name'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" value="<?= $user['email'] ?>" disabled>
                            <div class="form-text">Não é possível alterar o e-mail.</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone / WhatsApp</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= $user['phone'] ?>" placeholder="(11) 99999-8888">
                        </div>

                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf"
                                   value="<?= $user['cpf'] ?>" placeholder="000.000.000-00">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Endereço</label>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                      placeholder="Rua, número, bairro, cidade"><?= $user['address'] ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
