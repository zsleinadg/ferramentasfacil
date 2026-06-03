<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FerramentasFácil' ?></title>
    <meta name="description" content="<?= $metaDescription ?? 'Sistema de Locação de Ferramentas de Construção' ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
    <?php require basePath('app/Views/layouts/partials/header.php') ?>

    <main>
        <?php if (isset($_SESSION['_flash'])): ?>
            <?php foreach ($_SESSION['_flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show mt-3 container" role="alert">
                    <?= is_array($message) ? implode('<br>', $message) : $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['_flash']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <?php require basePath('app/Views/layouts/partials/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
