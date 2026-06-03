<?php

class ClientController extends BaseController
{
    private User $userModel;
    private Tool $toolModel;
    private Rental $rentalModel;
    private RentalStatusHistory $historyModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->toolModel = new Tool();
        $this->rentalModel = new Rental();
        $this->historyModel = new RentalStatusHistory();
    }

    public function dashboard(): void
    {
        $userId = $_SESSION['userId'];
        $user = $this->userModel->find($userId);

        $rentals = $this->rentalModel->getUserRentals($userId, 1, 5);

        $this->view('client/dashboard', [
            'title' => 'Meu Painel - FerramentasFácil',
            'user' => $user,
            'rentals' => $rentals,
        ]);
    }

    public function profile(): void
    {
        $userId = $_SESSION['userId'];
        $user = $this->userModel->find($userId);

        $this->view('client/profile', [
            'title' => 'Meu Perfil - FerramentasFácil',
            'user' => $user,
        ]);
    }

    public function updateProfile(): void
    {
        $userId = $_SESSION['userId'];
        $user = $this->userModel->find($userId);

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $errors = [];
        if (empty($name)) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $this->redirect('/cliente/perfil');
        }

        $this->userModel->update($userId, [
            'name' => $name,
            'phone' => $phone,
            'cpf' => $cpf,
            'address' => $address,
        ]);

        $_SESSION['userName'] = $name;
        $_SESSION['_flash']['success'] = 'Perfil atualizado com sucesso!';
        $this->redirect('/cliente/perfil');
    }

    public function rentForm(int $toolId): void
    {
        $tool = $this->toolModel->findWithCategory($toolId);
        if (!$tool) {
            abort(404);
        }

        $userId = $_SESSION['userId'];
        $user = $this->userModel->find($userId);

        $this->view('client/rent', [
            'title' => "Alugar {$tool['toolname']} - FerramentasFácil",
            'tool' => $tool,
            'user' => $user,
        ]);
    }

    public function rent(int $toolId): void
    {
        $tool = $this->toolModel->findWithCategory($toolId);
        if (!$tool) {
            abort(404);
        }

        if ($tool['availablestock'] < 1) {
            $_SESSION['_flash']['error'] = 'Ferramenta indisponível no momento.';
            $this->redirect("/ferramenta/{$tool['slug']}");
        }

        $startDate = $_POST['startDate'] ?? '';
        $expectedEndDate = $_POST['expectedEndDate'] ?? '';

        if (empty($startDate) || empty($expectedEndDate)) {
            $_SESSION['_flash']['error'] = 'Selecione as datas de locação.';
            $this->redirect("/cliente/alugar/{$toolId}");
        }

        $start = new DateTime($startDate);
        $end = new DateTime($expectedEndDate);
        $today = new DateTime('today');

        if ($start < $today) {
            $_SESSION['_flash']['error'] = 'A data inicial não pode ser no passado.';
            $this->redirect("/cliente/alugar/{$toolId}");
        }

        if ($end <= $start) {
            $_SESSION['_flash']['error'] = 'A data final deve ser posterior à data inicial.';
            $this->redirect("/cliente/alugar/{$toolId}");
        }

        $rentalDays = (int) $start->diff($end)->days;
        if ($rentalDays < (int) $tool['minrentaldays']) {
            $_SESSION['_flash']['error'] = "Período mínimo de {$tool['minrentaldays']} dia(s).";
            $this->redirect("/cliente/alugar/{$toolId}");
        }
        if ($rentalDays > (int) $tool['maxrentaldays']) {
            $_SESSION['_flash']['error'] = "Período máximo de {$tool['maxrentaldays']} dia(s).";
            $this->redirect("/cliente/alugar/{$toolId}");
        }

        $userId = $_SESSION['userId'];

        $rentalId = $this->rentalModel->createRental([
            'userId' => $userId,
            'toolId' => $toolId,
            'startDate' => $startDate,
            'expectedEndDate' => $expectedEndDate,
            'dailyPrice' => $tool['dailyprice'],
            'depositAmount' => $tool['depositamount'] ?? 0,
            'fineAmount' => 0,
            'status' => 'pending',
            'paymentStatus' => 'pending',
            'registeredBy' => $userId,
        ]);

        $this->historyModel->logChange((int) $rentalId, null, 'pending', $userId, 'Locação solicitada pelo cliente.');

        $_SESSION['_flash']['success'] = 'Locação solicitada com sucesso! Aguarde a confirmação do administrador.';
        $this->redirect('/cliente/locacoes');
    }

    public function rentals(): void
    {
        $userId = $_SESSION['userId'];
        $page = (int) ($_GET['page'] ?? 1);
        $rentals = $this->rentalModel->getUserRentals($userId, $page, 10);

        $this->view('client/rentals', [
            'title' => 'Minhas Locações - FerramentasFácil',
            'rentals' => $rentals,
        ]);
    }

    public function rentalDetail(int $id): void
    {
        $userId = $_SESSION['userId'];
        $rental = $this->rentalModel->findWithDetails($id);

        if (!$rental || $rental['userid'] != $userId) {
            abort(404);
        }

        $history = $this->rentalModel->getStatusHistory($id);

        $this->view('client/rental-detail', [
            'title' => "Locação {$rental['rentalcode']} - FerramentasFácil",
            'rental' => $rental,
            'history' => $history,
        ]);
    }
}
