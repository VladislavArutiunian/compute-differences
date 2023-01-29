<?php

namespace Code\Formatters\Stylish;

use Code\Functional\Difference;

const DEFAULT_INTEND = '    ';

function format(mixed $data, int $depth = 1): string
{
    $lines = array_map(function ($value) use ($depth) {
        return match (Difference\getType($value)) {
            'difference' => formatDifference($value, $depth),
            'node' => formatNode($value, $depth),
        };
    }, $data);
    $bracketIndent = str_repeat(DEFAULT_INTEND, $depth - 1);
    $result = ["{", ...$lines, "$bracketIndent}"];
    return implode("\n", $result);
}

function formatDifference(array $difference, int $depth): string
{
    $reducedIndent = str_repeat(DEFAULT_INTEND, $depth - 1);
    $addIntend = $reducedIndent . '  + ';
    $deleteIntend = $reducedIndent . '  - ';
    $savedIntend = str_repeat(DEFAULT_INTEND, $depth);
    $newValue = Difference\getNewValue($difference);
    $oldValue = Difference\getOldValue($difference);
    $key = Difference\getKey($difference);
    return match (Difference\getStatus($difference)) {
        'added' => sprintf(
            "$addIntend%s:%s",
            $key, formatValue($newValue, $depth + 1)
        ),
        'saved' => sprintf(
            "$savedIntend%s:%s",
            $key, formatValue($newValue, $depth + 1)
        ),
        'deleted' => sprintf(
            "$deleteIntend%s:%s",
            $key, formatValue($oldValue, $depth + 1)
        ),
        'changed' => sprintf(
            "$deleteIntend%s:%s\n$addIntend%s:%s",
            $key,
            formatValue($oldValue, $depth + 1),
            $key,
            formatValue($newValue, $depth + 1)
        ),
    };
}

function formatNode(array $node, int $depth): string
{
    $nodeIndent = str_repeat(DEFAULT_INTEND, $depth);
    $children = Difference\getChildren($node);
    return sprintf("$nodeIndent%s: %s", Difference\getKey($node), format($children, $depth + 1));
}

function formatValue(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return ($value === '') ? "" : " $value";
    }
    $valueIndent = str_repeat(DEFAULT_INTEND, $depth);
    $lines = array_map(
        function ($key, $item) use ($valueIndent, $depth) {
            return sprintf("$valueIndent%s:%s", $key, formatValue($item, $depth + 1));
        },
        array_keys($value),
        $value
    );
    $bracketIntend = str_repeat(DEFAULT_INTEND, $depth - 1);
    $result = [" {", ...$lines, "$bracketIntend}"];
    return implode("\n", $result);
}
