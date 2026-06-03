<?php

class GoogleAuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function redirectToGoogle(): void
    {
        $config = require basePath('config/google.php');

        $state = bin2hex(random_bytes(16));
        $_SESSION['_google_state'] = $state;

        $params = http_build_query([
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'email profile',
            'state' => $state,
        ]);

        redirect('https://accounts.google.com/o/oauth2/auth?' . $params);
    }

    public function callback(): void
    {
        $config = require basePath('config/google.php');

        $code = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';

        if (
            empty($state) || empty($_SESSION['_google_state']) ||
            !hash_equals($_SESSION['_google_state'], $state)
        ) {
            $_SESSION['_flash']['error'] = 'State inválido. Tente novamente.';
            unset($_SESSION['_google_state']);
            $this->redirect('/login');
        }

        unset($_SESSION['_google_state']);

        if (empty($code)) {
            $_SESSION['_flash']['error'] = 'Autenticação com Google cancelada.';
            $this->redirect('/login');
        }

        $tokenData = $this->exchangeCode($code, $config);
        if (!$tokenData || !isset($tokenData['access_token'])) {
            $_SESSION['_flash']['error'] = 'Erro ao obter token do Google.';
            $this->redirect('/login');
        }

        $googleUser = $this->fetchUserInfo($tokenData['access_token']);
        if (!$googleUser || empty($googleUser['email'])) {
            $_SESSION['_flash']['error'] = 'Erro ao obter dados do Google.';
            $this->redirect('/login');
        }

        $existingUser = $this->userModel->findByEmail($googleUser['email']);

        if ($existingUser) {
            if (!$existingUser['googleid']) {
                $this->userModel->linkGoogleAccount($existingUser['userid'], $googleUser['sub']);
            }
            $user = $this->userModel->find($existingUser['userid']);
        } else {
            $roleStmt = \BaseModel::db()->prepare("SELECT roleId FROM roles WHERE roleName = 'client'");
            $roleStmt->execute();
            $clientRole = $roleStmt->fetch();
            $roleId = $clientRole ? $clientRole['roleid'] : 3;

            $this->userModel->create([
                'name' => $googleUser['name'] ?? $googleUser['email'],
                'email' => $googleUser['email'],
                'googleId' => $googleUser['sub'],
                'avatarUrl' => $googleUser['picture'] ?? null,
                'roleId' => $roleId,
                'emailVerifiedAt' => date('Y-m-d H:i:s'),
            ]);

            $user = $this->userModel->findByEmail($googleUser['email']);
        }

        if (!$user || !$user['isactive']) {
            $_SESSION['_flash']['error'] = 'Conta desativada. Contate o administrador.';
            $this->redirect('/login');
        }

        $_SESSION['userId'] = $user['userid'];
        $_SESSION['userName'] = $user['name'];
        $_SESSION['userEmail'] = $user['email'];
        $_SESSION['roleId'] = $user['roleid'];

        $roleName = $this->userModel->getRoleName($user['userid']);
        $_SESSION['roleName'] = $roleName;

        $this->userModel->updateLastLogin($user['userid']);

        $redirectTo = $_SESSION['_redirect_after_login'] ?? '/';
        unset($_SESSION['_redirect_after_login']);
        $this->redirect($redirectTo);
    }

    private function exchangeCode(string $code, array $config): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'code' => $code,
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'redirect_uri' => $config['redirect_uri'],
                'grant_type' => 'authorization_code',
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        return json_decode($response, true);
    }

    private function fetchUserInfo(string $accessToken): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/userinfo',
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$accessToken}"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        return json_decode($response, true);
    }
}
