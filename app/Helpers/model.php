<?php

use Illuminate\Database\Eloquent\Model;
use App\Models\Contracts\Timezone as TimezoneContract;

if (! function_exists('getFillableFields')) {
    /**
     * Get the attributes that are mass assignable.
     *
     * @param  string  $model
     */
    function getFillableFields(string $model): array
    {
        return app($model)->getFillable();
    }
}

if (! function_exists('filterDataFillableFields')) {
    /**
     * Filter to only include mass assignable attributes.
     *
     * @param  string  $model
     * @param  array  $data
     */
    function filterDataFillableFields(string $model, array $data = []): array
    {
        return collect($data)
            ->only(getFillableFields($model))
            ->toArray();
    }
}

if (! function_exists('getModelId')) {
    /**
     * Get the value of the model's primary key.
     *
     * @param  mixed  $model
     *
     * @throws \InvalidArgumentException
     */
    function getModelId(mixed $model): mixed
    {
        if ($model instanceof Model) {
            return $model->getKey();
        }

        if (is_int($model) || is_string($model)) {
            return $model;
        }

        throw new InvalidArgumentException("The argument type is invalid.", 500);
    }
}

if (! function_exists('getModelTimezone')) {
    /**
     * Retrieve the timezone for the given model or fallback to the application timezone.
     *
     * @param  Model|null  $model  The model instance to get timezone from, or null to use default.
     * @return string The resolved timezone.
     */
    function getModelTimezone(?Model $model = null): string
    {
        if (empty($model)) {
            return config('app.timezone');
        }

        return $model instanceof TimezoneContract
            ? $model->getTimezone()
            : config('app.timezone');
    }
}
