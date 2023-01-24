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
        $json1 = $this->getFixtureFullPath('plain1.json');
        $json2 = $this->getFixtureFullPath('plain2.json');
        $resultFile = $this->getFixtureFullPath('plain_json_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($json1, $json2));
    }

    public function testPlainYaml()
    {
        $yaml1 = $this->getFixtureFullPath('plain1.yaml');
        $yaml2 = $this->getFixtureFullPath('plain2.yaml');
        $resultFile = $this->getFixtureFullPath('plain_yaml_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($yaml1, $yaml2));
    }
    public function testNestedJson()
    {
        $nestedJson1 = $this->getFixtureFullPath('nested1.json');
        $nestedJson2 = $this->getFixtureFullPath('nested2.json');
        $resultFile = $this->getFixtureFullPath('nested_json_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedJson1, $nestedJson2));
    }

    public function testNestedYaml()
    {
        $nestedYaml1 = $this->getFixtureFullPath('nested1.json');
        $nestedYaml2 = $this->getFixtureFullPath('nested2.json');
        $resultFile = $this->getFixtureFullPath('nested_json_result.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($nestedYaml1, $nestedYaml2));
    }
}
