<?php $title = $title ?? 'Alugar Ferramenta'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/catalogo">Catálogo</a></li>
            <li class="breadcrumb-item"><a href="/ferramenta/<?= $tool['slug'] ?>"><?= $tool['toolname'] ?></a></li>
            <li class="breadcrumb-item active">Alugar</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card">
                <div class="ratio ratio-4x3 bg-light rounded-top overflow-hidden">
                    <?php if ($tool['coverimageurl']): ?>
                        <img src="<?= imageUrl($tool['coverimageurl']) ?>" alt="<?= $tool['toolname'] ?>" class="object-fit-cover">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $tool['toolname'] ?></h5>
                    <p class="text-muted small mb-2"><?= $tool['categoryname'] ?> <?= $tool['brand'] ? '| ' . $tool['brand'] : '' ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Preço</small>
                            <strong class="text-primary fs-5">R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?>/dia</strong>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Caução</small>
                            <strong>R$ <?= number_format((float) $tool['depositamount'], 2, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Solicitar Locação</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/cliente/alugar/<?= $tool['toolid'] ?>" id="rentForm">
                        <?= csrfField() ?>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Data de Retirada</label>
                                <input type="date" name="startDate" id="startDate"
                                       class="form-control" required
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="expectedEndDate" class="form-label">Data de Devolução</label>
                                <input type="date" name="expectedEndDate" id="expectedEndDate"
                                       class="form-control" required
                                       min="<?= date('Y-m-d', strtotime('+' . $tool['minrentaldays'] . ' days')) ?>">
                            </div>
                        </div>

                        <div class="bg-light rounded p-4 mb-4">
                            <h6 class="mb-3">Resumo do Pedido</h6>
                            <div class="row mb-2">
                                <div class="col-6 text-muted">Valor da diária:</div>
                                <div class="col-6 text-end" id="summaryDaily">R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 text-muted">Período:</div>
                                <div class="col-6 text-end" id="summaryDays">---</div>
                            </div>
                            <hr>
                            <div class="row mb-2">
                                <div class="col-6 fw-bold">Valor Total:</div>
                                <div class="col-6 text-end fw-bold text-primary fs-5" id="summaryTotal">R$ 0,00</div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-muted">Caução (devolutiva):</div>
                                <div class="col-6 text-end" id="summaryDeposit">R$ <?= number_format((float) $tool['depositamount'], 2, ',', '.') ?></div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Solicitar Locação
                            </button>
                            <p class="text-muted small text-center mb-0 mt-2">
                                <i class="bi bi-info-circle"></i>
                                Sua solicitação será analisada e confirmada pelo administrador.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Informações Importantes</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li>Período mínimo: <strong><?= $tool['minrentaldays'] ?> dia(s)</strong></li>
                        <li>Período máximo: <strong><?= $tool['maxrentaldays'] ?> dia(s)</strong></li>
                        <li>O valor da caução será devolvido mediante devolução em bom estado.</li>
                        <li>Multa de 50% do valor da diária por dia de atraso na devolução.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dailyPrice = <?= (float) $tool['dailyprice'] ?>;
const depositAmount = <?= (float) $tool['depositamount'] ?>;
const minDays = <?= (int) $tool['minrentaldays'] ?>;
const maxDays = <?= (int) $tool['maxrentaldays'] ?>;

const startInput = document.getElementById('startDate');
const endInput = document.getElementById('expectedEndDate');

function updateSummary() {
    const start = startInput.value ? new Date(startInput.value) : null;
    const end = endInput.value ? new Date(endInput.value) : null;

    const daysEl = document.getElementById('summaryDays');
    const totalEl = document.getElementById('summaryTotal');

    if (start && end && end > start) {
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        daysEl.textContent = diffDays + ' dia(s)';
        const total = diffDays * dailyPrice;
        totalEl.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    } else {
        daysEl.textContent = 'Selecione as datas';
        totalEl.textContent = 'R$ 0,00';
    }
}

startInput.addEventListener('change', function() {
    const start = new Date(this.value);
    const minEnd = new Date(start);
    minEnd.setDate(minEnd.getDate() + minDays);
    endInput.min = minEnd.toISOString().split('T')[0];
    endInput.value = '';
    updateSummary();
});

endInput.addEventListener('change', updateSummary);
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
