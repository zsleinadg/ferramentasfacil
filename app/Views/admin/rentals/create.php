<?php $title = $title ?? 'Nova Locação - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Nova Locação</h1>
    <a href="/admin/locacoes" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/locacoes">
            <?= csrfField() ?>

            <div class="mb-3">
                <label for="userId" class="form-label">Cliente</label>
                <select name="userId" id="userId" class="form-select" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['userid'] ?>">
                            <?= $client['name'] ?> (<?= $client['email'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="toolId" class="form-label">Ferramenta</label>
                <select name="toolId" id="toolId" class="form-select" required>
                    <option value="">Selecione uma ferramenta</option>
                    <?php foreach ($tools as $tool): ?>
                        <option value="<?= $tool['toolid'] ?>">
                            <?= $tool['toolname'] ?> (R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?>/dia - Estoque: <?= $tool['availablestock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="startDate" class="form-label">Data Início</label>
                    <input type="date" name="startDate" id="startDate" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="expectedEndDate" class="form-label">Data Prevista Devolução</label>
                    <input type="date" name="expectedEndDate" id="expectedEndDate" class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Registrar Locação
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('toolId').addEventListener('change', function() {
    // can be used to populate min/max rental days
});
document.getElementById('startDate').addEventListener('change', function() {
    const start = new Date(this.value);
    const end = document.getElementById('expectedEndDate');
    if (end.value && new Date(end.value) <= start) {
        end.value = '';
    }
    const minDate = new Date(start);
    minDate.setDate(minDate.getDate() + 1);
    end.min = minDate.toISOString().split('T')[0];
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
