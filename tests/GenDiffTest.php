<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Code\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function getFixtureFullPath(string $fileName): string
    {
        $fullPath = [__DIR__, 'fixtures', $fileName];
        return realpath(implode('/', $fullPath));
    }

    public function testPlainAbsPath()
    {
        $json1 = $this->getFixtureFullPath('file1.json');
        $json2 = $this->getFixtureFullPath('file2.json');
        $resultFile = $this->getFixtureFullPath('result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($json1, $json2));
    }
}
