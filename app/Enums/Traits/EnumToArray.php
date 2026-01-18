<?php

namespace App\Enums\Traits;

use Illuminate\Support\Collection;

trait EnumToArray
{
    /**
     * Converts the name into headline format.
     */
    public function label(): string
    {
        return str($this->name)->headline();
    }

    /**
     * Create an array of names.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Create an array of labels.
     */
    public static function labels(): array
    {
        return array_map(
            fn ($instance) => str($instance->value)->headline()->__toString(),
            self::cases()
        );
    }

    /**
     * Create an array of values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Create an associative array.
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::labels());
    }

    /**
     * Create a collection.
     */
    public static function collection(): Collection
    {
        return collect(self::array())
            ->map(fn ($value, $key) => ['key' => $key, 'label' => $value])
            ->values();
    }
}
