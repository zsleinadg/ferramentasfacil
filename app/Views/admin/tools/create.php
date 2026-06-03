<?php $title = $title ?? 'Nova Ferramenta - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Nova Ferramenta</h1>
    <a href="/admin/ferramentas" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/ferramentas" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label">Nome da Ferramenta *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= old('name') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category_id" class="form-label">Categoria *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['categoryid'] ?>"
                                <?= old('category_id') == $cat['categoryid'] ? 'selected' : '' ?>>
                                <?= $cat['categoryname'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="brand" name="brand"
                           value="<?= old('brand') ?>" placeholder="Ex: Bosch, Makita">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="model" name="model"
                           value="<?= old('model') ?>">
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Descrição *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= old('description') ?></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="daily_price" class="form-label">Preço por Dia (R$) *</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="daily_price" name="daily_price"
                           value="<?= old('daily_price') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="deposit_amount" class="form-label">Caução (R$)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="deposit_amount" name="deposit_amount"
                           value="<?= old('deposit_amount') ?? 0 ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="total_stock" class="form-label">Quantidade em Estoque *</label>
                    <input type="number" min="1" class="form-control" id="total_stock" name="total_stock"
                           value="<?= old('total_stock') ?? 1 ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="min_days" class="form-label">Mínimo de Dias</label>
                    <input type="number" min="1" class="form-control" id="min_days" name="min_days"
                           value="<?= old('min_days') ?? 1 ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="max_days" class="form-label">Máximo de Dias</label>
                    <input type="number" min="1" class="form-control" id="max_days" name="max_days"
                           value="<?= old('max_days') ?? 30 ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="available">Disponível</option>
                        <option value="maintenance">Manutenção</option>
                        <option value="inactive">Inativo</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cover_image" class="form-label">Imagem Principal</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gallery" class="form-label">Galeria (múltiplas fotos)</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" accept="image/jpeg,image/png,image/webp" multiple>
                </div>
                <div class="col-12 mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                           <?= old('is_featured') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_featured">Destacar na Landing Page</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<script>
document.getElementById('name')?.addEventListener('input', function () {
    const slugField = document.getElementById('slug');
    if (slugField && !slugField.value) {
        slugField.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
    }
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
