<?php

namespace Differ\Differ;

use function Code\Formatters\Plain\format as convertToPlain;
use function Code\Formatters\Stylish\format as convertToStylish;
use function Code\Formatters\Json\format as convertToJson;
use function Code\Functional\Difference\createDifference;
use function Code\Functional\Difference\createNode;
use function Code\Functional\Parsers\parse;
use function Functional\sort as fSort;

function genDiff(string $firstPath, string $secondPath, string $format = 'stylish'): string
{
    $firstColl = parse($firstPath);
    $secondColl = parse($secondPath);
    $diff = buildDiff($firstColl, $secondColl);

    return match ($format) {
        "stylish" => convertToStylish($diff),
        "plain" => convertToPlain($diff),
        "json" => convertToJson($diff),
        default => throw new \Exception("No such format"),
    };
}

function buildDiff(array $firstColl, array $secondColl): array
{
    $getStatus = function (string $key) use ($firstColl, $secondColl): string {
        if (array_key_exists($key, $firstColl) && array_key_exists($key, $secondColl)) {
            return ($firstColl[$key] === $secondColl[$key]) ? 'saved' : 'changed';
        }
        return array_key_exists($key, $firstColl) ? 'deleted' : 'added';
    };
    $keys = array_unique(array_merge(array_keys($firstColl), array_keys($secondColl)));
    $sortedKeys = fSort($keys, fn($left, $right) => $left <=> $right);
    return array_reduce($sortedKeys, function ($acc, $key) use ($getStatus, $firstColl, $secondColl) {
        $firstValue = $firstColl[$key] ?? null;
        $secondValue = $secondColl[$key] ?? null;
        if (isAssocArray($firstValue) && isAssocArray($secondValue)) {
            $newNode = createNode($key, buildDiff($firstValue, $secondValue));
        } else {
            $status = $getStatus($key);
            $newNode = createDifference($key, $firstValue, $secondValue, $status);
        }
        return array_merge($acc, [$newNode]);
    }, []);
}

function isAssocArray(mixed $value): bool
{
    if (!is_array($value)) {
        return false;
    }
    return count(array_filter(array_keys($value), 'is_string')) == count($value);
}
