<?php

namespace App\Support;

class ArrayDeepMerge
{
    /**
     * Merge đệ quy: giữ cấu trúc của $base, override từ $override.
     * - array associative: merge theo key
     * - array numeric (list): nếu override có, sẽ replace toàn bộ list (phù hợp items/faq)
     */
    public static function merge(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (!array_key_exists($key, $base)) {
                $base[$key] = $value;
                continue;
            }

            // list array -> replace
            if (is_array($value) && self::isList($value)) {
                $base[$key] = $value;
                continue;
            }

            if (is_array($value) && is_array($base[$key])) {
                $base[$key] = self::merge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }

        return $base;
    }

    private static function isList(array $array): bool
    {
        if ($array === []) return true;
        return array_keys($array) === range(0, count($array) - 1);
    }
}
