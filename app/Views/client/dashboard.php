<?php $title = $title ?? 'Meu Painel - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                    <h5><?= $user['name'] ?></h5>
                    <p class="text-muted mb-1"><?= $user['email'] ?></p>
                    <a href="/cliente/perfil" class="btn btn-outline-primary btn-sm">Editar Perfil</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Minhas Locações</h5>
                    <a href="/cliente/locacoes" class="btn btn-outline-primary btn-sm">Ver Todas</a>
                </div>
                <div class="card-body">
                    <?php if (empty($rentals['data'])): ?>
                        <p class="text-muted mb-0">Você ainda não possui locações.</p>
                    <?php else: ?>
                        <?php foreach ($rentals['data'] as $r): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <strong><?= $r['toolname'] ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($r['startdate'])) ?> - <?= date('d/m/Y', strtotime($r['expectedenddate'])) ?>
                                    </small>
                                </div>
                                <div class="text-end">
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
                                    <br>
                                    <small class="text-muted">R$ <?= number_format((float) $r['totalamount'], 2, ',', '.') ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <a href="/catalogo" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Ver Catálogo
                    </a>
                    <a href="/cliente/locacoes" class="btn btn-outline-secondary">
                        <i class="bi bi-clock-history"></i> Histórico
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
