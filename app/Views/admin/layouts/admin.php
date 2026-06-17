<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Administrador - FerramentasFácil' ?></title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
    <div class="d-flex vh-100 overflow-hidden">
        <nav class="admin-sidebar bg-dark text-white p-3 flex-shrink-0 overflow-y-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="/admin/dashboard" class="text-white text-decoration-none fw-bold fs-5">
                    <i class="bi bi-tools"></i> Admin
                </a>
                <button class="btn btn-sm btn-outline-light d-md-none" onclick="toggleSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/ferramentas">
                        <i class="bi bi-wrench"></i> Ferramentas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/categorias">
                        <i class="bi bi-tags"></i> Categorias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/locacoes">
                        <i class="bi bi-calendar-check"></i> Locações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/usuarios">
                        <i class="bi bi-people"></i> Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/mensagens">
                        <i class="bi bi-envelope"></i> Mensagens
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/relatorios">
                        <i class="bi bi-graph-up"></i> Relatórios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/admin/configuracoes">
                        <i class="bi bi-gear"></i> Configurações
                    </a>
                </li>
            </ul>
            <hr class="text-secondary">
            <a href="/" class="nav-link text-white small"><i class="bi bi-house"></i> Ver Site</a>
            <a href="/logout" class="nav-link text-white small"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <div class="admin-overlay d-md-none" onclick="toggleSidebar()"></div>

        <main class="flex-grow-1 p-4 overflow-y-auto">
            <button class="btn btn-outline-dark mb-3 d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i> Menu
            </button>
            <?php if (isset($_SESSION['_flash'])): ?>
                <?php foreach ($_SESSION['_flash'] as $type => $message): ?>
                    <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                        <?= is_array($message) ? implode('<br>', $message) : $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['_flash']); ?>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
    function toggleSidebar() {
        document.querySelector('.admin-sidebar').classList.toggle('show');
        document.querySelector('.admin-overlay').classList.toggle('show');
    }
    </script>
</body>
</html>
