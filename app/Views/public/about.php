<?php $title = $title ?? 'Sobre Nós - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <h1>Sobre Nós</h1>
    <p class="lead text-muted">Conheça o FerramentasFácil e nossa história.</p>
    <p>Em breve... Conteúdo institucional será adicionado aqui.</p>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
