<?php $title = $title ?? 'Detalhe da Locação'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Locação <?= $rental['rentalcode'] ?></h1>
    <div>
        <a href="/admin/locacoes" class="btn btn-outline-secondary">Voltar</a>
        <?php if ($rental['status'] === 'pending'): ?>
            <form method="POST" action="/admin/locacoes/<?= $rental['rentalid'] ?>/confirmar" class="d-inline">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Confirmar Locação
                </button>
            </form>
        <?php endif; ?>
        <?php if ($rental['status'] === 'active'): ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#returnModal">
                <i class="bi bi-arrow-return-left"></i> Registrar Devolução
            </button>
        <?php endif; ?>
        <?php if (in_array($rental['status'], ['pending', 'active'])): ?>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                <i class="bi bi-x-lg"></i> Cancelar
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Informações da Locação</h5></div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Código</small>
                        <strong><?= $rental['rentalcode'] ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Data da Locação</small>
                        <strong><?= date('d/m/Y H:i', strtotime($rental['createdat'])) ?></strong>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Início</small>
                        <strong><?= date('d/m/Y', strtotime($rental['startdate'])) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Prevista Devolução</small>
                        <strong><?= date('d/m/Y', strtotime($rental['expectedenddate'])) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Devolução Efetiva</small>
                        <strong><?= $rental['actualenddate'] ? date('d/m/Y', strtotime($rental['actualenddate'])) : '---' ?></strong>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Dias</small>
                        <strong><?= $rental['rentaldays'] ?> dia(s)</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status</small>
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
                    <div class="col-md-4">
                        <small class="text-muted d-block">Pagamento</small>
                        <?php $paymentClasses = ['pending' => 'warning', 'paid' => 'success', 'refunded' => 'info']; ?>
                        <span class="badge bg-<?= $paymentClasses[$rental['paymentstatus']] ?? 'secondary' ?> fs-6">
                            <?= match ($rental['paymentstatus']) {
                                'pending' => 'Pendente',
                                'paid' => 'Pago',
                                'refunded' => 'Reembolsado',
                                default => $rental['paymentstatus']
                            } ?>
                        </span>
                    </div>
                </div>

                <?php if ($rental['notes']): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Observações</small>
                        <p class="mb-0"><?= nl2br($rental['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Histórico de Status</h5></div>
            <div class="card-body">
                <?php if (empty($history)): ?>
                    <p class="text-muted mb-0">Nenhum registro de alteração.</p>
                <?php else: ?>
                    <ul class="timeline">
                        <?php foreach ($history as $h): ?>
                            <li class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        <?= match ($h['newstatus']) {
                                            'pending' => 'Solicitada',
                                            'active' => 'Ativada',
                                            'returned' => 'Devolvida',
                                            'overdue' => 'Atrasada',
                                            'cancelled' => 'Cancelada',
                                            default => $h['newstatus']
                                        } ?>
                                    </strong>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($h['createdat'])) ?></small>
                                </div>
                                <small class="text-muted">
                                    Por: <?= $h['changedbyname'] ?>
                                    <?php if ($h['changereason']): ?> - <?= $h['changereason'] ?><?php endif; ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Cliente</h5></div>
            <div class="card-body">
                <p class="mb-1"><strong><?= $rental['username'] ?></strong></p>
                <p class="mb-1"><small class="text-muted"><?= $rental['useremail'] ?></small></p>
                <?php if ($rental['userphone']): ?>
                    <p class="mb-0"><small class="text-muted"><?= $rental['userphone'] ?></small></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Ferramenta</h5></div>
            <div class="card-body">
                <p class="mb-1"><strong><?= $rental['toolname'] ?></strong></p>
                <p class="mb-0"><small class="text-muted"><?= $rental['categoryname'] ?></small></p>
                <?php if ($rental['coverimageurl']): ?>
                    <img src="<?= imageUrl($rental['coverimageurl']) ?>" class="img-fluid rounded mt-2" alt="<?= $rental['toolname'] ?>">
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Financeiro</h5></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Valor Diária</td>
                        <td class="text-end">R$ <?= number_format((float) $rental['dailyprice'], 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Dias</td>
                        <td class="text-end"><?= $rental['rentaldays'] ?></td>
                    </tr>
                    <tr>
                        <td>Valor Total</td>
                        <td class="text-end fw-bold">R$ <?= number_format((float) $rental['totalamount'], 2, ',', '.') ?></td>
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
    </div>
</div>

<?php if ($rental['status'] === 'active'): ?>
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/locacoes/<?= $rental['rentalid'] ?>/devolver">
                <?= csrfField() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Devolução</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="actualEndDate" class="form-label">Data de Devolução</label>
                        <input type="date" name="actualEndDate" id="actualEndDate" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fineAmount" class="form-label">Multa por Atraso (R$)</label>
                        <input type="number" name="fineAmount" id="fineAmount" class="form-control"
                               step="0.01" min="0" value="0">
                        <small class="text-muted">50% do valor da diária por dia de atraso.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Devolução</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (in_array($rental['status'], ['pending', 'active'])): ?>
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/locacoes/<?= $rental['rentalid'] ?>/cancelar">
                <?= csrfField() ?>
                <input type="hidden" name="_method" value="DELETE">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Cancelar Locação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja cancelar a locação <strong><?= $rental['rentalcode'] ?></strong>?</p>
                    <p class="text-muted mb-0">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">Sim, Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
