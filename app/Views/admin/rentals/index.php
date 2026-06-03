<?php $title = $title ?? 'Locações - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Locações</h1>
    <a href="/admin/locacoes/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nova Locação
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="btn-group mb-3">
            <a href="/admin/locacoes" class="btn btn-outline-secondary <?= !$currentStatus ? 'active' : '' ?>">Todas</a>
            <a href="/admin/locacoes?status=pending" class="btn btn-outline-warning <?= $currentStatus === 'pending' ? 'active' : '' ?>">Pendentes</a>
            <a href="/admin/locacoes?status=active" class="btn btn-outline-primary <?= $currentStatus === 'active' ? 'active' : '' ?>">Ativas</a>
            <a href="/admin/locacoes?status=overdue" class="btn btn-outline-danger <?= $currentStatus === 'overdue' ? 'active' : '' ?>">Atrasadas</a>
            <a href="/admin/locacoes?status=returned" class="btn btn-outline-success <?= $currentStatus === 'returned' ? 'active' : '' ?>">Devolvidas</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Ferramenta</th>
                        <th>Período</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rentals['data'])): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Nenhuma locação encontrada.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rentals['data'] as $r): ?>
                            <tr>
                                <td><strong><?= $r['rentalcode'] ?></strong></td>
                                <td><?= $r['username'] ?><br><small class="text-muted"><?= $r['useremail'] ?></small></td>
                                <td><?= $r['toolname'] ?></td>
                                <td><?= date('d/m/Y', strtotime($r['startdate'])) ?> - <?= date('d/m/Y', strtotime($r['expectedenddate'])) ?><br><small class="text-muted"><?= $r['rentaldays'] ?> dia(s)</small></td>
                                <td>R$ <?= number_format((float) $r['totalamount'], 2, ',', '.') ?></td>
                                <td>
                                    <?php $statusClasses = ['pending' => 'warning', 'active' => 'primary', 'returned' => 'success', 'overdue' => 'danger', 'cancelled' => 'secondary']; ?>
                                    <span class="badge bg-<?= $statusClasses[$r['status']] ?? 'secondary' ?>">
                                        <?= match ($r['status']) {
                                            'pending' => 'Pendente',
                                            'active' => 'Ativa',
                                            'returned' => 'Devolvida',
                                            'overdue' => 'Atrasada',
                                            'cancelled' => 'Cancelada',
                                            default => $r['status']
                                        } ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $paymentClasses = ['pending' => 'warning', 'paid' => 'success', 'refunded' => 'info']; ?>
                                    <span class="badge bg-<?= $paymentClasses[$r['paymentstatus']] ?? 'secondary' ?>">
                                        <?= match ($r['paymentstatus']) {
                                            'pending' => 'Pendente',
                                            'paid' => 'Pago',
                                            'refunded' => 'Reembolsado',
                                            default => $r['paymentstatus']
                                        } ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/locacoes/<?= $r['rentalid'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($rentals['lastPage'] > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $rentals['lastPage']; $i++): ?>
                        <li class="page-item <?= $i === $rentals['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="/admin/locacoes?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
