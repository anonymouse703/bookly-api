<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Facades\Validator;

trait HasValidator
{
    protected function validate($value, $name, $rules, $messages = [], $attributes = [])
    {
        return Validator::make([$name => $value], [$name => $rules], $messages, $attributes)
            ->errors()
            ->first($name);
    }
}
