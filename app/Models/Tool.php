<?php

class Tool extends BaseModel
{
    protected string $table = 'tools';
    protected string $primaryKey = 'toolId';
    protected bool $useSoftDelete = true;

    public function getAllWithCategory(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = self::db()->query(
            "SELECT COUNT(*) as total FROM tools WHERE deletedAt IS NULL"
        );
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT t.*, c.categoryName, c.slug as categorySlug
             FROM tools t
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE t.deletedAt IS NULL
             ORDER BY t.toolId DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => (int) ceil($total / $perPage),
        ];
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT t.*, c.categoryName, c.slug as categorySlug
             FROM tools t
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE t.slug = :slug AND t.deletedAt IS NULL
             LIMIT 1"
        );
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findWithCategory(int|string $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT t.*, c.categoryName, c.slug as categorySlug
             FROM tools t
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE t.toolId = :id AND t.deletedAt IS NULL"
        );
        $stmt->execute([':id' => (int) $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getFeatured(int $limit = 8): array
    {
        $stmt = self::db()->prepare(
            "SELECT t.*, c.categoryName
             FROM tools t
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE t.isFeatured = TRUE AND t.status = 'available' AND t.deletedAt IS NULL
             ORDER BY t.viewCount DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(string $term, ?int $categoryId = null, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $where = "WHERE (t.toolName ILIKE :term OR t.description ILIKE :term) AND t.deletedAt IS NULL";
        $params = [':term' => "%{$term}%"];

        if ($categoryId) {
            $where .= " AND t.categoryId = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        $countStmt = self::db()->prepare("SELECT COUNT(*) as total FROM tools t {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT t.*, c.categoryName
             FROM tools t
             JOIN toolCategories c ON t.categoryId = c.categoryId
             {$where}
             ORDER BY t.toolName ASC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => (int) ceil($total / $perPage),
        ];
    }

    public function hasActiveRentals(int $toolId): bool
    {
        $stmt = self::db()->prepare(
            "SELECT COUNT(*) as total FROM rentals
             WHERE toolId = :id AND status IN ('active', 'overdue')"
        );
        $stmt->execute([':id' => $toolId]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function decrementStock(int $toolId, int $qty = 1): bool
    {
        $stmt = self::db()->prepare(
            "UPDATE tools SET availableStock = availableStock - :qty, updatedAt = NOW()
             WHERE toolId = :id AND availableStock >= :qty"
        );
        return $stmt->execute([':id' => $toolId, ':qty' => $qty]);
    }

    public function incrementStock(int $toolId, int $qty = 1): bool
    {
        $stmt = self::db()->prepare(
            "UPDATE tools SET availableStock = availableStock + :qty, updatedAt = NOW()
             WHERE toolId = :id"
        );
        return $stmt->execute([':id' => $toolId, ':qty' => $qty]);
    }

    public function createFromData(array $data): string|false
    {
        return $this->create([
            'categoryId' => $data['category_id'],
            'toolName' => $data['name'],
            'slug' => $data['slug'],
            'brand' => $data['brand'] ?? null,
            'model' => $data['model'] ?? null,
            'description' => $data['description'],
            'dailyPrice' => $data['daily_price'],
            'depositAmount' => $data['deposit_amount'] ?? 0,
            'totalStock' => $data['total_stock'],
            'availableStock' => $data['total_stock'],
            'minRentalDays' => $data['min_days'] ?? 1,
            'maxRentalDays' => $data['max_days'] ?? 30,
            'coverImageUrl' => $data['cover_image'] ?? null,
            'status' => $data['status'] ?? 'available',
            'isFeatured' => isset($data['is_featured']) ? 'true' : 'false',
        ]);
    }

    public function updateFromData(int $id, array $data): bool
    {
        return $this->update($id, [
            'categoryId' => $data['category_id'],
            'toolName' => $data['name'],
            'slug' => $data['slug'],
            'brand' => $data['brand'] ?? null,
            'model' => $data['model'] ?? null,
            'description' => $data['description'],
            'dailyPrice' => $data['daily_price'],
            'depositAmount' => $data['deposit_amount'] ?? 0,
            'totalStock' => $data['total_stock'],
            'minRentalDays' => $data['min_days'] ?? 1,
            'maxRentalDays' => $data['max_days'] ?? 30,
            'coverImageUrl' => $data['cover_image'] ?? null,
            'status' => $data['status'] ?? 'available',
            'isFeatured' => isset($data['is_featured']) ? 'true' : 'false',
        ]);
    }
}
