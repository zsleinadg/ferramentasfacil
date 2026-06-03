<?php $title = $title ?? 'Categorias - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Categorias</h1>
    <a href="/admin/categorias/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nova Categoria
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <p class="text-muted mb-0">Nenhuma categoria cadastrada.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Slug</th>
                            <th>Ferramentas</th>
                            <th>Ordem</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td>
                                    <?php if ($cat['iconclass']): ?>
                                        <i class="<?= $cat['iconclass'] ?>"></i>
                                    <?php endif; ?>
                                    <?= $cat['categoryname'] ?>
                                </td>
                                <td><code><?= $cat['slug'] ?></code></td>
                                <td><span class="badge bg-secondary"><?= $cat['toolcount'] ?? 0 ?></span></td>
                                <td><?= $cat['sortorder'] ?></td>
                                <td>
                                    <?php if ($cat['isactive']): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/categorias/<?= $cat['categoryid'] ?>/editar" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/admin/categorias/<?= $cat['categoryid'] ?>" class="d-inline"
                                          onsubmit="return confirm('Excluir esta categoria?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
