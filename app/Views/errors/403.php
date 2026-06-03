<?php $title = 'Acesso negado'; ?>
<?php ob_start(); ?>
<div class="container text-center py-5">
    <h1 class="display-1 text-danger">403</h1>
    <h2 class="mb-4">Acesso Negado</h2>
    <p class="text-muted mb-4">Você não tem permissão para acessar esta página.</p>
    <a href="/" class="btn btn-primary">Voltar ao Início</a>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
