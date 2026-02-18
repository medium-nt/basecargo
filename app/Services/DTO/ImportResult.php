<?php

namespace App\Services\DTO;

class ImportResult
{
    public int $createdCount = 0;

    public int $updatedCount = 0;

    public array $errors = [];

    public function isSuccess(): bool
    {
        return empty($this->errors);
    }

    public function getTotalProcessed(): int
    {
        return $this->createdCount + $this->updatedCount;
    }
}
