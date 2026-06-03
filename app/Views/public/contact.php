<?php $title = $title ?? 'Contato - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <h1>Contato</h1>
    <p class="lead text-muted">Entre em contato conosco.</p>
    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="/contato">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensagem</label>
                    <textarea class="form-control" name="mensagem" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
        <div class="col-md-6">
            <h5>Informações</h5>
            <p><i class="bi bi-whatsapp"></i> (11) 99999-8888</p>
            <p><i class="bi bi-envelope"></i> contato@ferramentasfacil.com.br</p>
            <p><i class="bi bi-geo-alt"></i> Rua Exemplo, 123 - Centro</p>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
