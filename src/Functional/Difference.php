<?php

namespace Code\Functional\Difference;

function createDifference(string $key, mixed $oldValue, mixed $newValue, string $status): array
{
    return [
        'key' => $key,
        'old_value' => normalize($oldValue),
        'new_value' => normalize($newValue),
        'status' => $status,
        'type' => 'difference'
    ];
}

function createNode(string $key, array $children): array
{
    return [
        'key' => $key,
        'children' => $children,
        'type' => 'node'
    ];
}

function isDifference(mixed $value): bool
{
    if (!is_array($value)) {
        return false;
    }
    return array_key_exists('status', $value);
}

function normalize(mixed $value): mixed
{
    if (is_null($value)) {
        return 'null';
    }
    return (is_bool($value)) ? var_export($value, 1) : $value;
}

function getOldValue(array $difference): mixed
{
    return $difference['old_value'];
}

function getNewValue(array $difference): mixed
{
    return $difference['new_value'];
}

function getStatus(array $difference): string
{
    return $difference['status'];
}

function getType(array $difference): string
{
    return $difference['type'];
}

//
