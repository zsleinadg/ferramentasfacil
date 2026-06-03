<?php $title = $title ?? 'Minhas Locações - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <h1 class="mb-4">Minhas Locações</h1>

    <?php if (empty($rentals['data'])): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-3">Você ainda não possui locações.</p>
                <a href="/catalogo" class="btn btn-primary">
                    <i class="bi bi-search"></i> Ver Catálogo
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($rentals['data'] as $r): ?>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="row g-0 h-100">
                            <div class="col-md-4">
                                <div class="ratio ratio-4x3 h-100 bg-light rounded-start">
                                    <?php if ($r['coverimageurl']): ?>
                                        <img src="<?= imageUrl($r['coverimageurl']) ?>" alt="<?= $r['toolname'] ?>" class="object-fit-cover">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0"><?= $r['toolname'] ?></h6>
                                        <small class="text-muted"><?= $r['rentalcode'] ?></small>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar"></i>
                                        <?= date('d/m/Y', strtotime($r['startdate'])) ?> -
                                        <?= date('d/m/Y', strtotime($r['expectedenddate'])) ?>
                                        <br>
                                        <i class="bi bi-clock"></i> <?= $r['rentaldays'] ?> dia(s)
                                    </p>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div>
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
                                            <strong class="ms-2">R$ <?= number_format((float) $r['totalamount'], 2, ',', '.') ?></strong>
                                        </div>
                                        <a href="/cliente/locacoes/<?= $r['rentalid'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($rentals['lastPage'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $rentals['lastPage']; $i++): ?>
                        <li class="page-item <?= $i === $rentals['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="/cliente/locacoes?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
