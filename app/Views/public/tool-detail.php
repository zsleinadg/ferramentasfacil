<?php $title = $title ?? $tool['toolname'] . ' - FerramentasFácil'; ?>
<?php ob_start(); ?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/catalogo">Catálogo</a></li>
            <li class="breadcrumb-item"><a href="/catalogo?categoria=<?= $tool['categoryslug'] ?>"><?= $tool['categoryname'] ?></a></li>
            <li class="breadcrumb-item active"><?= $tool['toolname'] ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="ratio ratio-4x3 bg-light rounded overflow-hidden">
                <?php if ($tool['coverimageurl']): ?>
                    <img src="<?= imageUrl($tool['coverimageurl']) ?>" alt="<?= $tool['toolname'] ?>" class="object-fit-cover">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light">
                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($gallery)): ?>
                <div class="d-flex gap-2 mt-3 overflow-auto">
                    <?php foreach ($gallery as $img): ?>
                        <img src="<?= imageUrl($img['imageurl']) ?>" alt="<?= $img['alttext'] ?? '' ?>"
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                             class="rounded border" onclick="document.querySelector('.ratio img')?.src=this.src">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold mb-2"><?= $tool['toolname'] ?></h1>
            <?php if ($tool['brand']): ?>
                <p class="text-muted mb-2">Marca: <strong><?= $tool['brand'] ?></strong><?= $tool['model'] ? ' | Modelo: ' . $tool['model'] : '' ?></p>
            <?php endif; ?>

            <div class="mb-3">
                <span class="badge bg-secondary"><?= $tool['categoryname'] ?></span>
                <?php if ($tool['isfeatured']): ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Destaque</span>
                <?php endif; ?>
            </div>

            <div class="bg-light rounded p-4 mb-4">
                <div class="row text-center">
                    <div class="col-4 border-end">
                        <small class="text-muted d-block">Preço</small>
                        <strong class="text-primary fs-4">R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?></strong>
                        <small class="text-muted">/dia</small>
                    </div>
                    <div class="col-4 border-end">
                        <small class="text-muted d-block">Caução</small>
                        <strong class="text-dark fs-4">R$ <?= number_format((float) $tool['depositamount'], 2, ',', '.') ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Disponibilidade</small>
                        <?php if ($tool['availablestock'] > 0): ?>
                            <strong class="text-success fs-4"><?= $tool['availablestock'] ?></strong>
                            <small class="text-muted">em estoque</small>
                        <?php else: ?>
                            <strong class="text-danger fs-4">0</strong>
                            <small class="text-muted">indisponível</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5>Descrição</h5>
                <p class="text-muted"><?= nl2br($tool['description']) ?></p>
            </div>

            <div class="d-flex gap-2 mb-3">
                <div class="text-muted small">
                    <i class="bi bi-calendar"></i> Mín. <?= $tool['minrentaldays'] ?> dia(s)
                </div>
                <div class="text-muted small">
                    <i class="bi bi-calendar"></i> Máx. <?= $tool['maxrentaldays'] ?> dia(s)
                </div>
                <div class="text-muted small">
                    <i class="bi bi-eye"></i> <?= $tool['viewcount'] ?> visualizações
                </div>
            </div>

            <?php if ($tool['availablestock'] > 0): ?>
                <?php if (isset($_SESSION['userId'])): ?>
                    <a href="/cliente/alugar/<?= $tool['toolid'] ?>" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-cart-check"></i> Alugar Agora
                    </a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Faça Login para Alugar
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg w-100" disabled>Indisponível no Momento</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
