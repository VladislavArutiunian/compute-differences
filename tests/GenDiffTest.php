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

    /**
     * @dataProvider fileProvider
     */
    public function testStyleStylish(string $fileName1, string $fileName2)
    {
        $file1 = $this->getFixtureFullPath($fileName1);
        $file2 = $this->getFixtureFullPath($fileName2);
        $resultFile = $this->getFixtureFullPath('result_stylish.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2));
    }

    /**
     * @dataProvider fileProvider
     */
    public function testJsonStylePlain(string $fileName1, string $fileName2)
    {
        $file1 = $this->getFixtureFullPath($fileName1);
        $file2 = $this->getFixtureFullPath($fileName2);
        $resultFile = $this->getFixtureFullPath('result_plain.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2, 'plain'));
    }

    /**
     * @dataProvider fileProvider
     */
    public function testJsonStyleJson(string $fileName1, string $fileName2)
    {
        $file1 = $this->getFixtureFullPath($fileName1);
        $file2 = $this->getFixtureFullPath($fileName2);
        $resultFile = $this->getFixtureFullPath('result_json.txt');
        $this->assertStringEqualsFile($resultFile, genDiff($file1, $file2, 'json'));
    }

    public function fileProvider(): array
    {
        return [
            'json file names' => ['nested1.json', 'nested2.json'],
            'yaml file names' => ['nested1.yaml', 'nested2.yaml']
        ];
    }
}
