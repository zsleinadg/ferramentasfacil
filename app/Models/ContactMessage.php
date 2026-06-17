<?php

class ContactMessage extends BaseModel
{
    protected string $table = 'contactMessages';
    protected string $primaryKey = 'messageId';

    public function getAllPaginated(int $page = 1, int $perPage = 20): array
    {
        return $this->paginate($page, $perPage);
    }

    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['isRead' => true]);
    }

    public function getUnreadCount(): int
    {
        $stmt = self::db()->query("SELECT COUNT(*) as total FROM contactMessages WHERE isRead = FALSE");
        return (int) $stmt->fetch()['total'];
    }
}