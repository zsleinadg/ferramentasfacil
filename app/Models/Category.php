<?php

class Category extends BaseModel
{
    protected string $table = 'toolCategories';
    protected string $primaryKey = 'categoryId';

    public function getAllActive(): array
    {
        $stmt = self::db()->query(
            "SELECT * FROM toolCategories WHERE isActive = TRUE ORDER BY sortOrder ASC, categoryName ASC"
        );
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->firstWhere('slug', $slug);
    }

    public function hasTools(int $categoryId): bool
    {
        $stmt = self::db()->prepare(
            "SELECT COUNT(*) as total FROM tools WHERE categoryId = :id AND deletedAt IS NULL"
        );
        $stmt->execute([':id' => $categoryId]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function getWithToolCount(): array
    {
        $stmt = self::db()->query(
            "SELECT c.*, COUNT(t.toolId) as toolCount
             FROM toolCategories c
             LEFT JOIN tools t ON c.categoryId = t.categoryId AND t.deletedAt IS NULL
             GROUP BY c.categoryId
             ORDER BY c.sortOrder ASC, c.categoryName ASC"
        );
        return $stmt->fetchAll();
    }

    public function createFromData(array $data): string|false
    {
        return $this->create([
            'categoryName' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'iconClass' => $data['icon'] ?? null,
            'imageUrl' => $data['image_url'] ?? null,
            'isActive' => isset($data['is_active']) ? true : false,
            'sortOrder' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function updateFromData(int $id, array $data): bool
    {
        return $this->update($id, [
            'categoryName' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'iconClass' => $data['icon'] ?? null,
            'imageUrl' => $data['image_url'] ?? null,
            'isActive' => isset($data['is_active']) ? true : false,
            'sortOrder' => (int) ($data['sort_order'] ?? 0),
        ]);
    }
}
