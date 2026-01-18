<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasUniqueReferenceNumber
{
    /**
     * Boot the trait and set up model events
     */
    protected static function bootHasUniqueReferenceNumber(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getReferenceNumberColumn()})) {
                $model->{$model->getReferenceNumberColumn()} = $model->generateUniqueReferenceNumber();
            }
        });
    }

    /**
     * Generate a unique reference number
     */
    public function generateUniqueReferenceNumber(): string
    {
        $prefix = $this->getReferenceNumberPrefix();
        $length = $this->getReferenceNumberLength();
        $column = $this->getReferenceNumberColumn();

        do {
            $referenceNumber = $prefix . $this->generateRandomString($length);
        } while ($this->referenceNumberExists($referenceNumber, $column));

        return $referenceNumber;
    }

    /**
     * Check if reference number already exists
     */
    protected function referenceNumberExists(string $referenceNumber, string $column): bool
    {
        return static::where($column, $referenceNumber)->exists();
    }

    /**
     * Generate random string for reference number
     */
    protected function generateRandomString(int $length): string
    {
        $type = $this->getReferenceNumberType();

        return match($type) {
            'numeric' => $this->generateNumeric($length),
            'alphanumeric' => strtoupper(Str::random($length)),
            'alpha' => strtoupper(Str::random($length)),
            'timestamp' => $this->generateTimestamp($length),
            default => strtoupper(Str::random($length)),
        };
    }

    /**
     * Generate numeric string
     */
    protected function generateNumeric(int $length): string
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= random_int(0, 9);
        }
        return $number;
    }

    /**
     * Generate timestamp-based reference
     */
    protected function generateTimestamp(int $length): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random($length - strlen($timestamp)));
        return $timestamp . $random;
    }

    /**
     * Get the reference number column name
     * Override in model if different
     */
    public function getReferenceNumberColumn(): string
    {
        return 'reference_number';
    }

    /**
     * Get the reference number prefix
     * Override in model to customize
     */
    public function getReferenceNumberPrefix(): string
    {
        return 'REF-';
    }

    /**
     * Get the reference number length (without prefix)
     * Override in model to customize
     */
    public function getReferenceNumberLength(): int
    {
        return 10;
    }

    /**
     * Get the reference number type
     * Options: 'numeric', 'alphanumeric', 'alpha', 'timestamp'
     * Override in model to customize
     */
    public function getReferenceNumberType(): string
    {
        return 'alphanumeric';
    }
}