<?php $title = $title ?? 'Nova Categoria - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Nova Categoria</h1>
    <a href="/admin/categorias" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/categorias">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= old('name') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <input type="text" class="form-control" id="slug" name="slug"
                           value="<?= old('slug') ?>" placeholder="Deixe em branco para gerar automático">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="icon" class="form-label">Ícone (Bootstrap Icons)</label>
                    <input type="text" class="form-control" id="icon" name="icon"
                           value="<?= old('icon') ?>" placeholder="Ex: bi bi-hammer">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort_order" class="form-label">Ordem de exibição</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                           value="<?= old('sort_order') ?? 0 ?>">
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                </div>
                <div class="col-12 mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Categoria ativa</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<script>
document.getElementById('name')?.addEventListener('input', function () {
    const slug = document.getElementById('slug');
    if (!slug.value || slug.dataset.auto === 'true' || !slug.dataset.auto) {
        slug.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        slug.dataset.auto = 'true';
    }
});
document.getElementById('slug')?.addEventListener('input', function () {
    this.dataset.auto = 'false';
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
