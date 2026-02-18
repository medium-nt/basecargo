<?php

namespace App\Services\DTO;

class ImportValidationResult
{
    public array $validRows = [];

    public array $errors = [];

    public int $totalRows = 0;

    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }

    public function getValidRowsCount(): int
    {
        return count($this->validRows);
    }

    public function getErrorsCount(): int
    {
        return count($this->errors);
    }

    /**
     * Создать объект из JSON
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        $result = new self;
        $result->validRows = $data['validRows'] ?? [];
        $result->errors = $data['errors'] ?? [];
        $result->totalRows = $data['totalRows'] ?? 0;

        return $result;
    }
}
