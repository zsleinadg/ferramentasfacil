<?php

class HomeController extends BaseController
{
    private Tool $toolModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->toolModel = new Tool();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $featuredTools = $this->toolModel->getFeatured(8);
        $categories = $this->categoryModel->getAllActive();

        $this->view('public/home', [
            'title' => 'FerramentasFácil - Locação de Ferramentas',
            'metaDescription' => 'Alugue ferramentas de construção civil com facilidade. Qualidade e preço justo para seu projeto.',
            'featuredTools' => $featuredTools,
            'categories' => $categories,
        ]);
    }

    public function contactSubmit(): void
    {
        $_SESSION['_flash']['success'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
        $this->redirect('/contato');
    }
}
