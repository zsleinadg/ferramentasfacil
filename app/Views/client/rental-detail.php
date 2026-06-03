<?php $title = $title ?? 'Detalhe da Locação'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/cliente/locacoes">Minhas Locações</a></li>
            <li class="breadcrumb-item active"><?= $rental['rentalcode'] ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Locação <?= $rental['rentalcode'] ?></h5>
                    <?php $statusClasses = ['pending' => 'warning', 'active' => 'primary', 'returned' => 'success', 'overdue' => 'danger', 'cancelled' => 'secondary']; ?>
                    <span class="badge bg-<?= $statusClasses[$rental['status']] ?? 'secondary' ?> fs-6">
                        <?= match ($rental['status']) {
                            'pending' => 'Pendente',
                            'active' => 'Ativa',
                            'returned' => 'Devolvida',
                            'overdue' => 'Atrasada',
                            'cancelled' => 'Cancelada',
                            default => $rental['status']
                        } ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Data de Retirada</small>
                            <strong><?= date('d/m/Y', strtotime($rental['startdate'])) ?></strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Prevista Devolução</small>
                            <strong><?= date('d/m/Y', strtotime($rental['expectedenddate'])) ?></strong>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Devolução Efetiva</small>
                            <strong><?= $rental['actualenddate'] ? date('d/m/Y', strtotime($rental['actualenddate'])) : '---' ?></strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Dias Locados</small>
                            <strong><?= $rental['rentaldays'] ?> dia(s)</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Status do Pagamento</small>
                            <?php $paymentClasses = ['pending' => 'warning', 'paid' => 'success', 'refunded' => 'info']; ?>
                            <span class="badge bg-<?= $paymentClasses[$rental['paymentstatus']] ?? 'secondary' ?>">
                                <?= match ($rental['paymentstatus']) {
                                    'pending' => 'Pendente',
                                    'paid' => 'Pago',
                                    'refunded' => 'Reembolsado',
                                    default => $rental['paymentstatus']
                                } ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Ferramenta</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?php if ($rental['coverimageurl']): ?>
                                <img src="<?= imageUrl($rental['coverimageurl']) ?>" alt="<?= $rental['toolname'] ?>" class="img-fluid rounded">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <h5><?= $rental['toolname'] ?></h5>
                            <p class="text-muted mb-0"><?= $rental['categoryname'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Histórico da Locação</h5></div>
                <div class="card-body">
                    <?php if (empty($history)): ?>
                        <p class="text-muted mb-0">Nenhum registro disponível.</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($history as $h): ?>
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <strong>
                                            <?= match ($h['newstatus']) {
                                                'pending' => 'Solicitada',
                                                'active' => 'Confirmada',
                                                'returned' => 'Devolvida',
                                                'overdue' => 'Atrasada',
                                                'cancelled' => 'Cancelada',
                                                default => $h['newstatus']
                                            } ?>
                                        </strong>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($h['createdat'])) ?></small>
                                    </div>
                                    <?php if ($h['changereason']): ?>
                                        <small class="text-muted"><?= $h['changereason'] ?></small>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Financeiro</h5></div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Valor da Diária</td>
                            <td class="text-end">R$ <?= number_format((float) $rental['dailyprice'], 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Período</td>
                            <td class="text-end"><?= $rental['rentaldays'] ?> dia(s)</td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end text-primary">R$ <?= number_format((float) $rental['totalamount'], 2, ',', '.') ?></td>
                        </tr>
                        <?php if ((float) $rental['depositamount'] > 0): ?>
                            <tr>
                                <td>Caução</td>
                                <td class="text-end">R$ <?= number_format((float) $rental['depositamount'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ((float) $rental['fineamount'] > 0): ?>
                            <tr class="text-danger">
                                <td>Multa</td>
                                <td class="text-end">R$ <?= number_format((float) $rental['fineamount'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Ações</h5></div>
                <div class="card-body">
                    <a href="/catalogo" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-search"></i> Ver Catálogo
                    </a>
                    <a href="/cliente/locacoes" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
