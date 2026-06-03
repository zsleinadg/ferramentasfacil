<?php

class Rental extends BaseModel
{
    protected string $table = 'rentals';
    protected string $primaryKey = 'rentalId';
    protected bool $useSoftDelete = false;

    public function generateRentalCode(): string
    {
        $date = date('Ymd');
        $stmt = self::db()->prepare(
            "SELECT COUNT(*) as total FROM rentals WHERE rentalCode LIKE :prefix"
        );
        $stmt->execute([':prefix' => "LOC-{$date}-%"]);
        $count = (int) $stmt->fetch()['total'] + 1;
        return "LOC-{$date}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function createRental(array $data): string|false
    {
        $data['rentalCode'] = $this->generateRentalCode();
        $data['rentalDays'] = $this->calculateRentalDays($data['startDate'], $data['expectedEndDate']);
        $data['totalAmount'] = (float) $data['dailyPrice'] * (int) $data['rentalDays'];
        return $this->create($data);
    }

    public function calculateRentalDays(string $startDate, string $endDate): int
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        return (int) max(1, $start->diff($end)->days);
    }

    public function getAllWithDetails(int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $offset = ($page - 1) * $perPage;
        $where = '';
        $params = [];

        if ($status) {
            $where = "WHERE r.status = :status";
            $params[':status'] = $status;
        }

        $countStmt = self::db()->prepare("SELECT COUNT(*) as total FROM rentals r {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT r.*, u.name as userName, u.email as userEmail, t.toolName, t.coverImageUrl
             FROM rentals r
             JOIN users u ON r.userId = u.userId
             JOIN tools t ON r.toolId = t.toolId
             {$where}
             ORDER BY r.createdAt DESC
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

    public function findWithDetails(int $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT r.*, u.name as userName, u.email as userEmail, u.phone as userPhone,
                    t.toolName, t.coverImageUrl, t.dailyPrice as currentDailyPrice,
                    c.categoryName
             FROM rentals r
             JOIN users u ON r.userId = u.userId
             JOIN tools t ON r.toolId = t.toolId
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE r.rentalId = :id"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getUserRentals(int $userId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = self::db()->prepare(
            "SELECT COUNT(*) as total FROM rentals WHERE userId = :userId"
        );
        $countStmt->execute([':userId' => $userId]);
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT r.*, t.toolName, t.coverImageUrl, c.categoryName
             FROM rentals r
             JOIN tools t ON r.toolId = t.toolId
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE r.userId = :userId
             ORDER BY r.createdAt DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => (int) ceil($total / $perPage),
        ];
    }

    public function getActiveRentalsCount(): int
    {
        $stmt = self::db()->query(
            "SELECT COUNT(*) as total FROM rentals WHERE status IN ('active', 'overdue')"
        );
        return (int) $stmt->fetch()['total'];
    }

    public function getMonthlyRevenue(): float
    {
        $stmt = self::db()->query(
            "SELECT COALESCE(SUM(totalAmount), 0) as total FROM rentals
             WHERE status = 'returned'
             AND EXTRACT(MONTH FROM createdAt) = EXTRACT(MONTH FROM NOW())
             AND EXTRACT(YEAR FROM createdAt) = EXTRACT(YEAR FROM NOW())"
        );
        return (float) $stmt->fetch()['total'];
    }

    public function getPendingCount(): int
    {
        $stmt = self::db()->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'pending'");
        return (int) $stmt->fetch()['total'];
    }

    public function markOverdue(): int
    {
        $stmt = self::db()->query(
            "UPDATE rentals SET status = 'overdue', updatedAt = NOW()
             WHERE status = 'active' AND expectedEndDate < CURRENT_DATE"
        );
        return $stmt->rowCount();
    }

    public function getOverdueCount(): int
    {
        $stmt = self::db()->query(
            "SELECT COUNT(*) as total FROM rentals WHERE status = 'overdue'"
        );
        return (int) $stmt->fetch()['total'];
    }

    public function getMonthlyData(): array
    {
        $stmt = self::db()->query(
            "SELECT TO_CHAR(createdAt, 'YYYY-MM') as month,
                    COUNT(*) as total,
                    COALESCE(SUM(totalAmount), 0) as revenue
             FROM rentals
             WHERE createdAt >= NOW() - INTERVAL '12 months'
             GROUP BY month
             ORDER BY month ASC"
        );
        return $stmt->fetchAll();
    }

    public function getTopTools(int $limit = 5): array
    {
        $stmt = self::db()->prepare(
            "SELECT t.toolName, COUNT(r.rentalId) as rentalCount,
                    COALESCE(SUM(r.totalAmount), 0) as revenue
             FROM rentals r
             JOIN tools t ON r.toolId = t.toolId
             WHERE r.status IN ('returned', 'active', 'overdue')
             GROUP BY t.toolName
             ORDER BY rentalCount DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStatusHistory(int $rentalId): array
    {
        $stmt = self::db()->prepare(
            "SELECT rsh.*, u.name as changedByName
             FROM rentalStatusHistory rsh
             JOIN users u ON rsh.changedBy = u.userId
             WHERE rsh.rentalId = :rentalId
             ORDER BY rsh.createdAt ASC"
        );
        $stmt->execute([':rentalId' => $rentalId]);
        return $stmt->fetchAll();
    }
}
