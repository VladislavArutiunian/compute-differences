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

    public function testPlainJson()
    {
        $json1 = $this->getFixtureFullPath('file1.json');
        $json2 = $this->getFixtureFullPath('file2.json');
        $resultFile = $this->getFixtureFullPath('json_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($json1, $json2));
    }

    public function testPlainYaml()
    {
        $yaml1 = $this->getFixtureFullPath('file1.yaml');
        $yaml2 = $this->getFixtureFullPath('file2.yaml');
        $resultFile = $this->getFixtureFullPath('yaml_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($yaml1, $yaml2));
    }
}
