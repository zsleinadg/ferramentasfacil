<?php

class PageController extends BaseController
{
    private ContactMessage $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactMessage();
    }

    public function about(): void
    {
        $this->view('public/about', [
            'title' => 'Sobre Nós - FerramentasFácil',
            'metaDescription' => 'Conheça o FerramentasFácil, sua plataforma confiável para locação de ferramentas de construção civil.',
        ]);
    }

    public function contact(): void
    {
        $this->view('public/contact', [
            'title' => 'Contato - FerramentasFácil',
            'metaDescription' => 'Entre em contato com o FerramentasFácil. Tire suas dúvidas ou solicite um orçamento.',
        ]);
    }

    public function contactSubmit(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = $this->validate($_POST, [
            'name' => 'required|min:2|max:150',
            'email' => 'required|email',
            'message' => 'required|min:10|max:5000',
        ]);

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = 'Verifique os campos obrigatórios e tente novamente.';
            $_SESSION['_flash']['fields'] = ['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message];
            $this->redirect('/contato');
            return;
        }

        $this->contactModel->create([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        $_SESSION['_flash']['success'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
        $this->redirect('/contato');
    }

    public function adminMessages(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $result = $this->contactModel->getAllPaginated($page, 20);

        $this->view('admin/mensagens/index', [
            'title' => 'Mensagens de Contato - Administrador',
            'messages' => $result['data'],
            'pagination' => [
                'page' => $result['page'],
                'perPage' => $result['perPage'],
                'lastPage' => $result['lastPage'],
                'total' => $result['total'],
            ],
        ]);
    }

    public function markAsRead(int $id): void
    {
        $this->contactModel->markAsRead($id);
        $this->redirect('/admin/mensagens');
    }
}