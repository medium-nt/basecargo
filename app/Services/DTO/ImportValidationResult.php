<?php

namespace App\Services\DTO;

class ImportValidationResult
{
    public array $validRows = [];

    public array $errors = [];

    public array $warnings = [];

    public int $totalRows = 0;

    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }

    public function hasWarnings(): bool
    {
        return ! empty($this->warnings);
    }

    public function getValidRowsCount(): int
    {
        return count($this->validRows);
    }

    public function getErrorsCount(): int
    {
        return count($this->errors);
    }

    public function getWarningsCount(): int
    {
        return count($this->warnings);
    }

    public function hasWarningForRow(int $index): bool
    {
        return isset($this->warnings[$index]);
    }

    public function getWarningForRow(int $index): ?string
    {
        return $this->warnings[$index] ?? null;
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
        $result->warnings = $data['warnings'] ?? [];
        $result->totalRows = $data['totalRows'] ?? 0;

        return $result;
    }
}
