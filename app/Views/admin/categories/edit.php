<?php $title = $title ?? 'Editar Categoria - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Editar Categoria</h1>
    <a href="/admin/categorias" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/categorias/<?= $category['categoryid'] ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= $category['categoryname'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <input type="text" class="form-control" id="slug" name="slug"
                           value="<?= $category['slug'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="icon" class="form-label">Ícone (Bootstrap Icons)</label>
                    <input type="text" class="form-control" id="icon" name="icon"
                           value="<?= $category['iconclass'] ?>" placeholder="Ex: bi bi-hammer">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort_order" class="form-label">Ordem de exibição</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                           value="<?= $category['sortorder'] ?? 0 ?>">
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $category['description'] ?></textarea>
                </div>
                <div class="col-12 mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                           <?= $category['isactive'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">Categoria ativa</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
