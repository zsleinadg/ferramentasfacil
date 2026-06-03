<?php $title = $title ?? 'Ferramentas - Administrador'; ?>
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Ferramentas</h1>
    <a href="/admin/ferramentas/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nova Ferramenta
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/ferramentas" class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nome..."
                       value="<?= $search ?? '' ?>">
            </div>
            <div class="col-md-4">
                <select class="form-select" name="category_id">
                    <option value="">Todas as categorias</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['categoryid'] ?>"
                            <?= ($selectedCategory == $cat['categoryid']) ? 'selected' : '' ?>>
                            <?= $cat['categoryname'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($tools)): ?>
            <p class="text-muted mb-0">Nenhuma ferramenta encontrada.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço/Dia</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tools as $tool): ?>
                            <tr>
                                <td>
                                    <?php if ($tool['coverimageurl']): ?>
                                        <img src="<?= imageUrl($tool['coverimageurl']) ?>" alt="<?= $tool['toolname'] ?>"
                                             style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= $tool['toolname'] ?></strong></td>
                                <td><?= $tool['categoryname'] ?? '-' ?></td>
                                <td>R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= $tool['availablestock'] > 0 ? 'success' : 'danger' ?>">
                                        <?= $tool['availablestock'] ?>/<?= $tool['totalstock'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'available' => ['bg-success', 'Disponível'],
                                        'rented' => ['bg-warning', 'Alugado'],
                                        'maintenance' => ['bg-secondary', 'Manutenção'],
                                        'inactive' => ['bg-danger', 'Inativo'],
                                    ];
                                    $label = $statusLabels[$tool['status']] ?? ['bg-secondary', $tool['status']];
                                    ?>
                                    <span class="badge <?= $label[0] ?>"><?= $label[1] ?></span>
                                </td>
                                <td>
                                    <a href="/admin/ferramentas/<?= $tool['toolid'] ?>/editar" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/admin/ferramentas/<?= $tool['toolid'] ?>" class="d-inline"
                                          onsubmit="return confirm('Excluir esta ferramenta?')">
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

            <?php if ($pagination['lastPage'] > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <?php for ($i = 1; $i <= $pagination['lastPage']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?? '' ?>&category_id=<?= $selectedCategory ?? '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/admin/layouts/admin.php'); ?>
