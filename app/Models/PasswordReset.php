<?php

class PasswordReset extends BaseModel
{
    protected string $table = 'passwordResetTokens';
    protected string $primaryKey = 'tokenId';

    public function createToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);

        $this->create([
            'userId' => $userId,
            'token' => $hash,
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ]);

        return $token;
    }

    public function findValidToken(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $stmt = self::db()->prepare(
            "SELECT * FROM passwordResetTokens
             WHERE token = :token
             AND usedAt IS NULL
             AND expiresAt > NOW()
             ORDER BY createdAt DESC
             LIMIT 1"
        );
        $stmt->execute([':token' => $hash]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function markAsUsed(int $tokenId): bool
    {
        $stmt = self::db()->prepare(
            "UPDATE passwordResetTokens SET usedAt = NOW() WHERE tokenId = :tokenId"
        );
        return $stmt->execute([':tokenId' => $tokenId]);
    }
}
