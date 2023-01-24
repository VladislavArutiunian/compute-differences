<?php

namespace Code\Functional\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): array
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    return convertToArray($filePath, $fileExtension);
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
