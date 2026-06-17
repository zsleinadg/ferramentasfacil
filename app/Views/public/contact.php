<?php $title = $title ?? 'Contato - FerramentasFácil'; ?>
<?php ob_start(); ?>

<section class="bg-dark text-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Contato</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 700px;">
            Tem alguma dúvida ou quer fazer um orçamento? Envie sua mensagem e responderemos em breve.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-7">
                <h3 class="fw-bold mb-4">Envie sua Mensagem</h3>
                <form method="POST" action="/contato" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required
                                   value="<?= $_SESSION['_flash']['fields']['name'] ?? '' ?>"
                                   placeholder="Seu nome completo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required
                                   value="<?= $_SESSION['_flash']['fields']['email'] ?? '' ?>"
                                   placeholder="seu@email.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Assunto</label>
                            <input type="text" class="form-control" name="subject"
                                   value="<?= $_SESSION['_flash']['fields']['subject'] ?? '' ?>"
                                   placeholder="Ex: Orçamento, Dúvida sobre locação...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mensagem <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="6" required
                                      placeholder="Descreva sua dúvida ou solicitação..."><?= $_SESSION['_flash']['fields']['message'] ?? '' ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send"></i> Enviar Mensagem
                            </button>
                        </div>
                    </div>
                </form>
                <?php unset($_SESSION['_flash']['fields']); ?>
            </div>

            <div class="col-md-5">
                <div class="card border-0 bg-light p-4 h-100">
                    <h4 class="fw-bold mb-4">Informações de Contato</h4>

                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </div>
                        <div>
                            <strong class="d-block">WhatsApp</strong>
                            <span class="text-muted">(11) 99999-8888</span>
                            <br>
                            <a href="https://wa.me/5511999998888" target="_blank" class="small text-primary text-decoration-none">
                                <i class="bi bi-box-arrow-up-right"></i> Falar pelo WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                            <i class="bi bi-envelope fs-5"></i>
                        </div>
                        <div>
                            <strong class="d-block">E-mail</strong>
                            <span class="text-muted">contato@ferramentasfacil.com.br</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                            <i class="bi bi-geo-alt fs-5"></i>
                        </div>
                        <div>
                            <strong class="d-block">Endereço</strong>
                            <span class="text-muted">Rua Exemplo, 123 - Centro<br>São Paulo - SP</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                            <i class="bi bi-clock fs-5"></i>
                        </div>
                        <div>
                            <strong class="d-block">Horário de Funcionamento</strong>
                            <span class="text-muted">
                                Seg a Sex: 07h00 - 18h00<br>
                                Sáb: 08h00 - 12h00
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require basePath('app/Views/layouts/base.php'); ?>