<?php

class ToolController extends BaseController
{
    private Tool $toolModel;
    private Category $categoryModel;
    private ToolImage $toolImageModel;

    public function __construct()
    {
        $this->toolModel = new Tool();
        $this->categoryModel = new Category();
        $this->toolImageModel = new ToolImage();
    }

    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;

        if ($search) {
            $result = $this->toolModel->search($search, $categoryId ? (int) $categoryId : null, $page);
        } else {
            $result = $this->toolModel->getAllWithCategory($page);
        }

        $categories = $this->categoryModel->getAllActive();

        $this->view('admin/tools/index', [
            'title' => 'Ferramentas - Administrador',
            'tools' => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function create(): void
    {
        $categories = $this->categoryModel->getAllActive();

        $this->view('admin/tools/create', [
            'title' => 'Nova Ferramenta - Administrador',
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $data = $_POST;

        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'O nome da ferramenta é obrigatório.';
        }
        if (empty($data['category_id'])) {
            $errors[] = 'A categoria é obrigatória.';
        }
        if (empty($data['description'])) {
            $errors[] = 'A descrição é obrigatória.';
        }
        if (empty($data['daily_price']) || (float) $data['daily_price'] <= 0) {
            $errors[] = 'O preço diário deve ser maior que zero.';
        }
        if (empty($data['total_stock']) || (int) $data['total_stock'] < 1) {
            $errors[] = 'O estoque total deve ser pelo menos 1.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $_SESSION['_old_input'] = $data;
            $this->redirect('/admin/ferramentas/criar');
        }

        if (empty($data['slug'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']), '-'));
        }

        $coverImage = null;
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            try {
                $coverImage = Upload::image($_FILES['cover_image'], 'tools');
            } catch (RuntimeException $e) {
                $_SESSION['_flash']['error'] = $e->getMessage();
                $_SESSION['_old_input'] = $data;
                $this->redirect('/admin/ferramentas/criar');
            }
        }

        $data['cover_image'] = $coverImage;

        $toolId = $this->toolModel->createFromData($data);

        if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
            $this->uploadGallery($toolId, $_FILES['gallery']);
        }

        $_SESSION['_flash']['success'] = 'Ferramenta criada com sucesso!';
        $this->redirect('/admin/ferramentas');
    }

    public function edit(int $id): void
    {
        $tool = $this->toolModel->findWithCategory($id);
        if (!$tool) {
            abort(404);
        }

        $categories = $this->categoryModel->getAllActive();
        $gallery = $this->toolImageModel->getByToolId($id);

        $this->view('admin/tools/edit', [
            'title' => 'Editar Ferramenta - Administrador',
            'tool' => $tool,
            'categories' => $categories,
            'gallery' => $gallery,
        ]);
    }

    public function update(int $id): void
    {
        $tool = $this->toolModel->find($id);
        if (!$tool) {
            abort(404);
        }

        $data = $_POST;

        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'O nome da ferramenta é obrigatório.';
        }
        if (empty($data['description'])) {
            $errors[] = 'A descrição é obrigatória.';
        }
        if (empty($data['daily_price']) || (float) $data['daily_price'] <= 0) {
            $errors[] = 'O preço diário deve ser maior que zero.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash']['error'] = implode('<br>', $errors);
            $this->redirect('/admin/ferramentas/' . $id . '/editar');
        }

        if (empty($data['slug'])) {
            $data['slug'] = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']), '-'));
        }

        $coverImage = $tool['coverimageurl'];
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            try {
                if ($coverImage) {
                    Upload::deleteImage($coverImage);
                }
                $coverImage = Upload::image($_FILES['cover_image'], 'tools');
            } catch (RuntimeException $e) {
                $_SESSION['_flash']['error'] = $e->getMessage();
                $this->redirect('/admin/ferramentas/' . $id . '/editar');
            }
        }

        $data['cover_image'] = $coverImage;
        $this->toolModel->updateFromData($id, $data);

        if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
            $this->uploadGallery($id, $_FILES['gallery']);
        }

        $_SESSION['_flash']['success'] = 'Ferramenta atualizada com sucesso!';
        $this->redirect('/admin/ferramentas');
    }

    public function destroy(int $id): void
    {
        $tool = $this->toolModel->find($id);
        if (!$tool) {
            abort(404);
        }

        if ($this->toolModel->hasActiveRentals($id)) {
            $_SESSION['_flash']['error'] = 'Não é possível excluir uma ferramenta com locações ativas.';
            $this->redirect('/admin/ferramentas');
        }

        if ($tool['coverimageurl']) {
            Upload::deleteImage($tool['coverimageurl']);
        }

        Upload::deleteGallery($id);

        $this->toolModel->delete($id);
        $_SESSION['_flash']['success'] = 'Ferramenta excluída com sucesso!';
        $this->redirect('/admin/ferramentas');
    }

    private function uploadGallery(int $toolId, array $files): void
    {
        $names = $files['name'];
        $tmpNames = $files['tmp_name'];
        $errors = $files['error'];

        $sortOrder = $this->toolImageModel->getByToolId($toolId);
        $nextOrder = count($sortOrder);

        for ($i = 0; $i < count($names); $i++) {
            if ($errors[$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileChunk = [
                'name' => $names[$i],
                'tmp_name' => $tmpNames[$i],
                'error' => $errors[$i],
                'size' => $files['size'][$i],
            ];

            try {
                $path = Upload::image($fileChunk, 'tools');
                if ($path) {
                    $this->toolImageModel->addImage($toolId, $path, null, $nextOrder++);
                }
            } catch (RuntimeException $e) {
                continue;
            }
        }
    }
}
