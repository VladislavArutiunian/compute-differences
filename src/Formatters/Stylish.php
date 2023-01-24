<?php

namespace Code\Formatters\Stylish;

use Code\Functional\Difference;

function format(mixed $data, int $depth = 1): string
{
    $defaultIndent = str_repeat(' ', 4);
    $reducedIndent = str_repeat($defaultIndent, $depth - 1);
    $lines = array_map(function ($value) use ($depth) {
        return match (Difference\getType($value)) {
            'difference' => formatDifference($value, $depth),
            'node' => formatNode($value, $depth),
        };
    }, $data);
    $result = ["{", ...$lines, "$reducedIndent}"];
    return implode("\n", $result);
}

function formatDifference($value, $depth): string
{
    $defaultIndent = str_repeat(' ', 4);
    $reducedIndent = str_repeat($defaultIndent, $depth - 1);
    return match (Difference\getStatus($value)) {
        'added' => sprintf("$reducedIndent  + %s:%s", $value['key'], formatValue($value['new_value'], $depth + 1)),
        'saved' => sprintf("$reducedIndent    %s:%s", $value['key'], formatValue($value['new_value'], $depth + 1)),
        'deleted' => sprintf("$reducedIndent  - %s:%s", $value['key'], formatValue($value['old_value'], $depth + 1)),
        'changed' => sprintf(
            "$reducedIndent  - %s:%s\n$reducedIndent  + %s:%s",
            $value['key'],
            formatValue($value['old_value'], $depth + 1),
            $value['key'],
            formatValue($value['new_value'], $depth + 1)
        ),
    };
}

function formatValue($value, $depth): string
{
    if (!is_array($value)) {
        return ($value === '') ? "" : " $value";
    }
    $defaultIndent = str_repeat(' ', 4);
    $reducedIndent = str_repeat($defaultIndent, $depth - 1);
    $lines = array_map(
        function ($key, $item) use ($depth, $reducedIndent) {
            return sprintf("$reducedIndent    $key:%s", formatValue($item, $depth + 1));
        },
        array_keys($value),
        $value
    );
    $result = [" {", ...$lines, "$reducedIndent}"];
    return implode("\n", $result);
}

function formatNode($value, $depth): string
{
    $defaultIndent = str_repeat(' ', 4);
    $reducedIndent = str_repeat($defaultIndent, $depth - 1);
    return sprintf("$reducedIndent    %s: %s", $value['key'], format($value['children'], $depth + 1));
}
