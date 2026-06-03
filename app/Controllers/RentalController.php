<?php

class RentalController extends BaseController
{
    private Rental $rentalModel;
    private RentalStatusHistory $historyModel;
    private Tool $toolModel;
    private User $userModel;

    public function __construct()
    {
        $this->rentalModel = new Rental();
        $this->historyModel = new RentalStatusHistory();
        $this->toolModel = new Tool();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? null;

        $rentals = $this->rentalModel->getAllWithDetails($page, 20, $status);

        $this->view('admin/rentals/index', [
            'title' => 'Locações - Administrador',
            'rentals' => $rentals,
            'currentStatus' => $status,
        ]);
    }

    public function create(): void
    {
        $tools = $this->toolModel->all();
        $clients = $this->userModel->getClients();

        $this->view('admin/rentals/create', [
            'title' => 'Nova Locação - Administrador',
            'tools' => $tools,
            'clients' => $clients,
        ]);
    }

    public function store(): void
    {
        $toolId = (int) ($_POST['toolId'] ?? 0);
        $userId = (int) ($_POST['userId'] ?? 0);
        $startDate = $_POST['startDate'] ?? '';
        $expectedEndDate = $_POST['expectedEndDate'] ?? '';

        $tool = $this->toolModel->findWithCategory($toolId);
        if (!$tool) {
            $_SESSION['_flash']['error'] = 'Ferramenta não encontrada.';
            $this->redirect('/admin/locacoes/criar');
        }

        if ($tool['availablestock'] < 1) {
            $_SESSION['_flash']['error'] = 'Ferramenta sem estoque disponível.';
            $this->redirect('/admin/locacoes/criar');
        }

        $rentalDays = $this->rentalModel->calculateRentalDays($startDate, $expectedEndDate);
        if ($rentalDays < (int) $tool['minrentaldays']) {
            $_SESSION['_flash']['error'] = "Período mínimo de {$tool['minrentaldays']} dia(s).";
            $this->redirect('/admin/locacoes/criar');
        }
        if ($rentalDays > (int) $tool['maxrentaldays']) {
            $_SESSION['_flash']['error'] = "Período máximo de {$tool['maxrentaldays']} dia(s).";
            $this->redirect('/admin/locacoes/criar');
        }

        $rentalId = $this->rentalModel->createRental([
            'userId' => $userId,
            'toolId' => $toolId,
            'startDate' => $startDate,
            'expectedEndDate' => $expectedEndDate,
            'dailyPrice' => $tool['dailyprice'],
            'depositAmount' => $tool['depositamount'] ?? 0,
            'fineAmount' => 0,
            'status' => 'active',
            'paymentStatus' => 'pending',
            'registeredBy' => $_SESSION['userId'],
        ]);

        $this->toolModel->decrementStock($toolId);

        $this->historyModel->logChange((int) $rentalId, null, 'active', $_SESSION['userId'], 'Locação registrada pelo administrador.');

        $_SESSION['_flash']['success'] = "Locação {$rentalId} criada com sucesso!";
        $this->redirect('/admin/locacoes');
    }

    public function show(int $id): void
    {
        $rental = $this->rentalModel->findWithDetails($id);
        if (!$rental) {
            abort(404);
        }

        $statusHistory = $this->rentalModel->getStatusHistory($id);

        $this->view('admin/rentals/show', [
            'title' => "Locação {$rental['rentalcode']}",
            'rental' => $rental,
            'history' => $statusHistory,
        ]);
    }

    public function confirm(int $id): void
    {
        $rental = $this->rentalModel->findWithDetails($id);
        if (!$rental || $rental['status'] !== 'pending') {
            $_SESSION['_flash']['error'] = 'Locação não encontrada ou já processada.';
            $this->redirect('/admin/locacoes');
        }

        $tool = $this->toolModel->find($rental['toolid']);
        if (!$tool || (int) $tool['availablestock'] < 1) {
            $_SESSION['_flash']['error'] = 'Ferramenta sem estoque disponível.';
            $this->redirect('/admin/locacoes');
        }

        $this->rentalModel->update($id, ['status' => 'active']);
        $this->toolModel->decrementStock($rental['toolid']);
        $this->historyModel->logChange($id, 'pending', 'active', $_SESSION['userId'], 'Locação confirmada pelo administrador.');

        $_SESSION['_flash']['success'] = "Locação {$rental['rentalcode']} confirmada!";
        $this->redirect("/admin/locacoes/{$id}");
    }

    public function returnTool(int $id): void
    {
        $rental = $this->rentalModel->findWithDetails($id);
        if (!$rental) {
            abort(404);
        }

        $actualEndDate = $_POST['actualEndDate'] ?? date('Y-m-d');
        $fineAmount = (float) ($_POST['fineAmount'] ?? 0);

        $expectedEnd = new DateTime($rental['expectedenddate']);
        $actualEnd = new DateTime($actualEndDate);

        if ($actualEnd > $expectedEnd && $fineAmount <= 0) {
            $overdueDays = (int) $expectedEnd->diff($actualEnd)->days;
            $fineAmount = $overdueDays * (float) $rental['dailyprice'] * 0.5;
        }

        $this->rentalModel->update($id, [
            'actualEndDate' => $actualEndDate,
            'fineAmount' => $fineAmount,
            'status' => 'returned',
            'paymentStatus' => 'paid',
        ]);

        $this->toolModel->incrementStock($rental['toolid']);

        $this->historyModel->logChange($id, $rental['status'], 'returned', $_SESSION['userId'], 'Ferramenta devolvida.');

        $_SESSION['_flash']['success'] = "Locação {$rental['rentalcode']} finalizada como devolvida.";
        $this->redirect("/admin/locacoes/{$id}");
    }

    public function cancel(int $id): void
    {
        $rental = $this->rentalModel->findWithDetails($id);
        if (!$rental) {
            abort(404);
        }

        $this->rentalModel->update($id, [
            'status' => 'cancelled',
            'paymentStatus' => 'refunded',
        ]);

        if ($rental['status'] === 'active') {
            $this->toolModel->incrementStock($rental['toolid']);
        }

        $this->historyModel->logChange($id, $rental['status'], 'cancelled', $_SESSION['userId'], 'Locação cancelada.');

        $_SESSION['_flash']['success'] = "Locação {$rental['rentalcode']} cancelada.";
        $this->redirect("/admin/locacoes");
    }
}
