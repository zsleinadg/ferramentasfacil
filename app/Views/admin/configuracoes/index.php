<?php $title = $title ?? 'Configurações - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Configurações</h1>
</div>

<form method="POST" action="/admin/configuracoes">
    <?= csrfField() ?>

    <?php foreach ($settings as $group => $items): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <?= match ($group) {
                        'company' => 'Dados da Empresa',
                        'rental' => 'Políticas de Locação',
                        'system' => 'Sistema',
                        default => ucfirst($group)
                    } ?>
                </h5>
            </div>
            <div class="card-body">
                <?php foreach ($items as $item): ?>
                    <div class="mb-3">
                        <label for="settings_<?= $item['settingkey'] ?>" class="form-label">
                            <?= $item['description'] ?? $item['settingkey'] ?>
                        </label>
                        <?php if (in_array($item['settingkey'], ['company_address', 'rental_policy'])): ?>
                            <textarea name="settings[<?= $item['settingkey'] ?>]" id="settings_<?= $item['settingkey'] ?>" class="form-control" rows="3"><?= htmlspecialchars($item['settingvalue'] ?? '') ?></textarea>
                        <?php else: ?>
                            <input type="text" name="settings[<?= $item['settingkey'] ?>]" id="settings_<?= $item['settingkey'] ?>" class="form-control" value="<?= htmlspecialchars($item['settingvalue'] ?? '') ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-lg"></i> Salvar Configurações
        </button>
    </div>
</form>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
