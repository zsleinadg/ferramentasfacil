<?php

class CategoryController extends BaseController
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $categories = $this->categoryModel->getWithToolCount();

        $this->view('admin/categories/index', [
            'title' => 'Categorias - Administrador',
            'categories' => $categories,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/categories/create', [
            'title' => 'Nova Categoria - Administrador',
        ]);
    }

    public function store(): void
    {
        $data = $_POST;

        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'O nome da categoria é obrigatório.';
        }
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']), '-'));
        }

        $existing = $this->categoryModel->findBySlug($data['slug']);
        if ($existing) {
            $errors[] = 'Já existe uma categoria com este slug.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $_SESSION['_old_input'] = $data;
            $this->redirect('/admin/categorias/criar');
        }

        $this->categoryModel->createFromData($data);
        $_SESSION['_flash']['success'] = 'Categoria criada com sucesso!';
        $this->redirect('/admin/categorias');
    }

    public function edit(int $id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            abort(404);
        }

        $this->view('admin/categories/edit', [
            'title' => 'Editar Categoria - Administrador',
            'category' => $category,
        ]);
    }

    public function update(int $id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            abort(404);
        }

        $data = $_POST;

        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'O nome da categoria é obrigatório.';
        }
        if (empty($data['slug'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']), '-'));
        }

        $existing = $this->categoryModel->findBySlug($data['slug']);
        if ($existing && $existing['categoryid'] != $id) {
            $errors[] = 'Já existe outra categoria com este slug.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $_SESSION['_old_input'] = $data;
            $this->redirect('/admin/categorias/' . $id . '/editar');
        }

        $this->categoryModel->updateFromData($id, $data);
        $_SESSION['_flash']['success'] = 'Categoria atualizada com sucesso!';
        $this->redirect('/admin/categorias');
    }

    public function destroy(int $id): void
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            abort(404);
        }

        if ($this->categoryModel->hasTools($id)) {
            $_SESSION['_flash']['error'] = 'Não é possível excluir uma categoria com ferramentas vinculadas. Desative-a.';
            $this->redirect('/admin/categorias');
        }

        $this->categoryModel->delete($id);
        $_SESSION['_flash']['success'] = 'Categoria excluída com sucesso!';
        $this->redirect('/admin/categorias');
    }
}
