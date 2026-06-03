<?php $title = $title ?? 'Cadastro - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Criar Conta</h3>

                    <form method="POST" action="/cadastro">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?= old('name') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= old('email') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefone / WhatsApp</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?= old('phone') ?>" placeholder="(11) 99999-8888">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       minlength="8" required>
                                <div class="form-text">Mínimo de 8 caracteres.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" minlength="8" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Aceito os <a href="#" class="text-decoration-none">termos de uso</a> e
                                <a href="#" class="text-decoration-none">política de privacidade</a>.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Criar Conta</button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3 text-muted">
                Já tem conta? <a href="/login">Faça login</a>
            </p>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
