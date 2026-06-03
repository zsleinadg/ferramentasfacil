<?php $title = $title ?? 'Editar Ferramenta - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Editar Ferramenta</h1>
    <a href="/admin/ferramentas" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/ferramentas/<?= $tool['toolid'] ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="name" class="form-label">Nome da Ferramenta *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= $tool['toolname'] ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category_id" class="form-label">Categoria *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['categoryid'] ?>"
                                <?= $tool['categoryid'] == $cat['categoryid'] ? 'selected' : '' ?>>
                                <?= $cat['categoryname'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="brand" name="brand"
                           value="<?= $tool['brand'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="model" name="model"
                           value="<?= $tool['model'] ?>">
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Descrição *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= $tool['description'] ?></textarea>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="daily_price" class="form-label">Preço por Dia (R$) *</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="daily_price" name="daily_price"
                           value="<?= $tool['dailyprice'] ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="deposit_amount" class="form-label">Caução (R$)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="deposit_amount" name="deposit_amount"
                           value="<?= $tool['depositamount'] ?? 0 ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="total_stock" class="form-label">Estoque Total</label>
                    <input type="number" min="1" class="form-control" id="total_stock" name="total_stock"
                           value="<?= $tool['totalstock'] ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="min_days" class="form-label">Mín. Dias</label>
                    <input type="number" min="1" class="form-control" id="min_days" name="min_days"
                           value="<?= $tool['minrentaldays'] ?? 1 ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="max_days" class="form-label">Máx. Dias</label>
                    <input type="number" min="1" class="form-control" id="max_days" name="max_days"
                           value="<?= $tool['maxrentaldays'] ?? 30 ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="available" <?= $tool['status'] === 'available' ? 'selected' : '' ?>>Disponível</option>
                        <option value="rented" <?= $tool['status'] === 'rented' ? 'selected' : '' ?>>Alugado</option>
                        <option value="maintenance" <?= $tool['status'] === 'maintenance' ? 'selected' : '' ?>>Manutenção</option>
                        <option value="inactive" <?= $tool['status'] === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                <div class="col-12 mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                           <?= $tool['isfeatured'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_featured">Destacar na Landing Page</label>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Imagem Atual</label>
                    <div>
                        <?php if ($tool['coverimageurl']): ?>
                            <img src="<?= imageUrl($tool['coverimageurl']) ?>" alt="Cover" style="max-height: 150px;" class="rounded">
                        <?php else: ?>
                            <p class="text-muted">Nenhuma imagem</p>
                        <?php endif; ?>
                    </div>
                    <label for="cover_image" class="form-label mt-2">Nova Imagem Principal</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Galeria</label>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <?php foreach ($gallery as $img): ?>
                            <div class="position-relative">
                                <img src="<?= imageUrl($img['imageurl']) ?>" alt="<?= $img['alttext'] ?? '' ?>"
                                     style="width: 80px; height: 80px; object-fit: cover;" class="rounded border">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <label for="gallery" class="form-label">Adicionar Fotos</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" accept="image/jpeg,image/png,image/webp" multiple>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
