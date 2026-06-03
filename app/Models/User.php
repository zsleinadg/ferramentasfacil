<?php

class User extends BaseModel
{
    protected string $table = 'users';
    protected string $primaryKey = 'userId';
    protected bool $useSoftDelete = true;

    public function findByEmail(string $email): ?array
    {
        return $this->firstWhere('email', $email);
    }

    public function findByGoogleId(string $googleId): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM users WHERE googleId = :googleId AND deletedAt IS NULL LIMIT 1"
        );
        $stmt->execute([':googleId' => $googleId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function createFromGoogle(array $googleUser, int $roleId): string|false
    {
        return $this->create([
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'googleId' => $googleUser['id'],
            'avatarUrl' => $googleUser['picture'] ?? null,
            'roleId' => $roleId,
            'emailVerifiedAt' => date('Y-m-d H:i:s'),
        ]);
    }

    public function linkGoogleAccount(int $userId, string $googleId): bool
    {
        return $this->update($userId, ['googleId' => $googleId]);
    }

    public function validateCredentials(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user || !$user['passwordhash']) {
            return null;
        }
        if (!password_verify($password, $user['passwordhash'])) {
            return null;
        }
        return $user;
    }

    public function updateLastLogin(int $userId): void
    {
        $this->update($userId, ['lastLoginAt' => date('Y-m-d H:i:s')]);
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->update($userId, ['passwordHash' => $hash]);
    }

    public function getRoleName(int $userId): ?string
    {
        $stmt = self::db()->prepare(
            "SELECT r.roleName FROM users u
             JOIN roles r ON u.roleId = r.roleId
             WHERE u.userId = :userId"
        );
        $stmt->execute([':userId' => $userId]);
        $result = $stmt->fetch();
        return $result ? $result['rolename'] : null;
    }

    public function getAllWithRoles(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = self::db()->query(
            "SELECT COUNT(*) as total FROM users WHERE deletedAt IS NULL"
        );
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT u.*, r.roleName, r.displayName
             FROM users u
             JOIN roles r ON u.roleId = r.roleId
             WHERE u.deletedAt IS NULL
             ORDER BY u.userId DESC
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

    public function clientCount(): int
    {
        $stmt = self::db()->query(
            "SELECT COUNT(*) as total FROM users
             WHERE roleId = (SELECT roleId FROM roles WHERE roleName = 'client')
             AND deletedAt IS NULL"
        );
        return (int) $stmt->fetch()['total'];
    }

    public function getClients(): array
    {
        $stmt = self::db()->query(
            "SELECT userId, name, email, cpf FROM users
             WHERE roleId = (SELECT roleId FROM roles WHERE roleName = 'client')
             AND deletedAt IS NULL
             ORDER BY name ASC"
        );
        return $stmt->fetchAll();
    }

    public function getAllRoles(): array
    {
        $stmt = self::db()->query("SELECT * FROM roles ORDER BY roleId ASC");
        return $stmt->fetchAll();
    }

    public function searchUsers(string $term, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = self::db()->prepare(
            "SELECT COUNT(*) as total FROM users
             WHERE deletedAt IS NULL
             AND (name ILIKE :term OR email ILIKE :term)"
        );
        $countStmt->execute([':term' => "%{$term}%"]);
        $total = (int) $countStmt->fetch()['total'];

        $stmt = self::db()->prepare(
            "SELECT u.*, r.roleName, r.displayName
             FROM users u
             JOIN roles r ON u.roleId = r.roleId
             WHERE u.deletedAt IS NULL
             AND (u.name ILIKE :term OR u.email ILIKE :term)
             ORDER BY u.userId DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':term', "%{$term}%");
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => (int) ceil($total / $perPage),
        ];
    }

    public function findWithRole(int $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT u.*, r.roleName, r.displayName
             FROM users u
             JOIN roles r ON u.roleId = r.roleId
             WHERE u.userId = :id AND u.deletedAt IS NULL"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
