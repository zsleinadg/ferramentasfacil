<?php $title = $title ?? 'Recuperar Senha - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-2">Recuperar Senha</h3>
                    <p class="text-muted text-center mb-4">
                        Informe seu e-mail para receber um link de recuperação.
                    </p>

                    <form method="POST" action="/esqueci-senha">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enviar Link</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/login" class="text-decoration-none small">Voltar ao login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
