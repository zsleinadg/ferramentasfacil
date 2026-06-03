<?php $title = $title ?? 'Detalhe do Usuário'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><?= $user['name'] ?></h1>
    <a href="/admin/usuarios" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Informações</h5></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">ID</td>
                        <td><?= $user['userid'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nome</td>
                        <td><?= $user['name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">E-mail</td>
                        <td><?= $user['email'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telefone</td>
                        <td><?= $user['phone'] ?? '---' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">CPF</td>
                        <td><?= $user['cpf'] ?? '---' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Role</td>
                        <td><span class="badge bg-info"><?= $user['displayname'] ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <?php if ($user['isactive']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Cadastro</td>
                        <td><?= date('d/m/Y H:i', strtotime($user['createdat'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Último Login</td>
                        <td><?= $user['lastloginat'] ? date('d/m/Y H:i', strtotime($user['lastloginat'])) : '---' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Alterar Role</h5></div>
            <div class="card-body">
                <?php if ($user['userid'] === $_SESSION['userId']): ?>
                    <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> Você não pode alterar seu próprio role.</p>
                <?php else: ?>
                    <form method="POST" action="/admin/usuarios/<?= $user['userid'] ?>/role">
                        <?= csrfField() ?>
                        <div class="mb-2">
                            <select name="roleId" class="form-select">
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['roleid'] ?>" <?= $role['roleid'] === $user['roleid'] ? 'selected' : '' ?>>
                                        <?= $role['displayname'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Salvar</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Ações</h5></div>
            <div class="card-body">
                <?php if ($user['userid'] !== $_SESSION['userId']): ?>
                    <form method="POST" action="/admin/usuarios/<?= $user['userid'] ?>/toggle" onsubmit="return confirm('Tem certeza?')">
                        <?= csrfField() ?>
                        <?php if ($user['isactive']): ?>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-person-x"></i> Desativar Conta
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-person-check"></i> Reativar Conta
                            </button>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> Você não pode desativar sua própria conta.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Histórico de Locações</h5>
            </div>
            <div class="card-body">
                <?php if (empty($rentals['data'])): ?>
                    <p class="text-muted mb-0">Este usuário ainda não realizou locações.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Ferramenta</th>
                                    <th>Período</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rentals['data'] as $r): ?>
                                    <tr>
                                        <td><strong><?= $r['rentalcode'] ?></strong></td>
                                        <td><?= $r['toolname'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($r['startdate'])) ?> - <?= date('d/m/Y', strtotime($r['expectedenddate'])) ?></td>
                                        <td>R$ <?= number_format((float) $r['totalamount'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php $classes = ['pending' => 'warning', 'active' => 'primary', 'returned' => 'success', 'overdue' => 'danger', 'cancelled' => 'secondary']; ?>
                                            <span class="badge bg-<?= $classes[$r['status']] ?? 'secondary' ?>">
                                                <?= $r['status'] ?>
                                            </span>
                                        </td>
                                        <td><a href="/admin/locacoes/<?= $r['rentalid'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($rentals['lastPage'] > 1): ?>
                        <nav>
                            <ul class="pagination pagination-sm justify-content-center">
                                <?php for ($i = 1; $i <= $rentals['lastPage']; $i++): ?>
                                    <li class="page-item <?= $i === $rentals['page'] ? 'active' : '' ?>">
                                        <a class="page-link" href="/admin/usuarios/<?= $user['userid'] ?>?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
