<?php $title = 'Página não encontrada'; ?>
<?php ob_start(); ?>
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <h2 class="mb-4">Página não encontrada</h2>
    <p class="text-muted mb-4">A página que você procura não existe ou foi movida.</p>
    <a href="/" class="btn btn-primary">Voltar ao Início</a>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
