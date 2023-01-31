<?php

namespace Code\Formatters\Plain;

use Code\Functional\Difference;

function format(array $data, array $parents = []): string
{
    $lines = array_map(function ($value) use ($parents) {
            return match (Difference\getType($value)) {
                'difference' => formatDifference($value, $parents),
                'node' => formatNode($value, $parents),
            };
    }, $data);
    $result = array_filter($lines, fn ($line) => $line !== '');
    return implode("\n", $result);
}

function formatDifference(array $diff, array $parents): string
{
    $parent = Difference\getKey($diff);
    $newParents = array_merge($parents, [$parent]);
    $parentsStr = sprintf("'%s'", implode('.', $newParents));
    $oldValue = Difference\getOldValue($diff);
    $newValue = Difference\getNewValue($diff);
    return match (Difference\getStatus($diff)) {
        'saved' => '',
        'added' => sprintf("Property $parentsStr was added with value: %s", formatValue($newValue)),
        'deleted' => sprintf("Property $parentsStr was removed"),
        'changed' => sprintf(
            "Property $parentsStr was updated. From %s to %s",
            formatValue($oldValue),
            formatValue($newValue)
        )
    };
}

function formatNode(array $node, array $parents): string
{
    $parent = Difference\getKey($node);
    $newParents = array_merge($parents, [$parent]);
    $children = Difference\getChildren($node);
    return format($children, $newParents);
}

function formatValue(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    } elseif ($value == 'null' || $value == 'true' || $value == 'false' || is_int($value)) {
        return $value;
    } else {
        return sprintf("'%s'", $value);
    }
}
