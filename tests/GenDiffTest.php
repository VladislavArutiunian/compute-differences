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

    public function testJsonStylish()
    {
        $nestedJson1 = $this->getFixtureFullPath('nested1.json');
        $nestedJson2 = $this->getFixtureFullPath('nested2.json');
        $resultFile = $this->getFixtureFullPath('result_stylish.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedJson1, $nestedJson2));
    }

    public function testYamlStylish()
    {
        $nestedYaml1 = $this->getFixtureFullPath('nested1.yaml');
        $nestedYaml2 = $this->getFixtureFullPath('nested2.yaml');
        $resultFile = $this->getFixtureFullPath('result_stylish.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedYaml1, $nestedYaml2));
    }

    public function testJsonPlain()
    {
        $nestedJson1 = $this->getFixtureFullPath('nested1.json');
        $nestedJson2 = $this->getFixtureFullPath('nested2.json');
        $resultFile = $this->getFixtureFullPath('result_plain.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedJson1, $nestedJson2, 'plain'));
    }

    public function testYamlPlain()
    {
        $nestedYaml1 = $this->getFixtureFullPath('nested1.yaml');
        $nestedYaml2 = $this->getFixtureFullPath('nested2.yaml');
        $resultFile = $this->getFixtureFullPath('result_plain.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedYaml1, $nestedYaml2, 'plain'));
    }
}
