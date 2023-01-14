<?php

namespace Code\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parse(string $firstPath, string $secondPath): array
{
    $firstFileExtension = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileExtension = pathinfo($secondPath, PATHINFO_EXTENSION);
    return [
        convertToArray($firstPath, $firstFileExtension),
        convertToArray($secondPath, $secondFileExtension)
    ];
}

/**
 * @throws Exception
 */
function convertToArray(string $path, string $fileExtension): array
{
    return match ($fileExtension) {
        "json" => json_decode(file_get_contents($path), true),
        "yaml", "yml" => Yaml::parse(file_get_contents($path)),
        default => throw new Exception("Неверное расширение файла"),
    };
}
