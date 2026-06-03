<?php $title = $title ?? 'Relatórios - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Relatórios</h1>
    <div>
        <button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label">Período Predefinido</label>
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="week" <?= $period === 'week' ? 'selected' : '' ?>>Última Semana</option>
                    <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>Último Mês</option>
                    <option value="quarter" <?= $period === 'quarter' ? 'selected' : '' ?>>Último Trimestre</option>
                    <option value="year" <?= $period === 'year' ? 'selected' : '' ?>>Último Ano</option>
                    <option value="custom" <?= $period === 'custom' ? 'selected' : '' ?>>Personalizado</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label">De</label>
                <input type="date" name="startDate" class="form-control" value="<?= $startDate ?>">
            </div>
            <div class="col-auto">
                <label class="form-label">Até</label>
                <input type="date" name="endDate" class="form-control" value="<?= $endDate ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Total Locações</h6>
                <h2 class="card-text text-primary mt-2"><?= $totalRentals ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Receita Total</h6>
                <h2 class="card-text text-success mt-2">R$ <?= number_format($totalRevenue, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Multas</h6>
                <h2 class="card-text text-danger mt-2">R$ <?= number_format($totalFines, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Concluídas</h6>
                <h2 class="card-text text-info mt-2"><?= $statusCount['returned'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Locações do Período</h5></div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Ferramenta</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rentals)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">Nenhuma locação no período.</td></tr>
                            <?php else: ?>
                                <?php foreach ($rentals as $r): ?>
                                    <tr>
                                        <td><a href="/admin/locacoes/<?= $r['rentalid'] ?>"><?= $r['rentalcode'] ?></a></td>
                                        <td><?= $r['username'] ?></td>
                                        <td><?= $r['toolname'] ?></td>
                                        <td>R$ <?= number_format((float) $r['totalamount'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php $classes = ['pending' => 'warning', 'active' => 'primary', 'returned' => 'success', 'overdue' => 'danger', 'cancelled' => 'secondary']; ?>
                                            <span class="badge bg-<?= $classes[$r['status']] ?? 'secondary' ?>"><?= $r['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Ferramentas Mais Locadas</h5></div>
            <div class="card-body">
                <?php if (empty($topTools)): ?>
                    <p class="text-muted mb-0">Nenhum dado disponível.</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ferramenta</th>
                                <th class="text-end">Locações</th>
                                <th class="text-end">Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topTools as $t): ?>
                                <tr>
                                    <td><?= $t['toolname'] ?></td>
                                    <td class="text-end"><?= $t['totallocs'] ?></td>
                                    <td class="text-end">R$ <?= number_format((float) $t['receita'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0">Status das Locações</h5></div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pendente', 'Ativa', 'Devolvida', 'Atrasada', 'Cancelada'],
        datasets: [{
            data: [<?= $statusCount['pending'] ?>, <?= $statusCount['active'] ?>, <?= $statusCount['returned'] ?>, <?= $statusCount['overdue'] ?>, <?= $statusCount['cancelled'] ?>],
            backgroundColor: ['#ffc107', '#0d6efd', '#198754', '#dc3545', '#6c757d']
        }]
    }
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
