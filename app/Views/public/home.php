<?php $title = $title ?? 'FerramentasFácil - Locação de Ferramentas'; ?>
<?php ob_start(); ?>

<section class="hero text-light text-center d-flex align-items-center" style="min-height: 85vh;">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Alugue Ferramentas para Sua Obra</h1>
        <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 600px;">
            Equipamentos de qualidade para construção civil. Aluguel fácil, rápido e sem burocracia.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/catalogo" class="btn btn-primary btn-lg px-5">Ver Catálogo</a>
            <a href="/cadastro" class="btn btn-outline-light btn-lg px-5">Criar Conta</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Como Funciona</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-search fs-2"></i>
                </div>
                <h5>1. Escolha a Ferramenta</h5>
                <p class="text-muted">Navegue pelo catálogo e encontre a ferramenta ideal para seu projeto.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-calendar-check fs-2"></i>
                </div>
                <h5>2. Defina o Período</h5>
                <p class="text-muted">Escolha as datas de retirada e devolução. Veja o valor na hora.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                    <i class="bi bi-hand-thumbs-up fs-2"></i>
                </div>
                <h5>3. Retire e Use</h5>
                <p class="text-muted">Retire sua ferramenta e use à vontade. Devolva no prazo combinado.</p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($featuredTools)): ?>
<section class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Ferramentas em Destaque</h2>
            <a href="/catalogo" class="btn btn-outline-primary">Ver Todas</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredTools as $tool): ?>
                <div class="col-md-3 col-6">
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
                            <div class="mt-auto">
                                <strong class="text-primary fs-5">R$ <?= number_format((float) $tool['dailyprice'], 2, ',', '.') ?></strong>
                                <small class="text-muted">/dia</small>
                            </div>
                            <a href="/ferramenta/<?= $tool['slug'] ?>" class="btn btn-sm btn-outline-primary mt-2 w-100">Alugar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($categories)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Categorias</h2>
        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-3 col-6">
                    <a href="/catalogo?categoria=<?= $cat['slug'] ?>" class="text-decoration-none">
                        <div class="card border-0 bg-light text-center py-4 h-100">
                            <div class="card-body">
                                <?php if ($cat['iconclass']): ?>
                                    <i class="<?= $cat['iconclass'] ?> fs-1 text-primary mb-2 d-block"></i>
                                <?php else: ?>
                                    <i class="bi bi-tools fs-1 text-primary mb-2 d-block"></i>
                                <?php endif; ?>
                                <h6 class="mb-0 text-dark"><?= $cat['categoryname'] ?></h6>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="bg-dark text-light py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">Pronto para começar?</h2>
        <p class="text-white-50 mb-4">Cadastre-se agora e faça sua primeira locação!</p>
        <a href="/cadastro" class="btn btn-primary btn-lg px-5">Criar Conta Gratuita</a>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Perguntas Frequentes</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="accordion" id="faq">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq1">Preciso de garantia para alugar?</button></h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faq">
                            <div class="accordion-body">Sim, é necessário deixar um depósito caução que será devolvido na devolução da ferramenta em boas condições.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">Qual o prazo mínimo de locação?</button></h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faq">
                            <div class="accordion-body">O prazo mínimo é de 1 dia, podendo variar conforme a ferramenta.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">O que acontece se devolver atrasado?</button></h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faq">
                            <div class="accordion-body">Será cobrada uma multa por dia de atraso, conforme a política definida no momento da locação.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>
