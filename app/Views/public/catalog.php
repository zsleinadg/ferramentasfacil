<?php $title = $title ?? 'Catálogo - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold">Catálogo de Ferramentas</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filtros</h5>
                    <form method="GET" action="/catalogo">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoria</label>
                            <select class="form-select" name="categoria" onchange="this.form.submit()">
                                <option value="">Todas as categorias</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['slug'] ?>" <?= $selectedCategory === $cat['slug'] ? 'selected' : '' ?>>
                                        <?= $cat['categoryname'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Busca</label>
                            <input type="text" class="form-control" name="search" placeholder="Buscar ferramenta..." value="<?= $search ?>">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        <?php if ($search || $selectedCategory): ?>
                            <a href="/catalogo" class="btn btn-outline-secondary w-100 mt-2">Limpar Filtros</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?php if (empty($tools)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Nenhuma ferramenta encontrada</h4>
                    <p class="text-muted">Tente ajustar os filtros ou buscar por outro termo.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($tools as $tool): ?>
                        <div class="col-md-4 col-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="ratio ratio-4x3 bg-light">
                                    <?php if ($tool['coverimageurl']): ?>
                                        <img src="<?= imageUrl($tool['coverimageurl']) ?>" alt="<?= $tool['toolname'] ?>" class="card-img-top object-fit-cover">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-image text-muted fs-1"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted"><?= $tool['categoryname'] ?></small>
                                    <h6 class="card-title mt-1"><?= $tool['toolname'] ?></h6>
                                    <?php if ($tool['brand']): ?>
                                        <small class="text-muted"><?= $tool['brand'] ?><?= $tool['model'] ? ' - ' . $tool['model'] : '' ?></small>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div>
                                            <strong class="text-primary fs-5">R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?></strong>
                                            <small class="text-muted">/dia</small>
                                        </div>
                                        <?php if ($tool['availablestock'] > 0): ?>
                                            <span class="badge bg-success">Disponível</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Indisponível</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="/ferramenta/<?= $tool['slug'] ?>" class="btn btn-outline-primary btn-sm mt-2 w-100">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($pagination['lastPage'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $pagination['lastPage']; $i++): ?>
                                <li class="page-item <?= $i === $pagination['page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>&categoria=<?= $selectedCategory ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
