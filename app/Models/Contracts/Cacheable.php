<?php

namespace App\Models\Contracts;

interface Cacheable
{
    /**
     * Flushes the model cache.
     */
    public function clearCache(): void;
}
