<?php

class CatalogController extends BaseController
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
        $page = (int) ($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $categorySlug = trim($_GET['categoria'] ?? '');

        $categoryId = null;
        if ($categorySlug) {
            $cat = $this->categoryModel->findBySlug($categorySlug);
            $categoryId = $cat ? $cat['categoryid'] : null;
        }

        if ($search) {
            $result = $this->toolModel->search($search, $categoryId, $page, 12);
        } elseif ($categoryId) {
            $result = $this->toolModel->search('', $categoryId, $page, 12);
        } else {
            $stmt = \BaseModel::db()->prepare(
                "SELECT t.*, c.categoryName, c.slug as categorySlug
                 FROM tools t
                 JOIN toolCategories c ON t.categoryId = c.categoryId
                 WHERE t.deletedAt IS NULL AND t.status = 'available'
                 ORDER BY t.createdAt DESC"
            );
            $stmt->execute();
            $allTools = $stmt->fetchAll();
            $total = count($allTools);
            $offset = ($page - 1) * 12;
            $result = [
                'data' => array_slice($allTools, $offset, 12),
                'total' => $total,
                'page' => $page,
                'perPage' => 12,
                'lastPage' => (int) ceil($total / 12),
            ];
        }

        $categories = $this->categoryModel->getAllActive();

        $this->view('public/catalog', [
            'title' => 'Catálogo de Ferramentas - FerramentasFácil',
            'metaDescription' => 'Confira nossas ferramentas disponíveis para locação.',
            'tools' => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $categorySlug,
        ]);
    }

    public function show(string $param): void
    {
        $tool = $this->toolModel->findBySlug($param);

        if (!$tool && is_numeric($param)) {
            $tool = $this->toolModel->findWithCategory((int) $param);
        }

        if (!$tool) {
            abort(404);
        }

        $toolImageModel = new ToolImage();
        $gallery = $toolImageModel->getByToolId($tool['toolid']);

        $catStmt = \BaseModel::db()->prepare(
            "SELECT COUNT(*) as total FROM tools WHERE categoryId = :catId AND status = 'available' AND deletedAt IS NULL"
        );
        $catStmt->execute([':catId' => $tool['categoryid']]);
        $availableInCategory = (int) $catStmt->fetch()['total'];

        \BaseModel::db()->prepare(
            "UPDATE tools SET viewCount = viewCount + 1 WHERE toolId = :id"
        )->execute([':id' => $tool['toolid']]);

        $this->view('public/tool-detail', [
            'title' => $tool['toolname'] . ' - FerramentasFácil',
            'metaDescription' => mb_substr(strip_tags($tool['description']), 0, 160),
            'tool' => $tool,
            'gallery' => $gallery,
            'availableInCategory' => $availableInCategory,
        ]);
    }
}
