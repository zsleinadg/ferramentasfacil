<?php

class UserController extends BaseController
{
    private User $userModel;
    private Rental $rentalModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->rentalModel = new Rental();
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');

        if ($search) {
            $users = $this->userModel->searchUsers($search, $page);
        } else {
            $users = $this->userModel->getAllWithRoles($page);
        }

        $this->view('admin/usuarios/index', [
            'title' => 'Usuários - Administrador',
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function show(int $id): void
    {
        $user = $this->userModel->findWithRole($id);
        if (!$user) {
            abort(404);
        }

        $roles = $this->userModel->getAllRoles();
        $rentals = $this->rentalModel->getUserRentals($id, 1, 20);

        $this->view('admin/usuarios/show', [
            'title' => "{$user['name']} - Usuário",
            'user' => $user,
            'roles' => $roles,
            'rentals' => $rentals,
        ]);
    }

    public function updateRole(int $id): void
    {
        $target = $this->userModel->find($id);
        if (!$target) {
            abort(404);
        }

        if ($id === (int) $_SESSION['userId']) {
            $_SESSION['_flash']['error'] = 'Você não pode alterar seu próprio role.';
            $this->redirect("/admin/usuarios/{$id}");
        }

        $roleId = (int) ($_POST['roleId'] ?? 0);
        $roleStmt = \BaseModel::db()->prepare("SELECT * FROM roles WHERE roleId = :id");
        $roleStmt->execute([':id' => $roleId]);
        $role = $roleStmt->fetch();

        if (!$role) {
            $_SESSION['_flash']['error'] = 'Role inválido.';
            $this->redirect("/admin/usuarios/{$id}");
        }

        $this->userModel->update($id, ['roleId' => $roleId]);
        $_SESSION['_flash']['success'] = "Role de {$target['name']} alterado para {$role['displayname']}.";
        $this->redirect("/admin/usuarios/{$id}");
    }

    public function toggleActive(int $id): void
    {
        $target = $this->userModel->find($id);
        if (!$target) {
            abort(404);
        }

        if ($id === (int) $_SESSION['userId']) {
            $_SESSION['_flash']['error'] = 'Você não pode desativar sua própria conta.';
            $this->redirect('/admin/usuarios');
        }

        $newStatus = $target['isactive'] ? 'false' : 'true';
        $this->userModel->update($id, ['isActive' => $newStatus]);

        $msg = $target['isactive'] ? 'desativado' : 'ativado';
        $_SESSION['_flash']['success'] = "Usuário {$target['name']} {$msg} com sucesso.";
        $this->redirect('/admin/usuarios');
    }
}
