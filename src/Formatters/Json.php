<?php

namespace Code\Formatters\Json;

use Code\Functional\Difference;

const DEFAULT_INTEND = '  ';

function format(array $data, int $depth = 1): string
{
    $lines = array_map(
        function ($value) use ($depth) {
            return match (Difference\getType($value)) {
                'difference' => formatDifference($value, $depth),
                'node' => formatNode($value, $depth),
                default => throw new \Exception("Node type " . Difference\getType($value) . " doesn't exist")
            };
        },
        $data
    );
    $normalizedLines = removeLastSymb($lines, ',');
    $bracketIntend = str_repeat(DEFAULT_INTEND, $depth - 1);
    $result = ["{", ...$normalizedLines, "$bracketIntend}"];
    return implode("\n", $result);
}

function formatNode(array $node, int $depth): string
{
    $nodeIntend = str_repeat(DEFAULT_INTEND, $depth);
    $key = Difference\getKey($node);
    $children = Difference\getChildren($node);
    return sprintf("%s\"%s\": %s,", $nodeIntend, $key, format($children, $depth + 1));
}

function formatDifference(array $diff, int $depth): string
{
    $diffIntend = str_repeat(DEFAULT_INTEND, $depth);
    $key = Difference\getKey($diff);
    $newValue = Difference\getNewValue($diff);
    $oldValue = Difference\getOldValue($diff);
    $status = Difference\getStatus($diff);
    $value = match ($status) {
        'added' => sprintf("\"newValue\": %s, \"status\": \"%s\"", formatValue($newValue), $status),
        'deleted', 'saved' => sprintf('"oldValue": %s, "status": "%s"', formatValue($oldValue), $status),
        'changed' => sprintf(
            '"oldValue": %s, "newValue": %s, "status": "%s"',
            formatValue($oldValue),
            formatValue($newValue),
            $status
        ),
        default => throw new \Exception("Status $status doesn't exist")
    };
    return sprintf("$diffIntend\"%s\": {%s},", $key, $value);
}

function formatValue(mixed $value): string
{
    if (!is_array($value)) {
        return (is_int($value) || $value == 'false' || $value == 'true' || $value == 'null') ?
                $value : sprintf('"%s"', $value);
    }
    $lines = array_map(
        function ($key, $item) {
            return sprintf("%s: %s, ", formatValue($key), formatValue($item));
        },
        array_keys($value),
        $value
    );
    $normalizedLines = removeLastSymb($lines, ', ');
    $result = ["{", ...$normalizedLines, "}"];
    return implode("", $result);
}

function removeLastSymb(array $lines, string $symbols): array
{
    $lastLineIndex = count($lines) - 1;
    return array_map(
        fn ($key, $line) => ($key == $lastLineIndex) ? rtrim($line, $symbols) : $line,
        array_keys($lines),
        $lines
    );
}
