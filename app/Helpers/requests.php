<?php

use Illuminate\Http\Request;

if ( ! function_exists('getRequestIP')) {
    /**
     * Get current request IP
     * @param Request $request
     * @return string|null
     */
    function getRequestIP(Request $request): ?string
    {
        return $request->header('cf-connecting-ip') ?? $request->ip();
    }
}
