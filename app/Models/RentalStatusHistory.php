<?php

class RentalStatusHistory extends BaseModel
{
    protected string $table = 'rentalStatusHistory';
    protected string $primaryKey = 'historyId';
    protected bool $useSoftDelete = false;

    public function logChange(int $rentalId, ?string $previousStatus, string $newStatus, int $changedBy, ?string $reason = null): string|false
    {
        return $this->create([
            'rentalId' => $rentalId,
            'previousStatus' => $previousStatus,
            'newStatus' => $newStatus,
            'changedBy' => $changedBy,
            'changeReason' => $reason,
        ]);
    }
}
