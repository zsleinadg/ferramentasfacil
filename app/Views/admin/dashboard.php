<?php $title = $title ?? 'Dashboard - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Dashboard</h1>
    <span class="text-muted">Bem-vindo(a), <?= $_SESSION['userName'] ?? 'Admin' ?></span>
</div>

<?php if ($overdueRentals > 0): ?>
    <div class="alert alert-danger d-flex justify-content-between align-items-center">
        <span><i class="bi bi-exclamation-triangle-fill"></i> <strong><?= $overdueRentals ?></strong> locação(ões) em atraso!</span>
        <a href="/admin/locacoes?status=overdue" class="btn btn-danger btn-sm">Ver Atrasadas</a>
    </div>
<?php endif; ?>

<?php if ($pendingRentals > 0): ?>
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock"></i> <strong><?= $pendingRentals ?></strong> locação(ões) pendente(s) de confirmação.</span>
        <a href="/admin/locacoes?status=pending" class="btn btn-warning btn-sm">Ver Pendentes</a>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Clientes</h6>
                <h2 class="card-text text-primary mt-2"><?= $clientCount ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Ferramentas</h6>
                <h2 class="card-text text-success mt-2"><?= $toolCount ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Locações Ativas</h6>
                <a href="/admin/locacoes?status=active" class="text-decoration-none">
                    <h2 class="card-text text-warning mt-2"><?= $activeRentals ?></h2>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">Receita do Mês</h6>
                <h2 class="card-text text-info mt-2">R$ <?= number_format((float) $monthlyRevenue, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Receita Mensal (últimos 12 meses)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ferramentas Mais Alugadas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($topTools)): ?>
                    <p class="text-muted mb-0">Nenhuma locação realizada ainda.</p>
                <?php else: ?>
                    <table class="table table-sm mb-0">
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
                                    <td class="text-end"><?= $t['rentalcount'] ?></td>
                                    <td class="text-end">R$ <?= number_format((float) $t['revenue'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const months = <?= json_encode(array_map(fn($d) => $d['month'], $monthlyData)) ?>;
const revenues = <?= json_encode(array_map(fn($d) => (float) $d['revenue'], $monthlyData)) ?>;
const counts = <?= json_encode(array_map(fn($d) => (int) $d['total'], $monthlyData)) ?>;

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Receita (R$)',
                data: revenues,
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                yAxisID: 'y',
            },
            {
                label: 'Locações',
                data: counts,
                backgroundColor: 'rgba(25, 135, 84, 0.5)',
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                title: { display: true, text: 'Receita (R$)' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
                title: { display: true, text: 'Locações' }
            }
        }
    }
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
