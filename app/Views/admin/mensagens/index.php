<?php $title = $title ?? 'Mensagens de Contato - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Mensagens de Contato</h1>
    <span class="text-muted">Total: <?= $pagination['total'] ?></span>
</div>

<?php if (empty($messages)): ?>
    <div class="text-center py-5">
        <i class="bi bi-envelope-open text-muted" style="font-size: 3rem;"></i>
        <h4 class="mt-3">Nenhuma mensagem recebida</h4>
        <p class="text-muted">As mensagens enviadas pelo formulário de contato aparecerão aqui.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 40px;"></th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Assunto</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr class="<?= !$msg['isread'] ? 'fw-bold' : '' ?>">
                        <td>
                            <?php if (!$msg['isread']): ?>
                                <span class="badge bg-primary rounded-pill" title="Não lida">Novo</span>
                            <?php else: ?>
                                <span class="text-muted small"><i class="bi bi-check2-all"></i> Lida</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($msg['email']) ?></a></td>
                        <td><?= htmlspecialchars($msg['subject'] ?: '—') ?></td>
                        <td style="max-width: 300px;">
                            <div class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($msg['message']) ?>">
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <small><?= date('d/m/Y H:i', strtotime($msg['createdat'])) ?></small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#msgModal<?= $msg['messageid'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <?php if (!$msg['isread']): ?>
                                <form method="POST" action="/admin/mensagens/<?= $msg['messageid'] ?>/read" class="d-inline">
                                    <button class="btn btn-sm btn-outline-success" title="Marcar como lida">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="msgModal<?= $msg['messageid'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Mensagem de <?= htmlspecialchars($msg['name']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nome:</strong> <?= htmlspecialchars($msg['name']) ?></p>
                                    <p><strong>E-mail:</strong> <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></p>
                                    <?php if ($msg['subject']): ?>
                                        <p><strong>Assunto:</strong> <?= htmlspecialchars($msg['subject']) ?></p>
                                    <?php endif; ?>
                                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($msg['createdat'])) ?></p>
                                    <hr>
                                    <p><strong>Mensagem:</strong></p>
                                    <p class="text-muted" style="white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <?php if (!$msg['isread']): ?>
                                        <form method="POST" action="/admin/mensagens/<?= $msg['messageid'] ?>/read" class="d-inline">
                                            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Marcar como Lida</button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['lastPage'] > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pagination['lastPage']; $i++): ?>
                    <li class="page-item <?= $i === $pagination['page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>