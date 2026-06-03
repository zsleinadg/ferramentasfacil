<?php

class ToolImage extends BaseModel
{
    protected string $table = 'toolImages';
    protected string $primaryKey = 'imageId';

    public function getByToolId(int $toolId): array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM toolImages WHERE toolId = :toolId ORDER BY sortOrder ASC, imageId ASC"
        );
        $stmt->execute([':toolId' => $toolId]);
        return $stmt->fetchAll();
    }

    public function addImage(int $toolId, string $imageUrl, ?string $altText = null, int $sortOrder = 0): string|false
    {
        return $this->create([
            'toolId' => $toolId,
            'imageUrl' => $imageUrl,
            'altText' => $altText,
            'sortOrder' => $sortOrder,
        ]);
    }

    public function deleteByImageId(int $imageId): bool
    {
        return $this->forceDelete($imageId);
    }

    public function deleteByToolId(int $toolId): void
    {
        $stmt = self::db()->prepare("DELETE FROM toolImages WHERE toolId = :toolId");
        $stmt->execute([':toolId' => $toolId]);
    }
}
