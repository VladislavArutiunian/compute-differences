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
        "json" => json_decode(getFile($path), true),
        "yaml", "yml" => Yaml::parse(getFile($path)),
        default => throw new Exception("Неверное расширение файла"),
    };
}

/**
 * @throws Exception
 */
function getFile(string $filePath): string
{
    $result = file_get_contents($filePath);
    return (is_string($result)) ? $result : throw new \Exception("file " . $filePath . " doesn't exist");
}
