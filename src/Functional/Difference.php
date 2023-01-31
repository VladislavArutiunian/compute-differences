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

function normalize(mixed $value): mixed
{
    return match (\gettype($value)) {
        "boolean" => var_export($value, true),
        "NULL" => 'null',
        default => $value
    };
}

function getOldValue(array $difference): mixed
{
    return $difference['old_value'];
}

function getNewValue(array $difference): mixed
{
    return $difference['new_value'];
}

function getChildren(array $node): array
{
    return $node['children'];
}

function getStatus(array $difference): string
{
    return $difference['status'];
}

function getType(array $difference): string
{
    return $difference['type'];
}

function getKey(array $difference): string
{
    return $difference['key'];
}
