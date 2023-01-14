<?php

namespace Code\GenDiff;

use function Code\Parsers\parse;

function genDiff(string $firstPath, string $secondPath, string $format = 'stylish'): string
{
    [$firstColl, $secondColl] = parse($firstPath, $secondPath);
    $res = [];
    foreach ($firstColl as $key => $value) {
        $value = normalize($value);
        if (array_key_exists($key, $secondColl)) {
            $value2 = normalize($secondColl[$key]);
            $str = ($value === $value2) ? '    ' . "$key: $value\n" : "  - $key: $value\n  + $key: $value2\n";
        } else {
            $str = "  - $key: $value\n";
        }
        $res[] = $str;
    }
    foreach ($secondColl as $key => $value) {
        if (!array_key_exists($key, $firstColl)) {
            $normalizedValue = (is_bool($value)) ? var_export($value, 1) : $value;
            $res[] = "  + $key: $normalizedValue\n";
        }
    }
    $compare = function ($left, $right) use ($res) {
        $leftValue = $res[$left];
        $rightValue = $res[$right];
        $filter = '+- ';
        $normalizedLeft = ltrim($leftValue, $filter);
        $normalizedRight = ltrim($rightValue, $filter);
        return $normalizedLeft[0] <=> $normalizedRight[0];
    };
    uksort($res, $compare);
    return "{\n" . implode('', $res) . "}";
}

function normalize(bool|string $value): string
{
    return (is_bool($value)) ? var_export($value, 1) : $value;
}
