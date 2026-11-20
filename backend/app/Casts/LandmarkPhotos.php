<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores one path as a plain string (legacy format) and multiple paths as a
 * JSON array, while always exposing the attribute as a normalized array.
 */
class LandmarkPhotos implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (blank($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value, 'is_string'));
        }

        $decoded = json_decode((string) $value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded, 'is_string'));
        }

        return [(string) $value];
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $value = $decoded;
            }
        }

        $paths = array_values(array_filter((array) $value, 'is_string'));

        if ($paths === []) {
            return null;
        }

        return count($paths) === 1 ? $paths[0] : json_encode($paths);
    }
}
