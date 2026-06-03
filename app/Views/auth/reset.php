<?php $title = $title ?? 'Redefinir Senha - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-2">Redefinir Senha</h3>
                    <p class="text-muted text-center mb-4">Digite sua nova senha.</p>

                    <form method="POST" action="/redefinir-senha/<?= $token ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova senha</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   minlength="8" required>
                            <div class="form-text">Mínimo de 8 caracteres.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" minlength="8" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
