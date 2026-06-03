<?php

class AuthController extends BaseController
{
    private User $userModel;
    private PasswordReset $resetModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->resetModel = new PasswordReset();
    }

    public function loginForm(): void
    {
        $this->view('auth/login', [
            'title' => 'Login - FerramentasFácil',
        ]);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['_flash']['error'] = 'Preencha todos os campos.';
            $this->redirect('/login');
        }

        $user = $this->userModel->validateCredentials($email, $password);

        if (!$user) {
            $_SESSION['_flash']['error'] = 'E-mail ou senha inválidos.';
            $this->redirect('/login');
        }

        if (!$user['isactive']) {
            $_SESSION['_flash']['error'] = 'Sua conta está desativada. Entre em contato com o administrador.';
            $this->redirect('/login');
        }

        $roleName = $this->userModel->getRoleName($user['userid']);
        $this->userModel->updateLastLogin($user['userid']);

        $_SESSION['userId'] = $user['userid'];
        $_SESSION['userName'] = $user['name'];
        $_SESSION['userEmail'] = $user['email'];
        $_SESSION['roleName'] = $roleName;

        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        $_SESSION['_flash']['success'] = 'Login realizado com sucesso! Bem-vindo(a), ' . $user['name'] . '.';

        if ($roleName !== 'client') {
            $this->redirect('/admin/dashboard');
        }
        $this->redirect('/cliente/dashboard');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', [
            'title' => 'Cadastro - FerramentasFácil',
        ]);
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $terms = isset($_POST['terms']);

        $errors = [];

        if (empty($name)) {
            $errors[] = 'O nome é obrigatório.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Informe um e-mail válido.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter no mínimo 8 caracteres.';
        }
        if ($password !== $passwordConfirmation) {
            $errors[] = 'As senhas não conferem.';
        }
        if (!$terms) {
            $errors[] = 'Você precisa aceitar os termos de uso.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $_SESSION['_old_input'] = ['name' => $name, 'email' => $email, 'phone' => $phone];
            $this->redirect('/cadastro');
        }

        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            $_SESSION['_flash']['error'] = 'Este e-mail já está cadastrado.';
            $_SESSION['_old_input'] = ['name' => $name, 'email' => $email, 'phone' => $phone];
            $this->redirect('/cadastro');
        }

        $roleId = 3;

        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'passwordHash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'phone' => $phone,
            'roleId' => $roleId,
        ]);

        if (!$userId) {
            $_SESSION['_flash']['error'] = 'Erro ao criar conta. Tente novamente.';
            $this->redirect('/cadastro');
        }

        $_SESSION['_flash']['success'] = 'Conta criada com sucesso! Faça login para continuar.';
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('/');
    }

    public function forgotForm(): void
    {
        $this->view('auth/forgot', [
            'title' => 'Recuperar Senha - FerramentasFácil',
        ]);
    }

    public function forgot(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['_flash']['error'] = 'Informe um e-mail válido.';
            $this->redirect('/esqueci-senha');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = $this->resetModel->createToken($user['userid']);
            $resetLink = env('APP_URL') . '/redefinir-senha/' . $token;
        }

        $_SESSION['_flash']['success'] = 'Se o e-mail existir em nossa base, você receberá um link de recuperação.';
        $this->redirect('/login');
    }

    public function resetForm(string $token): void
    {
        $valid = $this->resetModel->findValidToken($token);

        if (!$valid) {
            $_SESSION['_flash']['error'] = 'Token inválido ou expirado. Solicite um novo link.';
            $this->redirect('/esqueci-senha');
        }

        $this->view('auth/reset', [
            'title' => 'Redefinir Senha - FerramentasFácil',
            'token' => $token,
        ]);
    }

    public function reset(string $token): void
    {
        $valid = $this->resetModel->findValidToken($token);

        if (!$valid) {
            $_SESSION['_flash']['error'] = 'Token inválido ou expirado. Solicite um novo link.';
            $this->redirect('/esqueci-senha');
        }

        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if (strlen($password) < 8) {
            $_SESSION['_flash']['error'] = 'A senha deve ter no mínimo 8 caracteres.';
            $this->redirect('/redefinir-senha/' . $token);
        }

        if ($password !== $passwordConfirmation) {
            $_SESSION['_flash']['error'] = 'As senhas não conferem.';
            $this->redirect('/redefinir-senha/' . $token);
        }

        $this->userModel->updatePassword($valid['userid'], $password);
        $this->resetModel->markAsUsed($valid['tokenid']);

        $_SESSION['_flash']['success'] = 'Senha redefinida com sucesso! Faça login com sua nova senha.';
        $this->redirect('/login');
    }
}
