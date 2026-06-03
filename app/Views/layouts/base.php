<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FerramentasFácil - Locação de Ferramentas de Construção' ?></title>
    <meta name="description" content="<?= $metaDescription ?? 'Sistema de Locação de Ferramentas de Construção. Alugue ferramentas profissionais para sua obra com preço acessível.' ?>">
    <meta name="keywords" content="ferramentas, locação, aluguel, construção, obra, ferramentasfácil">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $canonicalUrl ?? (env('APP_URL', 'https://ferramentasfacil.com') . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?>">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <meta property="og:title" content="<?= $title ?? 'FerramentasFácil - Locação de Ferramentas' ?>">
    <meta property="og:description" content="<?= $metaDescription ?? 'Alugue ferramentas profissionais para sua obra com preço acessível.' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $canonicalUrl ?? (env('APP_URL', 'https://ferramentasfacil.com') . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?>">
    <meta property="og:image" content="<?= $ogImage ?? '/assets/img/og-image.png' ?>">
    <meta name="twitter:card" content="summary_large_image">
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
