<?php

class BaseModel
{
    protected static ?PDO $pdo = null;
    protected string $table;
    protected string $primaryKey = '';
    protected bool $useSoftDelete = false;

    private function softDeleteClause(): string
    {
        return $this->useSoftDelete ? ' AND deletedAt IS NULL' : '';
    }

    public static function db(): PDO
    {
        if (self::$pdo === null) {
            $config = require basePath('config/database.php');

            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};sslmode={$config['sslmode']}";
            self::$pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            self::$pdo->exec("SET NAMES 'UTF8'");
        }

        return self::$pdo;
    }

    public function all(): array
    {
        $query = "SELECT * FROM {$this->table}";
        if ($this->useSoftDelete) {
            $query .= " WHERE deletedAt IS NULL";
        }
        $query .= " ORDER BY {$this->primaryKey} DESC";
        $stmt = self::db()->query($query);
        return $stmt->fetchAll();
    }

    public function find(int|string $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id" . $this->softDeleteClause()
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int|string $id): ?array
    {
        return $this->find($id);
    }

    public function create(array $data): string|false
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = self::db()->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
        );

        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }

        $stmt->execute($params);
        return self::db()->lastInsertId();
    }

    public function update(int|string $id, array $data): bool
    {
        $sets = [];
        $params = [":{$this->primaryKey}" => $id];

        foreach ($data as $key => $value) {
            $sets[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }

        $sets[] = 'updatedAt = NOW()';

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :{$this->primaryKey}";
        $stmt = self::db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int|string $id): bool
    {
        if ($this->useSoftDelete) {
            $stmt = self::db()->prepare(
                "UPDATE {$this->table} SET deletedAt = NOW() WHERE {$this->primaryKey} = :id"
            );
            return $stmt->execute([':id' => $id]);
        }
        return $this->forceDelete($id);
    }

    public function forceDelete(int|string $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function paginate(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $where = $this->useSoftDelete ? ' WHERE deletedAt IS NULL' : '';
        $countStmt = self::db()->query("SELECT COUNT(*) as total FROM {$this->table}{$where}");
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT * FROM {$this->table}{$where} ORDER BY {$this->primaryKey} DESC LIMIT :limit OFFSET :offset"
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

    public function where(string $column, mixed $value): array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = :value" . $this->softDeleteClause() . " ORDER BY {$this->primaryKey} DESC"
        );
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll();
    }

    public function firstWhere(string $column, mixed $value): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = :value" . $this->softDeleteClause() . " LIMIT 1"
        );
        $stmt->execute([':value' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
