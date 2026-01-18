<?php

use Illuminate\Support\Arr;

if (! function_exists('generateCacheKey')) {
    /**
     * Get initials from a string.
     *
     * @param  string  $prefix
     * @param  array  $options
     */
    function generateCacheKey(string $prefix, array $options = []): string
    {
        $sortedOptions = Arr::sortRecursive($options);

        $serializedData = json_encode($sortedOptions);

        $hashedData = md5($serializedData);

        return "{$prefix}_$hashedData";
    }
}
