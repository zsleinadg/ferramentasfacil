<?php $title = $title ?? 'Usuários - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Usuários</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="search" name="search" class="form-control" placeholder="Buscar por nome ou e-mail..." value="<?= $search ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                <?php if ($search): ?>
                    <a href="/admin/usuarios" class="btn btn-outline-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users['data'])): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Nenhum usuário encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users['data'] as $u): ?>
                            <tr>
                                <td><?= $u['userid'] ?></td>
                                <td><?= $u['name'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><span class="badge bg-info"><?= $u['displayname'] ?></span></td>
                                <td>
                                    <?php if ($u['isactive']): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($u['createdat'])) ?></td>
                                <td>
                                    <a href="/admin/usuarios/<?= $u['userid'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($users['lastPage'] > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $users['lastPage']; $i++): ?>
                        <li class="page-item <?= $i === $users['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="/admin/usuarios?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
