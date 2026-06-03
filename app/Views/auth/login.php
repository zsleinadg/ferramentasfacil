<?php $title = $title ?? 'Login - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Entrar</h3>

                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= old('email') ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Lembrar-me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>

                        <div class="text-center mb-3">
                            <span class="text-muted">ou</span>
                        </div>

                        <a href="/auth/google" class="btn btn-outline-dark w-100 mb-3">
                            <i class="bi bi-google"></i> Entrar com Google
                        </a>

                        <div class="text-center">
                            <a href="/esqueci-senha" class="text-decoration-none small">Esqueci minha senha</a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3 text-muted">
                Ainda não tem conta? <a href="/cadastro">Cadastre-se</a>
            </p>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
